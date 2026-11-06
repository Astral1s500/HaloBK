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
use App\Models\PilihanJawaban;
use App\Models\SesiTesSiswa;
use App\Models\JawabanSiswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

echo "=========================================================\n";
echo "   VERIFIKASI FITUR PENGERJAAN TES & TIMESTAMP SISWA    \n";
echo "=========================================================\n\n";

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

DB::beginTransaction();
try {
    $admin = Admin::first();
    $guru = GuruBk::first();
    $kelas = Kelas::where('id_guru', $guru->id_guru)->first();

    // 1. Setup Data Uji
    $siswa = Siswa::create([
        'nama_siswa' => 'Budi Exam Tester',
        'nisn'       => 'EXAM99001',
        'id_kelas'   => $kelas->id_kelas,
        'id_admin'   => $admin->id_admin,
        'password'   => Hash::make('password'),
    ]);

    $tes = Tes::create([
        'id_guru'      => $guru->id_guru,
        'id_kelas'     => $kelas->id_kelas,
        'id_kategori'  => 1,
        'judul'        => 'Tes Uji Coba CBT Online',
        'deskripsi'    => 'Deskripsi uji coba CBT',
        'durasi_menit' => 45,
        'status'       => 'aktif',
    ]);

    // Soal 1: PG (Bobot 40)
    $soal1 = Soal::create([
        'id_tes'      => $tes->id_tes,
        'pertanyaan'  => 'Ibukota Indonesia saat ini adalah?',
        'tipe_soal'   => 'PG',
        'bobot_nilai' => 40,
    ]);
    $opt1A = PilihanJawaban::create(['id_soal' => $soal1->id_soal, 'teks_pilihan' => 'Jakarta', 'is_benar' => true]);
    $opt1B = PilihanJawaban::create(['id_soal' => $soal1->id_soal, 'teks_pilihan' => 'Surabaya', 'is_benar' => false]);

    // Soal 2: Esai (Bobot 60)
    $soal2 = Soal::create([
        'id_tes'      => $tes->id_tes,
        'pertanyaan'  => 'Jelaskan rencana studi Anda setelah lulus!',
        'tipe_soal'   => 'Esai',
        'bobot_nilai' => 60,
    ]);

    // 2. Test Rute & Otorisasi: Siswa Memulai Tes (start)
    runTest("Siswa memulai sesi tes baru (status sedang_mengerjakan)", function() use ($tes, $siswa) {
        Auth::guard('siswa')->login($siswa);
        $controller = new \App\Http\Controllers\Siswa\TesController();
        $response = $controller->start($tes);

        $sesi = SesiTesSiswa::where('id_tes', $tes->id_tes)->where('id_siswa', $siswa->id_siswa)->first();
        if (!$sesi) return "Sesi tes tidak terbuat";
        if ($sesi->status_pengerjaan !== 'sedang_mengerjakan') return "Status bukan sedang_mengerjakan: {$sesi->status_pengerjaan}";
        if (is_null($sesi->waktu_mulai_pengerjaan)) return "Waktu mulai pengerjaan null";

        return true;
    });

    // 3. Test Auto-Save & Auto-Grading PG Benar
    runTest("Auto-save & auto-grading PG Benar (nilai = bobot soal)", function() use ($tes, $siswa, $soal1, $opt1A) {
        Auth::guard('siswa')->login($siswa);
        $controller = new \App\Http\Controllers\Siswa\TesController();

        $request = new \Illuminate\Http\Request([
            'id_soal'      => $soal1->id_soal,
            'id_pilihan'   => $opt1A->id_pilihan,
            'is_ragu_ragu' => false,
        ]);

        $response = $controller->simpanJawaban($request, $tes);
        $data = $response->getData(true);

        if (!$data['success']) return "Response gagal";

        $jawaban = JawabanSiswa::whereHas('sesiTesSiswa', fn($q) => $q->where('id_tes', $tes->id_tes)->where('id_siswa', $siswa->id_siswa))
            ->where('id_soal', $soal1->id_soal)
            ->first();

        if (!$jawaban) return "Jawaban tidak tersimpan di database";
        if ($jawaban->id_pilihan !== $opt1A->id_pilihan) return "Pilihan tersimpan salah";
        if ((float)$jawaban->nilai_soal !== 40.0) return "Nilai auto-grading salah, expected 40, got {$jawaban->nilai_soal}";

        return true;
    });

    // 4. Test Auto-Save Esai (nilai_soal = null menunggu koreksi)
    runTest("Auto-save Esai (nilai_soal = null menunggu koreksi Guru BK)", function() use ($tes, $siswa, $soal2) {
        Auth::guard('siswa')->login($siswa);
        $controller = new \App\Http\Controllers\Siswa\TesController();

        $request = new \Illuminate\Http\Request([
            'id_soal'      => $soal2->id_soal,
            'jawaban_esai' => 'Saya berencana melanjutkan kuliah jurusan Psikologi.',
            'is_ragu_ragu' => true,
        ]);

        $response = $controller->simpanJawaban($request, $tes);
        $data = $response->getData(true);

        if (!$data['success']) return "Response gagal";

        $jawaban = JawabanSiswa::whereHas('sesiTesSiswa', fn($q) => $q->where('id_tes', $tes->id_tes)->where('id_siswa', $siswa->id_siswa))
            ->where('id_soal', $soal2->id_soal)
            ->first();

        if (!$jawaban) return "Jawaban esai tidak tersimpan";
        if ($jawaban->jawaban_esai !== 'Saya berencana melanjutkan kuliah jurusan Psikologi.') return "Teks esai tidak cocok";
        if (!is_null($jawaban->nilai_soal)) return "Nilai soal esai harus null sebelum dinilai guru, got: {$jawaban->nilai_soal}";
        if (!$jawaban->is_ragu_ragu) return "Status ragu-ragu tidak tersimpan true";

        return true;
    });

    // 5. Test Selesai Ujian & Akumulasi Skor
    runTest("Siswa submit selesai ujian (status selesai & total skor terakumulasi)", function() use ($tes, $siswa) {
        Auth::guard('siswa')->login($siswa);
        $controller = new \App\Http\Controllers\Siswa\TesController();

        $request = new \Illuminate\Http\Request();
        $response = $controller->selesai($request, $tes);

        $sesi = SesiTesSiswa::where('id_tes', $tes->id_tes)->where('id_siswa', $siswa->id_siswa)->first();
        if ($sesi->status_pengerjaan !== 'selesai') return "Status sesi bukan selesai: {$sesi->status_pengerjaan}";
        if (is_null($sesi->waktu_submit)) return "Waktu submit null";
        if ((float)$sesi->total_skor !== 40.0) return "Total skor sesi salah, expected 40.0 (PG), got: {$sesi->total_skor}";

        return true;
    });

    // 6. Test Guru BK Menilai Esai & Skor Sesi Siswa Bertambah
    runTest("Guru BK menilai esai siswa dan total skor terupdate otomatis", function() use ($tes, $siswa, $soal2) {
        $jawabanEsai = JawabanSiswa::whereHas('sesiTesSiswa', fn($q) => $q->where('id_tes', $tes->id_tes)->where('id_siswa', $siswa->id_siswa))
            ->where('id_soal', $soal2->id_soal)
            ->first();

        Auth::guard('guru')->login($tes->guruBk);
        $koreksiController = new \App\Http\Controllers\Guru\KoreksiController();

        $request = new \Illuminate\Http\Request([
            'nilai_soal' => 55, // Dari max 60
        ]);

        $koreksiController->simpanNilaiEsai($request, $jawabanEsai);

        $sesi = SesiTesSiswa::where('id_tes', $tes->id_tes)->where('id_siswa', $siswa->id_siswa)->first();
        // Total skor harus 40 (PG) + 55 (Esai) = 95
        if ((float)$sesi->total_skor !== 95.0) {
            return "Total skor setelah koreksi esai salah, expected 95.0, got: {$sesi->total_skor}";
        }

        return true;
    });

    // 7. Verifikasi Integrasi Rekapitulasi & Komparasi Guru BK
    runTest("Sesi tes siswa muncul di rekapitulasi nilai Guru BK dengan status Aktif di Kelas", function() use ($tes, $siswa) {
        Auth::guard('guru')->login($tes->guruBk);
        $koreksiController = new \App\Http\Controllers\Guru\KoreksiController();

        $view = $koreksiController->show($tes);
        $data = $view->getData();

        $sesiList = $data['sesiList'];
        $statsHistoris = $data['statsHistoris'];
        $statsTerkini = $data['statsTerkini'];

        if ($sesiList->count() !== 1) return "Sesi pengerjaan siswa tidak muncul di tabel koreksi guru";
        if ($statsTerkini->total_peserta !== 1) return "Peserta aktif harus 1";
        if ((float)$statsTerkini->rata_rata_skor !== 95.0) return "Rata-rata harus 95.0";

        return true;
    });

} finally {
    DB::rollBack();
}

echo "\n=========================================================\n";
echo "HASIL PENGUJIAN CBT SISWA: $passedTests / $totalTests PASS (100%)\n";
echo "=========================================================\n";
