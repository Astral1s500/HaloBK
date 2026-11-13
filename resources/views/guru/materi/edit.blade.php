@extends('layouts.app')

@section('title', 'Edit Materi Bimbingan')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Header & Breadcrumb -->
    <div class="flex items-start justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('guru.dashboard') }}" class="hover:text-slate-900 transition-colors">Dashboard</a>
                <span>&rsaquo;</span>
                <a href="{{ route('guru.materi.index') }}" class="hover:text-slate-900 transition-colors">Materi Bimbingan</a>
                <span>&rsaquo;</span>
                <span class="text-slate-800 font-semibold">Edit</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Materi Bimbingan</h1>
            <p class="text-xs text-slate-500 mt-0.5">Perbarui informasi materi atau unggah dokumen pengganti.</p>
        </div>

        <a
            href="{{ route('guru.materi.index') }}"
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

        <form action="{{ route('guru.materi.update', $materi->id_materi) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="judul_materi" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Judul Materi Bimbingan
                </label>
                <input 
                    type="text" 
                    id="judul_materi" 
                    name="judul_materi" 
                    value="{{ old('judul_materi', $materi->judul_materi) }}" 
                    required 
                    autofocus
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-1 focus:ring-slate-900 focus:border-slate-900 transition-colors"
                >
            </div>

            <!-- Target Kelas & Kategori Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="id_kelas" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Target Kelas Binaan
                    </label>
                    <select 
                        id="id_kelas" 
                        name="id_kelas" 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium text-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 focus:border-slate-900 transition-colors"
                    >
                        <option value="" {{ old('id_kelas', $materi->id_kelas) ? '' : 'selected' }}>Semua Kelas Binaan (Materi Terbuka / Umum)</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id_kelas }}" {{ old('id_kelas', $materi->id_kelas) == $kelas->id_kelas ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }} (Kode: {{ $kelas->kode_unik_kelas }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="id_kategori" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Kategori Materi
                    </label>
                    <select 
                        id="id_kategori" 
                        name="id_kategori" 
                        required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium text-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 focus:border-slate-900 transition-colors"
                    >
                        <option value="">-- Pilih Kategori Materi --</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id_kategori }}" {{ old('id_kategori', $materi->id_kategori) == $kategori->id_kategori ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="file_materi" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Ganti File Dokumen <span class="text-slate-400 font-normal">(Kosongkan jika tidak ingin mengganti file)</span>
                </label>
                
                @if($materi->file_path)
                    <div class="mb-2.5 flex items-center gap-2 p-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="text-slate-600 truncate flex-1 font-mono text-[11px]">{{ basename($materi->file_path) }}</span>
                        <a href="{{ route('guru.materi.buka', $materi->id_materi) }}" target="_blank" class="text-slate-900 font-semibold underline shrink-0">Lihat File</a>
                    </div>
                @endif

                <input 
                    type="file" 
                    id="file_materi" 
                    name="file_materi" 
                    accept=".pdf,.doc,.docx,.ppt,.pptx"
                    class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium text-slate-900 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800 transition-colors"
                >
            </div>

            <div>
                <label for="deskripsi" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Deskripsi / Ringkasan Materi <span class="text-slate-400 font-normal">(Opsional)</span>
                </label>
                <textarea 
                    id="deskripsi" 
                    name="deskripsi" 
                    rows="4" 
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-1 focus:ring-slate-900 focus:border-slate-900 transition-colors"
                >{{ old('deskripsi', $materi->deskripsi) }}</textarea>
            </div>

            <!-- Pilihan Warna dihapus -->

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a 
                    href="{{ route('guru.materi.index') }}" 
                    class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800 transition-colors"
                >
                    Batal
                </a>
                <button 
                    type="submit" 
                    class="bg-slate-900 hover:bg-slate-800 text-white font-semibold py-2 px-5 rounded-lg text-xs tracking-wide transition-colors cursor-pointer"
                >
                    Perbarui Materi
                </button>
            </div>
        </form>

    </div>

</div>

<!-- (tidak ada script tambahan) -->
@endsection
