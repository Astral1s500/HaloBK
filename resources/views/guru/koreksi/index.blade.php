@extends('layouts.app')

@section('title', 'Koreksi & Rekapitulasi Nilai')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('guru.dashboard') }}" class="hover:text-slate-900 transition-colors">Dashboard</a>
                <span>&rsaquo;</span>
                <span class="text-slate-800 font-semibold">Koreksi & Nilai</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Koreksi & Rekapitulasi Nilai</h1>
            <p class="text-xs text-slate-500 mt-0.5">Pilih paket tes untuk melihat hasil pengerjaan siswa dan mengoreksi jawaban esai.</p>
        </div>

        <a
            href="{{ route('guru.dashboard') }}"
            class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 py-2.5 px-4 rounded-xl transition-colors cursor-pointer shrink-0"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    <!-- Daftar Paket Tes yang Sudah Ada Pengerjaan -->
    @forelse($tesList as $tes)
        @php
            $perluKoreksi = ($tes->total_esai > 0 && $tes->esai_belum_dinilai > 0);
            $cardBorderClass = $perluKoreksi
                ? 'border-amber-200 hover:border-amber-300'
                : 'border-emerald-200 hover:border-emerald-300';
        @endphp
        <div class="bg-white rounded-2xl border shadow-2xs p-5 md:p-6 hover:-translate-y-0.5 transition-all duration-200 {{ $cardBorderClass }}">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <!-- Informasi Tes -->
                <div class="space-y-1.5 flex-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="text-sm font-bold text-slate-900">{{ $tes->judul }}</h3>
                        @if($tes->isForm())
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Formulir
                            </span>
                        @endif
                        @if($tes->status === 'aktif')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Aktif</span>
                        @elseif($tes->status === 'draft')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">Draft</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-50 text-slate-700 border border-slate-200">Selesai</span>
                        @endif

                        @if($tes->soal_paling_rawan)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-medium bg-slate-100 text-slate-700 border border-slate-200" title="Butir soal yang paling banyak dijawab salah oleh siswa">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Soal Paling Rawan: #{{ $tes->soal_paling_rawan->nomor }} ({{ $tes->soal_paling_rawan->persentase }}% Salah)</span>
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-600">
                        Kelas: <strong class="text-slate-800">{{ $tes->kelas->nama_kelas ?? '-' }}</strong>
                        &bull; {{ $tes->total_soal }} Butir Pertanyaan ({{ $tes->total_esai }} Esai)
                    </p>
                </div>

                <!-- Statistik Ringkas -->
                <div class="flex items-center gap-4">
                    <div class="text-center px-3 bg-slate-50 py-1.5 rounded-xl border border-slate-200 shadow-2xs">
                        <span class="block text-lg font-extrabold text-slate-900">{{ $tes->total_selesai }}</span>
                        <span class="block text-[10px] text-slate-500 font-medium">Siswa Selesai</span>
                    </div>

                    @if($tes->total_esai > 0)
                        <div class="text-center px-3 py-1.5 rounded-xl border shadow-2xs {{ $perluKoreksi ? 'bg-amber-50 border-amber-200' : 'bg-emerald-50 border-emerald-200' }}">
                            @if($tes->esai_belum_dinilai > 0)
                                <span class="block text-lg font-extrabold text-amber-700">{{ $tes->esai_belum_dinilai }}</span>
                                <span class="block text-[10px] text-amber-800 font-semibold">Perlu Koreksi</span>
                            @else
                                <span class="block text-lg font-extrabold text-emerald-700">✓</span>
                                <span class="block text-[10px] text-emerald-800 font-semibold">Sudah Dinilai</span>
                            @endif
                        </div>
                    @endif

                    <a 
                        href="{{ route('guru.koreksi.show', $tes->id_tes) }}"
                        class="inline-flex items-center gap-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold py-2.5 px-4 rounded-xl transition-colors cursor-pointer shrink-0 shadow-2xs"
                    >
                        <span>{{ $tes->isForm() ? 'Lihat Data Formulir' : 'Lihat Hasil & Koreksi' }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-12 text-center space-y-3">
            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <h3 class="text-sm font-bold text-slate-800">Belum Ada Tes yang Dikerjakan Siswa</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto">
                Setelah siswa mulai mengerjakan paket tes Anda, hasil dan skor mereka akan muncul di sini untuk dikoreksi.
            </p>
        </div>
    @endforelse

</div>
@endsection
