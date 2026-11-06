<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Kelas;
use App\Models\Tes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TesController extends Controller
{
    /**
     * Menampilkan daftar seluruh paket tes yang dibuat oleh Guru BK yang sedang login.
     */
    public function index(Request $request)
    {
        $guruId = Auth::guard('guru')->id();
        $query = Tes::where('id_guru', $guruId)
            ->with(['kelas', 'kategori'])
            ->withCount('soals');

        // Filter kelas jika dipilih
        if ($request->filled('kelas')) {
            $query->where('id_kelas', $request->kelas);
        }

        // Filter status jika dipilih
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter tipe jika dipilih (tes atau form)
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        // Pencarian judul tes
        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        $tesList = $query->latest()->get();
        $kelasList = Kelas::where('id_guru', $guruId)->orderBy('nama_kelas')->get();

        return view('guru.tes.index', compact('tesList', 'kelasList'));
    }

    /**
     * Menampilkan form pembuatan paket tes baru.
     */
    public function create()
    {
        $guruId = Auth::guard('guru')->id();
        $kelasList = Kelas::where('id_guru', $guruId)->orderBy('nama_kelas')->get();
        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('guru.tes.create', compact('kelasList', 'kategoris'));
    }

    /**
     * Menyimpan paket tes baru ke database.
     */
    public function store(Request $request)
    {
        $guruId = Auth::guard('guru')->id();

        $request->validate([
            'judul'         => ['required', 'string', 'max:255'],
            'tipe'          => ['nullable', 'in:tes,form'],
            'id_kelas'      => ['required', Rule::exists('kelas', 'id_kelas')->where('id_guru', $guruId)],
            'id_kategori'   => ['required', 'exists:kategoris,id_kategori'],
            'durasi_menit'  => ['required', 'integer', 'min:1', 'max:360'],
            'status'        => ['required', 'in:draft,aktif,selesai'],
            'waktu_mulai'   => ['nullable', 'date'],
            'waktu_selesai' => ['nullable', 'date', 'after_or_equal:waktu_mulai'],
        ], [
            'judul.required'        => 'Judul asesmen wajib diisi.',
            'id_kelas.required'     => 'Kelas target wajib dipilih.',
            'id_kelas.exists'       => 'Kelas target yang dipilih tidak valid atau bukan kelas binaan Anda.',
            'id_kategori.required'  => 'Kategori asesmen wajib dipilih.',
            'durasi_menit.required' => 'Durasi pengerjaan wajib ditentukan.',
            'durasi_menit.min'      => 'Durasi minimal 1 menit.',
            'waktu_selesai.after_or_equal' => 'Waktu selesai harus sama atau setelah waktu mulai.',
        ]);

        $tipe = $request->input('tipe', 'tes');
        $tes = Tes::create([
            'id_guru'       => $guruId,
            'id_kelas'      => $request->id_kelas,
            'id_kategori'   => $request->id_kategori,
            'judul'         => $request->judul,
            'tipe'          => $tipe,
            'durasi_menit'  => $request->durasi_menit,
            'acak_soal'     => $request->has('acak_soal'),
            'status'        => $request->status,
            'waktu_mulai'   => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
        ]);

        return redirect()->route('guru.tes.show', $tes->id_tes)
            ->with('success', 'Paket tes berhasil dibuat! Sekarang Anda dapat menambahkan butir-butir soal.');
    }

    /**
     * Menampilkan detail paket tes dan bank soal di dalamnya.
     */
    public function show(Tes $te)
    {
        $this->authorizeTes($te);

        $te->load([
            'kelas',
            'kategori',
            'soals.pilihanJawabans',
        ]);

        return view('guru.tes.show', ['tes' => $te]);
    }

    /**
     * Menampilkan form edit paket tes.
     */
    public function edit(Tes $te)
    {
        $this->authorizeTes($te);

        $guruId = Auth::guard('guru')->id();
        $kelasList = Kelas::where('id_guru', $guruId)->orderBy('nama_kelas')->get();
        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('guru.tes.edit', ['tes' => $te, 'kelasList' => $kelasList, 'kategoris' => $kategoris]);
    }

    /**
     * Memperbarui informasi paket tes.
     */
    public function update(Request $request, Tes $te)
    {
        $this->authorizeTes($te);
        $guruId = Auth::guard('guru')->id();

        $request->validate([
            'judul'         => ['required', 'string', 'max:255'],
            'tipe'          => ['nullable', 'in:tes,form'],
            'id_kelas'      => ['required', Rule::exists('kelas', 'id_kelas')->where('id_guru', $guruId)],
            'id_kategori'   => ['required', 'exists:kategoris,id_kategori'],
            'durasi_menit'  => ['required', 'integer', 'min:1', 'max:360'],
            'status'        => ['required', 'in:draft,aktif,selesai'],
            'waktu_mulai'   => ['nullable', 'date'],
            'waktu_selesai' => ['nullable', 'date', 'after_or_equal:waktu_mulai'],
        ], [
            'judul.required'        => 'Judul asesmen wajib diisi.',
            'id_kelas.required'     => 'Kelas target wajib dipilih.',
            'id_kelas.exists'       => 'Kelas target yang dipilih tidak valid atau bukan kelas binaan Anda.',
            'id_kategori.required'  => 'Kategori asesmen wajib dipilih.',
            'durasi_menit.required' => 'Durasi pengerjaan wajib ditentukan.',
        ]);

        $tipe = $request->input('tipe', $te->tipe ?? 'tes');
        $te->update([
            'id_kelas'      => $request->id_kelas,
            'id_kategori'   => $request->id_kategori,
            'judul'         => $request->judul,
            'tipe'          => $tipe,
            'durasi_menit'  => $request->durasi_menit,
            'acak_soal'     => $request->has('acak_soal'),
            'status'        => $request->status,
            'waktu_mulai'   => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
        ]);

        return redirect()->route('guru.tes.index')->with('success', 'Paket tes berhasil diperbarui.');
    }

    /**
     * Mengubah status tes secara cepat (draft / aktif / selesai).
     */
    public function toggleStatus(Request $request, Tes $te)
    {
        $this->authorizeTes($te);

        $request->validate([
            'status' => ['required', 'in:draft,aktif,selesai'],
        ]);

        $te->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status tes berhasil diubah menjadi ' . strtoupper($request->status) . '.');
    }

    /**
     * Otomatis mengisikan bank butir soal psikometrik standar yang seimbang ke paket asesmen.
     */
    public function terapkanTemplate(Tes $te)
    {
        $this->authorizeTes($te);
        $total = \App\Services\PsikometrikService::terapkanTemplateKeTes($te);

        return redirect()->route('guru.tes.show', $te->id_tes)
            ->with('success', "Berhasil menambahkan {$total} butir soal psikometrik seimbang untuk asesmen {$te->jenis_psikometrik}!");
    }

    /**
     * Menghapus paket tes dan seluruh butir soal di dalamnya.
     */
    public function destroy(Tes $te)
    {
        $this->authorizeTes($te);

        $te->delete();

        return redirect()->route('guru.tes.index')->with('success', 'Paket tes dan seluruh butir soal di dalamnya berhasil dihapus.');
    }

    /**
     * Validasi kepemilikan paket tes oleh Guru BK yang login.
     */
    private function authorizeTes(Tes $tes): void
    {
        if ($tes->id_guru != Auth::guard('guru')->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengelola paket tes ini.');
        }
    }
}
