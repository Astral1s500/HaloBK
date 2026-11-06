<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\PilihanJawaban;
use App\Models\Soal;
use App\Models\Tes;
use App\Services\PsikometrikService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SoalController extends Controller
{
    /**
     * Menampilkan form pembuatan butir soal baru pada paket tes tertentu.
     */
    public function create(Request $request, Tes $te)
    {
        $this->authorizeTes($te);

        $te->load(['soals.pilihanJawabans']);

        $tipe = PsikometrikService::deteksiJenis($te);
        $dimensiList = PsikometrikService::getDimensiList($tipe);
        $keseimbangan = PsikometrikService::analisisKeseimbangan($te);

        // Grouping butir soal yang sudah ada per dimensi
        $soalsByDimensi = [];
        foreach (array_keys($dimensiList) as $dimKey) {
            $soalsByDimensi[$dimKey] = [];
        }
        foreach ($te->soals as $s) {
            $d = $s->dimensi;
            if (!isset($soalsByDimensi[$d])) {
                $soalsByDimensi[$d] = [];
            }
            $soalsByDimensi[$d][] = $s;
        }

        // Dimensi tab aktif (dari parameter query atau dimensi pertama)
        $activeDimensi = $request->query('dimensi');
        if (!$activeDimensi || !array_key_exists($activeDimensi, $dimensiList)) {
            $activeDimensi = array_key_first($dimensiList);
        }

        return view('guru.soal.create', [
            'tes'            => $te,
            'te'             => $te,
            'tipe'           => $tipe,
            'dimensiList'    => $dimensiList,
            'keseimbangan'   => $keseimbangan,
            'soalsByDimensi' => $soalsByDimensi,
            'activeDimensi'  => $activeDimensi,
        ]);
    }

    /**
     * Menyimpan beberapa butir pernyataan sekaligus untuk satu dimensi terpilih (Saran 1).
     */
    public function batchStore(Request $request, Tes $te)
    {
        $this->authorizeTes($te);

        $request->validate([
            'dimensi'      => ['required', 'string'],
            'pernyataan'   => ['required', 'array', 'min:1'],
            'pernyataan.*' => ['nullable', 'string'],
        ], [
            'dimensi.required'    => 'Dimensi pengukuran wajib dipilih.',
            'pernyataan.required' => 'Minimal masukkan satu butir pernyataan.',
        ]);

        $dimensi = $request->dimensi;
        $statements = array_filter($request->input('pernyataan', []), fn($p) => !is_null($p) && trim($p) !== '');

        if (empty($statements)) {
            return back()->withErrors(['pernyataan' => 'Semua baris pernyataan kosong. Mohon ketikkan minimal satu butir pernyataan.'])->withInput();
        }

        $opsiLikert = [
            'Sangat Sesuai / Selalu',
            'Sesuai / Sering',
            'Cukup / Kadang-kadang',
            'Kurang Sesuai / Jarang',
            'Tidak Sesuai / Tidak Pernah',
        ];

        // Jika guru mengkustomisasi teks opsi skala Likert
        if ($request->filled('opsi_kustom') && is_array($request->opsi_kustom)) {
            $filteredOpsi = array_filter($request->opsi_kustom, fn($o) => !is_null($o) && trim($o) !== '');
            if (count($filteredOpsi) >= 2) {
                $opsiLikert = array_values($filteredOpsi);
            }
        }

        $countSaved = 0;
        foreach ($statements as $teksPernyataan) {
            $pertanyaanBerdimensi = PsikometrikService::sematkanDimensi(trim($teksPernyataan), $dimensi);

            $soal = Soal::create([
                'id_tes'          => $te->id_tes,
                'pertanyaan'      => $pertanyaanBerdimensi,
                'tipe_soal'       => 'PG',
                'bobot_nilai'     => 5,
                'timer_per_soal'  => null,
                'gambar_lampiran' => null,
            ]);

            foreach ($opsiLikert as $idx => $teksOpsi) {
                PilihanJawaban::create([
                    'id_soal'      => $soal->id_soal,
                    'teks_pilihan' => $teksOpsi,
                    'is_benar'     => ($idx === 0),
                ]);
            }

            $countSaved++;
        }

        return redirect()->route('guru.tes.soal.create', ['te' => $te->id_tes, 'dimensi' => $dimensi])
            ->with('success', "Berhasil menambahkan {$countSaved} butir soal sekaligus untuk Dimensi {$dimensi}!");
    }

    /**
     * Menyimpan butir soal baru (Pilihan Ganda) ke database dengan dimensi pengukurannya.
     */
    public function store(Request $request, Tes $te)
    {
        $this->authorizeTes($te);

        $rules = [
            'pertanyaan'       => ['required', 'string'],
            'tipe_soal'        => ['required', 'in:PG,Esai'],
            'bobot_nilai'      => ['required', 'numeric', 'min:0'],
            'dimensi'          => ['nullable', 'string'],
        ];

        if ($request->tipe_soal === 'PG') {
            $rules['pilihan'] = ['required', 'array'];
            $rules['kunci_jawaban'] = ['required', 'integer'];
        }

        $messages = [
            'pertanyaan.required'     => 'Teks pertanyaan soal wajib diisi.',
            'bobot_nilai.required'    => 'Bobot nilai soal wajib ditentukan.',
            'pilihan.required'        => 'Pilihan opsi jawaban wajib diisi.',
            'kunci_jawaban.required'  => 'Pilih salah satu opsi sebagai kunci jawaban.',
        ];

        $request->validate($rules, $messages);

        // Validasi kustom untuk PG: minimal 2 opsi tidak kosong & kunci jawaban valid
        if ($request->tipe_soal === 'PG') {
            $nonEmptyPilihan = array_filter($request->input('pilihan', []), fn($p) => !is_null($p) && trim($p) !== '');
            if (count($nonEmptyPilihan) < 2) {
                return back()->withErrors(['pilihan' => 'Pilihan jawaban wajib diisi minimal 2 opsi (misal A dan B).'])->withInput();
            }

            $kunciIdx = (int) $request->kunci_jawaban;
            if (!isset($request->pilihan[$kunciIdx]) || trim($request->pilihan[$kunciIdx]) === '') {
                return back()->withErrors(['kunci_jawaban' => 'Kunci jawaban yang dipilih harus pada opsi yang memiliki teks pilihan.'])->withInput();
            }
        }

        // Sematkan dimensi ke teks pertanyaan
        $pertanyaanTersimpan = $request->pertanyaan;
        if ($request->filled('dimensi')) {
            $pertanyaanTersimpan = PsikometrikService::sematkanDimensi($request->pertanyaan, $request->dimensi);
        }

        // Buat record Soal
        $soal = Soal::create([
            'id_tes'          => $te->id_tes,
            'pertanyaan'      => $pertanyaanTersimpan,
            'tipe_soal'       => $request->tipe_soal,
            'bobot_nilai'     => $request->bobot_nilai,
            'timer_per_soal'  => null,
            'gambar_lampiran' => null,
        ]);

        // Simpan opsi pilihan jawaban PG
        if ($request->tipe_soal === 'PG' && is_array($request->pilihan)) {
            foreach ($request->pilihan as $index => $teksOpsi) {
                if (trim($teksOpsi) !== '') {
                    PilihanJawaban::create([
                        'id_soal'      => $soal->id_soal,
                        'teks_pilihan' => trim($teksOpsi),
                        'is_benar'     => ((int) $request->kunci_jawaban === (int) $index),
                    ]);
                }
            }
        }

        return redirect()->route('guru.tes.show', $te->id_tes)
            ->with('success', 'Butir soal berhasil ditambahkan ke asesmen psikometrik!');
    }

    /**
     * Menampilkan form edit butir soal.
     */
    public function edit(Soal $soal)
    {
        $this->authorizeTes($soal->tes);
        $soal->load('pilihanJawabans');

        $tes = $soal->tes;
        $tipe = PsikometrikService::deteksiJenis($tes);
        $dimensiList = PsikometrikService::getDimensiList($tipe);
        $currentDimensi = $soal->dimensi;
        $cleanPertanyaan = $soal->clean_pertanyaan;

        return view('guru.soal.edit', [
            'soal'            => $soal,
            'tes'             => $tes,
            'tipe'            => $tipe,
            'dimensiList'     => $dimensiList,
            'currentDimensi'  => $currentDimensi,
            'cleanPertanyaan' => $cleanPertanyaan,
        ]);
    }

    /**
     * Memperbarui data butir soal dan opsi jawabannya.
     */
    public function update(Request $request, Soal $soal)
    {
        $this->authorizeTes($soal->tes);

        $rules = [
            'pertanyaan'       => ['required', 'string'],
            'tipe_soal'        => ['required', 'in:PG,Esai'],
            'bobot_nilai'      => ['required', 'numeric', 'min:0'],
            'dimensi'          => ['nullable', 'string'],
        ];

        if ($request->tipe_soal === 'PG') {
            $rules['pilihan'] = ['required', 'array'];
            $rules['kunci_jawaban'] = ['required', 'integer'];
        }

        $request->validate($rules);

        // Validasi kustom untuk PG: minimal 2 opsi tidak kosong & kunci jawaban valid
        if ($request->tipe_soal === 'PG') {
            $nonEmptyPilihan = array_filter($request->input('pilihan', []), fn($p) => !is_null($p) && trim($p) !== '');
            if (count($nonEmptyPilihan) < 2) {
                return back()->withErrors(['pilihan' => 'Pilihan jawaban wajib diisi minimal 2 opsi.'])->withInput();
            }

            $kunciIdx = (int) $request->kunci_jawaban;
            if (!isset($request->pilihan[$kunciIdx]) || trim($request->pilihan[$kunciIdx]) === '') {
                return back()->withErrors(['kunci_jawaban' => 'Kunci jawaban yang dipilih harus pada opsi yang memiliki teks pilihan.'])->withInput();
            }
        }

        // Sematkan dimensi ke pertanyaan
        $pertanyaanTersimpan = $request->pertanyaan;
        if ($request->filled('dimensi')) {
            $pertanyaanTersimpan = PsikometrikService::sematkanDimensi($request->pertanyaan, $request->dimensi);
        }

        $soal->update([
            'pertanyaan'      => $pertanyaanTersimpan,
            'tipe_soal'       => $request->tipe_soal,
            'bobot_nilai'     => $request->bobot_nilai,
        ]);

        // Perbarui pilihan jawaban untuk PG secara in-place agar ID pilihan lama tidak terhapus
        if ($request->tipe_soal === 'PG' && is_array($request->pilihan)) {
            $existingPilihan = $soal->pilihanJawabans->values();

            $nonEmptyInput = [];
            foreach ($request->pilihan as $idx => $teks) {
                if (trim($teks) !== '') {
                    $nonEmptyInput[$idx] = trim($teks);
                }
            }

            $inputKeys = array_keys($nonEmptyInput);
            $countInput = count($inputKeys);
            $countExisting = $existingPilihan->count();

            for ($i = 0; $i < max($countInput, $countExisting); $i++) {
                if ($i < $countInput && $i < $countExisting) {
                    $origIdx = $inputKeys[$i];
                    $existingPilihan[$i]->update([
                        'teks_pilihan' => $nonEmptyInput[$origIdx],
                        'is_benar'     => ((int) $request->kunci_jawaban === (int) $origIdx),
                    ]);
                } elseif ($i < $countInput && $i >= $countExisting) {
                    $origIdx = $inputKeys[$i];
                    PilihanJawaban::create([
                        'id_soal'      => $soal->id_soal,
                        'teks_pilihan' => $nonEmptyInput[$origIdx],
                        'is_benar'     => ((int) $request->kunci_jawaban === (int) $origIdx),
                    ]);
                } elseif ($i >= $countInput && $i < $countExisting) {
                    $existingPilihan[$i]->delete();
                }
            }
        }

        return redirect()->route('guru.tes.show', $soal->id_tes)
            ->with('success', 'Butir soal berhasil diperbarui!');
    }

    /**
     * Menghapus butir soal.
     */
    public function destroy(Soal $soal)
    {
        $this->authorizeTes($soal->tes);
        $tesId = $soal->id_tes;

        // Cegah penghapusan butir soal jika sudah ada siswa yang mengerjakan tes ini
        if ($soal->jawabanSiswas()->exists()) {
            return redirect()->route('guru.tes.show', $tesId)
                ->with('error', 'Butir soal tidak dapat dihapus karena sudah memiliki riwayat jawaban siswa pada tes ini.');
        }

        if ($soal->gambar_lampiran && Storage::disk('public')->exists($soal->gambar_lampiran)) {
            Storage::disk('public')->delete($soal->gambar_lampiran);
        }

        $soal->delete();

        return redirect()->route('guru.tes.show', $tesId)
            ->with('success', 'Butir soal berhasil dihapus dari asesmen.');
    }

    /**
     * Validasi kepemilikan paket tes oleh Guru BK yang login.
     */
    private function authorizeTes(Tes $tes): void
    {
        if ($tes->id_guru != Auth::guard('guru')->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengelola butir soal ini.');
        }
    }
}
