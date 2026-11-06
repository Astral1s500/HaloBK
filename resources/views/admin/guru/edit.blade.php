@extends('layouts.app')

@section('title', 'Edit Guru BK')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Header & Breadcrumb -->
    <div class="flex items-start justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-900 transition-colors">Dashboard</a>
                <span>&rsaquo;</span>
                <a href="{{ route('admin.guru.index') }}" class="hover:text-slate-900 transition-colors">Master Guru BK</a>
                <span>&rsaquo;</span>
                <span class="text-slate-800 font-semibold">Edit</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Data Guru BK</h1>
            <p class="text-xs text-slate-500 mt-0.5">Perbarui informasi guru bimbingan konseling.</p>
        </div>
        <!-- Tombol Kembali: Arahnya ke halaman daftar Guru BK -->
        <a
            href="{{ route('admin.guru.index') }}"
            class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 px-3 py-2 rounded-lg transition-colors shrink-0 mt-1"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    <!-- Card Form -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 md:p-8">
        
        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-xs font-medium">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.guru.update', $guru->id_guru) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="nama_guru" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Nama Lengkap Guru BK
                </label>
                <input 
                    type="text" 
                    id="nama_guru" 
                    name="nama_guru" 
                    value="{{ old('nama_guru', $guru->nama_guru) }}" 
                    required 
                    autofocus
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-1 focus:ring-slate-900 focus:border-slate-900 transition-colors"
                >
            </div>

            <div>
                <label for="nip" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    NIP (Nomor Induk Pegawai)
                </label>
                <input 
                    type="text" 
                    id="nip" 
                    name="nip" 
                    value="{{ old('nip', $guru->nip) }}" 
                    required
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-1 focus:ring-slate-900 focus:border-slate-900 transition-colors"
                >
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Kata Sandi Baru <span class="text-slate-400 font-normal">(Kosongkan jika tidak ingin diubah)</span>
                </label>
                <input 
                    type="text" 
                    id="password" 
                    name="password" 
                    placeholder="Ketik kata sandi baru untuk mengganti"
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-1 focus:ring-slate-900 focus:border-slate-900 transition-colors"
                >
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a 
                    href="{{ route('admin.guru.index') }}" 
                    class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800 transition-colors"
                >
                    Batal
                </a>
                <button 
                    type="submit" 
                    class="bg-slate-900 hover:bg-slate-800 text-white font-semibold py-2 px-5 rounded-lg text-xs tracking-wide transition-colors cursor-pointer"
                >
                    Perbarui Data
                </button>
            </div>
        </form>

    </div>

</div>
@endsection
