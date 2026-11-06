@extends('layouts.app')

@section('title', 'Paket Asesmen & Tes')

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('guru.dashboard') }}" class="hover:text-slate-900 transition-colors">Dashboard</a>
                <span>&rsaquo;</span>
                <span class="text-slate-800 font-semibold">Paket Tes</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Paket Asesmen & Tes</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola paket tes minat, bakat, psikotes, jadwal pengerjaan, dan bank soal.</p>
        </div>

        <div class="flex items-center gap-2">
            <a
                href="{{ route('guru.dashboard') }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 py-2.5 px-4 rounded-xl transition-colors cursor-pointer"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
            <a 
                href="{{ route('guru.tes.create') }}" 
                class="inline-flex items-center gap-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold py-2.5 px-4 rounded-xl shadow-sm transition-colors cursor-pointer"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Buat Paket Tes Baru</span>
            </a>
        </div>
    </div>

    <!-- Filter & Pencarian -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row items-center justify-between gap-3">
        <form action="{{ route('guru.tes.index') }}" method="GET" class="w-full flex flex-col sm:flex-row items-center gap-3">
            <!-- Filter Kelas -->
            <div class="w-full sm:w-56">
                <select 
                    name="kelas" 
                    onchange="this.form.submit()"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium text-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 focus:border-slate-900 transition-colors"
                >
                    <option value="">-- Semua Kelas --</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id_kelas }}" {{ request('kelas') == $kelas->id_kelas ? 'selected' : '' }}>
                            {{ $kelas->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Status -->
            <div class="w-full sm:w-40">
                <select 
                    name="status" 
                    onchange="this.form.submit()"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium text-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 focus:border-slate-900 transition-colors"
                >
                    <option value="">-- Semua Status --</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <!-- Filter Tipe -->
            <div class="w-full sm:w-40">
                <select 
                    name="tipe" 
                    onchange="this.form.submit()"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium text-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 focus:border-slate-900 transition-colors"
                >
                    <option value="">-- Semua Tipe --</option>
                    <option value="tes" {{ request('tipe') == 'tes' ? 'selected' : '' }}>Tes CBT</option>
                    <option value="form" {{ request('tipe') == 'form' ? 'selected' : '' }}>Formulir Pendataan</option>
                </select>
            </div>

            <!-- Input Pencarian -->
            <div class="w-full sm:flex-1 relative">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari judul paket tes..."
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

            @if(request('kelas') || request('status') || request('tipe') || request('search'))
                <a 
                    href="{{ route('guru.tes.index') }}" 
                    class="text-xs text-rose-600 hover:text-rose-800 font-semibold transition-colors px-2 py-1 shrink-0"
                >
                    Reset Filter
                </a>
            @endif
        </form>
    </div>

    <!-- Tabel Daftar Paket Tes -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold text-slate-600 uppercase tracking-wider">
                        <th class="py-3.5 px-4 text-center w-12">No</th>
                        <th class="py-3.5 px-4">Judul Paket Tes</th>
                        <th class="py-3.5 px-4">Target Kelas</th>
                        <th class="py-3.5 px-4">Kategori & Durasi</th>
                        <th class="py-3.5 px-4 text-center">Jumlah Soal</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4">Tanggal Dibuat</th>
                        <th class="py-3.5 px-4 text-center w-48">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                    @forelse($tesList as $index => $tes)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3.5 px-4 text-center font-medium text-slate-400">{{ $index + 1 }}</td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    @if($tes->isForm())
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            Formulir
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                            Tes CBT
                                        </span>
                                    @endif
                                </div>
                                <a href="{{ route('guru.tes.show', $tes->id_tes) }}" class="font-bold text-slate-900 hover:underline block text-sm">
                                    {{ $tes->judul }}
                                </a>
                                <span class="text-[11px] text-slate-400 block mt-0.5">
                                    Acak Soal: {{ $tes->acak_soal ? 'Ya' : 'Tidak' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-800">
                                    {{ $tes->kelas->nama_kelas ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="block text-slate-900 font-medium">{{ $tes->kategori->nama_kategori ?? '-' }}</span>
                                <span class="block text-slate-500 text-[11px] mt-0.5">{{ $tes->durasi_menit }} Menit</span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700">
                                    {{ $tes->soals_count }} Soal
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($tes->status === 'aktif')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                                        Aktif
                                    </span>
                                @elseif($tes->status === 'draft')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800">
                                        Draft
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-200 text-slate-700">
                                        Selesai
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="text-slate-500">
                                    <span class="block text-xs font-medium">{{ $tes->created_at ? $tes->created_at->format('d M Y') : '-' }}</span>
                                    @if($tes->created_at)
                                        <span class="block text-[11px] text-slate-400 mt-0.5">{{ $tes->created_at->format('H:i') }} WIB</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a 
                                        href="{{ route('guru.tes.show', $tes->id_tes) }}" 
                                        class="px-2.5 py-1 text-[11px] font-semibold text-white bg-slate-900 hover:bg-slate-800 rounded-lg transition-colors"
                                    >
                                        Kelola Soal
                                    </a>
                                    <a 
                                        href="{{ route('guru.tes.edit', $tes->id_tes) }}" 
                                        class="px-2 py-1 text-[11px] font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors"
                                    >
                                        Edit
                                    </a>
                                    <form action="{{ route('guru.tes.destroy', $tes->id_tes) }}" method="POST" class="inline form-delete" data-confirm-title="Hapus Paket Asesmen" data-confirm-message="Yakin ingin menghapus paket {{ $tes->isForm() ? 'formulir' : 'tes' }} '{{ $tes->judul_tes }}' beserta seluruh butir soalnya?">
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit" 
                                            class="px-2 py-1 text-[11px] font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors cursor-pointer"
                                        >
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-xs text-slate-400">
                                Belum ada paket tes yang dibuat. Silakan klik tombol <strong>+ Buat Paket Tes Baru</strong>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
