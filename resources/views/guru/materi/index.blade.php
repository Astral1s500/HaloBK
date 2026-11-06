@extends('layouts.app')

@section('title', 'Materi Bimbingan')

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('guru.dashboard') }}" class="hover:text-slate-900 transition-colors">Dashboard</a>
                <span>&rsaquo;</span>
                <span class="text-slate-800 font-semibold">Materi Bimbingan</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Modul & Materi Bimbingan</h1>
            <p class="text-xs text-slate-500 mt-0.5">Unggah dan kelola modul edukatif, bimbingan karir, dan pengembangan diri untuk siswa.</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <a
                href="{{ route('guru.dashboard') }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 py-2.5 px-4 rounded-xl transition-colors cursor-pointer"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
            <!-- Tombol Tambah Kategori Baru Modal -->
            <button 
                type="button" 
                onclick="openKategoriModal()"
                class="inline-flex items-center gap-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-semibold py-2.5 px-4 rounded-xl transition-colors cursor-pointer border border-slate-200"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                <span>+ Kategori Baru</span>
            </button>
            <a 
                href="{{ route('guru.materi.create') }}" 
                class="inline-flex items-center gap-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold py-2.5 px-4 rounded-xl shadow-sm transition-colors cursor-pointer"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Unggah Materi Baru</span>
            </a>
        </div>
    </div>

    <!-- Filter & Pencarian -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row items-center justify-between gap-3">
        <form action="{{ route('guru.materi.index') }}" method="GET" class="w-full flex flex-col sm:flex-row items-center gap-3">
            <!-- Filter Kelas -->
            <div class="w-full sm:w-48">
                <select 
                    name="kelas" 
                    onchange="this.form.submit()"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium text-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 focus:border-slate-900 transition-colors"
                >
                    <option value="">Semua Kelas</option>
                    @foreach($kelasList as $k)
                        <option value="{{ $k->id_kelas }}" {{ request('kelas') == $k->id_kelas ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Kategori -->
            <div class="w-full sm:w-56">
                <select 
                    name="kategori" 
                    onchange="this.form.submit()"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium text-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 focus:border-slate-900 transition-colors"
                >
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat->id_kategori }}" {{ request('kategori') == $kat->id_kategori ? 'selected' : '' }}>
                            {{ $kat->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Input Pencarian -->
            <div class="w-full sm:flex-1 relative">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari judul materi..."
                    class="w-full pl-9 pr-3.5 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-1 focus:ring-slate-900 focus:border-slate-900 transition-colors"
                >
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <button 
                type="submit" 
                class="w-full sm:w-auto px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-lg transition-colors cursor-pointer shrink-0"
            >
                Cari
            </button>

            @if(request('kelas') || request('kategori') || request('search'))
                <a 
                    href="{{ route('guru.materi.index') }}" 
                    class="text-xs text-slate-500 hover:text-slate-800 font-semibold underline shrink-0"
                >
                    Reset Filter
                </a>
            @endif
        </form>
    </div>

    <!-- Tabel / Daftar Materi -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold text-slate-600 uppercase tracking-wider">
                        <th class="py-3.5 px-4 text-center w-12">No</th>
                        <th class="py-3.5 px-4">Judul Materi & Deskripsi</th>
                        <th class="py-3.5 px-4">Target Kelas</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4 text-center">Warna Card</th>
                        <th class="py-3.5 px-4">File Dokumen</th>
                        <th class="py-3.5 px-4">Tanggal Unggah</th>
                        <th class="py-3.5 px-4 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                    @forelse($materis as $index => $materi)
                        @php
                            $cfg = $materi->color_config;
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3.5 px-4 text-center font-medium text-slate-400">{{ $index + 1 }}</td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-900 block text-sm">{{ $materi->judul_materi }}</span>
                                @if($materi->deskripsi)
                                    <span class="text-slate-500 text-[11px] mt-0.5 line-clamp-1 block">{{ $materi->deskripsi }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                @if($materi->kelas)
                                    <span class="inline-flex items-center gap-1 font-bold text-slate-900 bg-slate-100 px-2 py-0.5 rounded text-[11px] border border-slate-200">
                                        {{ $materi->kelas->nama_kelas }}
                                    </span>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700">
                                    {{ $materi->kategori->nama_kategori ?? 'Umum' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-semibold border {{ $cfg['badge'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $cfg['dot'] }}"></span>
                                    {{ $cfg['name'] }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($materi->file_path)
                                    <a 
                                        href="{{ asset('storage/' . $materi->file_path) }}" 
                                        target="_blank" 
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-100 hover:bg-slate-200 text-slate-800 transition-colors"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span>Buka Dokumen</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 italic">Tidak ada file</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="text-slate-500">
                                    <span class="block text-xs font-medium">{{ $materi->created_at ? $materi->created_at->format('d M Y') : '-' }}</span>
                                    @if($materi->created_at)
                                        <span class="block text-[11px] text-slate-400 mt-0.5">{{ $materi->created_at->format('H:i') }} WIB</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a 
                                        href="{{ route('guru.materi.edit', $materi->id_materi) }}" 
                                        class="px-2.5 py-1 text-[11px] font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors"
                                    >
                                        Edit
                                    </a>
                                    <form action="{{ route('guru.materi.destroy', $materi->id_materi) }}" method="POST" class="inline form-delete" data-confirm-title="Hapus Materi Bimbingan" data-confirm-message="Yakin ingin menghapus materi '{{ $materi->judul_materi }}' beserta berkas lampirannya?">
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit" 
                                            class="px-2.5 py-1 text-[11px] font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors cursor-pointer"
                                        >
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-xs text-slate-400">
                                Belum ada materi bimbingan yang diunggah. Silakan klik tombol <strong>+ Unggah Materi Baru</strong>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal Tambah Kategori Baru -->
<div id="kategoriModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xl max-w-md w-full p-6 space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-base font-bold text-slate-900">Tambah Kategori Materi Baru</h3>
            <button type="button" onclick="closeKategoriModal()" class="text-slate-400 hover:text-slate-700 text-lg">&times;</button>
        </div>
        
        <form action="{{ route('guru.kategori.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="modalNamaKategori" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Nama Kategori Baru
                </label>
                <input 
                    type="text" 
                    id="modalNamaKategori" 
                    name="nama_kategori" 
                    placeholder="Contoh: Bimbingan Belajar & Akademik" 
                    required
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium text-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 focus:border-slate-900"
                >
            </div>
            
            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                <button 
                    type="button" 
                    onclick="closeKategoriModal()" 
                    class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800 transition-colors"
                >
                    Batal
                </button>
                <button 
                    type="submit" 
                    class="bg-slate-900 hover:bg-slate-800 text-white font-semibold py-2 px-5 rounded-lg text-xs tracking-wide transition-colors cursor-pointer"
                >
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openKategoriModal() {
    const modal = document.getElementById('kategoriModal');
    modal.classList.remove('hidden');
    document.getElementById('modalNamaKategori').focus();
}

function closeKategoriModal() {
    document.getElementById('kategoriModal').classList.add('hidden');
}
</script>
@endsection
