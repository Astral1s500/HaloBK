@extends('layouts.app')

@section('title', 'Hasil & Rekap Asessment')

@push('styles')
<style>
    .btn-sky-action {
        background-color: #0284c7 !important;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 0.825rem;
        padding: 0.6rem 1.2rem;
        border-radius: 12px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        box-shadow: 0 3px 10px rgba(2, 132, 199, 0.25);
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-sky-action:hover {
        background-color: #0369a1 !important;
        transform: translateY(-1px);
    }
    .card-sky {
        background: #ffffff;
        border: 1px solid #e0f2fe;
        border-radius: 20px;
        box-shadow: 0 4px 16px -2px rgba(2, 132, 199, 0.05);
    }
</style>
@endpush

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('guru.dashboard') }}" class="hover:text-sky-600 transition-colors">Dashboard</a>
                <span>&rsaquo;</span>
                <span class="text-sky-700 font-semibold">Hasil & Rekap Asessment</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Hasil & Rekap Asessment</h1>
            <p class="text-xs text-slate-500 mt-0.5">Pilih asessment untuk memantau nilai dan hasil pengerjaan siswa.</p>
        </div>

        <a
            href="{{ route('guru.dashboard') }}"
            class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-200 py-2.5 px-4 rounded-xl transition-all shadow-xs cursor-pointer shrink-0"
        >
            <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Daftar Asessment -->
    @forelse($tesList as $tes)
        <div class="card-sky p-5 md:p-6 hover:shadow-md transition-all">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <!-- Informasi Asessment -->
                <div class="space-y-1.5 flex-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="text-sm font-bold text-slate-900">{{ $tes->judul }}</h3>
                        @if($tes->status === 'aktif')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Aktif</span>
                        @elseif($tes->status === 'draft')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">Draft</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-50 text-slate-700 border border-slate-200">Selesai</span>
                        @endif

                        @if($tes->soal_paling_rawan)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-medium bg-rose-50 text-rose-700 border border-rose-200" title="Butir soal yang paling banyak dijawab salah oleh siswa">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Soal Paling Rawan: #{{ $tes->soal_paling_rawan->nomor }} ({{ $tes->soal_paling_rawan->persentase }}% Salah)</span>
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-600">
                        Target Kelas: <strong class="text-slate-800">{{ $tes->kelas->nama_kelas ?? '-' }}</strong>
                        &bull; {{ $tes->total_soal }} Butir Soal PG
                    </p>
                </div>

                <!-- Statistik Ringkas & Tombol Lihat -->
                <div class="flex items-center gap-4">
                    <div class="text-center px-3.5 bg-sky-50/60 py-2 rounded-xl border border-sky-100">
                        <span class="block text-lg font-extrabold text-sky-900">{{ $tes->total_selesai }}</span>
                        <span class="block text-[10px] text-sky-600 font-medium">Siswa Selesai</span>
                    </div>

                    <a 
                        href="{{ route('guru.koreksi.show', $tes->id_tes) }}"
                        class="btn-sky-action"
                    >
                        <span>Lihat Hasil</span>
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="card-sky p-12 text-center space-y-3">
            <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 mx-auto flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <h3 class="text-sm font-bold text-slate-800">Belum Ada Asessment yang Dikerjakan Siswa</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto">
                Setelah siswa mulai mengerjakan asessment Anda, hasil dan perolehan skor mereka akan otomatis terekap di sini.
            </p>
        </div>
    @endforelse

</div>
@endsection
