@extends('layouts.app')

@section('title', 'Edit Kelas')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Header & Breadcrumb -->
    <div class="flex items-start justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('guru.dashboard') }}" class="hover:text-slate-900 transition-colors">Dashboard</a>
                <span>&rsaquo;</span>
                <a href="{{ route('guru.kelas.index') }}" class="hover:text-slate-900 transition-colors">Kelas Binaan</a>
                <span>&rsaquo;</span>
                <span class="text-slate-800 font-semibold">Edit</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Kelas Binaan</h1>
            <p class="text-xs text-slate-500 mt-0.5">Perbarui nama kelas binaan. Kode unik bersifat permanen dan terkunci.</p>
        </div>

        <a
            href="{{ route('guru.kelas.index') }}"
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

        <form action="{{ route('guru.kelas.update', $kelas->id_kelas) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="nama_kelas" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Nama Kelas
                </label>
                <input 
                    type="text" 
                    id="nama_kelas" 
                    name="nama_kelas" 
                    value="{{ old('nama_kelas', $kelas->nama_kelas) }}" 
                    required 
                    autofocus
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-1 focus:ring-slate-900 focus:border-slate-900 transition-colors"
                >
            </div>

            <div>
                <label for="kode_unik_kelas" class="text-xs font-semibold text-slate-700 mb-1.5 flex items-center justify-between">
                    <span class="flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Kode Unik Kelas
                    </span>
                    <span class="text-[10px] font-semibold text-slate-500 bg-slate-100 border border-slate-200 px-2 py-0.5 rounded">Terkunci / Permanen</span>
                </label>
                <div class="relative">
                    <input 
                        type="text" 
                        id="kode_unik_kelas" 
                        value="{{ $kelas->kode_unik_kelas }}" 
                        disabled
                        class="w-full px-3.5 py-2.5 bg-slate-100/90 border border-slate-200 rounded-lg text-xs font-mono font-bold text-slate-500 tracking-wider uppercase cursor-not-allowed select-none"
                    >
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                </div>
                <p class="text-[11px] text-slate-400 mt-1.5 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Kode unik bersifat permanen agar siswa yang telah bergabung tidak terputus dari kelas.
                </p>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a 
                    href="{{ route('guru.kelas.index') }}" 
                    class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800 transition-colors"
                >
                    Batal
                </a>
                <button 
                    type="submit" 
                    class="bg-slate-900 hover:bg-slate-800 text-white font-semibold py-2 px-5 rounded-lg text-xs tracking-wide transition-colors cursor-pointer"
                >
                    Perbarui Kelas
                </button>
            </div>
        </form>

    </div>

</div>
@endsection
