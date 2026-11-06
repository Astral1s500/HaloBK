<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\JawabanSiswa;
use App\Models\PilihanJawaban;
use App\Models\SesiTesSiswa;
use App\Models\Soal;
use App\Models\Tes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TesController extends Controller
{
    /**
     * Halaman pengantar & konfirmasi sebelum siswa mulai mengerjakan tes.
     */
    public function show(Tes $te): View|RedirectResponse
    {
        $this->authorizeAksesTes($te);

        $siswa = Auth::guard('siswa')->user();
        $te->load(['kelas.guruBk', 'kategori', 'soals.pilihanJawabans']);

        $sesi = SesiTesSiswa::where('id_tes', $te->id_tes)
            ->where('id_siswa', $siswa->id_siswa)
            ->first();

        // Jika sudah selesai, langsung arahkan ke halaman hasil
        if ($sesi && $sesi->status_pengerjaan === 'selesai') {
            return redirect()->route('siswa.tes.hasil', $te->id_tes);
        }

        $totalSoal = $te->soals->count();
        $totalPG = $te->soals->where('tipe_soal', 'PG')->count();
        $totalEsai = $te->soals->where('tipe_soal', 'Esai')->count();
        $bobotTotal = $te->soals->sum('bobot_nilai');

        return view('siswa.tes.show', [
            'tes'        => $te,
            'sesi'       => $sesi,
            'totalSoal'  => $totalSoal,
            'totalPG'    => $totalPG,
            'totalEsai'  => $totalEsai,
            'bobotTotal' => $bobotTotal,
        ]);
    }

    /**
     * Memulai sesi tes baru atau melanjutkan sesi yang sedang berjalan.
     */
    public function start(Tes $te): RedirectResponse
    {
        $this->authorizeAksesTes($te);

        $siswa = Auth::guard('siswa')->user();

        if ($te->soals()->count() === 0) {
            return back()->with('error', 'Paket tes ini belum memiliki butir soal yang dapat dikerjakan.');
        }

        $sesi = SesiTesSiswa::firstOrCreate(
            [
                'id_tes'   => $te->id_tes,
                'id_siswa' => $siswa->id_siswa,
            ],
            [
                'waktu_mulai_pengerjaan' => now(),
                'status_pengerjaan'      => 'sedang_mengerjakan',
                'total_skor'             => 0,
            ]
        );

        if ($sesi->status_pengerjaan === 'selesai') {
            return redirect()->route('siswa.tes.hasil', $te->id_tes);
        }

        // Jika sebelumnya belum ada waktu mulai (atau belum_mulai)
        if ($sesi->status_pengerjaan === 'belum_mulai' || is_null($sesi->waktu_mulai_pengerjaan)) {
            $sesi->update([
                'waktu_mulai_pengerjaan' => now(),
                'status_pengerjaan'      => 'sedang_mengerjakan',
            ]);
        }

        return redirect()->route('siswa.tes.kerjakan', $te->id_tes);
    }

    /**
     * Ruang ujian online CBT siswa.
     */
    public function kerjakan(Tes $te): View|RedirectResponse
    {
        $this->authorizeAksesTes($te);

        $siswa = Auth::guard('siswa')->user();

        $sesi = SesiTesSiswa::where('id_tes', $te->id_tes)
            ->where('id_siswa', $siswa->id_siswa)
            ->first();

        if (!$sesi) {
            return redirect()->route('siswa.tes.show', $te->id_tes);
        }

        if ($sesi->status_pengerjaan === 'selesai') {
            return redirect()->route('siswa.tes.hasil', $te->id_tes);
        }

        // Hitung sisa waktu pengerjaan
        $waktuMulai = $sesi->waktu_mulai_pengerjaan ?? now();
        $waktuSelesaiMaksimal = $waktuMulai->copy()->addMinutes((int) $te->durasi_menit);
        $sisaDetik = now()->diffInSeconds($waktuSelesaiMaksimal, false);

        // Jika waktu pengerjaan telah habis, otomatis selesaikan ujian
        if ($sisaDetik <= 0) {
            $this->finalizeSesi($sesi);
            return redirect()->route('siswa.tes.hasil', $te->id_tes)
                ->with('info', 'Waktu pengerjaan tes telah berakhir. Seluruh jawaban Anda telah disimpan secara otomatis.');
        }

        // Ambil butir soal dan jawaban yang tersimpan
        $soals = $te->soals()->with('pilihanJawabans')->orderBy('id_soal')->get();
        $jawabanMap = $sesi->jawabanSiswas->keyBy('id_soal');

        return view('siswa.tes.kerjakan', [
            'tes'         => $te,
            'sesi'        => $sesi,
            'soals'       => $soals,
            'jawabanMap'  => $jawabanMap,
            'sisaDetik'   => max(0, $sisaDetik),
            'waktuMaks'   => $waktuSelesaiMaksimal,
        ]);
    }

    /**
     * Menyimpan jawaban per butir soal via AJAX (Auto-Save).
     */
    public function simpanJawaban(Request $request, Tes $te): JsonResponse
    {
        $this->authorizeAksesTes($te);

        $siswa = Auth::guard('siswa')->user();

        $sesi = SesiTesSiswa::where('id_tes', $te->id_tes)
            ->where('id_siswa', $siswa->id_siswa)
            ->first();

        if (!$sesi || $sesi->status_pengerjaan !== 'sedang_mengerjakan') {
            return response()->json(['error' => 'Sesi tes tidak aktif.'], 403);
        }

        // Cek kedaluwarsa waktu
        $waktuMulai = $sesi->waktu_mulai_pengerjaan ?? now();
        $waktuSelesaiMaksimal = $waktuMulai->copy()->addMinutes((int) $te->durasi_menit);
        if (now()->greaterThan($waktuSelesaiMaksimal)) {
            $this->finalizeSesi($sesi);
            return response()->json(['expired' => true, 'redirect' => route('siswa.tes.hasil', $te->id_tes)], 410);
        }

        $validated = $request->validate([
            'id_soal'       => ['required', 'exists:soals,id_soal'],
            'id_pilihan'    => ['nullable', 'exists:pilihan_jawabans,id_pilihan'],
            'jawaban_esai'  => ['nullable', 'string'],
            'is_ragu_ragu'  => ['nullable', 'boolean'],
        ]);

        $soal = Soal::where('id_soal', $validated['id_soal'])
            ->where('id_tes', $te->id_tes)
            ->firstOrFail();

        $jawaban = JawabanSiswa::firstOrNew([
            'id_sesi' => $sesi->id_sesi,
            'id_soal' => $soal->id_soal,
        ]);

        $jawaban->is_ragu_ragu = $request->boolean('is_ragu_ragu');

        if ($soal->tipe_soal === 'PG') {
            $jawaban->id_pilihan = $validated['id_pilihan'] ?? null;
            $jawaban->jawaban_esai = null;

            // Auto-grade soal PG secara instan
            if ($jawaban->id_pilihan) {
                $pilihan = PilihanJawaban::where('id_pilihan', $jawaban->id_pilihan)
                    ->where('id_soal', $soal->id_soal)
                    ->first();

                $jawaban->nilai_soal = ($pilihan && $pilihan->is_benar) ? (float) $soal->bobot_nilai : 0.0;
            } else {
                $jawaban->nilai_soal = 0.0;
            }
        } else {
            // Soal tipe Esai
            $jawaban->jawaban_esai = $validated['jawaban_esai'] ?? null;
            $jawaban->id_pilihan = null;
            // Menunggu koreksi guru BK
            $jawaban->nilai_soal = null;
        }

        $jawaban->save();

        // Update skor sementara pada sesi
        $sesi->update([
            'total_skor' => $sesi->jawabanSiswas()->whereNotNull('nilai_soal')->sum('nilai_soal'),
        ]);

        $isTerjawab = ($soal->tipe_soal === 'PG' && !is_null($jawaban->id_pilihan)) 
                   || ($soal->tipe_soal === 'Esai' && !empty(trim((string)$jawaban->jawaban_esai)));

        return response()->json([
            'success'      => true,
            'is_terjawab'  => $isTerjawab,
            'is_ragu_ragu' => (bool) $jawaban->is_ragu_ragu,
        ]);
    }

    /**
     * Siswa menyelesaikan ujian secara manual (Submit Final).
     */
    public function selesai(Request $request, Tes $te): RedirectResponse
    {
        $this->authorizeAksesTes($te);

        $siswa = Auth::guard('siswa')->user();

        $sesi = SesiTesSiswa::where('id_tes', $te->id_tes)
            ->where('id_siswa', $siswa->id_siswa)
            ->firstOrFail();

        $this->finalizeSesi($sesi);

        return redirect()->route('siswa.tes.hasil', $te->id_tes)
            ->with('success', 'Selamat! Anda telah berhasil menyelesaikan tes ' . $te->judul . '.');
    }

    /**
     * Menampilkan halaman rangkuman hasil dan skor tes siswa dengan analisis profesional.
     */
    public function hasil(Tes $te): View|RedirectResponse
    {
        $this->authorizeAksesTes($te);

        $siswa = Auth::guard('siswa')->user();
        $siswa->load('kelas');

        $sesi = SesiTesSiswa::where('id_tes', $te->id_tes)
            ->where('id_siswa', $siswa->id_siswa)
            ->first();

        if (!$sesi) {
            return redirect()->route('siswa.tes.show', $te->id_tes);
        }

        $te->load(['kelas.guruBk', 'kategori', 'soals.pilihanJawabans']);

        $totalSoal = $te->soals->count();
        $totalEsai = $te->soals->where('tipe_soal', 'Esai')->count();
        $totalPG = $te->soals->where('tipe_soal', 'PG')->count();
        $bobotTotal = (float) $te->soals->sum('bobot_nilai');

        $jawabanList = $sesi->jawabanSiswas()
            ->with(['soal.pilihanJawabans', 'pilihanJawaban'])
            ->get()
            ->keyBy('id_soal');

        // Analisis PG
        $pgBenar = 0;
        $pgSalah = 0;
        $pgKosong = 0;

        foreach ($te->soals->where('tipe_soal', 'PG') as $soal) {
            $jawaban = $jawabanList->get($soal->id_soal);
            if ($jawaban && !is_null($jawaban->id_pilihan)) {
                if ((float) $jawaban->nilai_soal > 0) {
                    $pgBenar++;
                } else {
                    $pgSalah++;
                }
            } else {
                $pgKosong++;
            }
        }

        // Analisis Esai
        $esaiBelumDinilai = 0;
        $esaiSudahDinilai = 0;
        foreach ($te->soals->where('tipe_soal', 'Esai') as $soal) {
            $jawaban = $jawabanList->get($soal->id_soal);
            if ($jawaban && !is_null($jawaban->nilai_soal)) {
                $esaiSudahDinilai++;
            } else {
                $esaiBelumDinilai++;
            }
        }

        // Persentase & Predikat Skor
        $totalSkor = (float) $sesi->total_skor;
        $persentase = $bobotTotal > 0 ? round(($totalSkor / $bobotTotal) * 100, 1) : 0;

        if ($persentase >= 85) {
            $predikat = (object) [
                'grade' => 'A',
                'label' => 'Sangat Memuaskan',
                'badge' => 'emerald',
                'pesan' => 'Luar biasa! Pemahaman Anda terhadap materi asesmen ini sangat komprehensif dan memuaskan.',
            ];
        } elseif ($persentase >= 75) {
            $predikat = (object) [
                'grade' => 'B',
                'label' => 'Baik / Kompeten',
                'badge' => 'blue',
                'pesan' => 'Kerja bagus! Anda telah mencapai standar kompetensi bimbingan yang diharapkan dengan baik.',
            ];
        } elseif ($persentase >= 60) {
            $predikat = (object) [
                'grade' => 'C',
                'label' => 'Cukup',
                'badge' => 'amber',
                'pesan' => 'Cukup baik. Disarankan untuk mempelajari kembali modul edukasi terkait guna memperdalam pemahaman.',
            ];
        } else {
            $predikat = (object) [
                'grade' => 'D',
                'label' => 'Perlu Pendampingan',
                'badge' => 'rose',
                'pesan' => 'Disarankan untuk berkonsultasi dan mendapatkan bimbingan lebih lanjut dengan Guru BK pengampu.',
            ];
        }

        // Durasi Pengerjaan (Menggunakan integer math agar tidak ada angka desimal berlebih)
        $durasiPengerjaanText = '-';
        if ($sesi->waktu_mulai_pengerjaan && $sesi->waktu_submit) {
            $totalDetik = (int) abs($sesi->waktu_mulai_pengerjaan->diffInSeconds($sesi->waktu_submit));
            $menit = (int) floor($totalDetik / 60);
            $detik = (int) ($totalDetik % 60);

            if ($menit > 0 && $detik > 0) {
                $durasiPengerjaanText = "{$menit} Menit {$detik} Detik";
            } elseif ($menit > 0) {
                $durasiPengerjaanText = "{$menit} Menit";
            } else {
                $durasiPengerjaanText = "{$detik} Detik";
            }
        }

        $stats = (object) [
            'total_soal'         => $totalSoal,
            'total_pg'           => $totalPG,
            'total_esai'         => $totalEsai,
            'bobot_total'        => $bobotTotal,
            'pg_benar'           => $pgBenar,
            'pg_salah'           => $pgSalah,
            'pg_kosong'          => $pgKosong,
            'akurasi_pg'         => $totalPG > 0 ? round(($pgBenar / $totalPG) * 100, 1) : 0,
            'esai_belum_dinilai' => $esaiBelumDinilai,
            'esai_sudah_dinilai' => $esaiSudahDinilai,
            'persentase'         => $persentase,
        ];

        return view('siswa.tes.hasil', [
            'siswa'            => $siswa,
            'tes'              => $te,
            'sesi'             => $sesi,
            'stats'            => $stats,
            'predikat'         => $predikat,
            'jawabanList'      => $jawabanList,
            'durasiPengerjaan' => $durasiPengerjaanText,
        ]);
    }

    /**
     * Helper: Menandai sesi selesai dan mengakumulasi skor total.
     */
    private function finalizeSesi(SesiTesSiswa $sesi): void
    {
        $totalSkor = $sesi->jawabanSiswas()->whereNotNull('nilai_soal')->sum('nilai_soal');

        $sesi->update([
            'status_pengerjaan' => 'selesai',
            'waktu_submit'      => now(),
            'total_skor'        => $totalSkor,
        ]);
    }

    /**
     * Validasi hak akses siswa terhadap paket tes.
     */
    private function authorizeAksesTes(Tes $tes): void
    {
        $siswa = Auth::guard('siswa')->user();

        // Tes harus berstatus aktif
        if ($tes->status !== 'aktif') {
            abort(403, 'Paket tes ini tidak sedang aktif atau telah diarsipkan oleh Guru BK.');
        }

        // Siswa harus terdaftar di kelas target (jika tes spesifik untuk kelas tertentu)
        if ($tes->id_kelas && $siswa->id_kelas !== $tes->id_kelas) {
            abort(403, 'Paket tes ini ditujukan khusus untuk kelas binaan lain.');
        }
    }
}
