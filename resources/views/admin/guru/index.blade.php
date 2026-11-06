@extends('layouts.app')

@section('title', 'Kelola Akun Guru BK')

@push('styles')
<style>
    .btn-sky-solid {
        background-color: #0284c7 !important;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 0.825rem;
        padding: 0.6rem 1.15rem;
        border-radius: 12px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        box-shadow: 0 2px 8px rgba(2, 132, 199, 0.25);
        border: none;
        transition: all 0.2s;
    }
    .btn-sky-solid:hover {
        background-color: #0369a1 !important;
        transform: translateY(-1px);
    }
</style>
@endpush

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-sky-600 transition-colors">Dashboard</a>
                <span>&rsaquo;</span>
                <span class="text-sky-700 font-semibold">Akun Guru</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Kelola Akun Guru BK</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola seluruh akun pendidik bimbingan konseling di sekolah.</p>
        </div>

        <div class="flex items-center gap-2">
            <!-- Tombol Kembali: ke halaman Dashboard -->
            <a
                href="{{ route('admin.dashboard') }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-200 py-2.5 px-4 rounded-xl transition-all shadow-xs cursor-pointer"
            >
                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
            <!-- Tombol Tambah Guru BK -->
            <a 
                href="{{ route('admin.guru.create') }}" 
                class="btn-sky-solid"
            >
                <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Akun Guru</span>
            </a>
        </div>
    </div>

    <!-- Tabel Data Guru BK -->
    <div class="bg-white rounded-3xl border border-sky-100 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-sky-50/60 border-b border-sky-100 text-[11px] font-bold text-slate-600 uppercase tracking-wider">
                        <th class="py-3.5 px-4 text-center w-12">No</th>
                        <th class="py-3.5 px-4">Nama Lengkap Guru BK</th>
                        <th class="py-3.5 px-4">NIP</th>
                        <th class="py-3.5 px-4 text-center">Kelas Diampu</th>
                        <th class="py-3.5 px-4 text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                    @forelse($gurus as $index => $guru)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3.5 px-4 text-center font-medium text-slate-400">{{ $index + 1 }}</td>
                            <td class="py-3.5 px-4 font-semibold text-slate-900">{{ $guru->nama_guru }}</td>
                            <td class="py-3.5 px-4 font-mono text-slate-600">{{ $guru->nip }}</td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700">
                                    {{ $guru->kelas->count() }} Kelas
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a 
                                        href="{{ route('admin.guru.edit', $guru->id_guru) }}" 
                                        class="px-2.5 py-1 text-[11px] font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors"
                                    >
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.guru.destroy', $guru->id_guru) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus data Guru BK ini?');">
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
                            <td colspan="5" class="py-8 text-center text-xs text-slate-400">
                                Belum ada data Guru BK yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
