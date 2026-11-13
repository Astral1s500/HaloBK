@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@push('styles')
<link rel="stylesheet" href="{{ asset('vendor/driverjs/driver.css') }}">
<style>
    /* ==========================================================================
       BENTO GRID SISWA - DESAIN PRESISI 1:1 SESUAI MOCKUP REFERENSI
       Bebas dependensi build, self-contained CSS, rapi & proporsional
       ========================================================================== */
    .bento-card-clean {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 22px;
        padding: 1.25rem 1.35rem;
        box-shadow: 0 4px 18px -2px rgba(2, 132, 199, 0.04), 0 2px 6px rgba(0, 0, 0, 0.02);
        transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .bento-card-clean:hover {
        border-color: #bae6fd;
        box-shadow: 0 8px 24px -4px rgba(2, 132, 199, 0.08);
    }

    /* Hero Card dengan background biru solid persis mockup */
    .hero-siswa-box {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 70%, #075985 100%);
        border-radius: 22px;
        padding: 1.35rem 1.45rem;
        color: #ffffff;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 25px -5px rgba(2, 132, 199, 0.28);
    }

    /* Container Donut Chart Terpusat yang Terkunci Ukurannya */
    .donut-fixed-container {
        width: 102px !important;
        height: 102px !important;
        min-width: 102px !important;
        max-width: 102px !important;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* Metrik List Rapi & Berjarak Proporsional (Bebas Cut-off) */
    .metric-row-pill {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        padding: 0.55rem 0.85rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 700;
        width: 100%;
        line-height: 1.2;
        transition: all 0.15s ease;
    }
    .metric-row-green {
        background-color: #ecfdf5;
        border: 1px solid #d1fae5;
        color: #065f46;
    }
    .metric-row-amber {
        background-color: #fffbeb;
        border: 1px solid #fef3c7;
        color: #92400e;
    }
    .metric-row-sky {
        background-color: #f0f9ff;
        border: 1px solid #e0f2fe;
        color: #0369a1;
    }

    /* Card Item Asesmen Aktif */
    .item-tes-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.1rem 1.2rem;
        transition: all 0.2s ease;
    }
    .item-tes-box:hover {
        border-color: #7dd3fc;
        box-shadow: 0 6px 18px -3px rgba(2, 132, 199, 0.1);
        transform: translateY(-1px);
    }

    /* Pill Card Riwayat */
    .item-riwayat-pill {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        padding: 0.75rem 0.95rem;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
    }
    .item-riwayat-pill.bg-blue-tint {
        background-color: #eff6ff;
        border-color: #dbeafe;
    }
    .item-riwayat-pill.bg-green-tint {
        background-color: #f0fdf4;
        border-color: #dcfce7;
    }
    .item-riwayat-pill:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    }

    /* Card Materi Edukasi dengan Ilustrasi Hangat */
    .materi-preview-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.2s ease;
    }
    .materi-preview-card:hover {
        border-color: #7dd3fc;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px -3px rgba(2, 132, 199, 0.12);
    }

    /* Tombol Aksi */
    .btn-sky-pill {
        background-color: #0284c7;
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.75rem;
        padding: 0.45rem 1.05rem;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        border: none;
        box-shadow: 0 2px 6px rgba(2, 132, 199, 0.25);
        transition: all 0.2s;
        text-decoration: none;
        cursor: pointer;
    }
    .btn-sky-pill:hover {
        background-color: #0369a1;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(2, 132, 199, 0.35);
    }

    .btn-outline-pill {
        background-color: #ffffff;
        color: #0369a1 !important;
        font-weight: 700;
        font-size: 0.75rem;
        padding: 0.4rem 0.95rem;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        border: 1.5px solid #bae6fd;
        transition: all 0.2s;
        text-decoration: none;
        cursor: pointer;
    }
    .btn-outline-pill:hover {
        background-color: #f0f9ff;
        border-color: #7dd3fc;
    }

    /* Card Onboarding Khusus Siswa Belum Punya Kelas */
    .onboarding-card {
        background: #ffffff;
        border: 1.5px solid #e0f2fe;
        border-radius: 26px;
        box-shadow: 0 20px 45px -10px rgba(2, 132, 199, 0.12);
        padding: 2.75rem 2.25rem;
        max-width: 520px;
        margin: 2rem auto;
        text-align: center;
    }
    .input-code-huge {
        width: 100%;
        text-align: center;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 1.25rem;
        font-weight: 700;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        padding: 0.85rem 1.25rem;
        border-radius: 16px;
        border: 2px solid #bae6fd;
        background: #f0f9ff;
        color: #0c4a6e;
        outline: none;
    }
    .input-code-huge:focus {
        border-color: #0284c7;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.15);
    }
    .btn-join-huge {
        width: 100%;
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        color: #ffffff !important;
        font-size: 0.95rem;
        font-weight: 700;
        padding: 0.85rem 1.5rem;
        border-radius: 16px;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        box-shadow: 0 6px 18px rgba(2, 132, 199, 0.3);
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-2 sm:px-4 space-y-5 pb-12">

    @if(!$kelas)
        {{-- ================================================================= --}}
        {{-- ONBOARDING: SISWA BELUM PUNYA KELAS                               --}}
        {{-- ================================================================= --}}
        <div class="onboarding-card">
            <div class="w-16 h-16 rounded-2xl bg-sky-100 text-sky-600 flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Bergabung ke Kelas Binaan</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-2 max-w-md mx-auto leading-relaxed">
                Anda belum terdaftar di kelas manapun. Masukkan <strong>Kode Unik Kelas</strong> dari Guru BK Anda untuk membuka asesmen &amp; materi bimbingan.
            </p>
            <form action="{{ route('siswa.joinKelas') }}" method="POST" class="mt-6 space-y-4 max-w-sm mx-auto">
                @csrf
                <input type="text" name="kode_unik_kelas" class="input-code-huge" placeholder="KODE KELAS" required autocomplete="off" autofocus>
                <button type="submit" class="btn-join-huge">
                    <span>Gabung ke Kelas</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>
        </div>

    @else
        {{-- ================================================================= --}}
        {{-- KONDISI 2: 3-KOLOM BENTO GRID PRESISI 1:1 DENGAN MOCKUP REFERENSI --}}
        {{-- ================================================================= --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">

            {{-- ============================================================= --}}
            {{-- KOLOM 1: KIRI (lg:col-span-4)                                 --}}
            {{-- 1. Hero Siswa + Karakter Pelajar Ransel                       --}}
            {{-- 2. Aktivitas & Progres (Donut Chart + Metrik Lengkap)         --}}
            {{-- 3. Kelas Binaan Anda (Hubungi Guru BK & Status Kelas)         --}}
            {{-- ============================================================= --}}
            <div class="lg:col-span-4 space-y-5">

                <!-- 1. HERO SISWA CARD -->
                <div class="hero-siswa-box flex items-center justify-between gap-3">
                    <div class="space-y-1 z-10 min-w-0 flex-1">
                        <span class="inline-flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-white/20 text-white backdrop-blur-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-sky-300"></span>
                            NISN: {{ $siswa->nisn }}
                        </span>
                        <h1 class="text-xl sm:text-2xl font-black text-white leading-tight tracking-tight mt-1">
                            Halo,<br>{{ $siswa->nama_siswa }}!
                        </h1>
                        <div class="text-xs text-sky-100 pt-1 space-y-0.5">
                            <p class="font-medium">Kelas: <strong class="text-white">{{ $kelas->nama_kelas }}</strong></p>
                            <p class="font-medium text-[11px] truncate">Guru BK: <strong class="text-white">{{ $kelas->guruBk->nama_guru ?? 'Guru BK' }}</strong></p>
                        </div>
                    </div>

                    <!-- Karakter Siswa Ransel Sekolah Ceria Sesuai Mockup -->
                    <div style="width: 110px; height: 110px; min-width: 110px;" class="relative flex items-center justify-center shrink-0">
                        <svg viewBox="0 0 120 120" class="w-full h-full drop-shadow-md" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Lingkaran Latar Belakang Hangat -->
                            <circle cx="60" cy="60" r="50" fill="#bae6fd" fill-opacity="0.4"/>
                            <!-- Baju Oranye Hangat Pelajar -->
                            <path d="M26 118C26 90 42 78 60 78C78 78 94 90 94 118" fill="#f97316"/>
                            <!-- Tali Ransel Biru Sekolah -->
                            <path d="M40 84L36 118" stroke="#0284c7" stroke-width="5" stroke-linecap="round"/>
                            <path d="M80 84L84 118" stroke="#0284c7" stroke-width="5" stroke-linecap="round"/>
                            <!-- Kerah Baju Putih -->
                            <path d="M50 78L60 90L70 78" stroke="#ffffff" stroke-width="3.5" stroke-linecap="round"/>
                            <!-- Leher -->
                            <rect x="53" y="66" width="14" height="15" rx="3" fill="#fed7aa"/>
                            <!-- Kepala -->
                            <circle cx="60" cy="50" r="23" fill="#fed7aa"/>
                            <!-- Rambut Remaja Modern -->
                            <path d="M37 48C37 34 48 24 60 24C73 24 83 32 83 46C79 43 74 45 70 45C64 45 58 41 52 45C46 43 40 46 37 48Z" fill="#334155"/>
                            <!-- Telinga -->
                            <circle cx="37" cy="51" r="4.5" fill="#fed7aa"/>
                            <circle cx="83" cy="51" r="4.5" fill="#fed7aa"/>
                            <!-- Mata & Senyum Ramah -->
                            <circle cx="53" cy="50" r="2.5" fill="#0f172a"/>
                            <circle cx="67" cy="50" r="2.5" fill="#0f172a"/>
                            <path d="M55 58C58 62 62 62 65 58" stroke="#ea580c" stroke-width="2.4" stroke-linecap="round"/>
                            <!-- Pipi Merona -->
                            <circle cx="48" cy="55" r="3.5" fill="#fca5a5" fill-opacity="0.7"/>
                            <circle cx="72" cy="55" r="3.5" fill="#fca5a5" fill-opacity="0.7"/>
                            <!-- Tas Ransel di Belakang Pundak -->
                            <path d="M86 73C93 73 98 78 98 86V112" stroke="#0284c7" stroke-width="8" stroke-linecap="round"/>
                        </svg>
                    </div>
                </div>

                <!-- 2. CARD AKTIVITAS & PROGRES (Donut Chart + Metrik Lengkap Bebas Truncate) -->
                <div class="bento-card-clean space-y-3.5">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <h3 class="text-xs sm:text-sm font-bold text-slate-800 tracking-tight flex items-center gap-2">
                            <span class="text-base leading-none">📊</span>
                            Aktivitas &amp; Progres
                        </h3>
                        <span class="text-[10px] font-semibold text-slate-400">Semester Ini</span>
                    </div>

                    @php
                        $persen = (int) ($stats->persen_selesai ?? 0);
                        $radius = 38;
                        $circumference = 2 * 3.14159 * $radius;
                        $strokeDashoffset = $circumference - ($persen / 100) * $circumference;
                    @endphp

                    <!-- Flexbox Donut Chart & Metrik Terkunci Proporsinya -->
                    <div class="flex items-center gap-4 pt-1">
                        {{-- Circular Donut Ring SVG (Terkunci di 102px agar tidak melebar) --}}
                        <div class="donut-fixed-container">
                            <svg class="w-full h-full transform -rotate-90" viewBox="0 0 96 96">
                                <circle cx="48" cy="48" r="{{ $radius }}" stroke="#f1f5f9" stroke-width="9" fill="transparent" />
                                <circle 
                                    cx="48" 
                                    cy="48" 
                                    r="{{ $radius }}" 
                                    stroke="#0284c7" 
                                    stroke-width="9" 
                                    stroke-linecap="round" 
                                    fill="transparent"
                                    stroke-dasharray="{{ $circumference }}"
                                    stroke-dashoffset="{{ $strokeDashoffset }}"
                                    style="transition: stroke-dashoffset 0.8s ease;"
                                />
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                                <span class="text-xl font-black text-slate-900 leading-none">{{ $persen }}%</span>
                            </div>
                        </div>

                        {{-- 3 Pill Metrik Sesuai Mockup Referensi (Teks Lengkap, Jelas, & Rapi) --}}
                        <div class="space-y-2 flex-1 min-w-0">
                            <!-- Metrik 1: Asesmen Selesai -->
                            <div class="metric-row-pill metric-row-green">
                                <span class="w-4 h-4 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] shrink-0 font-bold">✓</span>
                                <span class="truncate">{{ $stats->total_tes_selesai }} Assesment Selesai</span>
                            </div>

                            <!-- Metrik 2: Perlu Dikerjakan -->
                            <div class="metric-row-pill metric-row-amber">
                                <span class="w-4 h-4 rounded-full bg-amber-500 text-white flex items-center justify-center text-[10px] shrink-0 font-bold">!</span>
                                <span class="truncate">{{ $stats->total_tes_aktif }} Perlu Dikerjakan</span>
                            </div>

                            <!-- Metrik 3: Materi Siap Baca -->
                            <div class="metric-row-pill metric-row-sky">
                                <span class="w-4 h-4 rounded-full bg-sky-500 text-white flex items-center justify-center text-[10px] shrink-0 font-bold">i</span>
                                <span class="truncate">{{ $stats->total_materi }} Materi Siap Baca</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. CARD KELAS BINAAN ANDA -->
                <div class="bento-card-clean space-y-3">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Kelas Binaan Anda
                        </h3>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            {{ $kelas->nama_kelas }}
                        </span>
                    </div>

                    <div class="space-y-1.5 text-xs text-slate-600">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400">Guru BK Pembimbing:</span>
                            <span class="font-bold text-slate-800">{{ $kelas->guruBk->nama_guru ?? 'Guru BK' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400">Teman Sekelas:</span>
                            <span class="font-bold text-slate-800">{{ $stats->total_teman }} Siswa</span>
                        </div>
                    </div>

                    <!-- Tombol Keluar dari Kelas Ini -->
                    <form action="{{ route('siswa.leaveKelas') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin keluar dari kelas {{ $kelas->nama_kelas }}? Anda harus memasukkan kode kelas baru untuk mengakses soal kembali.');" class="pt-2">
                        @csrf
                        <button type="submit" class="w-full text-center py-2 px-3 rounded-xl text-xs font-semibold text-rose-600 hover:text-rose-700 bg-rose-50/70 hover:bg-rose-100 border border-rose-100 transition-colors cursor-pointer">
                            Keluar dari Kelas Ini
                        </button>
                    </form>
                </div>

            </div>

            {{-- ============================================================= --}}
            {{-- KOLOM 2: TENGAH (lg:col-span-4)                               --}}
            {{-- 1. Aktivitas Hari Ini (Pencarian & Status Bar Mockup)         --}}
            {{-- 2. Tugas & Assesment Aktif                                    --}}
            {{-- 3. Riwayat & Aktivitas Terbaru (Pill Row Rapi Mockup)         --}}
            {{-- ============================================================= --}}
            <div class="lg:col-span-4 space-y-5">

                <!-- 1. BAR AKTIVITAS HARI INI / REKOMENDASI (Persis Mockup) -->
                <div class="bento-card-clean space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-bold text-slate-800 tracking-tight flex items-center gap-1.5">
                            Aktivitas Hari Ini / Rekomendasi
                        </h3>
                    </div>

                    <!-- Search Input Pintasan Cepat + Status Badge Selesai ala Mockup -->
                    <div class="flex items-center gap-2">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input 
                                type="text" 
                                id="quickSearchTest"
                                oninput="filterTestsByKeyword(this.value)"
                                placeholder="Pintasan cepat cari asesmen..." 
                                class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder:text-slate-400 focus:outline-none focus:border-sky-400 focus:bg-white transition-colors"
                            >
                        </div>
                        <span class="px-2.5 py-1.5 text-[11px] font-bold rounded-xl {{ $stats->total_tes_aktif > 0 ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }} shrink-0">
                            {{ $stats->total_tes_aktif > 0 ? 'Ada Tugas' : 'Selesai' }}
                        </span>
                    </div>

                    <!-- Filter Pill Tabs Cepat -->
                    @if($tesAktif->isNotEmpty())
                        <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl text-[11px] font-semibold" id="testFilterTabs">
                            <button type="button" onclick="filterTests('all', this)" class="flex-1 py-1 rounded-lg transition-all cursor-pointer bg-white text-sky-700 shadow-xs text-center">
                                Semua ({{ $tesAktif->count() }})
                            </button>
                            <button type="button" onclick="filterTests('pending', this)" class="flex-1 py-1 rounded-lg transition-all cursor-pointer text-slate-600 hover:text-slate-900 text-center">
                                Perlu ({{ $stats->total_tes_aktif }})
                            </button>
                            <button type="button" onclick="filterTests('completed', this)" class="flex-1 py-1 rounded-lg transition-all cursor-pointer text-slate-600 hover:text-slate-900 text-center">
                                Selesai ({{ $tesAktif->count() - $stats->total_tes_aktif }})
                            </button>
                        </div>
                    @endif
                </div>

                <!-- 2. CARD TUGAS & ASSESMENT AKTIF -->
                <div class="bento-card-clean space-y-3.5">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <h2 class="text-xs sm:text-sm font-bold text-slate-900 tracking-tight flex items-center gap-2">
                            <span class="text-base leading-none">📋</span>
                            Tugas &amp; Assesment Aktif
                        </h2>
                        <span class="text-[11px] text-slate-400 font-medium">{{ $tesAktif->count() }} Paket</span>
                    </div>

                    @if($tesAktif->isEmpty())
                        <div class="py-8 text-center text-xs text-slate-400 bg-slate-50 rounded-2xl border border-slate-100">
                            <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center mx-auto mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="font-bold text-slate-800 text-xs">Belum Ada Assesment Aktif</h3>
                            <p class="text-[11px] text-slate-500 mt-0.5">Guru BK belum mengaktifkan paket tes untuk kelas ini.</p>
                        </div>
                    @else
                        <div class="space-y-3" id="testCardsContainer">
                            @foreach($tesAktif as $tes)
                                @php
                                     $sesi = $tes->sesi_siswa;
                                     $sudahSelesai = ($sesi && $sesi->status_pengerjaan === 'selesai');
                                     $sedangMengerjakan = ($sesi && $sesi->status_pengerjaan === 'sedang_mengerjakan');
                                     $filterTag = $sudahSelesai ? 'completed' : 'pending';
                                @endphp
                                <div data-status="{{ $filterTag }}" class="item-tes-box space-y-2.5">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                            {{ $tes->kategori->nama_kategori ?? 'Umum' }}
                                        </span>

                                        @if($sudahSelesai)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                Selesai
                                            </span>
                                        @elseif($sedangMengerjakan)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                Sedang Dikerjakan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-sky-50 text-sky-700 border border-sky-200">
                                                Tersedia
                                            </span>
                                        @endif
                                    </div>

                                    <div>
                                        <h3 class="text-xs sm:text-sm font-bold text-slate-900 leading-snug">
                                            {{ $tes->judul }}
                                        </h3>
                                        <p class="text-[11px] text-slate-400 mt-0.5">
                                            Durasi: <strong>{{ $tes->durasi_menit ?? 30 }} Menit</strong> &bull; {{ $tes->soals_count ?? 0 }} Soal
                                        </p>
                                    </div>

                                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                                        @if($sudahSelesai)
                                            <span class="text-xs font-bold text-emerald-700 font-mono">
                                                Skor: {{ number_format($sesi->total_skor, 1) }}
                                            </span>
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('siswa.tes.show', $tes->id_tes) }}" class="btn-sky-pill">
                                                    <span>Mulai Asesmen</span>
                                                </a>
                                                <a href="{{ route('siswa.tes.hasil', $tes->id_tes) }}" class="btn-outline-pill">
                                                    <span>Lihat Hasil</span>
                                                </a>
                                            </div>
                                        @elseif($sedangMengerjakan)
                                            <span class="text-[11px] text-amber-700 font-medium">Lanjutkan pengerjaan</span>
                                            <a href="{{ route('siswa.tes.kerjakan', $tes->id_tes) }}" class="btn-sky-pill">
                                                <span>Lanjutkan</span>
                                            </a>
                                        @else
                                            <span class="text-[11px] text-slate-400 font-medium">Siap dikerjakan</span>
                                            <a href="{{ route('siswa.tes.show', $tes->id_tes) }}" class="btn-sky-pill">
                                                <span>Mulai Asesmen</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- 3. CARD RIWAYAT & AKTIVITAS TERBARU (Pill Row Rapi Mockup) -->
                <div class="bento-card-clean space-y-3">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <h3 class="text-xs font-bold text-slate-800 tracking-tight flex items-center gap-2">
                            <span class="text-base leading-none">⏱️</span>
                            Riwayat &amp; Aktivitas Terbaru
                        </h3>
                        <span class="text-[10px] text-slate-400 font-medium">{{ $riwayatTes->count() }} Tuntas</span>
                    </div>

                    @if($riwayatTes->isEmpty())
                        <div class="py-6 text-center text-xs text-slate-400 bg-slate-50 rounded-xl">
                            Belum ada riwayat pengerjaan asesmen yang tuntas.
                        </div>
                    @else
                        <div class="space-y-2.5">
                            @foreach($riwayatTes as $index => $riwayat)
                                @php
                                    $isEven = ($index % 2 === 0);
                                @endphp
                                <div class="item-riwayat-pill {{ $isEven ? 'bg-blue-tint' : 'bg-green-tint' }}">
                                    <!-- Icon Bullet Checkmark Sesuai Mockup -->
                                    <div class="w-6 h-6 rounded-full {{ $isEven ? 'bg-sky-600' : 'bg-emerald-600' }} text-white flex items-center justify-center shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center justify-between gap-1">
                                            <p class="font-bold text-slate-800 text-xs">
                                                Mengerjakan {{ $riwayat->tes->judul ?? 'Asesmen' }}
                                            </p>
                                            <span class="font-bold font-mono text-xs {{ $isEven ? 'text-sky-700' : 'text-emerald-700' }} shrink-0">
                                                ({{ number_format($riwayat->total_skor, 1) }})
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between text-[10px] text-slate-400 mt-0.5">
                                            <span>{{ $riwayat->waktu_submit ? \Carbon\Carbon::parse($riwayat->waktu_submit)->format('d M Y, H:i') : '-' }} WIB</span>
                                            <a href="{{ route('siswa.tes.hasil', $riwayat->id_tes) }}" class="font-bold text-sky-600 hover:text-sky-800">
                                                Rincian &rarr;
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

            {{-- ============================================================= --}}
            {{-- KOLOM 3: KANAN (lg:col-span-4)                                --}}
            {{-- 1. Materi Bimbingan Konseling (Grid Modul Sesuai Mockup)       --}}
            {{-- 2. Agenda / Pesan BK (Ilustrasi Kalender & Memo Mockup)       --}}
            {{-- ============================================================= --}}
            <div class="lg:col-span-4 space-y-5">

                <!-- 1. CARD MATERI BIMBINGAN KONSELING -->
                <div class="bento-card-clean space-y-3.5">
                    <div class="flex items-start justify-between gap-2 pb-2 border-b border-slate-100">
                        <div>
                            <h2 class="text-xs sm:text-sm font-bold text-slate-900 tracking-tight flex items-center gap-1.5">
                                <span class="text-base leading-none">📖</span>
                                Materi Bimbingan Konseling
                            </h2>
                            <p class="text-[11px] text-slate-400">Modul Bimbingan Konseling</p>
                        </div>
                        <a href="{{ route('siswa.materi.index') }}" class="text-[10px] font-bold text-sky-700 hover:text-sky-900 bg-sky-50 hover:bg-sky-100 border border-sky-100 px-2.5 py-1 rounded-xl shrink-0 transition-colors">
                            Lihat Semua Materi
                        </a>
                    </div>

                    @if($materiList->isEmpty())
                        <!-- Empty State Menarik & Proporsional Sesuai Mockup Card -->
                        <div class="p-4 rounded-2xl bg-amber-50/40 border border-amber-100 text-center space-y-2">
                            <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center mx-auto shadow-2xs">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <h4 class="font-bold text-slate-800 text-xs">Belum Ada Materi Terbit</h4>
                            <p class="text-[11px] text-slate-500 leading-relaxed max-w-xs mx-auto">
                                Modul bimbingan pribadi, sosial, belajar, dan karir akan otomatis muncul saat dibagikan oleh Guru BK.
                            </p>
                        </div>
                    @else
                        <!-- List Modul Bergaya Card Dua Kolom Hangat Persis Mockup -->
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($materiList->take(4) as $index => $materi)
                                @php
                                    $ext = strtolower(pathinfo($materi->file_path ?? '', PATHINFO_EXTENSION));
                                    $isPeach = ($index % 2 === 0);
                                    $coverBg = $isPeach ? 'background-color: #ffedd5; border-color: #fed7aa;' : 'background-color: #dcfce7; border-color: #bbf7d0;';
                                    $coverTextColor = $isPeach ? '#c2410c' : '#15803d';
                                @endphp
                                <div class="materi-preview-card p-2.5 space-y-2">
                                    <!-- Banner Ilustrasi Membaca Buku Bergaya Mockup -->
                                    <div style="{{ $coverBg }}" class="w-full h-24 rounded-xl border flex flex-col items-center justify-center p-2 relative overflow-hidden">
                                        <svg viewBox="0 0 64 64" style="color: {{ $coverTextColor }};" class="h-10 w-10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="32" cy="18" r="7" fill="currentColor" fill-opacity="0.85"/>
                                            <path d="M18 52C18 40 24 32 32 32C40 32 46 40 46 52" fill="currentColor" fill-opacity="0.75"/>
                                            <!-- Buku Terbuka -->
                                            <path d="M22 38L32 44L42 38V48L32 54L22 48V38Z" fill="#ffffff" stroke="currentColor" stroke-width="2"/>
                                        </svg>
                                        <span style="color: {{ $coverTextColor }};" class="text-[9px] font-black uppercase mt-1 px-1.5 py-0.5 rounded bg-white/90 shadow-2xs">
                                            {{ $ext ?: 'DOC' }}
                                        </span>
                                    </div>

                                    <div>
                                        <h4 class="text-xs font-bold text-slate-800 line-clamp-1 leading-tight" title="{{ $materi->judul_materi }}">
                                            {{ $materi->judul_materi }}
                                        </h4>
                                        <p class="text-[10px] text-slate-400 truncate mt-0.5">
                                            {{ $materi->kategori->nama_kategori ?? 'Umum' }}
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-1.5 pt-1 border-t border-slate-100">
                                        <a href="{{ route('siswa.materi.buka', $materi->id_materi) }}" target="_blank" class="flex-1 text-center py-1 rounded-lg text-[10px] font-bold bg-sky-50 hover:bg-sky-100 text-sky-700 transition-colors">
                                            Buka Modul
                                        </a>
                                        <a href="{{ route('siswa.materi.download', $materi->id_materi) }}" class="p-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 transition-colors" title="Unduh File">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

        </div>
    @endif

</div>

{{-- Script Tab & Pencarian Cepat --}}
<script>
function filterTests(status, btn) {
    const tabs = document.querySelectorAll('#testFilterTabs button');
    tabs.forEach(t => {
        t.classList.remove('bg-white', 'text-sky-700', 'shadow-xs');
        t.classList.add('text-slate-600');
    });
    btn.classList.add('bg-white', 'text-sky-700', 'shadow-xs');
    btn.classList.remove('text-slate-600');

    const cards = document.querySelectorAll('#testCardsContainer .item-tes-box');
    cards.forEach(c => {
        if (status === 'all') {
            c.style.display = 'block';
        } else {
            c.style.display = (c.getAttribute('data-status') === status) ? 'block' : 'none';
        }
    });
}

function filterTestsByKeyword(keyword) {
    const term = keyword.toLowerCase().trim();
    const cards = document.querySelectorAll('#testCardsContainer .item-tes-box');
    cards.forEach(c => {
        const text = c.innerText.toLowerCase();
        c.style.display = text.includes(term) ? 'block' : 'none';
    });
}
</script>
@endsection
