<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Kelas;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MateriController extends Controller
{
    /**
     * Menampilkan daftar seluruh materi bimbingan yang diunggah oleh Guru BK yang sedang login.
     */
    public function index(Request $request)
    {
        $guruId = Auth::guard('guru')->id();
        $query = Materi::where('id_guru', $guruId)->with(['kategori', 'kelas']);

        // Filter kelas binaan jika dipilih
        if ($request->filled('kelas')) {
            $query->where('id_kelas', $request->kelas);
        }

        // Filter kategori jika dipilih
        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }

        // Pencarian judul materi
        if ($request->filled('search')) {
            $query->where('judul_materi', 'like', '%' . $request->search . '%');
        }

        $materis = $query->latest()->get();
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        $kelasList = Kelas::where('id_guru', $guruId)->orderBy('nama_kelas')->get();

        return view('guru.materi.index', compact('materis', 'kategoris', 'kelasList'));
    }

    /**
     * Menampilkan form unggah materi bimbingan baru.
     */
    public function create()
    {
        $guruId = Auth::guard('guru')->id();
        $kelasList = Kelas::where('id_guru', $guruId)->orderBy('nama_kelas')->get();
        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('guru.materi.create', compact('kelasList', 'kategoris'));
    }

    /**
     * Menyimpan kategori materi baru ke database.
     */
    public function storeKategori(Request $request)
    {
        $request->validate([
            'nama_kategori' => ['required', 'string', 'max:255', 'unique:kategoris,nama_kategori'],
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique'   => 'Kategori ini sudah terdaftar di sistem.',
        ]);

        $kategori = Kategori::create([
            'nama_kategori' => trim($request->nama_kategori),
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'  => true,
                'message'  => 'Kategori baru berhasil ditambahkan!',
                'kategori' => $kategori,
            ]);
        }

        return redirect()->back()->with('success', 'Kategori "' . $kategori->nama_kategori . '" berhasil ditambahkan!');
    }

    /**
     * Menyimpan file materi baru ke storage dan database.
     */
    public function store(Request $request)
    {
        $guruId = Auth::guard('guru')->id();

        $request->validate([
            'judul_materi' => ['required', 'string', 'max:255'],
            'id_kelas'     => ['nullable', Rule::exists('kelas', 'id_kelas')->where('id_guru', $guruId)],
            'id_kategori'  => ['required', 'exists:kategoris,id_kategori'],
            'deskripsi'    => ['nullable', 'string'],
            'file_materi'  => ['required', 'file', 'mimes:pdf,doc,docx,ppt,pptx', 'max:10240'], // Maksimal 10MB
        ], [
            'judul_materi.required' => 'Judul materi wajib diisi.',
            'id_kelas.exists'       => 'Target kelas yang dipilih tidak valid atau bukan kelas binaan Anda.',
            'id_kategori.required'  => 'Kategori materi wajib dipilih.',
            'id_kategori.exists'    => 'Kategori yang dipilih tidak valid.',
            'file_materi.required'  => 'File dokumen materi wajib diunggah.',
            'file_materi.mimes'     => 'Format file harus berupa PDF, DOC, DOCX, PPT, atau PPTX.',
            'file_materi.max'       => 'Ukuran file dokumen maksimal 10 MB.',
        ]);

        // Simpan file ke folder storage/app/public/materi_files
        $filePath = $request->file('file_materi')->store('materi_files', 'public');
        
        // Sinkronisasi otomatis ke public/storage jika folder fisik terpisah
        $publicDir = public_path('storage/materi_files');
        if (!is_dir($publicDir)) {
            @mkdir($publicDir, 0755, true);
        }
        @copy(Storage::disk('public')->path($filePath), public_path('storage/' . $filePath));

        Materi::create([
            'id_guru'      => $guruId,
            'id_kelas'     => $request->filled('id_kelas') ? $request->id_kelas : null,
            'id_kategori'  => $request->id_kategori,
            'judul_materi' => $request->judul_materi,
            'file_path'    => $filePath,
            'deskripsi'    => $request->deskripsi,
            'warna'        => $request->input('warna', 'netral'),
        ]);

        return redirect()->route('guru.materi.index')->with('success', 'Materi bimbingan berhasil diunggah!');
    }

    /**
     * Menampilkan form edit materi.
     */
    public function edit(Materi $materi)
    {
        $this->authorizeMateri($materi);
        $guruId = Auth::guard('guru')->id();
        $kelasList = Kelas::where('id_guru', $guruId)->orderBy('nama_kelas')->get();
        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('guru.materi.edit', compact('materi', 'kelasList', 'kategoris'));
    }

    /**
     * Memperbarui data materi dan mengganti file jika diunggah ulang.
     */
    public function update(Request $request, Materi $materi)
    {
        $this->authorizeMateri($materi);
        $guruId = Auth::guard('guru')->id();

        $request->validate([
            'judul_materi' => ['required', 'string', 'max:255'],
            'id_kelas'     => ['nullable', Rule::exists('kelas', 'id_kelas')->where('id_guru', $guruId)],
            'id_kategori'  => ['required', 'exists:kategoris,id_kategori'],
            'deskripsi'    => ['nullable', 'string'],
            'file_materi'  => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx', 'max:10240'],
        ], [
            'judul_materi.required' => 'Judul materi wajib diisi.',
            'id_kelas.exists'       => 'Target kelas yang dipilih tidak valid atau bukan kelas binaan Anda.',
            'id_kategori.required'  => 'Kategori materi wajib dipilih.',
            'file_materi.mimes'     => 'Format file harus berupa PDF, DOC, DOCX, PPT, atau PPTX.',
            'file_materi.max'       => 'Ukuran file dokumen maksimal 10 MB.',
        ]);

        $data = [
            'id_kelas'     => $request->filled('id_kelas') ? $request->id_kelas : null,
            'id_kategori'  => $request->id_kategori,
            'judul_materi' => $request->judul_materi,
            'deskripsi'    => $request->deskripsi,
        ];

        // Jika guru mengunggah file pengganti baru
        if ($request->hasFile('file_materi')) {
            // Hapus file lama jika ada
            if ($materi->file_path && Storage::disk('public')->exists($materi->file_path)) {
                Storage::disk('public')->delete($materi->file_path);
            }

            $data['file_path'] = $request->file('file_materi')->store('materi_files', 'public');
            
            // Sinkronisasi otomatis ke public/storage jika folder fisik terpisah
            $publicDir = public_path('storage/materi_files');
            if (!is_dir($publicDir)) {
                @mkdir($publicDir, 0755, true);
            }
            @copy(Storage::disk('public')->path($data['file_path']), public_path('storage/' . $data['file_path']));
        }

        $materi->update($data);

        return redirect()->route('guru.materi.index')->with('success', 'Materi bimbingan berhasil diperbarui!');
    }

    /**
     * Membuka dokumen materi untuk Guru BK.
     */
    public function buka(Materi $materi)
    {
        $this->authorizeMateri($materi);

        if ($materi->file_path && Storage::disk('public')->exists($materi->file_path)) {
            $fullPath = Storage::disk('public')->path($materi->file_path);
            $mime = Storage::disk('public')->mimeType($materi->file_path) ?: 'application/octet-stream';

            return response()->file($fullPath, [
                'Content-Type' => $mime,
                'Content-Disposition' => 'inline; filename="' . basename($materi->file_path) . '"',
            ]);
        }

        if ($materi->file_path && file_exists(public_path('storage/' . $materi->file_path))) {
            return response()->file(public_path('storage/' . $materi->file_path));
        }

        abort(404, 'File materi tidak ditemukan.');
    }

    /**
     * Menghapus materi bimbingan dan filenya dari storage.
     */
    public function destroy(Materi $materi)
    {
        $this->authorizeMateri($materi);

        // Hapus file dokumen dari storage
        if ($materi->file_path && Storage::disk('public')->exists($materi->file_path)) {
            Storage::disk('public')->delete($materi->file_path);
        }

        $materi->delete();

        return redirect()->route('guru.materi.index')->with('success', 'Materi bimbingan berhasil dihapus.');
    }

    /**
     * Memastikan Guru BK hanya bisa mengedit/menghapus materi yang diunggahnya sendiri.
     */
    private function authorizeMateri(Materi $materi): void
    {
        if ($materi->id_guru != Auth::guard('guru')->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengelola materi ini.');
        }
    }
}
