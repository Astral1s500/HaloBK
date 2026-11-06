<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GuruBk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GuruBKController extends Controller
{
    /**
     * Menampilkan daftar semua guru BK
     */
    public function index()
    {
        $gurus = GuruBK::latest()->get();
        return view('admin.guru.index', compact('gurus'));
    }

    /**
     * Menampilkan form tambah guru BK baru
     */
    public function create()
    {
        return view('admin.guru.create');
    }

    /**
     * Menyimpan data guru BK ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_guru' => ['required', 'string', 'max:255'],
            'nip'       => ['required', 'string', 'unique:guru_bks,nip', 'max:50'],
            'password'  => ['required', 'string', 'min:6'],
        ], [
            'nama_guru.required' => 'Nama Guru BK wajib diisi.',
            'nip.required'       => 'NIP wajib diisi.',
            'nip.unique'         => 'NIP sudah terdaftar di sistem.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 6 karakter.',
        ]);

        GuruBK::create([
            'nama_guru' => $request->nama_guru,
            'nip'       => $request->nip,
            'password'  => Hash::make($request->password),
        ]);

        return redirect()->route('admin.guru.index')->with('success', 'Guru BK berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit data Guru BK.
     */
    public function edit(GuruBK $guru)
    {
        return view('admin.guru.edit', compact('guru'));
    }

    /**
     * Memperbarui data Guru BK.
     */
    public function update(Request $request, GuruBK $guru)
    {
         $request->validate([
            'nama_guru' => ['required', 'string', 'max:255'],
            'nip'       => ['required', 'string', 'max:50', 'unique:guru_bks,nip,' . $guru->id_guru . ',id_guru'],
            'password'  => ['nullable', 'string', 'min:6'], // Opsional saat edit
        ], [
            'nama_guru.required' => 'Nama Guru BK wajib diisi.',
            'nip.required'       => 'NIP wajib diisi.',
            'nip.unique'         => 'NIP sudah digunakan Guru lain.',
            'password.min'       => 'Password minimal 6 karakter jika ingin diganti.',
        ]);

        $data = [
            'nama_guru' => $request->nama_guru,
            'nip' => $request->nip,
        ];

        // Jika password diisi, update password baru
        if ($request->filled('password')){
            $data['password'] = Hash::make($request->password);
        }
        
        $guru->update($data);

        return redirect()->route('admin.guru.index')->with('success', 'Data Guru berhasil diupdate!');
    }

    /**
     * Menghapus data guru
     */
    public function destroy(GuruBK $guru)
    {
        $guru->delete();
        return redirect()->route('admin.guru.index')->with('success', 'Guru BK berhasil dihapus!');
    }
}
