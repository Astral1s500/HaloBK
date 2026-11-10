@extends('layouts.app')

@section('title', 'Rekapitulasi Asesmen: ' . $tes->judul)

@push('styles')
<style>
    /* Styling Selaras Dashboard (Sky Cloud Guru BK, Siswa, & Admin) */
    .hero-guru-sky {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 60%, #075985 100%);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .stat-pill {
        background: rgba(255, 255, 255, 0.16);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.25);
        padding: 0.5rem 1rem;
        border-radius: 14px;
        display: flex;
        align-items: center;
        gap: 0.6rem;
        color: #ffffff;
    }

    .card-sky {
        background: #ffffff;
        border: 1px solid #e0f2fe;
        border-radius: 24px;
        box-shadow: 0 4px 18px -2px rgba(2, 132, 199, 0.07);
        transition: all 0.25s ease;
    }

    .card-sky:hover {
        box-shadow: 0 10px 25px -4px rgba(2, 132, 199, 0.12);
    }

    /* Kartu Karakteristik Interaktif (Bisa Diklik & Dipilih) */
    .dimensi-card {
        border-radius: 20px;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        position: relative;
    }

    .dimensi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px -5px rgba(2, 132, 199, 0.18);
        border-color: #38bdf8 !important;
    }

    .dimensi-card.is-active {
        border-color: #0284c7 !important;
        background: #f0f9ff !important;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.3), 0 12px 28px -4px rgba(2, 132, 199, 0.2) !important;
        transform: translateY(-3px) scale(1.02);
    }

    .dimensi-card.is-active .active-indicator {
        display: inline-flex !important;
    }

    /* Avatar Icon Box (Pasti Berwarna & Tajam) */
    .icon-box-sky {
        width: 48px;
        height: 48px;
        border-radius: 16px;
        background: #e0f2fe;
        color: #0284c7;
        border: 1px solid #bae6fd;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(2, 132, 199, 0.12);
        flex-shrink: 0;
    }

    /* Badge Dominan Standar Dashboard */
    .badge-dominant {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        color: #ffffff;
        font-weight: 700;
        font-size: 0.75rem;
        padding: 0.35rem 0.85rem;
        border-radius: 9999px;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        box-shadow: 0 2px 8px rgba(2, 132, 199, 0.25);
    }

    /* Tombol Aksi Solid Sky (Selaras Dashboard Guru & Siswa) */
    .btn-sky-action {
        background-color: #0284c7;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 0.825rem;
        padding: 0.6rem 1.15rem;
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
        background-color: #0369a1;
        transform: translateY(-1px);
        box-shadow: 0 5px 14px rgba(2, 132, 199, 0.35);
    }

    /* Modal Backdrop */
    .modal-backdrop-custom {
        background-color: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
    }
</style>
@endpush

@php
    // Palet Warna Kaya & Selaras per Karakteristik Psikometrik
    $paletteMapping = [
        // OCEAN (Kepribadian)
        'Openness'          => ['border' => '#fde68a', 'bg' => '#fffbeb', 'text' => '#b45309', 'badge_bg' => '#fef3c7', 'accent' => '#f59e0b', 'pill_text' => '#92400e', 'bar_grad' => 'linear-gradient(90deg, #f59e0b, #fbbf24)', 'icon' => '💡'],
        'Conscientiousness' => ['border' => '#c7d2fe', 'bg' => '#eef2ff', 'text' => '#4338ca', 'badge_bg' => '#e0e7ff', 'accent' => '#6366f1', 'pill_text' => '#3730a3', 'bar_grad' => 'linear-gradient(90deg, #6366f1, #818cf8)', 'icon' => '🎯'],
        'Extraversion'      => ['border' => '#bae6fd', 'bg' => '#f0f9ff', 'text' => '#0369a1', 'badge_bg' => '#e0f2fe', 'accent' => '#0284c7', 'pill_text' => '#075985', 'bar_grad' => 'linear-gradient(90deg, #0284c7, #38bdf8)', 'icon' => '🗣️'],
        'Agreeableness'     => ['border' => '#a7f3d0', 'bg' => '#ecfdf5', 'text' => '#047857', 'badge_bg' => '#d1fae5', 'accent' => '#10b981', 'pill_text' => '#065f46', 'bar_grad' => 'linear-gradient(90deg, #10b981, #34d399)', 'icon' => '🤝'],
        'Neuroticism'       => ['border' => '#fecdd3', 'bg' => '#fff1f2', 'text' => '#be123c', 'badge_bg' => '#ffe4e6', 'accent' => '#f43f5e', 'pill_text' => '#9f1239', 'bar_grad' => 'linear-gradient(90deg, #f43f5e, #fb7185)', 'icon' => '⚖️'],

        // VARK (Gaya Belajar)
        'Visual'            => ['border' => '#bae6fd', 'bg' => '#f0f9ff', 'text' => '#0369a1', 'badge_bg' => '#e0f2fe', 'accent' => '#0284c7', 'pill_text' => '#075985', 'bar_grad' => 'linear-gradient(90deg, #0284c7, #38bdf8)', 'icon' => '👁️'],
        'Auditori'          => ['border' => '#ddd6fe', 'bg' => '#f5f3ff', 'text' => '#6d28d9', 'badge_bg' => '#ede9fe', 'accent' => '#8b5cf6', 'pill_text' => '#5b21b6', 'bar_grad' => 'linear-gradient(90deg, #8b5cf6, #a78bfa)', 'icon' => '🎧'],
        'Reading'           => ['border' => '#a7f3d0', 'bg' => '#ecfdf5', 'text' => '#047857', 'badge_bg' => '#d1fae5', 'accent' => '#10b981', 'pill_text' => '#065f46', 'bar_grad' => 'linear-gradient(90deg, #10b981, #34d399)', 'icon' => '📖'],
        'Kinestetik'        => ['border' => '#fde68a', 'bg' => '#fffbeb', 'text' => '#b45309', 'badge_bg' => '#fef3c7', 'accent' => '#f59e0b', 'pill_text' => '#92400e', 'bar_grad' => 'linear-gradient(90deg, #f59e0b, #fbbf24)', 'icon' => '🖐️'],

        // RIASEC (Karier)
        'Realistic'         => ['border' => '#99f6e4', 'bg' => '#f0fdfa', 'text' => '#0f766e', 'badge_bg' => '#ccfbf1', 'accent' => '#0d9488', 'pill_text' => '#115e59', 'bar_grad' => 'linear-gradient(90deg, #0d9488, #2dd4bf)', 'icon' => '🛠️'],
        'Investigative'     => ['border' => '#bae6fd', 'bg' => '#f0f9ff', 'text' => '#0369a1', 'badge_bg' => '#e0f2fe', 'accent' => '#0284c7', 'pill_text' => '#075985', 'bar_grad' => 'linear-gradient(90deg, #0284c7, #38bdf8)', 'icon' => '🔬'],
        'Artistic'          => ['border' => '#fbcfe8', 'bg' => '#fdf2f8', 'text' => '#be185d', 'badge_bg' => '#fce7f3', 'accent' => '#ec4899', 'pill_text' => '#9d174d', 'bar_grad' => 'linear-gradient(90deg, #ec4899, #f472b6)', 'icon' => '🎨'],
        'Social'            => ['border' => '#a7f3d0', 'bg' => '#ecfdf5', 'text' => '#047857', 'badge_bg' => '#d1fae5', 'accent' => '#10b981', 'pill_text' => '#065f46', 'bar_grad' => 'linear-gradient(90deg, #10b981, #34d399)', 'icon' => '👥'],
        'Enterprising'      => ['border' => '#fed7aa', 'bg' => '#fff7ed', 'text' => '#c2410c', 'badge_bg' => '#ffedd5', 'accent' => '#f97316', 'pill_text' => '#9a3412', 'bar_grad' => 'linear-gradient(90deg, #f97316, #fb923c)', 'icon' => '💼'],
        'Conventional'      => ['border' => '#c7d2fe', 'bg' => '#eef2ff', 'text' => '#4338ca', 'badge_bg' => '#e0e7ff', 'accent' => '#6366f1', 'pill_text' => '#3730a3', 'bar_grad' => 'linear-gradient(90deg, #6366f1, #818cf8)', 'icon' => '📋'],
    ];

    $defaultPalette = [
        'border' => '#bae6fd', 'bg' => '#f0f9ff', 'text' => '#0369a1', 'badge_bg' => '#e0f2fe', 'accent' => '#0284c7', 'pill_text' => '#075985', 'bar_grad' => 'linear-gradient(90deg, #0284c7, #38bdf8)', 'icon' => '📊'
    ];

    // Petakan data siswa per dimensi untuk interaktivitas instan
    $siswaPerDimensi = [];
    foreach (array_keys($analisisKelas['dimensi_info']) as $k) {
        $siswaPerDimensi[$k] = [];
    }

    $sesiAnalyzedList = [];
    foreach ($sesiList as $sesi) {
        $analisisSiswa = \App\Services\PsikometrikService::analisisHasilSiswa($sesi);
        $dimPrimer = $analisisSiswa['dimensi_primer'];
        $entry = [
            'sesi_id'       => $sesi->id_sesi,
            'nama_siswa'    => $sesi->siswa->nama_siswa ?? 'Siswa Terhapus',
            'nisn'          => $sesi->siswa->nisn ?? '-',
            'waktu_submit'  => $sesi->waktu_submit ? $sesi->waktu_submit->format('d/m/Y, H:i') : '-',
            'dimensi_primer'=> $dimPrimer,
            'label_primer'  => $analisisSiswa['label_primer'],
            'skor_dimensi'  => $analisisSiswa['persentase_dimensi'][$dimPrimer] ?? 100,
            'url_konseling' => route('guru.koreksi.siswa', $sesi->id_sesi),
        ];
        $sesiAnalyzedList[] = $entry;
        if (!isset($siswaPerDimensi[$dimPrimer])) {
            $siswaPerDimensi[$dimPrimer] = [];
        }
        $siswaPerDimensi[$dimPrimer][] = $entry;
    }
@endphp

@section('content')
<div class="space-y-6 pb-12">

    <!-- ========================================================================= -->
    <!-- HERO BANNER GURU BK (Selaras Desain Dashboard Siswa, Guru & Admin)        -->
    <!-- ========================================================================= -->
    <div class="hero-guru-sky rounded-3xl p-6 sm:p-8 text-white relative overflow-hidden shadow-lg print:hidden">
        <!-- Dekorasi Aksen Lingkaran Gradien -->
        <div class="absolute -right-12 -bottom-16 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute right-28 -top-12 w-48 h-48 bg-sky-300/15 rounded-full blur-xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-3 max-w-2xl">
                <!-- Breadcrumbs -->
                <div class="flex items-center gap-2 text-xs text-sky-100/90 font-medium flex-wrap">
                    <a href="{{ route('guru.dashboard') }}" class="hover:text-white transition-colors flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                    <span>&rsaquo;</span>
                    <a href="{{ route('guru.koreksi.index') }}" class="hover:text-white transition-colors">Hasil Asessment</a>
                    <span>&rsaquo;</span>
                    <span class="text-white font-bold">{{ $tes->judul }}</span>
                </div>

                <!-- Judul & Badge Tipe -->
                <div class="flex items-center gap-3 flex-wrap">
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white leading-tight">
                        {{ $tes->judul }}
                    </h1>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-white/20 text-white border border-white/30 backdrop-blur-md">
                        {{ $analisisKelas['tipe_asesmen'] }}
                    </span>
                </div>

                <!-- Stat Pills Selaras Dashboard -->
                <div class="flex flex-wrap items-center gap-2.5 pt-1">
                    <div class="stat-pill text-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;color:#bae6fd;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span>Kelas: <strong>{{ $tes->kelas->nama_kelas ?? 'Umum' }}</strong></span>
                    </div>

                    <div class="stat-pill text-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;color:#bae6fd;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span><strong>{{ $komparasi->total_soal }}</strong> Butir Pertanyaan</span>
                    </div>

                    <div class="stat-pill text-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;color:#bae6fd;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>Total Peserta: <strong>{{ $statsHistoris->total_peserta }} Siswa</strong></span>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi Hero Header -->
            <div class="flex flex-wrap items-center gap-3 self-start lg:self-center shrink-0">
                <!-- Tombol Cetak / Export PDF -->
                <button 
                    type="button" 
                    onclick="window.print()" 
                    class="inline-flex items-center gap-2 bg-white text-slate-800 hover:bg-sky-50 font-semibold text-xs py-2.5 px-4 rounded-xl shadow-md transition-all cursor-pointer border border-white"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;color:#0284c7;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>Cetak Rekapitulasi</span>
                </button>

                <!-- Tombol Kembali -->
                <a
                    href="{{ route('guru.koreksi.index') }}"
                    class="inline-flex items-center gap-2 bg-white/15 hover:bg-white/25 text-white font-semibold text-xs py-2.5 px-4 rounded-xl backdrop-blur-md border border-white/25 transition-all shadow-sm cursor-pointer"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Judul Header Cetak (Hanya tampil saat dicetak) -->
    <div class="hidden print:block mb-6 pb-4 border-b border-slate-400">
        <h1 class="text-xl font-bold text-slate-900">REKAPITULASI PROFIL ASESMEN PSIKOMETRIK KELAS</h1>
        <p class="text-xs text-slate-600 mt-1">
            Asesmen: <strong>{{ $tes->judul }}</strong> | 
            Kelas: <strong>{{ $tes->kelas->nama_kelas ?? 'Umum' }}</strong> | 
            Guru BK: <strong>{{ Auth::guard('guru')->user()->nama_guru }}</strong> | 
            Tanggal Cetak: {{ now()->format('d F Y, H:i') }} WIB
        </p>
    </div>

    <!-- ========================================================================= -->
    <!-- 1. PETA KONDISI KELAS & DISTRIBUSI DOMINAN (INTERAKTIF & BISA DIKLIK)     -->
    <!-- Guru BK dapat mengklik tiap karakteristik untuk melihat daftar muridnya    -->
    <!-- ========================================================================= -->
    <div class="card-sky p-6 sm:p-7 space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-slate-100">
            <div class="flex items-start sm:items-center gap-3">
                <div class="icon-box-sky">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight flex items-center gap-2">
                        <span>Pemetaan Kondisi Kelas &amp; Distribusi Dominan</span>
                        <span class="text-[11px] font-semibold text-sky-700 bg-sky-50 px-2.5 py-0.5 rounded-full border border-sky-200 hidden sm:inline-block">
                            Interaktif
                        </span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        <strong>Klik salah satu karakteristik</strong> di bawah untuk memfilter dan melihat murid yang memiliki karakteristik tersebut.
                    </p>
                </div>
            </div>

            <!-- Badge Mayoritas & Tombol Reset -->
            <div class="flex items-center gap-2 flex-wrap">
                @if($analisisKelas['total_siswa_selesai'] > 0)
                    <div class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold bg-sky-50 text-sky-800 border border-sky-200 shadow-2xs">
                        <span>Mayoritas:</span>
                        <strong class="text-sky-900">{{ $analisisKelas['label_mayoritas'] }}</strong>
                    </div>
                @endif

                <button
                    type="button"
                    id="btn-reset-filter"
                    onclick="filterByKarakteristik('all')"
                    class="inline-flex items-center gap-1 text-xs font-semibold text-slate-600 hover:text-sky-700 bg-slate-50 hover:bg-sky-50 px-3 py-1.5 rounded-xl border border-slate-200 hover:border-sky-200 transition-all cursor-pointer"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Tampilkan Semua</span>
                </button>
            </div>
        </div>

        <!-- Sebaran Grid Karakteristik Interaktif (Dapat Diklik) -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3.5">
            
            <!-- Card 0: SEMUA MURID (Default Selected) -->
            <div
                id="card-dimensi-all"
                onclick="filterByKarakteristik('all')"
                class="dimensi-card is-active p-4 rounded-2xl border flex flex-col justify-between"
                style="background: #ffffff; border-color: #0284c7;"
                data-dimensi="all"
                title="Klik untuk menampilkan seluruh siswa tanpa filter"
            >
                <div class="flex items-center justify-between gap-1 mb-2">
                    <span class="w-8 h-8 rounded-xl bg-sky-100 text-sky-700 font-extrabold text-xs flex items-center justify-center border border-sky-200">
                        ALL
                    </span>
                    <span class="active-indicator hidden text-[9px] font-extrabold px-2 py-0.5 rounded-full bg-sky-600 text-white">
                        Aktif
                    </span>
                </div>

                <div class="space-y-1">
                    <span class="text-xs font-bold text-slate-800 block truncate">Semua Siswa</span>
                    <div class="flex items-baseline gap-1">
                        <span class="text-2xl font-black text-sky-700">{{ count($sesiAnalyzedList) }}</span>
                        <span class="text-[10px] text-slate-500 font-semibold">siswa (100%)</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden mt-1.5">
                        <div class="h-full bg-sky-500 rounded-full" style="width: 100%;"></div>
                    </div>
                </div>

                <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between text-[10px] font-semibold text-sky-700">
                    <span>Semua Karakter</span>
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </div>

            <!-- Card per Karakteristik / Dimensi Asesmen -->
            @foreach($analisisKelas['dimensi_info'] as $dimKey => $info)
                @php
                    $count = $analisisKelas['distribusi_dominan'][$dimKey] ?? 0;
                    $persen = $analisisKelas['persentase_distribusi'][$dimKey] ?? 0;
                    $isTop = ($dimKey === $analisisKelas['tipe_mayoritas'] && $count > 0);
                    $pal = $paletteMapping[$dimKey] ?? $defaultPalette;
                    $studentsForThisDim = $siswaPerDimensi[$dimKey] ?? [];
                @endphp
                <div
                    id="card-dimensi-{{ $dimKey }}"
                    onclick="filterByKarakteristik('{{ $dimKey }}')"
                    class="dimensi-card p-4 rounded-2xl border flex flex-col justify-between"
                    style="background: #ffffff; border-color: {{ $isTop ? $pal['border'] : '#e2e8f0' }};"
                    data-dimensi="{{ $dimKey }}"
                    title="Klik untuk melihat {{ $count }} murid dengan karakteristik {{ $info['nama'] }}"
                >
                    <div class="flex items-center justify-between gap-1 mb-2">
                        <span 
                            class="w-8 h-8 rounded-xl font-extrabold text-sm flex items-center justify-center shadow-2xs"
                            style="background: {{ $pal['badge_bg'] }}; color: {{ $pal['text'] }}; border: 1px solid {{ $pal['border'] }};"
                        >
                            {{ $pal['icon'] }}
                        </span>
                        
                        <span class="active-indicator hidden text-[9px] font-extrabold px-2 py-0.5 rounded-full text-white" style="background: {{ $pal['accent'] }};">
                            Dipilih
                        </span>

                        @if($isTop)
                            <span class="text-[9px] font-extrabold px-1.5 py-0.5 rounded-full bg-amber-100 text-amber-800 border border-amber-300">
                                Mayoritas
                            </span>
                        @endif
                    </div>

                    <div class="space-y-1">
                        <span class="text-xs font-bold text-slate-800 block truncate" title="{{ $info['nama'] }}">
                            {{ $info['nama'] }}
                        </span>
                        <div class="flex items-baseline gap-1">
                            <span class="text-2xl font-black" style="color: {{ $pal['text'] }};">{{ $count }}</span>
                            <span class="text-[10px] text-slate-500 font-semibold">siswa ({{ $persen }}%)</span>
                        </div>
                        
                        <!-- Progress Bar Selaras Warna -->
                        <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden mt-1.5">
                            <div class="h-full rounded-full transition-all duration-500" style="width: {{ $persen }}%; background: {{ $pal['bar_grad'] }};"></div>
                        </div>
                    </div>

                    <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between text-[10px] font-semibold" style="color: {{ $pal['text'] }};">
                        <span class="flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <span>Lihat Murid</span>
                        </span>
                        <span>&rarr;</span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- ========================================================================= -->
        <!-- BANNER DETAIL KARAKTERISTIK TERPILIH & CHIP NAMA MURID                     -->
        <!-- Tampil dinamis saat Guru BK mengklik salah satu kartu karakteristik       -->
        <!-- ========================================================================= -->
        <div id="panel-detail-karakteristik" class="p-5 rounded-2xl border transition-all" style="background: #f8fafc; border-color: #e2e8f0;">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-3 border-b border-slate-200/80">
                <div class="space-y-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span id="badge-karakter-terpilih" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-sky-100 text-sky-800 border border-sky-300">
                            🌟 Menampilkan: Semua Siswa
                        </span>
                        <span id="counter-karakter-terpilih" class="text-xs text-slate-500 font-semibold">
                            Total {{ count($sesiAnalyzedList) }} siswa terdaftar
                        </span>
                    </div>
                    <p id="desc-karakter-terpilih" class="text-xs text-slate-600 leading-relaxed max-w-3xl pt-0.5">
                        Menampilkan seluruh murid dari hasil pelaksanaan asesmen ini. Klik pada salah satu kartu karakteristik di atas untuk memfilter siswa secara spesifik.
                    </p>
                </div>

                <!-- Tombol Popup Modal Murid -->
                <div class="flex items-center gap-2 shrink-0">
                    <button
                        type="button"
                        id="btn-open-modal"
                        onclick="openStudentModal()"
                        class="inline-flex items-center gap-1.5 text-xs font-bold text-sky-700 hover:text-sky-800 bg-white hover:bg-sky-50 px-3.5 py-2 rounded-xl border border-sky-200 shadow-2xs transition-colors cursor-pointer"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Lihat Pop-up Daftar Murid</span>
                    </button>
                </div>
            </div>

            <!-- Chips Baris Nama Siswa Berkarakteristik Ini (Quick Preview) -->
            <div class="pt-3">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block mb-2">
                    Daftar Murid yang Teridentifikasi:
                </span>
                <div id="chips-container-murid" class="flex flex-wrap items-center gap-2">
                    @forelse($sesiAnalyzedList as $item)
                        <a 
                            href="{{ $item['url_konseling'] }}"
                            class="chip-murid inline-flex items-center gap-2 bg-white hover:bg-sky-50 border border-slate-200 hover:border-sky-300 py-1.5 px-3 rounded-xl text-xs font-semibold text-slate-800 shadow-2xs transition-all"
                            data-dimensi="{{ $item['dimensi_primer'] }}"
                            title="Klik untuk membuka Lembar Konseling {{ $item['nama_siswa'] }}"
                        >
                            <span class="w-5 h-5 rounded-md bg-sky-100 text-sky-700 font-bold text-[10px] flex items-center justify-center">
                                {{ strtoupper(substr($item['nama_siswa'], 0, 1)) }}
                            </span>
                            <span>{{ $item['nama_siswa'] }}</span>
                            <span class="text-[10px] font-mono text-slate-400">({{ $item['nisn'] }})</span>
                            <span class="text-[10px] font-bold text-sky-600 bg-sky-50 px-1.5 py-0.5 rounded border border-sky-100">
                                {{ $item['label_primer'] }}
                            </span>
                        </a>
                    @empty
                        <span class="text-xs text-slate-400 italic">Belum ada data siswa.</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 2. TABEL DAFTAR SISWA & LEMBAR KONSELING (TERHUBUNG FILTER KARAKTERISTIK) -->
    <!-- ========================================================================= -->
    <div class="card-sky overflow-hidden" id="section-tabel-siswa">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white">
            <div class="space-y-0.5">
                <h3 class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-2">
                    <span>Daftar Hasil &amp; Profil Siswa</span>
                    <span id="label-filter-aktif" class="text-xs font-bold text-sky-700 bg-sky-50 px-2.5 py-0.5 rounded-full border border-sky-200">
                        Semua Siswa
                    </span>
                </h3>
                <p class="text-xs text-slate-500">
                    Klik <strong>"Buka Lembar Konseling &amp; Ortu"</strong> untuk melihat profil naratif, potensi, strategi bimbingan, dan materi tindak lanjut.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <span id="table-counter-pill" class="text-xs font-bold text-sky-800 bg-sky-50 px-3 py-1.5 rounded-xl border border-sky-200 shadow-2xs">
                    {{ count($sesiAnalyzedList) }} Siswa Ditampilkan
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-sky-50/60 border-b border-sky-100 text-[11px] font-bold text-sky-900 uppercase tracking-wider">
                        <th class="py-3 px-4 text-center w-12">No</th>
                        <th class="py-3 px-4">Nama Siswa</th>
                        <th class="py-3 px-4">NISN</th>
                        <th class="py-3 px-4 text-center">Tipe Karakteristik Dominan</th>
                        <th class="py-3 px-4 text-center">Waktu Submit</th>
                        <th class="py-3 px-4 text-center w-52 print:hidden">Aksi &amp; Konseling</th>
                    </tr>
                </thead>
                <tbody id="tbody-siswa" class="divide-y divide-slate-100 text-xs text-slate-700">
                    @forelse($sesiAnalyzedList as $index => $item)
                        @php
                            $pal = $paletteMapping[$item['dimensi_primer']] ?? $defaultPalette;
                        @endphp
                        <tr 
                            class="row-siswa hover:bg-sky-50/40 transition-colors"
                            data-dimensi="{{ $item['dimensi_primer'] }}"
                            data-nama="{{ strtolower($item['nama_siswa']) }}"
                        >
                            <!-- Kolom No (Dihitung Ulang via JS Saat Filter Aktif) -->
                            <td class="py-3.5 px-4 text-center font-bold text-slate-400 row-number">
                                {{ $index + 1 }}
                            </td>

                            <!-- Nama Siswa & Avatar Inisial -->
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2.5">
                                    <div 
                                        class="w-8 h-8 rounded-xl font-extrabold text-xs flex items-center justify-center shrink-0 shadow-2xs"
                                        style="background: {{ $pal['badge_bg'] }}; color: {{ $pal['text'] }}; border: 1px solid {{ $pal['border'] }};"
                                    >
                                        {{ strtoupper(substr($item['nama_siswa'], 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-900 block text-xs sm:text-sm">
                                            {{ $item['nama_siswa'] }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 font-medium">Peserta Asesmen</span>
                                    </div>
                                </div>
                            </td>

                            <!-- NISN -->
                            <td class="py-3.5 px-4 font-mono text-slate-600 font-semibold">
                                <span class="bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200">
                                    {{ $item['nisn'] }}
                                </span>
                            </td>

                            <!-- Tipe Dominan Karakteristik -->
                            <td class="py-3.5 px-4 text-center">
                                <span class="badge-dominant">
                                    <span>{{ $pal['icon'] }}</span>
                                    <span>{{ $item['label_primer'] }}</span>
                                </span>
                            </td>

                            <!-- Waktu Submit -->
                            <td class="py-3.5 px-4 text-center text-slate-500 font-medium">
                                {{ $item['waktu_submit'] }} WIB
                            </td>

                            <!-- Aksi & Lembar Konseling -->
                            <td class="py-3.5 px-4 text-center print:hidden">
                                <a 
                                    href="{{ $item['url_konseling'] }}" 
                                    class="btn-sky-action w-full sm:w-auto justify-center"
                                    title="Lihat Lembar Diagnostik & Konseling untuk Orang Tua"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span>Lembar Konseling &amp; Ortu &rarr;</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr id="empty-state-initial">
                            <td colspan="6" class="py-12 text-center text-slate-400 text-xs">
                                <div class="max-w-xs mx-auto space-y-2">
                                    <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center mx-auto border border-sky-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                    </div>
                                    <p class="font-bold text-slate-700">Belum Ada Siswa Menyelesaikan Asesmen</p>
                                    <p class="text-[11px] text-slate-500">Hasil pengerjaan siswa akan langsung muncul di sini setelah disubmit.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                    <!-- Baris Kosong Dinamis Jika Filter Menghasilkan 0 Siswa -->
                    <tr id="row-empty-filter" class="hidden">
                        <td colspan="6" class="py-12 text-center">
                            <div class="max-w-sm mx-auto space-y-3">
                                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto border border-amber-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800 text-sm">Tidak Ada Siswa dengan Karakteristik Ini</p>
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        Belum ada murid di kelas ini yang memiliki kecenderungan dominan pada karakteristik yang dipilih.
                                    </p>
                                </div>
                                <button
                                    type="button"
                                    onclick="filterByKarakteristik('all')"
                                    class="btn-sky-action inline-flex items-center gap-1.5"
                                >
                                    <span>Tampilkan Semua Siswa</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- ========================================================================= -->
<!-- MODAL POP-UP: DAFTAR LENGKAP MURID BERKARAKTERISTIK TERPILIH             -->
<!-- ========================================================================= -->
<div 
    id="modal-murid-karakteristik" 
    class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop-custom hidden"
    aria-hidden="true"
>
    <div class="bg-white rounded-3xl max-w-xl w-full max-h-[85vh] flex flex-col shadow-2xl border border-sky-100 overflow-hidden transform transition-all">
        
        <!-- Header Modal -->
        <div class="p-6 border-b border-slate-100 flex items-center justify-between gap-3 bg-gradient-to-r from-sky-50 to-white">
            <div class="flex items-center gap-3">
                <span id="modal-icon-box" class="w-10 h-10 rounded-2xl bg-sky-100 text-sky-700 text-lg flex items-center justify-center border border-sky-200 shadow-2xs">
                    🌟
                </span>
                <div>
                    <h3 id="modal-title" class="text-base font-extrabold text-slate-900">
                        Daftar Murid Berkarakteristik
                    </h3>
                    <p id="modal-subtitle" class="text-xs text-slate-500 font-medium">
                        Hasil Analisis Psikometrik Kelas
                    </p>
                </div>
            </div>

            <button
                type="button"
                onclick="closeStudentModal()"
                class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors cursor-pointer"
                title="Tutup Modal"
            >
                <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Uraian Singkat Karakteristik di Modal -->
        <div class="px-6 py-3 bg-slate-50/70 border-b border-slate-100 text-xs text-slate-600">
            <p id="modal-desc" class="leading-relaxed"></p>
        </div>

        <!-- List Murid di Modal (Scrollable) -->
        <div id="modal-list-murid" class="p-6 overflow-y-auto space-y-3 divide-y divide-slate-100">
            <!-- Diisi Secara Dinamis via JavaScript -->
        </div>

        <!-- Footer Modal -->
        <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between gap-3">
            <span id="modal-footer-count" class="text-xs font-semibold text-slate-500">
                Total 0 Siswa
            </span>
            <div class="flex items-center gap-2">
                <button
                    type="button"
                    onclick="closeStudentModal()"
                    class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800 bg-white hover:bg-slate-100 border border-slate-200 rounded-xl transition-all cursor-pointer"
                >
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Data Lengkap Karakteristik & Siswa Terdaftar
    const dimensiData = {
        @foreach($analisisKelas['dimensi_info'] as $dimKey => $info)
            @php
                $students = $siswaPerDimensi[$dimKey] ?? [];
                $pal = $paletteMapping[$dimKey] ?? $defaultPalette;
            @endphp
            "{{ $dimKey }}": {
                key: "{{ $dimKey }}",
                nama: "{{ addslashes($info['nama']) }}",
                deskripsi: "{{ addslashes($info['deskripsi'] ?? '') }}",
                karakter: "{{ addslashes($info['karakter'] ?? '') }}",
                count: {{ count($students) }},
                persen: {{ $analisisKelas['persentase_distribusi'][$dimKey] ?? 0 }},
                accent: "{{ $pal['accent'] }}",
                icon: "{{ $pal['icon'] }}",
                students: @json($students)
            },
        @endforeach
    };

    const allStudents = @json($sesiAnalyzedList);
    let currentActiveFilter = 'all';

    /**
     * Memfilter tampilan tabel dan detail murid berdasarkan karakteristik yang diklik
     */
    function filterByKarakteristik(dimKey) {
        currentActiveFilter = dimKey;

        // 1. Update State Visual Kartu Karakteristik
        document.querySelectorAll('.dimensi-card').forEach(card => {
            card.classList.remove('is-active');
            const ind = card.querySelector('.active-indicator');
            if (ind) ind.classList.add('hidden');
        });

        const activeCard = document.getElementById(dimKey === 'all' ? 'card-dimensi-all' : 'card-dimensi-' + dimKey);
        if (activeCard) {
            activeCard.classList.add('is-active');
            const ind = activeCard.querySelector('.active-indicator');
            if (ind) ind.classList.remove('hidden');
        }

        // 2. Ambil List Siswa Sesuai Filter
        let matchedStudents = [];
        let labelName = 'Semua Siswa';
        let descText = 'Menampilkan seluruh murid dari hasil pelaksanaan asesmen ini. Klik pada salah satu kartu karakteristik di atas untuk memfilter siswa secara spesifik.';
        let iconSymbol = '🌟';

        if (dimKey === 'all') {
            matchedStudents = allStudents;
            labelName = 'Semua Siswa';
        } else if (dimensiData[dimKey]) {
            const data = dimensiData[dimKey];
            matchedStudents = data.students || [];
            labelName = data.nama;
            descText = data.deskripsi + (data.karakter ? ' Karakter khas: ' + data.karakter : '');
            iconSymbol = data.icon || '🎯';
        }

        // 3. Update Banner Detail Karakteristik
        const badgeEl = document.getElementById('badge-karakter-terpilih');
        const countEl = document.getElementById('counter-karakter-terpilih');
        const descEl = document.getElementById('desc-karakter-terpilih');
        const labelFilterTabel = document.getElementById('label-filter-aktif');
        const tableCounterPill = document.getElementById('table-counter-pill');

        if (badgeEl) badgeEl.innerHTML = `${iconSymbol} Menampilkan: ${labelName}`;
        if (countEl) countEl.innerText = `${matchedStudents.length} siswa dengan karakteristik ini`;
        if (descEl) descEl.innerText = descText;
        if (labelFilterTabel) labelFilterTabel.innerText = dimKey === 'all' ? 'Semua Siswa' : labelName;
        if (tableCounterPill) tableCounterPill.innerText = `${matchedStudents.length} Siswa Ditampilkan`;

        // 4. Update Chips Preview Murid di Atas Tabel
        const chipsContainer = document.getElementById('chips-container-murid');
        if (chipsContainer) {
            if (matchedStudents.length === 0) {
                chipsContainer.innerHTML = `
                    <div class="text-xs text-amber-700 bg-amber-50 px-3 py-1.5 rounded-xl border border-amber-200">
                        Belum ada murid di kelas ini dengan karakteristik dominan <strong>${labelName}</strong>.
                    </div>
                `;
            } else {
                chipsContainer.innerHTML = matchedStudents.map(s => `
                    <a 
                        href="${s.url_konseling}"
                        class="chip-murid inline-flex items-center gap-2 bg-white hover:bg-sky-50 border border-slate-200 hover:border-sky-300 py-1.5 px-3 rounded-xl text-xs font-semibold text-slate-800 shadow-2xs transition-all"
                        title="Klik untuk membuka Lembar Konseling ${s.nama_siswa}"
                    >
                        <span class="w-5 h-5 rounded-md bg-sky-100 text-sky-700 font-bold text-[10px] flex items-center justify-center">
                            ${(s.nama_siswa || 'S').charAt(0).toUpperCase()}
                        </span>
                        <span>${s.nama_siswa}</span>
                        <span class="text-[10px] font-mono text-slate-400">(${s.nisn})</span>
                        <span class="text-[10px] font-bold text-sky-600 bg-sky-50 px-1.5 py-0.5 rounded border border-sky-100">
                            ${s.label_primer || ''}
                        </span>
                    </a>
                `).join('');
            }
        }

        // 5. Filter Baris Tabel Secara Real-time
        const rows = document.querySelectorAll('.row-siswa');
        let visibleCount = 0;

        rows.forEach(row => {
            const rowDim = row.getAttribute('data-dimensi');
            if (dimKey === 'all' || rowDim === dimKey) {
                row.classList.remove('hidden');
                visibleCount++;
                const noCell = row.querySelector('.row-number');
                if (noCell) noCell.innerText = visibleCount;
            } else {
                row.classList.add('hidden');
            }
        });

        // 6. Tampilkan/Sembunyikan Empty State Jika 0 Hasil
        const emptyRow = document.getElementById('row-empty-filter');
        if (emptyRow) {
            if (visibleCount === 0 && allStudents.length > 0) {
                emptyRow.classList.remove('hidden');
            } else {
                emptyRow.classList.add('hidden');
            }
        }
    }

    /**
     * Membuka Modal Pop-up Daftar Murid
     */
    function openStudentModal() {
        const modal = document.getElementById('modal-murid-karakteristik');
        const modalTitle = document.getElementById('modal-title');
        const modalSubtitle = document.getElementById('modal-subtitle');
        const modalDesc = document.getElementById('modal-desc');
        const modalIcon = document.getElementById('modal-icon-box');
        const modalList = document.getElementById('modal-list-murid');
        const modalFooterCount = document.getElementById('modal-footer-count');

        let students = allStudents;
        let title = 'Semua Siswa';
        let desc = 'Daftar seluruh siswa yang telah menyelesaikan asesmen psikometrik ini.';
        let icon = '🌟';

        if (currentActiveFilter !== 'all' && dimensiData[currentActiveFilter]) {
            const d = dimensiData[currentActiveFilter];
            students = d.students || [];
            title = 'Murid Berkarakteristik: ' + d.nama;
            desc = d.deskripsi;
            icon = d.icon || '🎯';
        }

        modalTitle.innerText = title;
        modalSubtitle.innerText = `${students.length} Siswa Teridentifikasi`;
        modalDesc.innerText = desc;
        modalIcon.innerText = icon;
        modalFooterCount.innerText = `Total: ${students.length} Siswa`;

        if (students.length === 0) {
            modalList.innerHTML = `
                <div class="py-8 text-center text-slate-400 space-y-2">
                    <p class="font-bold text-slate-700">Tidak ada murid terdaftar</p>
                    <p class="text-xs">Belum ada murid di kelas ini dengan kecenderungan karakteristik ini.</p>
                </div>
            `;
        } else {
            modalList.innerHTML = students.map((s, idx) => `
                <div class="pt-3 first:pt-0 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-sky-100 text-sky-700 font-extrabold text-sm flex items-center justify-center border border-sky-200 shrink-0">
                            ${(s.nama_siswa || 'S').charAt(0).toUpperCase()}
                        </div>
                        <div>
                            <span class="font-bold text-slate-900 block text-sm">${s.nama_siswa}</span>
                            <div class="flex items-center gap-2 text-xs text-slate-500 font-mono">
                                <span>NISN: ${s.nisn}</span>
                                <span>&bull;</span>
                                <span class="text-sky-700 font-bold">${s.label_primer}</span>
                            </div>
                        </div>
                    </div>
                    <div class="shrink-0">
                        <a 
                            href="${s.url_konseling}" 
                            class="btn-sky-action text-xs"
                        >
                            <span>Lembar Konseling &rarr;</span>
                        </a>
                    </div>
                </div>
            `).join('');
        }

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    /**
     * Menutup Modal Pop-up
     */
    function closeStudentModal() {
        const modal = document.getElementById('modal-murid-karakteristik');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Tutup modal jika klik di luar box modal
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('modal-murid-karakteristik');
        if (event.target === modal) {
            closeStudentModal();
        }
    });

    // Keyboard ESC untuk menutup modal
    window.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeStudentModal();
        }
    });
</script>
@endpush
