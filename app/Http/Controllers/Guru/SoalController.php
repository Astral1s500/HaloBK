<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\PilihanJawaban;
use App\Models\Soal;
use App\Models\Tes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SoalController extends Controller
{
    /**
     * Menampilkan form pembuatan butir soal baru pada paket tes tertentu.
     */
    public function create(Tes $te)
    {
        $this->authorizeTes($te);
        return view('guru.soal.create', ['tes' => $te]);
    }

    /**
     * Menyimpan butir soal baru (Pilihan Ganda atau Esai) ke database.
     */
    public function store(Request $request, Tes $te)
    {
        $this->authorizeTes($te);

        $rules = [
            'pertanyaan'       => ['required', 'string'],
            'tipe_soal'        => ['required', 'in:PG,Esai'],
            'bobot_nilai'      => ['required', 'numeric', 'min:0'],
            'timer_per_soal'   => ['nullable', 'integer', 'min:5'],
            'gambar_lampiran'  => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'], // Max 3MB
        ];

        if ($request->tipe_soal === 'PG') {
            $rules['pilihan'] = ['required', 'array'];
            $rules['kunci_jawaban'] = ['required', 'integer'];
        }

        $messages = [
            'pertanyaan.required'     => 'Teks pertanyaan soal wajib diisi.',
            'bobot_nilai.required'    => 'Bobot nilai soal wajib ditentukan.',
            'pilihan.required'        => 'Pilihan opsi jawaban wajib diisi.',
            'kunci_jawaban.required'  => 'Pilih salah satu opsi sebagai kunci jawaban yang benar.',
            'gambar_lampiran.image'   => 'File lampiran harus berupa gambar (JPG, PNG, WEBP).',
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

        // Upload gambar lampiran jika ada
        $gambarPath = null;
        if ($request->hasFile('gambar_lampiran')) {
            $gambarPath = $request->file('gambar_lampiran')->store('soal_images', 'public');
        }

        // Buat record Soal
        $soal = Soal::create([
            'id_tes'          => $te->id_tes,
            'pertanyaan'      => $request->pertanyaan,
            'tipe_soal'       => $request->tipe_soal,
            'bobot_nilai'     => $request->bobot_nilai,
            'timer_per_soal'  => $request->timer_per_soal,
            'gambar_lampiran' => $gambarPath,
        ]);

        // Jika PG, simpan opsi pilihan jawaban yang memiliki teks
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
            ->with('success', 'Butir soal berhasil ditambahkan ke paket tes!');
    }

    /**
     * Menampilkan form edit butir soal.
     */
    public function edit(Soal $soal)
    {
        $this->authorizeTes($soal->tes);
        $soal->load('pilihanJawabans');

        return view('guru.soal.edit', ['soal' => $soal, 'tes' => $soal->tes]);
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
            'timer_per_soal'   => ['nullable', 'integer', 'min:5'],
            'gambar_lampiran'  => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
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

        $gambarPath = $soal->gambar_lampiran;

        // Jika upload gambar baru
        if ($request->hasFile('gambar_lampiran')) {
            if ($soal->gambar_lampiran && Storage::disk('public')->exists($soal->gambar_lampiran)) {
                Storage::disk('public')->delete($soal->gambar_lampiran);
            }
            $gambarPath = $request->file('gambar_lampiran')->store('soal_images', 'public');
        } elseif ($request->has('hapus_gambar') && $request->hapus_gambar == '1') {
            if ($soal->gambar_lampiran && Storage::disk('public')->exists($soal->gambar_lampiran)) {
                Storage::disk('public')->delete($soal->gambar_lampiran);
            }
            $gambarPath = null;
        }

        $soal->update([
            'pertanyaan'      => $request->pertanyaan,
            'tipe_soal'       => $request->tipe_soal,
            'bobot_nilai'     => $request->bobot_nilai,
            'timer_per_soal'  => $request->timer_per_soal,
            'gambar_lampiran' => $gambarPath,
        ]);

        // Perbarui pilihan jawaban untuk PG secara in-place agar ID pilihan lama tidak terhapus
        // dan riwayat jawaban siswa tidak rusak (tetap konsisten)
        if ($request->tipe_soal === 'PG' && is_array($request->pilihan)) {
            $existingPilihan = $soal->pilihanJawabans->values();

            // Kumpulkan input yang tidak kosong beserta index aslinya
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
                    // Update record pilihan yang sudah ada (pertahankan id_pilihan)
                    $origIdx = $inputKeys[$i];
                    $existingPilihan[$i]->update([
                        'teks_pilihan' => $nonEmptyInput[$origIdx],
                        'is_benar'     => ((int) $request->kunci_jawaban === (int) $origIdx),
                    ]);
                } elseif ($i < $countInput && $i >= $countExisting) {
                    // Buat record baru jika ada opsi tambahan
                    $origIdx = $inputKeys[$i];
                    PilihanJawaban::create([
                        'id_soal'      => $soal->id_soal,
                        'teks_pilihan' => $nonEmptyInput[$origIdx],
                        'is_benar'     => ((int) $request->kunci_jawaban === (int) $origIdx),
                    ]);
                } elseif ($i >= $countInput && $i < $countExisting) {
                    // Hapus opsi berlebih
                    $existingPilihan[$i]->delete();
                }
            }
        } elseif ($request->tipe_soal === 'Esai') {
            // Jika diubah jadi Esai, bersihkan pilihan ganda
            $soal->pilihanJawabans()->delete();
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
            ->with('success', 'Butir soal berhasil dihapus dari paket tes.');
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
