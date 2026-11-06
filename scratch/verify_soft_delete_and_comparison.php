<?php

$baseDir = 'c:/xampp/htdocs/HALOBK';
require $baseDir . '/vendor/autoload.php';
$app = require_once $baseDir . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Admin;
use App\Models\GuruBk;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Tes;
use App\Models\Soal;
use App\Models\SesiTesSiswa;
use App\Models\JawabanSiswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

echo "====================================================\n";
echo "    VERIFIKASI INTEGRITAS DATA & FITUR KOMPARASI    \n";
echo "====================================================\n\n";

$passedTests = 0;
$totalTests = 0;

function runTest($title, callable $fn) {
    global $passedTests, $totalTests;
    $totalTests++;
    echo "Test #$totalTests: $title ... ";
    try {
        $res = $fn();
        if ($res === true) {
            echo "✅ PASS\n";
            $passedTests++;
        } else {
            echo "❌ FAIL: $res\n";
        }
    } catch (\Throwable $e) {
        echo "❌ ERROR: " . $e->getMessage() . " at line " . $e->getLine() . "\n";
    }
}

// 1. Verifikasi Kolom deleted_at pada siswas
runTest("Kolom deleted_at ada pada tabel siswas", function() {
    return Illuminate\Support\Facades\Schema::hasColumn('siswas', 'deleted_at') ? true : "Kolom deleted_at tidak ditemukan";
});

// 2. Verifikasi Foreign Key id_admin adalah SET NULL
runTest("Foreign Key siswas.id_admin berstatus SET NULL", function() {
    $rule = DB::select("
        SELECT rc.DELETE_RULE
        FROM INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS rc
        JOIN INFORMATION_SCHEMA.KEY_COLUMN_USAGE kcu 
          ON rc.CONSTRAINT_NAME = kcu.CONSTRAINT_NAME AND rc.CONSTRAINT_SCHEMA = kcu.CONSTRAINT_SCHEMA
        WHERE kcu.TABLE_NAME = 'siswas' AND kcu.COLUMN_NAME = 'id_admin' AND kcu.CONSTRAINT_SCHEMA = 'halobk'
    ");
    if (empty($rule)) return "Foreign key tidak ditemukan";
    return $rule[0]->DELETE_RULE === 'SET NULL' ? true : "Expected SET NULL, got " . $rule[0]->DELETE_RULE;
});

// 3. Verifikasi Soft Delete pada Siswa Model
runTest("Model Siswa menggunakan SoftDeletes", function() {
    $traits = class_uses_recursive(Siswa::class);
    return in_array(Illuminate\Database\Eloquent\SoftDeletes::class, $traits) ? true : "Trait SoftDeletes tidak aktif";
});

// 4. Simulasi Ujian & Soft Delete Proteksi Nilai
runTest("Penghapusan siswa tidak menghapus riwayat sesi tes dan jawaban (Soft Delete Proteksi)", function() {
    DB::beginTransaction();
    try {
        $admin = Admin::first();
        $guru = GuruBk::first();
        $kelas = Kelas::where('id_guru', $guru->id_guru)->first();
        $tes = Tes::where('id_guru', $guru->id_guru)->first();

        // Buat Siswa dummy A (aktif) dan Siswa dummy B (akan dihapus)
        $siswaA = Siswa::create([
            'nama_siswa' => 'Siswa Aktif Test',
            'nisn'       => 'TEST99001',
            'id_kelas'   => $kelas->id_kelas,
            'id_admin'   => $admin->id_admin,
            'password'   => Hash::make('password'),
        ]);

        $siswaB = Siswa::create([
            'nama_siswa' => 'Siswa Keluar Test',
            'nisn'       => 'TEST99002',
            'id_kelas'   => $kelas->id_kelas,
            'id_admin'   => $admin->id_admin,
            'password'   => Hash::make('password'),
        ]);

        // Berikan sesi tes untuk keduanya
        $sesiA = SesiTesSiswa::create([
            'id_tes'            => $tes->id_tes,
            'id_siswa'          => $siswaA->id_siswa,
            'status_pengerjaan' => 'selesai',
            'total_skor'        => 80,
            'waktu_submit'      => now(),
        ]);

        $sesiB = SesiTesSiswa::create([
            'id_tes'            => $tes->id_tes,
            'id_siswa'          => $siswaB->id_siswa,
            'status_pengerjaan' => 'selesai',
            'total_skor'        => 60,
            'waktu_submit'      => now(),
        ]);

        // Hapus Siswa B (Soft Delete)
        $siswaB->delete();

        // Cek apakah siswaB berstatus trashed
        if (!$siswaB->trashed()) {
            DB::rollBack();
            return "Siswa B tidak berstatus trashed";
        }

        // Cek apakah record di database masih ada dengan deleted_at
        $dbRecord = DB::table('siswas')->where('id_siswa', $siswaB->id_siswa)->first();
        if (!$dbRecord || is_null($dbRecord->deleted_at)) {
            DB::rollBack();
            return "Record siswa B musnah dari database!";
        }

        // Cek apakah sesiB masih ada dan jawaban masih utuh
        $checkSesiB = SesiTesSiswa::find($sesiB->id_sesi);
        if (!$checkSesiB) {
            DB::rollBack();
            return "Sesi B ikut terhapus saat siswa B dihapus!";
        }

        // Cek relasi sesiB->siswa (harus bisa diakses lewat withTrashed)
        if (!$checkSesiB->siswa || $checkSesiB->siswa->nama_siswa !== 'Siswa Keluar Test') {
            DB::rollBack();
            return "Relasi sesiB->siswa gagal memuat siswa yang di-soft-delete";
        }

        DB::rollBack();
        return true;
    } catch (\Throwable $e) {
        DB::rollBack();
        throw $e;
    }
});

// 5. Verifikasi Fitur Restore Siswa
runTest("Fitur restore mengembalikan akun siswa ke status aktif", function() {
    DB::beginTransaction();
    try {
        $admin = Admin::first();
        $siswa = Siswa::create([
            'nama_siswa' => 'Siswa Restore Test',
            'nisn'       => 'TEST99003',
            'id_admin'   => $admin->id_admin,
            'password'   => Hash::make('password'),
        ]);

        $siswa->delete();
        if (!$siswa->trashed()) { DB::rollBack(); return "Gagal delete"; }

        // Restore
        $siswa->restore();
        if ($siswa->trashed()) { DB::rollBack(); return "Gagal restore"; }
        if (!is_null($siswa->deleted_at)) { DB::rollBack(); return "deleted_at masih terisi"; }

        DB::rollBack();
        return true;
    } catch (\Throwable $e) {
        DB::rollBack();
        throw $e;
    }
});

// 6. Verifikasi Perhitungan Dual Statistik (Historis vs Terkini vs Delta)
runTest("Kalkulasi Komparasi Nilai: Historis vs Terkini vs Delta akurat", function() {
    DB::beginTransaction();
    try {
        $admin = Admin::first();
        $guru = GuruBk::first();
        $kelas = Kelas::where('id_guru', $guru->id_guru)->first();
        $tes = Tes::where('id_guru', $guru->id_guru)->first();

        // 2 Siswa Aktif (skor 90 dan 80) => rata-rata aktif = 85.0
        $siswa1 = Siswa::create(['nama_siswa' => 'Siswa 1', 'nisn' => 'S1', 'id_kelas' => $kelas->id_kelas, 'id_admin' => $admin->id_admin, 'password' => 'x']);
        $siswa2 = Siswa::create(['nama_siswa' => 'Siswa 2', 'nisn' => 'S2', 'id_kelas' => $kelas->id_kelas, 'id_admin' => $admin->id_admin, 'password' => 'x']);
        
        // 1 Siswa Keluar/Dihapus (skor 60) => rata-rata historis (90 + 80 + 60) / 3 = 76.7
        $siswa3 = Siswa::create(['nama_siswa' => 'Siswa 3 (Keluar)', 'nisn' => 'S3', 'id_kelas' => $kelas->id_kelas, 'id_admin' => $admin->id_admin, 'password' => 'x']);

        $s1 = SesiTesSiswa::create(['id_tes' => $tes->id_tes, 'id_siswa' => $siswa1->id_siswa, 'status_pengerjaan' => 'selesai', 'total_skor' => 90]);
        $s2 = SesiTesSiswa::create(['id_tes' => $tes->id_tes, 'id_siswa' => $siswa2->id_siswa, 'status_pengerjaan' => 'selesai', 'total_skor' => 80]);
        $s3 = SesiTesSiswa::create(['id_tes' => $tes->id_tes, 'id_siswa' => $siswa3->id_siswa, 'status_pengerjaan' => 'selesai', 'total_skor' => 60]);

        // Siswa 3 keluar
        $siswa3->delete();

        // Ambil sesi seperti di KoreksiController
        $sesiList = SesiTesSiswa::where('id_tes', $tes->id_tes)
            ->whereIn('id_sesi', [$s1->id_sesi, $s2->id_sesi, $s3->id_sesi])
            ->where('status_pengerjaan', 'selesai')
            ->with('siswa')
            ->get();

        foreach ($sesiList as $sesi) {
            $siswa = $sesi->siswa;
            if (!$siswa || $siswa->trashed() || ($tes->id_kelas && $siswa->id_kelas !== $tes->id_kelas)) {
                $sesi->is_aktif = false;
            } else {
                $sesi->is_aktif = true;
            }
        }

        $sesiAktif = $sesiList->where('is_aktif', true);
        $sesiKeluar = $sesiList->where('is_aktif', false);

        $rataHistoris = round($sesiList->avg('total_skor'), 1); // (90+80+60)/3 = 76.7
        $rataTerkini = round($sesiAktif->avg('total_skor'), 1);   // (90+80)/2 = 85.0
        $delta = round($rataTerkini - $rataHistoris, 1);          // 85.0 - 76.7 = +8.3

        if ($sesiList->count() !== 3) { DB::rollBack(); return "Total peserta historis harus 3, got {$sesiList->count()}"; }
        if ($sesiAktif->count() !== 2) { DB::rollBack(); return "Total peserta aktif harus 2, got {$sesiAktif->count()}"; }
        if ($sesiKeluar->count() !== 1) { DB::rollBack(); return "Total peserta keluar harus 1, got {$sesiKeluar->count()}"; }
        if ($rataHistoris !== 76.7) { DB::rollBack(); return "Rata-rata historis salah: $rataHistoris"; }
        if ($rataTerkini !== 85.0) { DB::rollBack(); return "Rata-rata terkini salah: $rataTerkini"; }
        if ($delta !== 8.3) { DB::rollBack(); return "Delta salah: $delta"; }

        DB::rollBack();
        return true;
    } catch (\Throwable $e) {
        DB::rollBack();
        throw $e;
    }
});

// 7. Verifikasi Middleware Siswa Menolak Siswa yang di-Soft Delete
runTest("SiswaMiddleware memblokir akses siswa yang akunnya di-soft-delete", function() {
    DB::beginTransaction();
    try {
        $admin = Admin::first();
        $siswa = Siswa::create([
            'nama_siswa' => 'Siswa Trashed Block',
            'nisn'       => 'TEST99004',
            'id_admin'   => $admin->id_admin,
            'password'   => Hash::make('password'),
        ]);

        // Login kan siswa
        Auth::guard('siswa')->login($siswa);

        // Soft delete siswa
        $siswa->delete();

        // Jalankan SiswaMiddleware
        $middleware = new \App\Http\Middleware\SiswaMiddleware();
        $request = \Illuminate\Http\Request::create('/siswa/dashboard', 'GET');
        
        $response = $middleware->handle($request, function() {
            return response('Access Allowed');
        });

        // Harus redirect ke login
        $isRedirect = $response->isRedirect(route('login'));
        $isLoggedOut = !Auth::guard('siswa')->check();

        DB::rollBack();
        if (!$isRedirect) return "Middleware tidak melakukan redirect ke login";
        if (!$isLoggedOut) return "Siswa trashed masih berstatus login di guard";

        return true;
    } catch (\Throwable $e) {
        DB::rollBack();
        throw $e;
    }
});

echo "\n====================================================\n";
echo "HASIL: $passedTests / $totalTests Pengujian Berhasil (PASS)\n";
echo "====================================================\n";
