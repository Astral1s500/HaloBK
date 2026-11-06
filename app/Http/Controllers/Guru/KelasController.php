<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class KelasController extends Controller
{
    /**
     * Menampilkan daftar semua kelas yang diampu oleh Guru BK yang sedang login.
     */
    public function index()
    {
        $guruId = Auth::guard('guru')->id();
        $kelasList = Kelas::where('id_guru', $guruId)
            ->withCount('siswas')
            ->latest()
            ->get();

        return view('guru.kelas.index', compact('kelasList'));
    }

    /**
     * Menampilkan form pembuatan kelas baru.
     */
    public function create()
    {
        // Generate kode unik acak bawaan (misal: BK-7K9P2)
        $suggestedCode = 'BK-' . strtoupper(Str::random(5));
        return view('guru.kelas.create', compact('suggestedCode'));
    }

    /**
     * Menyimpan kelas baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas'       => ['required', 'string', 'max:255'],
            'kode_unik_kelas'  => ['required', 'string', 'max:50', 'unique:kelas,kode_unik_kelas'],
        ], [
            'nama_kelas.required'      => 'Nama kelas wajib diisi.',
            'kode_unik_kelas.required' => 'Kode unik kelas wajib diisi.',
            'kode_unik_kelas.unique'   => 'Kode unik kelas sudah digunakan. Gunakan kode lain.',
        ]);

        Kelas::create([
            'id_guru'         => Auth::guard('guru')->id(),
            'nama_kelas'      => $request->nama_kelas,
            'kode_unik_kelas' => strtoupper(trim($request->kode_unik_kelas)),
        ]);

        return redirect()->route('guru.kelas.index')->with('success', 'Kelas baru berhasil dibuat! Bagikan kode uniknya ke siswa.');
    }

    /**
     * Menampilkan detail kelas dan daftar siswa yang bergabung di kelas ini.
     */
    public function show(Kelas $kela)
    {
        $this->authorizeKelas($kela);

        $kela->load(['siswas' => function ($query) {
            $query->latest();
        }]);

        return view('guru.kelas.show', ['kelas' => $kela]);
    }

    /**
     * Menampilkan form edit kelas.
     */
    public function edit(Kelas $kela)
    {
        $this->authorizeKelas($kela);

        return view('guru.kelas.edit', ['kelas' => $kela]);
    }

    /**
     * Memperbarui data kelas.
     */
    public function update(Request $request, Kelas $kela)
    {
        $this->authorizeKelas($kela);

        $request->validate([
            'nama_kelas' => ['required', 'string', 'max:255'],
        ], [
            'nama_kelas.required' => 'Nama kelas wajib diisi.',
        ]);

        $kela->update([
            'nama_kelas' => trim($request->nama_kelas),
        ]);

        return redirect()->route('guru.kelas.index')->with('success', 'Data kelas berhasil diperbarui.');
    }

    /**
     * Menghapus kelas binaan.
     */
    public function destroy(Kelas $kela)
    {
        $this->authorizeKelas($kela);

        // Lepaskan siswa dari kelas ini sebelum dihapus (agar siswa tidak terhapus)
        Siswa::where('id_kelas', $kela->id_kelas)->update(['id_kelas' => null]);

        $kela->delete();

        return redirect()->route('guru.kelas.index')->with('success', 'Kelas berhasil dihapus. Siswa di dalamnya telah dilepaskan dari kelas ini.');
    }

    /**
     * Mengeluarkan siswa dari kelas ini.
     */
    public function kickStudent(Kelas $kelas, Siswa $siswa)
    {
        $this->authorizeKelas($kelas);

        if ($siswa->id_kelas == $kelas->id_kelas) {
            $siswa->update(['id_kelas' => null]);
            return redirect()->route('guru.kelas.show', $kelas->id_kelas)->with('success', 'Siswa ' . $siswa->nama_siswa . ' berhasil dikeluarkan dari kelas.');
        }

        return redirect()->route('guru.kelas.show', $kelas->id_kelas)->with('error', 'Siswa tidak ditemukan di kelas ini.');
    }

    /**
     * Pastikan Guru BK hanya bisa mengakses kelas yang memang diampunya sendiri.
     */
    private function authorizeKelas(Kelas $kelas): void
    {
        if ($kelas->id_guru != Auth::guard('guru')->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengelola kelas ini.');
        }
    }
}
