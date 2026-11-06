<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\JawabanSiswa;
use App\Models\SesiTesSiswa;
use App\Models\Tes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KoreksiController extends Controller
{
    /**
     * Menampilkan daftar seluruh paket tes yang sudah ada sesi pengerjaan siswa.
     * Ini adalah halaman utama koreksi, menampilkan ringkasan per paket tes.
     */
    public function index()
    {
        $guruId = Auth::guard('guru')->id();

        $tesList = Tes::where('id_guru', $guruId)
            ->withCount([
                'sesiTesSiswas as total_sesi',
                'sesiTesSiswas as total_selesai' => function ($q) {
                    $q->where('status_pengerjaan', 'selesai');
                },
                'soals as total_soal',
                'soals as total_esai' => function ($q) {
                    $q->where('tipe_soal', 'Esai');
                },
            ])
            ->with('kelas')
            ->having('total_sesi', '>', 0)
            ->latest()
            ->get();

        // Hitung berapa jawaban esai yang belum dinilai per tes serta hitung soal paling rawan
        foreach ($tesList as $tes) {
            $tes->esai_belum_dinilai = JawabanSiswa::whereHas('sesiTesSiswa', function ($q) use ($tes) {
                $q->where('id_tes', $tes->id_tes)->where('status_pengerjaan', 'selesai');
            })->whereHas('soal', function ($q) {
                $q->where('tipe_soal', 'Esai');
            })->whereNull('nilai_soal')->count();

            // Hitung soal paling rawan jika sudah ada sesi selesai
            $tes->soal_paling_rawan = null;
            if ($tes->total_selesai > 0 && $tes->total_soal > 0) {
                $sesiSelesaiIds = SesiTesSiswa::where('id_tes', $tes->id_tes)->where('status_pengerjaan', 'selesai')->pluck('id_sesi');
                $soals = $tes->soals()->with('pilihanJawabans')->orderBy('id_soal')->get();
                $highestErrorPct = -1;
                $mostCriticalNumber = 0;

                foreach ($soals as $idx => $s) {
                    $jawabanS = JawabanSiswa::where('id_soal', $s->id_soal)->whereIn('id_sesi', $sesiSelesaiIds)->get();
                    if ($s->isPilihanGanda()) {
                        $benar = $jawabanS->filter(fn($j) => $j->pilihanJawaban && $j->pilihanJawaban->is_benar)->count();
                    } else {
                        $passing = $s->bobot_nilai > 0 ? ($s->bobot_nilai * 0.5) : 1;
                        $benar = $jawabanS->filter(fn($j) => ($j->nilai_soal !== null) && ($j->nilai_soal >= $passing))->count();
                    }
                    $salah = $tes->total_selesai - $benar;
                    $errorPct = round(($salah / $tes->total_selesai) * 100, 1);

                    if ($errorPct > $highestErrorPct) {
                        $highestErrorPct = $errorPct;
                        $mostCriticalNumber = $idx + 1;
                    }
                }

                if ($highestErrorPct > 0) {
                    $tes->soal_paling_rawan = (object) [
                        'nomor'        => $mostCriticalNumber,
                        'persentase'   => $highestErrorPct,
                        'is_kritis'    => $highestErrorPct >= 50,
                    ];
                }
            }
        }

        return view('guru.koreksi.index', compact('tesList'));
    }

    /**
     * Menampilkan daftar seluruh siswa yang sudah mengerjakan paket tes tertentu.
     * Guru bisa melihat skor masing-masing siswa (rekapitulasi) dan masuk ke koreksi esai.
     */
    public function show(Tes $te)
    {
        $this->authorizeTes($te);

        $te->load(['kelas', 'soals']);

        $sesiList = SesiTesSiswa::where('id_tes', $te->id_tes)
            ->where('status_pengerjaan', 'selesai')
            ->with('siswa')
            ->withCount([
                'jawabanSiswas as total_jawaban',
                'jawabanSiswas as jawaban_esai_count' => function ($q) {
                    $q->whereHas('soal', fn($s) => $s->where('tipe_soal', 'Esai'));
                },
                'jawabanSiswas as esai_belum_dinilai' => function ($q) {
                    $q->whereHas('soal', fn($s) => $s->where('tipe_soal', 'Esai'))
                      ->whereNull('nilai_soal');
                },
            ])
            ->orderBy('total_skor', 'desc')
            ->get();

        // Tandai status keanggotaan setiap sesi pengerjaan siswa
        foreach ($sesiList as $sesi) {
            $siswa = $sesi->siswa;
            if (!$siswa) {
                $sesi->status_keanggotaan = 'deleted_permanent';
                $sesi->status_label = 'Data Siswa Dihapus Permanen';
                $sesi->is_aktif = false;
            } elseif ($siswa->trashed()) {
                $sesi->status_keanggotaan = 'soft_deleted';
                $sesi->status_label = 'Akun Dinonaktifkan / Arsip (' . ($siswa->deleted_at ? $siswa->deleted_at->format('d/m/Y') : '-') . ')';
                $sesi->is_aktif = false;
            } elseif ($te->id_kelas && $siswa->id_kelas !== $te->id_kelas) {
                $sesi->status_keanggotaan = 'keluar_kelas';
                $sesi->status_label = 'Keluar / Pindah Kelas';
                $sesi->is_aktif = false;
            } else {
                $sesi->status_keanggotaan = 'aktif';
                $sesi->status_label = 'Aktif di Kelas';
                $sesi->is_aktif = true;
            }
        }

        $sesiAktifList = $sesiList->where('is_aktif', true);
        $sesiKeluarList = $sesiList->where('is_aktif', false);

        // 1. Statistik Historis (Semua Peserta, termasuk yang sudah keluar) - Basis laporan lama ke pusat
        $statsHistoris = (object) [
            'total_peserta'  => $sesiList->count(),
            'rata_rata_skor' => $sesiList->count() > 0 ? round($sesiList->avg('total_skor'), 1) : 0,
            'skor_tertinggi' => $sesiList->max('total_skor') ?? 0,
            'skor_terendah'  => $sesiList->min('total_skor') ?? 0,
        ];

        // 2. Statistik Terkini (Hanya Siswa yang Masih Aktif di Kelas) - Revisi jika diminta pusat
        $statsTerkini = (object) [
            'total_peserta'  => $sesiAktifList->count(),
            'rata_rata_skor' => $sesiAktifList->count() > 0 ? round($sesiAktifList->avg('total_skor'), 1) : 0,
            'skor_tertinggi' => $sesiAktifList->max('total_skor') ?? 0,
            'skor_terendah'  => $sesiAktifList->min('total_skor') ?? 0,
        ];

        // 3. Data Komparasi & Selisih (Delta)
        $komparasi = (object) [
            'total_siswa_keluar' => $sesiKeluarList->count(),
            'delta_rata_rata'    => round($statsTerkini->rata_rata_skor - $statsHistoris->rata_rata_skor, 1),
            'bobot_total'        => $te->soals->sum('bobot_nilai'),
            'total_soal'         => $te->soals->count(),
            'total_esai'         => $te->soals->where('tipe_soal', 'Esai')->count(),
        ];

        // 4. Analisis Butir Soal & Peta Kerawanan (Item Error Analysis)
        $totalPesertaSelesai = $sesiList->count();
        $analisisSoal = collect();

        $soalsOrdered = $te->soals()->with('pilihanJawabans')->orderBy('id_soal')->get();

        foreach ($soalsOrdered as $index => $soal) {
            $jawabanCollection = JawabanSiswa::where('id_soal', $soal->id_soal)
                ->whereIn('id_sesi', $sesiList->pluck('id_sesi'))
                ->with('pilihanJawaban')
                ->get();

            $distribusiPilihan = [];
            $pengecohUtama = null;

            if ($soal->isPilihanGanda()) {
                $pilihanBenar = $soal->pilihanJawabans->firstWhere('is_benar', true);
                $jumlahBenar = $jawabanCollection->filter(fn($j) => $j->pilihanJawaban && $j->pilihanJawaban->is_benar)->count();
                $jumlahSalah = $totalPesertaSelesai - $jumlahBenar;
                $rataRataSkor = $totalPesertaSelesai > 0 ? round(($jumlahBenar * $soal->bobot_nilai) / $totalPesertaSelesai, 1) : 0;

                foreach ($soal->pilihanJawabans as $pil) {
                    $pemilih = $jawabanCollection->where('id_pilihan', $pil->id_pilihan)->count();
                    $pctPemilih = $totalPesertaSelesai > 0 ? round(($pemilih / $totalPesertaSelesai) * 100, 1) : 0;
                    $distribusiPilihan[] = (object) [
                        'id_pilihan'    => $pil->id_pilihan,
                        'teks_pilihan'  => $pil->teks_pilihan,
                        'is_benar'      => (bool) $pil->is_benar,
                        'total_pemilih' => $pemilih,
                        'persentase'    => $pctPemilih,
                    ];
                }

                $opsiSalah = collect($distribusiPilihan)->where('is_benar', false);
                $pengecohUtama = $opsiSalah->sortByDesc('total_pemilih')->first();
            } else {
                $pilihanBenar = null;
                $passingScore = $soal->bobot_nilai > 0 ? ($soal->bobot_nilai * 0.5) : 1;
                $jumlahBenar = $jawabanCollection->filter(fn($j) => ($j->nilai_soal !== null) && ($j->nilai_soal >= $passingScore))->count();
                $jumlahSalah = $totalPesertaSelesai - $jumlahBenar;
                $rataRataSkor = $jawabanCollection->whereNotNull('nilai_soal')->count() > 0 
                    ? round($jawabanCollection->whereNotNull('nilai_soal')->avg('nilai_soal'), 1) 
                    : 0;
            }

            $persentaseSalah = $totalPesertaSelesai > 0 ? round(($jumlahSalah / $totalPesertaSelesai) * 100, 1) : 0;
            $persentaseBenar = $totalPesertaSelesai > 0 ? round(($jumlahBenar / $totalPesertaSelesai) * 100, 1) : 0;

            if ($persentaseSalah >= 60) {
                $tingkatKerawanan = 'sangat_rawan';
                $badgeKerawanan = 'Sangat Rawan';
                $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200';
                $barColor = 'bg-rose-500';
                $rekomendasi = 'Mayoritas siswa salah pada soal ini. Disarankan melakukan pembahasan ulang konsep terkait saat sesi bimbingan.';
            } elseif ($persentaseSalah >= 30) {
                $tingkatKerawanan = 'cukup_rawan';
                $badgeKerawanan = 'Cukup Rawan';
                $badgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
                $barColor = 'bg-amber-500';
                $rekomendasi = 'Tingkat kesulitan sedang atau terdapat pengecoh. Berikan penekanan konsep pada materi ini.';
            } else {
                $tingkatKerawanan = 'aman';
                $badgeKerawanan = 'Dikuasai Baik';
                $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                $barColor = 'bg-emerald-500';
                $rekomendasi = 'Siswa telah menguasai butir pertanyaan ini dengan baik.';
            }

            $analisisSoal->push((object) [
                'nomor'               => $index + 1,
                'id_soal'             => $soal->id_soal,
                'soal'                => $soal,
                'tipe_soal'           => $soal->tipe_soal,
                'pertanyaan'          => $soal->pertanyaan,
                'bobot_nilai'         => $soal->bobot_nilai,
                'gambar_lampiran'     => $soal->gambar_lampiran,
                'jumlah_benar'        => $jumlahBenar,
                'jumlah_salah'        => $jumlahSalah,
                'persentase_benar'    => $persentaseBenar,
                'persentase_salah'    => $persentaseSalah,
                'rata_rata_skor'      => $rataRataSkor,
                'tingkat_kerawanan'   => $tingkatKerawanan,
                'badge_kerawanan'     => $badgeKerawanan,
                'badge_class'         => $badgeClass,
                'bar_color'           => $barColor,
                'rekomendasi'         => $rekomendasi,
                'pilihan_jawabans'    => $distribusiPilihan,
                'pengecoh_utama'      => $pengecohUtama,
                'pilihan_benar'       => $pilihanBenar,
            ]);
        }

        // Urutkan soal paling rawan (persentase salah tertinggi di atas)
        $soalPalingRawanList = $analisisSoal->sortByDesc('persentase_salah')->values();
        $soalPalingRawanUtama = ($totalPesertaSelesai > 0 && $soalPalingRawanList->isNotEmpty() && $soalPalingRawanList->first()->persentase_salah > 0)
            ? $soalPalingRawanList->first()
            : null;

        $totalSoalKritis = $analisisSoal->where('tingkat_kerawanan', 'sangat_rawan')->count();
        $totalSoalCukupRawan = $analisisSoal->where('tingkat_kerawanan', 'cukup_rawan')->count();
        $totalSoalAman = $analisisSoal->where('tingkat_kerawanan', 'aman')->count();

        // Analisis Psikometrik Kondisi Kelas Keseluruhan
        $analisisKelas = \App\Services\PsikometrikService::analisisHasilKelas($te);

        return view('guru.koreksi.show', [
            'tes'                  => $te,
            'sesiList'             => $sesiList,
            'statsHistoris'        => $statsHistoris,
            'statsTerkini'         => $statsTerkini,
            'komparasi'            => $komparasi,
            'analisisSoal'         => $analisisSoal,
            'soalPalingRawanUtama' => $soalPalingRawanUtama,
            'totalSoalKritis'      => $totalSoalKritis,
            'totalSoalCukupRawan'  => $totalSoalCukupRawan,
            'totalSoalAman'        => $totalSoalAman,
            'analisisKelas'        => $analisisKelas,
        ]);
    }

    /**
     * Menampilkan halaman koreksi jawaban dari satu siswa pada satu paket tes.
     * Guru dapat melihat seluruh jawaban PG (otomatis dikoreksi) dan jawaban Esai (perlu penilaian manual).
     */
    public function koreksiSiswa(SesiTesSiswa $sesi)
    {
        $this->authorizeTes($sesi->tes);

        $sesi->load([
            'siswa',
            'tes.kelas',
            'tes.soals.pilihanJawabans',
            'jawabanSiswas.soal.pilihanJawabans',
            'jawabanSiswas.pilihanJawaban',
        ]);

        // Urutkan soal sesuai urutan ID soal
        $soals = $sesi->tes->soals->sortBy('id_soal')->values();

        // Buat mapping id_soal => jawaban siswa
        $jawabanMap = $sesi->jawabanSiswas->keyBy('id_soal');

        // Analisis Profil Psikometrik Individual Siswa untuk Konseling & Tindak Lanjut Ortu
        $laporanPsikometrik = \App\Services\PsikometrikService::analisisHasilSiswa($sesi);

        return view('guru.koreksi.koreksi_siswa', [
            'sesi'               => $sesi,
            'tes'                => $sesi->tes,
            'siswa'              => $sesi->siswa,
            'soals'              => $soals,
            'jawabanMap'         => $jawabanMap,
            'laporanPsikometrik' => $laporanPsikometrik,
        ]);
    }

    /**
     * Menyimpan nilai manual Guru untuk jawaban esai satu per satu.
     */
    public function simpanNilaiEsai(Request $request, JawabanSiswa $jawaban)
    {
        // Pastikan jawaban ini milik soal Esai dan tes milik guru ini
        $this->authorizeTes($jawaban->sesiTesSiswa->tes);

        $request->validate([
            'nilai_soal' => ['required', 'numeric', 'min:0', 'max:' . $jawaban->soal->bobot_nilai],
        ], [
            'nilai_soal.required' => 'Nilai wajib diisi.',
            'nilai_soal.max'      => 'Nilai tidak boleh melebihi bobot soal (' . $jawaban->soal->bobot_nilai . ').',
        ]);

        $jawaban->update([
            'nilai_soal' => $request->nilai_soal,
        ]);

        // Hitung ulang total_skor sesi siswa
        $sesi = $jawaban->sesiTesSiswa;
        $sesi->update([
            'total_skor' => $sesi->jawabanSiswas()->sum('nilai_soal'),
        ]);

        return redirect()->back()->with('success', 'Nilai esai berhasil disimpan! Skor total siswa diperbarui otomatis.');
    }

    /**
     * Menyimpan semua nilai esai sekaligus (batch) dari halaman koreksi satu siswa.
     */
    public function simpanSemuaNilai(Request $request, SesiTesSiswa $sesi)
    {
        $this->authorizeTes($sesi->tes);

        $request->validate([
            'nilai' => ['required', 'array'],
            'nilai.*' => ['nullable', 'numeric', 'min:0'],
        ]);

        foreach ($request->nilai as $idJawaban => $nilai) {
            if (!is_null($nilai) && $nilai !== '') {
                $jawaban = JawabanSiswa::where('id_jawaban_siswa', $idJawaban)
                    ->where('id_sesi', $sesi->id_sesi)
                    ->first();

                if ($jawaban) {
                    $maxNilai = $jawaban->soal->bobot_nilai;
                    $jawaban->update([
                        'nilai_soal' => min((float) $nilai, $maxNilai),
                    ]);
                }
            }
        }

        // Hitung ulang total_skor sesi siswa
        $sesi->update([
            'total_skor' => $sesi->jawabanSiswas()->sum('nilai_soal'),
        ]);

        return redirect()->route('guru.koreksi.show', $sesi->id_tes)
            ->with('success', 'Seluruh nilai untuk siswa ' . $sesi->siswa->nama_siswa . ' berhasil disimpan!');
    }

    /**
     * Validasi kepemilikan tes oleh Guru BK yang sedang login.
     */
    private function authorizeTes(Tes $tes): void
    {
        if ($tes->id_guru != Auth::guard('guru')->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengoreksi tes ini.');
        }
    }
}
