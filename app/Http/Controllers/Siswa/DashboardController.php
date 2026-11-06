<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\SesiTesSiswa;
use App\Models\Siswa;
use App\Models\Tes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan Dashboard Siswa dengan data kelas, tes aktif, materi, dan statistik pengerjaan.
     */
    public function index()
    {
        /** @var Siswa $siswa */
        $siswa = Auth::guard('siswa')->user();
        $siswa->load(['kelas.guruBk']);

        $kelas = $siswa->kelas;
        $totalTeman = 0;
        $tesAktif = collect();
        $materiList = collect();
        $riwayatTes = collect();

        if ($kelas) {
            // Jumlah siswa dalam kelas yang sama
            $totalTeman = Siswa::where('id_kelas', $kelas->id_kelas)->count();

            // Daftar teman sekelas (untuk tampilan anggota ala Google Classroom)
            $temanSekelas = Siswa::where('id_kelas', $kelas->id_kelas)
                ->select(['id_siswa', 'nama_siswa', 'nisn'])
                ->orderBy('nama_siswa', 'asc')
                ->get();

            // Tes yang aktif untuk kelas siswa
            $tesAktif = Tes::where('id_kelas', $kelas->id_kelas)
                ->where('status', 'aktif')
                ->with(['kategori', 'soals'])
                ->withCount('soals')
                ->latest()
                ->get();

            // Ambil sesi tes siswa untuk setiap tes aktif untuk menentukan statusnya
            $sesiSiswaMap = SesiTesSiswa::where('id_siswa', $siswa->id_siswa)
                ->whereIn('id_tes', $tesAktif->pluck('id_tes'))
                ->get()
                ->keyBy('id_tes');

            // Petakan status sesi ke dalam koleksi tesAktif
            $tesAktif->each(function ($tes) use ($sesiSiswaMap) {
                $tes->sesi_siswa = $sesiSiswaMap->get($tes->id_tes);
            });

            // Materi bimbingan yang ditujukan khusus untuk kelas siswa ini
            $materiList = Materi::where(function ($q) use ($kelas) {
                    $q->where('id_kelas', $kelas->id_kelas)
                      ->orWhereNull('id_kelas');
                })
                ->with(['kategori', 'guruBk', 'kelas'])
                ->latest()
                ->take(6)
                ->get();
        } else {
            $temanSekelas = collect();
        }

        // Riwayat tes yang sudah selesai dikerjakan oleh siswa ini
        $riwayatTes = SesiTesSiswa::where('id_siswa', $siswa->id_siswa)
            ->where('status_pengerjaan', 'selesai')
            ->with(['tes.kategori', 'tes.kelas'])
            ->latest('waktu_submit')
            ->take(5)
            ->get();

        // Hitung statistik untuk KPI Card & Progress Bar
        $totalTesSelesai = SesiTesSiswa::where('id_siswa', $siswa->id_siswa)
            ->where('status_pengerjaan', 'selesai')
            ->count();

        $rataRataSkor = SesiTesSiswa::where('id_siswa', $siswa->id_siswa)
            ->where('status_pengerjaan', 'selesai')
            ->avg('total_skor') ?? 0;

        $totalTesAktif = $tesAktif->where(fn($t) => empty($t->sesi_siswa) || $t->sesi_siswa->status_pengerjaan !== 'selesai')->count();
        $totalTesKeseluruhan = $tesAktif->count();
        $persenSelesai = $totalTesKeseluruhan > 0 
            ? round((($totalTesKeseluruhan - $totalTesAktif) / $totalTesKeseluruhan) * 100) 
            : 0;

        $stats = (object) [
            'total_tes_aktif'   => $totalTesAktif,
            'total_tes_selesai' => $totalTesSelesai,
            'rata_rata_skor'    => round($rataRataSkor, 1),
            'total_materi'      => $materiList->count(),
            'total_teman'       => $totalTeman,
            'persen_selesai'    => $persenSelesai,
            'total_tes_kelas'   => $totalTesKeseluruhan,
        ];

        return view('siswa.dashboard', compact('siswa', 'kelas', 'tesAktif', 'materiList', 'riwayatTes', 'stats', 'temanSekelas'));
    }

    /**
     * Memproses aksi bergabung ke kelas menggunakan Kode Unik Kelas.
     */
    public function joinKelas(Request $request)
    {
        $request->validate([
            'kode_unik_kelas' => ['required', 'string', 'max:50'],
        ], [
            'kode_unik_kelas.required' => 'Kode unik kelas wajib diisi.',
        ]);

        $kode = strtoupper(trim($request->kode_unik_kelas));
        $kelas = Kelas::where('kode_unik_kelas', $kode)->first();

        if (!$kelas) {
            return back()->with('error', 'Kode kelas "' . $kode . '" tidak ditemukan. Pastikan kode sudah benar atau tanyakan kepada Guru BK Anda.');
        }

        /** @var Siswa $siswa */
        $siswa = Auth::guard('siswa')->user();

        if ($siswa->id_kelas == $kelas->id_kelas) {
            return back()->with('info', 'Anda sudah terdaftar di kelas ' . $kelas->nama_kelas . '.');
        }

        $siswa->update([
            'id_kelas' => $kelas->id_kelas,
        ]);

        return redirect()->route('siswa.dashboard')
            ->with('success', 'Berhasil bergabung ke kelas ' . $kelas->nama_kelas . ' (Guru BK: ' . ($kelas->guruBk->nama_guru ?? 'Guru BK') . ')!');
    }

    /**
     * Siswa keluar dari kelas saat ini.
     */
    public function leaveKelas(Request $request)
    {
        /** @var Siswa $siswa */
        $siswa = Auth::guard('siswa')->user();

        if (!$siswa->id_kelas) {
            return back()->with('error', 'Anda belum terdaftar di kelas mana pun.');
        }

        $namaKelas = $siswa->kelas->nama_kelas ?? 'Kelas';

        $siswa->update([
            'id_kelas' => null,
        ]);

        return redirect()->route('siswa.dashboard')
            ->with('success', 'Anda telah berhasil keluar dari kelas ' . $namaKelas . '. Silakan masukkan kode kelas baru.');
    }
}
