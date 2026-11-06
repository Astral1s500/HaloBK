@extends('layouts.app')

@section('title', 'Dashboard Guru BK')

@push('styles')
<link rel="stylesheet" href="{{ asset('vendor/driverjs/driver.css') }}">
<style>
    /* Styling Solid Sky Cloud Guru BK (Bypass Keterbatasan Kompilasi CSS) */
    .hero-guru-sky {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 60%, #075985 100%);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .stat-pill {
        background: rgba(255, 255, 255, 0.16);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.25);
        padding: 0.6rem 1.15rem;
        border-radius: 16px;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #ffffff;
    }

    /* List Card Fitur Vertikal (Tersusun ke Bawah) */
    .feature-row-card {
        background: #ffffff;
        border: 1px solid #e0f2fe;
        border-radius: 22px;
        padding: 1.25rem 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        box-shadow: 0 4px 15px -2px rgba(2, 132, 199, 0.06);
        transition: all 0.25s ease;
    }
    @media (min-width: 640px) {
        .feature-row-card {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
    }
    .feature-row-card:hover {
        transform: translateY(-2px);
        border-color: #7dd3fc;
        box-shadow: 0 10px 25px -4px rgba(2, 132, 199, 0.12);
    }

    /* Avatar Icon Box (Pasti Berwarna & Tajam) */
    .icon-box-kelas {
        width: 52px;
        height: 52px;
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
    .icon-box-assesment {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        background: #ccfbf1;
        color: #0d9488;
        border: 1px solid #99f6e4;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(13, 148, 136, 0.12);
        flex-shrink: 0;
    }
    .icon-box-hasil {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        background: #ede9fe;
        color: #7c3aed;
        border: 1px solid #ddd6fe;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(124, 58, 237, 0.12);
        flex-shrink: 0;
    }

    /* Tombol Aksi Solid Pasti Berwarna */
    .btn-sky-action {
        background-color: #0284c7;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 0.825rem;
        padding: 0.65rem 1.25rem;
        border-radius: 12px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        box-shadow: 0 3px 10px rgba(2, 132, 199, 0.25);
        transition: all 0.2s ease;
        border: none;
    }
    .btn-sky-action:hover {
        background-color: #0369a1;
        transform: translateY(-1px);
    }

    .btn-teal-action {
        background-color: #0d9488;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 0.825rem;
        padding: 0.65rem 1.25rem;
        border-radius: 12px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        box-shadow: 0 3px 10px rgba(13, 148, 136, 0.25);
        transition: all 0.2s ease;
        border: none;
    }
    .btn-teal-action:hover {
        background-color: #0f766e;
        transform: translateY(-1px);
    }

    .btn-purple-action {
        background-color: #7c3aed;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 0.825rem;
        padding: 0.65rem 1.25rem;
        border-radius: 12px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        box-shadow: 0 3px 10px rgba(124, 58, 237, 0.25);
        transition: all 0.2s ease;
        border: none;
    }
    .btn-purple-action:hover {
        background-color: #6d28d9;
        transform: translateY(-1px);
    }

    /* Badges */
    .badge-soft-sky {
        background: #f0f9ff;
        color: #0369a1;
        border: 1px solid #bae6fd;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-soft-teal {
        background: #f0fdfa;
        color: #0f766e;
        border: 1px solid #99f6e4;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-soft-purple {
        background: #faf5ff;
        color: #6d28d9;
        border: 1px solid #ddd6fe;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* Driver.js tour */
    .driver-popover.halobk-tour-popover {
        background-color: #ffffff;
        color: #1e293b;
        border-radius: 1.25rem;
        padding: 1.25rem;
        box-shadow: 0 25px 50px -12px rgba(2, 132, 199, 0.2);
        border: 1px solid #bae6fd;
        max-width: 360px;
        font-family: inherit;
    }
    .driver-popover.halobk-tour-popover .driver-popover-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.35rem;
        line-height: 1.35;
    }
    .driver-popover.halobk-tour-popover .driver-popover-description {
        font-size: 0.8rem;
        color: #475569;
        line-height: 1.5;
        margin-bottom: 1rem;
    }
    .driver-popover.halobk-tour-popover .driver-popover-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 0.75rem;
        border-top: 1px solid #f1f5f9;
    }
    .driver-popover.halobk-tour-popover .driver-popover-progress-text {
        font-size: 0.7rem;
        font-weight: 600;
        color: #0284c7;
    }
    .driver-popover.halobk-tour-popover .driver-popover-footer-btn {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.45rem 0.85rem;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
    }
    .driver-popover.halobk-tour-popover .driver-popover-prev-btn {
        background-color: #f1f5f9;
        color: #334155;
        margin-right: 0.5rem;
    }
    .driver-popover.halobk-tour-popover .driver-popover-prev-btn:hover {
        background-color: #e2e8f0;
        color: #0f172a;
    }
    .driver-popover.halobk-tour-popover .driver-popover-next-btn {
        background-color: #0284c7;
        color: #ffffff;
    }
    .driver-popover.halobk-tour-popover .driver-popover-close-btn {
        color: #94a3b8;
        font-size: 1.25rem;
        top: 0.75rem;
        right: 0.75rem;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">

    <!-- 1. Compact Hero Welcome & Status Bar (Sky Cloud Theme) -->
    <div id="tour-hero" class="w-full text-white rounded-3xl p-6 md:p-8 shadow-lg shadow-sky-900/10 flex flex-col xl:flex-row justify-between items-start xl:items-center gap-6 relative overflow-hidden hero-guru-sky">
        
        <!-- Ambient Decorative Cloud Circles -->
        <div style="position: absolute; right: -50px; top: -50px; width: 260px; height: 260px; border-radius: 50%; background: rgba(255, 255, 255, 0.12); pointer-events: none; filter: blur(30px);"></div>
        <div style="position: absolute; left: -40px; bottom: -40px; width: 200px; height: 200px; border-radius: 50%; background: rgba(186, 230, 253, 0.18); pointer-events: none; filter: blur(25px);"></div>

        <div class="space-y-2 min-w-0 relative z-10">
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.35rem 0.9rem; border-radius: 9999px; background: rgba(255, 255, 255, 0.18); backdrop-filter: blur(8px); border: 1px solid rgba(255, 255, 255, 0.25); font-size: 0.75rem; font-weight: 600;">
                <span style="width: 8px; height: 8px; border-radius: 50%; background-color: #7dd3fc; display: inline-block;"></span>
                <span>Panel Guru BK &bull; NIP: {{ Auth::guard('guru')->user()->nip }}</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight leading-tight">
                Selamat Datang, {{ Auth::guard('guru')->user()->nama_guru }}
            </h1>
            <p class="text-xs md:text-sm text-sky-100 max-w-xl leading-relaxed">
                Pusat manajemen bimbingan konseling, pantau kelas binaan, serta pembuatan dan evaluasi asesmen siswa.
            </p>
        </div>

        <!-- Quick Summary Stats (Hanya Kelas Binaan & Assesment) & Tombol Tur Fitur -->
        <div class="flex items-center gap-3 w-full xl:w-auto overflow-x-auto pb-1 xl:pb-0 shrink-0 relative z-10">
            <div id="tour-stats" class="flex items-center gap-3 shrink-0">
                
                <!-- Stat 1: Kelas Binaan -->
                <div class="stat-pill">
                    <div style="width: 32px; height: 32px; border-radius: 10px; background: rgba(255, 255, 255, 0.2); display: flex; align-items: center; justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <span class="block text-base font-extrabold text-white leading-tight">{{ $totalKelas ?? 0 }}</span>
                        <span class="text-[10px] text-sky-100 font-medium">Kelas Binaan</span>
                    </div>
                </div>

                <!-- Stat 2: Assesment -->
                <div class="stat-pill">
                    <div style="width: 32px; height: 32px; border-radius: 10px; background: rgba(255, 255, 255, 0.2); display: flex; align-items: center; justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <div>
                        <span class="block text-base font-extrabold text-white leading-tight">{{ $totalTes ?? 0 }}</span>
                        <span class="text-[10px] text-sky-100 font-medium">Assesment</span>
                    </div>
                </div>
            </div>

            <!-- Tombol Tour Panduan Fitur Guru BK Driver.js -->
            <button 
                type="button" 
                id="btnStartTour" 
                onclick="startGuruTour()"
                style="background: #ffffff; color: #0f172a; padding: 0.65rem 1.15rem; border-radius: 16px; border: none; cursor: pointer; display: flex; align-items: center; gap: 0.65rem; font-size: 0.8rem; font-weight: 700; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); transition: all 0.2s;"
                title="Mulai Tur Panduan Fitur Guru"
            >
                <div style="width: 28px; height: 28px; border-radius: 10px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                </div>
                <div class="text-left hidden sm:block">
                    <span style="display: block; font-size: 0.75rem; font-weight: 800; color: #0f172a; line-height: 1.1;">Tur Fitur</span>
                    <span style="font-size: 0.65rem; color: #0284c7; font-weight: 600;">Panduan Guru</span>
                </div>
            </button>
        </div>
    </div>

    <!-- 2. Layanan & Fitur Guru BK — Disusun Ke Bawah (Vertikal List Card Modern) -->
    <div>
        <div class="flex items-center justify-between mb-3.5">
            <div>
                <h2 class="text-base font-bold text-slate-900 tracking-tight">
                    Layanan &amp; Fitur Utama Guru BK
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Pilih menu untuk mengelola kelas binaan, pembuatan assesment, dan evaluasi hasil.</p>
            </div>
            <span class="text-xs text-sky-700 font-semibold bg-sky-50 px-3 py-1 rounded-full border border-sky-100">
                3 Fitur Terpadu
            </span>
        </div>

        <!-- Container Fitur Tersusun Ke Bawah -->
        <div class="space-y-3.5">
            
            <!-- Fitur 1: Kelas Binaan -->
            <div id="tour-card-kelas" class="feature-row-card">
                <div class="flex items-start sm:items-center gap-4">
                    <div class="icon-box-kelas">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:26px;height:26px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-base font-bold text-slate-900 tracking-tight">Kelas Binaan</h3>
                            <span class="badge-soft-sky">{{ $totalKelas ?? 0 }} Kelas Aktif</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                            Buat kelas bimbingan baru, dapatkan Kode Unik untuk dibagikan ke siswa, dan pantau seluruh anggota kelas binaan.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 shrink-0 self-end sm:self-auto pt-2 sm:pt-0">
                    <a 
                        href="{{ route('guru.kelas.index') }}" 
                        class="btn-sky-action"
                    >
                        <span>Kelola Kelas</span>
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Fitur 2: Assesment (Sebelumnya: Soal & Paket Tes) -->
            <div id="tour-card-tes" class="feature-row-card">
                <div class="flex items-start sm:items-center gap-4">
                    <div class="icon-box-assesment">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:26px;height:26px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-base font-bold text-slate-900 tracking-tight">Assesment</h3>
                            <span class="badge-soft-teal">{{ $totalTes ?? 0 }} Paket Assesment</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                            Buat instrumen evaluasi bimbingan, kuesioner psikologis, soal Pilihan Ganda &amp; Esai, serta jadwal pelaksanaan.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 shrink-0 self-end sm:self-auto pt-2 sm:pt-0">
                    <a 
                        href="{{ route('guru.tes.index') }}" 
                        class="btn-teal-action"
                    >
                        <span>Kelola Assesment</span>
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Fitur 3: Koreksi & Hasil Assesment -->
            <div id="tour-card-koreksi" class="feature-row-card">
                <div class="flex items-start sm:items-center gap-4">
                    <div class="icon-box-hasil">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:26px;height:26px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-base font-bold text-slate-900 tracking-tight">Hasil &amp; Koreksi Assesment</h3>
                            <span class="badge-soft-purple">Rekapitulasi Skor</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                            Periksa hasil pengerjaan siswa, berikan penilaian untuk jawaban esai/kuesioner, dan lihat rekapitulasi nilai lengkap.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 shrink-0 self-end sm:self-auto pt-2 sm:pt-0">
                    <a 
                        href="{{ route('guru.koreksi.index') }}" 
                        class="btn-purple-action"
                    >
                        <span>Lihat Hasil</span>
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- 3. Tabel Ringkas Kelas Binaan (Tampilan Elegan & Bersih) -->
    <div id="tour-table-kelas" class="bg-white rounded-3xl border border-sky-100 shadow-xs p-6 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-sky-50">
            <div>
                <h3 class="text-base font-bold text-slate-900 tracking-tight">Kelas Binaan Anda</h3>
                <p class="text-xs text-slate-500 mt-0.5">Salin &amp; bagikan Kode Unik kepada siswa untuk bergabung ke kelas binaan.</p>
            </div>
            <a 
                href="{{ route('guru.kelas.create') }}" 
                class="btn-sky-action self-start sm:self-auto"
            >
                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Buat Kelas Baru</span>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-sky-50/60 border-b border-sky-100 text-[11px] font-bold text-slate-600 uppercase tracking-wider">
                        <th class="py-3.5 px-4 rounded-l-xl">Nama Kelas</th>
                        <th class="py-3.5 px-4">Kode Unik</th>
                        <th class="py-3.5 px-4 text-center">Jumlah Siswa</th>
                        <th class="py-3.5 px-4 text-center w-32 rounded-r-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sky-50 text-xs text-slate-700">
                    @forelse($kelasList as $kelas)
                        <tr class="hover:bg-sky-50/40 transition-colors">
                            <td class="py-3.5 px-4 font-bold text-slate-900">{{ $kelas->nama_kelas }}</td>
                            <td class="py-3.5 px-4">
                                <div class="inline-flex items-center gap-1.5">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-sky-50 text-sky-800 border border-sky-200 tracking-wider">
                                        {{ $kelas->kode_unik_kelas }}
                                    </span>
                                    <button 
                                        type="button" 
                                        onclick="copyDashboardCode('{{ $kelas->kode_unik_kelas }}', this)" 
                                        class="inline-flex items-center gap-1 text-slate-600 hover:text-sky-700 text-[11px] font-semibold bg-white hover:bg-sky-50 border border-slate-200 px-2.5 py-1 rounded-lg transition-colors cursor-pointer shadow-2xs"
                                        title="Salin Kode Unik"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        <span>Salin</span>
                                    </button>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-sky-50 text-sky-700 border border-sky-100">
                                    {{ $kelas->siswas_count ?? 0 }} Siswa
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <a 
                                    href="{{ route('guru.kelas.show', $kelas->id_kelas) }}" 
                                    class="inline-flex items-center gap-1 text-[11px] font-semibold text-sky-700 hover:text-sky-900 bg-sky-50 hover:bg-sky-100 px-3 py-1.5 rounded-lg transition-colors cursor-pointer border border-sky-100"
                                >
                                    <span>Buka Kelas</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-xs text-slate-400">
                                Belum ada kelas binaan. Klik tombol <strong>+ Buat Kelas Baru</strong> di kanan atas untuk memulai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
function copyDashboardCode(text, btn) {
    navigator.clipboard.writeText(text).then(function() {
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<span style="color:#059669;font-weight:bold;">Tersalin!</span>';
        setTimeout(function() {
            btn.innerHTML = originalHtml;
        }, 1500);
    });
}
</script>
@endsection

@push('scripts')
<script src="{{ asset('vendor/driverjs/driver.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const driverFactory = (window.driver && window.driver.js && window.driver.js.driver) ? window.driver.js.driver : (window.driver ? window.driver : null);

    if (!driverFactory) {
        console.warn('Driver.js tidak dapat dimuat.');
        return;
    }

    const guruTourDriver = driverFactory({
        showProgress: true,
        animate: true,
        allowClose: true,
        overlayOpacity: 0.65,
        stagePadding: 8,
        stageRadius: 16,
        popoverClass: 'halobk-tour-popover',
        nextBtnText: 'Lanjut &rarr;',
        prevBtnText: '&larr; Kembali',
        doneBtnText: 'Selesai &#10003;',
        progressText: 'Langkah @{{current}} dari @{{total}}',
        steps: [
            {
                element: '#tour-hero',
                popover: {
                    title: '👋 Selamat Datang di Dashboard Guru BK',
                    description: 'Ini adalah pusat kendali utama Anda sebagai Guru BK. Di sini Anda dapat mengelola kelas binaan, pembuatan assesment, dan evaluasi hasil siswa secara terpusat.',
                    side: 'bottom',
                    align: 'start'
                }
            },
            {
                element: '#tour-stats',
                popover: {
                    title: '📊 Ringkasan Statistik',
                    description: 'Pantau jumlah kelas binaan aktif dan paket assesment yang tersedia secara sekilas.',
                    side: 'bottom',
                    align: 'end'
                }
            },
            {
                element: '#tour-card-kelas',
                popover: {
                    title: '🏫 Manajemen Kelas Binaan',
                    description: 'Buat kelas bimbingan baru, dapatkan Kode Unik untuk dibagikan kepada siswa, dan kelola daftar anggota siswa di setiap kelas.',
                    side: 'bottom',
                    align: 'start'
                }
            },
            {
                element: '#tour-card-tes',
                popover: {
                    title: '📝 Assesment & Kuesioner',
                    description: 'Buat instrumen evaluasi dan asesmen BK. Anda dapat membuat soal Pilihan Ganda, Esai, serta kuesioner asesmen minat bakat.',
                    side: 'bottom',
                    align: 'start'
                }
            },
            {
                element: '#tour-card-koreksi',
                popover: {
                    title: '✅ Hasil & Koreksi Assesment',
                    description: 'Periksa jawaban esai siswa, berikan penilaian, serta pantau rekapitulasi nilai ujian dan angket.',
                    side: 'bottom',
                    align: 'start'
                }
            },
            {
                element: '#tour-table-kelas',
                popover: {
                    title: '🔑 Kode Unik Kelas & Akses Cepat',
                    description: 'Tabel ringkas kelas binaan Anda. Anda dapat langsung menekan tombol <strong>Salin</strong> pada kode kelas untuk dibagikan ke siswa.',
                    side: 'top',
                    align: 'start'
                }
            },
            {
                element: '#btnStartTour',
                popover: {
                    title: '🎉 Panduan Siap!',
                    description: 'Tur pengenalan dashboard telah selesai. Anda dapat menekan tombol <strong>Tur Fitur</strong> ini kapan pun jika membutuhkan panduan kembali.',
                    side: 'bottom',
                    align: 'end'
                }
            }
        ]
    });

    window.startGuruTour = function () {
        guruTourDriver.drive();
    };

    const hasSeenTour = sessionStorage.getItem('halobk_tour_shown_guru');
    const isFreshLogin = {{ session('login_success') ? 'true' : 'false' }};

    if (!hasSeenTour || isFreshLogin) {
        sessionStorage.setItem('halobk_tour_shown_guru', 'true');

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Selamat Datang!',
                html: '<div class="space-y-1.5 mt-1"><p class="font-bold text-slate-800 text-sm md:text-base">{{ Auth::guard("guru")->user()->nama_guru }}</p><p class="text-xs text-slate-500">Anda berhasil masuk ke Panel Guru BK HaloBK. Mari ikuti tur panduan fitur!</p></div>',
                timer: 2300,
                timerProgressBar: true,
                showConfirmButton: true,
                confirmButtonText: 'Mulai Tur Sekarang &rarr;',
                confirmButtonColor: '#0284c7',
                customClass: {
                    popup: 'rounded-2xl shadow-2xl p-6',
                    title: 'text-lg font-bold text-slate-900',
                    confirmButton: 'rounded-xl px-5 py-2.5 text-xs font-semibold cursor-pointer'
                }
            }).then(() => {
                setTimeout(() => {
                    guruTourDriver.drive();
                }, 350);
            });
        } else {
            setTimeout(() => {
                guruTourDriver.drive();
            }, 500);
        }
    }
});
</script>
@endpush
