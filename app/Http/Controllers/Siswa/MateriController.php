<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    /**
     * Menampilkan daftar materi bimbingan untuk siswa yang sedang login.
     * Hanya menampilkan materi yang ditujukan untuk kelasnya atau berlaku untuk semua kelas (id_kelas NULL).
     */
    public function index(Request $request)
    {
        /** @var \App\Models\Siswa $siswa */
        $siswa = Auth::guard('siswa')->user();
        $siswa->load('kelas');

        $kelas = $siswa->kelas;

        if (!$kelas) {
            return view('siswa.materi.index', [
                'materis'   => collect(),
                'kategoris' => collect(),
                'kelas'     => null,
            ]);
        }

        $query = Materi::with(['kategori', 'guruBk', 'kelas'])
            ->where(function ($q) use ($kelas) {
                $q->where('id_kelas', $kelas->id_kelas)
                  ->orWhereNull('id_kelas');
            });

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }

        // Pencarian judul
        if ($request->filled('search')) {
            $query->where('judul_materi', 'like', '%' . $request->search . '%');
        }

        $materis   = $query->latest()->get();
        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('siswa.materi.index', compact('materis', 'kategoris', 'kelas'));
    }

    /**
     * Membuka / streaming file dokumen materi di tab baru peramban (PDF / dokumen).
     */
    public function buka(Materi $materi)
    {
        /** @var \App\Models\Siswa $siswa */
        $siswa = Auth::guard('siswa')->user();

        // Pastikan materi ini memang diperuntukkan untuk kelas siswa atau untuk semua
        if ($materi->id_kelas !== null && (string)$materi->id_kelas !== (string)$siswa->id_kelas) {
            abort(403, 'Anda tidak memiliki hak akses ke materi ini.');
        }

        // Periksa file di storage/app/public
        if ($materi->file_path && Storage::disk('public')->exists($materi->file_path)) {
            $fullPath = Storage::disk('public')->path($materi->file_path);
            $mime = Storage::disk('public')->mimeType($materi->file_path) ?: 'application/octet-stream';

            return response()->file($fullPath, [
                'Content-Type' => $mime,
                'Content-Disposition' => 'inline; filename="' . basename($materi->file_path) . '"',
            ]);
        }

        // Fallback jika file ada di public/storage
        if ($materi->file_path && file_exists(public_path('storage/' . $materi->file_path))) {
            return response()->file(public_path('storage/' . $materi->file_path));
        }

        abort(404, 'File materi tidak ditemukan.');
    }

    /**
     * Mengunduh file materi (hanya jika materi tersedia untuk kelas siswa).
     */
    public function download(Materi $materi)
    {
        /** @var \App\Models\Siswa $siswa */
        $siswa = Auth::guard('siswa')->user();

        // Pastikan materi ini memang diperuntukkan untuk kelas siswa atau untuk semua
        if ($materi->id_kelas !== null && (string)$materi->id_kelas !== (string)$siswa->id_kelas) {
            abort(403, 'Anda tidak memiliki akses ke materi ini.');
        }

        $ext = pathinfo($materi->file_path, PATHINFO_EXTENSION);
        $downloadName = $materi->judul_materi . ($ext ? '.' . $ext : '');

        // Cek di disk public (storage/app/public)
        if ($materi->file_path && Storage::disk('public')->exists($materi->file_path)) {
            return Storage::disk('public')->download($materi->file_path, $downloadName);
        }

        // Fallback jika ada di public/storage
        if ($materi->file_path && file_exists(public_path('storage/' . $materi->file_path))) {
            return response()->download(public_path('storage/' . $materi->file_path), $downloadName);
        }

        abort(404, 'File materi tidak ditemukan.');
    }
}
