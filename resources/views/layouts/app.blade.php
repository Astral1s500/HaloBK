<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - HaloBK</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- Fonts & Compiled Assets via Vite (Offline Ready) -->
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <!-- SweetAlert2 (Tersedia untuk Semua Akun) -->
    <link rel="stylesheet" href="{{ asset('vendor/sweetalert2/sweetalert2.min.css') }}">
    <style>
        .swal2-popup {
            border-radius: 1.25rem !important;
            font-family: inherit !important;
            padding: 1.75rem !important;
            box-shadow: 0 25px 50px -12px rgba(2, 132, 199, 0.15) !important;
        }
        .swal2-title {
            font-size: 1.15rem !important;
            font-weight: 700 !important;
            color: #0f172a !important;
        }
        .swal2-html-container {
            font-size: 0.85rem !important;
            color: #475569 !important;
            line-height: 1.5 !important;
        }
        .swal2-confirm, .swal2-cancel {
            border-radius: 0.75rem !important;
            font-size: 0.8rem !important;
            font-weight: 600 !important;
            padding: 0.6rem 1.25rem !important;
        }

        /* Ambient Body Background Sky Cloud */
        .app-bg-sky {
            background-color: #f8fafc;
            background-image: 
                radial-gradient(at 0% 0%, rgba(224, 242, 254, 0.6) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(240, 249, 255, 0.7) 0px, transparent 40%),
                radial-gradient(at 50% 100%, rgba(224, 242, 254, 0.4) 0px, transparent 50%);
            background-attachment: fixed;
        }

        .navbar-glass {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(224, 242, 254, 0.9);
            box-shadow: 0 4px 20px -2px rgba(2, 132, 199, 0.05);
        }

        /* ==========================================================================
           Sidebar Drawer Solid CSS (Pasti Berfungsi & Tidak Tergantung Tailwind)
           ========================================================================== */
        #main-sidebar {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            bottom: 0 !important;
            width: 300px !important;
            max-width: 85vw !important;
            height: 100vh !important;
            height: 100dvh !important;
            background: #ffffff !important;
            border-right: 1px solid #e0f2fe !important;
            box-shadow: 0 25px 60px -15px rgba(2, 132, 199, 0.3), 0 0 0 1px rgba(224, 242, 254, 0.8) !important;
            z-index: 999999 !important;
            transform: translateX(-105%) !important;
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-between !important;
            pointer-events: none;
            visibility: hidden;
        }

        #main-sidebar.is-open {
            transform: translateX(0%) !important;
            pointer-events: auto !important;
            visibility: visible !important;
        }

        #sidebar-backdrop {
            position: fixed !important;
            inset: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            background: rgba(15, 23, 42, 0.5) !important;
            backdrop-filter: blur(4px) !important;
            -webkit-backdrop-filter: blur(4px) !important;
            z-index: 999990 !important;
            opacity: 0 !important;
            visibility: hidden !important;
            pointer-events: none !important;
            transition: opacity 0.3s ease, visibility 0.3s ease !important;
        }

        #sidebar-backdrop.is-open {
            opacity: 1 !important;
            visibility: visible !important;
            pointer-events: auto !important;
        }

        /* Nav Link Item Sidebar */
        .sidebar-nav-item {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.75rem 1rem;
            border-radius: 16px;
            font-size: 0.825rem;
            font-weight: 600;
            text-decoration: none;
        }

        .sidebar-nav-item.is-active {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 15px rgba(2, 132, 199, 0.28) !important;
        }

        .sidebar-nav-item.is-active .nav-icon-box {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.3);
        }

        .sidebar-nav-item:not(.is-active) {
            color: #334155;
            background: transparent;
            border: 1px solid transparent;
        }

        .sidebar-nav-item:not(.is-active):hover {
            background: #f0f9ff;
            color: #0284c7;
            border-color: #bae6fd;
            transform: translateX(4px);
        }

        .nav-icon-box {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        /* ==========================================================================
           UNIVERSAL BUTTON ANIMATION (Tactile Hover Lift & Spring Press Feedback)
           ========================================================================== */
        button,
        .btn,
        .btn-animated,
        .btn-sky-action,
        .btn-action-primary,
        .btn-action-teal,
        .sidebar-nav-item,
        #btn-sidebar-toggle,
        #btn-sidebar-close,
        .dimensi-card {
            transition: transform 0.22s cubic-bezier(0.34, 1.56, 0.64, 1), 
                        box-shadow 0.22s ease, 
                        background-color 0.2s ease, 
                        border-color 0.2s ease, 
                        color 0.2s ease !important;
            user-select: none;
        }

        /* Hover: Elegan Terangkat & Bayangan Halus */
        button:hover,
        .btn:hover,
        .btn-animated:hover,
        .btn-sky-action:hover,
        .btn-action-primary:hover,
        .btn-action-teal:hover,
        #btn-sidebar-toggle:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px -2px rgba(2, 132, 199, 0.28);
        }

        /* Active / Click: Efek Tekan Membal Nyata (Spring Press) */
        button:active,
        .btn:active,
        .btn-animated:active,
        .btn-sky-action:active,
        .btn-action-primary:active,
        .btn-action-teal:active,
        .sidebar-nav-item:active,
        #btn-sidebar-toggle:active {
            transform: translateY(1.5px) scale(0.96) !important;
            box-shadow: 0 2px 6px rgba(2, 132, 199, 0.2) !important;
        }

        /* ===== NAVBAR HEADER: Stacking Context Eksplisit ===== */
        header.navbar-glass,
        #main-navbar {
            position: sticky !important;
            top: 0 !important;
            z-index: 50000 !important;    /* Jauh lebih tinggi dari konten, lebih rendah dari sidebar */
        }

        /* ===== TOMBOL HAMBURGER: Selalu Bisa Diklik ===== */
        #btn-sidebar-toggle {
            position: relative !important;
            z-index: 50001 !important;
            cursor: pointer !important;
            pointer-events: auto !important;
        }

        /* Tombol Tutup Sidebar Khusus (Pasti Bisa Diklik & Berputar Halus) */
        #btn-sidebar-close {
            position: relative !important;
            z-index: 1000000 !important;
            cursor: pointer !important;
            pointer-events: auto !important;
        }
        #btn-sidebar-close:hover {
            background-color: #fee2e2 !important;
            color: #dc2626 !important;
            border-color: #fca5a5 !important;
            transform: translateY(-2px) rotate(90deg) !important;
            box-shadow: 0 4px 14px rgba(220, 38, 38, 0.25) !important;
        }
        #btn-sidebar-close:active {
            transform: translateY(1px) scale(0.9) rotate(90deg) !important;
        }
    </style>
</head>
<body class="@yield('body_class', 'app-bg-sky') min-h-screen flex flex-col font-sans antialiased text-[#1e293b]">

    @php
        $isAuth = Auth::guard('admin')->check() || Auth::guard('guru')->check() || Auth::guard('siswa')->check();
        $brandRoute = route('login');
        if (Auth::guard('admin')->check()) {
            $brandRoute = route('admin.dashboard');
        } elseif (Auth::guard('guru')->check()) {
            $brandRoute = route('guru.dashboard');
        } elseif (Auth::guard('siswa')->check()) {
            $brandRoute = route('siswa.dashboard');
        }
    @endphp

    <!-- ========================================================================= -->
    <!-- SIDEBAR BACKDROP & DRAWER (BISA DIBUKA & DITUTUP)                         -->
    <!-- Menampung hanya FITUR UTAMA sistem sesuai peran akun login                -->
    <!-- ========================================================================= -->
    @if($isAuth)
        <!-- Backdrop Gelap (Klik untuk Menutup) -->
        <div
            id="sidebar-backdrop"
            onclick="closeSidebar();"
            aria-hidden="true"
        ></div>

        <!-- Panel Sidebar Drawer -->
        <aside
            id="main-sidebar"
            aria-label="Sidebar Fitur Utama"
        >
            <!-- Header Sidebar -->
            <div class="p-5 border-b border-sky-100 flex items-center justify-between bg-gradient-to-r from-sky-50/90 via-sky-50/40 to-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-white shadow-xs border border-sky-100 flex items-center justify-center p-1.5">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo HaloBK" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-slate-900 font-extrabold text-sm tracking-tight leading-tight">HaloBK</span>
                            @if(Auth::guard('admin')->check())
                                <span class="text-[9px] font-extrabold text-sky-700 bg-sky-100 px-1.5 py-0.2 rounded">Admin</span>
                            @elseif(Auth::guard('guru')->check())
                                <span class="text-[9px] font-extrabold text-teal-700 bg-teal-100 px-1.5 py-0.2 rounded">Guru BK</span>
                            @elseif(Auth::guard('siswa')->check())
                                <span class="text-[9px] font-extrabold text-blue-700 bg-blue-100 px-1.5 py-0.2 rounded">Siswa</span>
                            @endif
                        </div>
                        <span class="text-[11px] text-sky-700 font-semibold">Menu Fitur Utama</span>
                    </div>
                </div>

                <!-- Tombol Tutup (X) yang Pasti Bisa Diklik & Beranimasi -->
                <button
                    id="btn-sidebar-close"
                    type="button"
                    onclick="closeSidebar();"
                    class="w-9 h-9 rounded-xl bg-white text-slate-400 border border-slate-200 flex items-center justify-center shadow-2xs"
                    title="Tutup Menu Sidebar (Esc)"
                    aria-label="Tutup Sidebar"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;pointer-events:none;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- List Menu Fitur Utama -->
            <div class="p-4 overflow-y-auto flex-1 space-y-2">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 px-3 block mb-2">
                    Fitur Utama
                </span>

                <!-- 1. JIKA LOGIN SEBAGAI GURU BK -->
                @if(Auth::guard('guru')->check())
                    <!-- Dashboard -->
                    @php $isActive = request()->routeIs('guru.dashboard'); @endphp
                    <a href="{{ route('guru.dashboard') }}" class="sidebar-nav-item {{ $isActive ? 'is-active' : '' }}">
                        <div class="nav-icon-box {{ $isActive ? '' : 'bg-sky-50 text-sky-600 border-sky-100' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                        <div>
                            <span class="block leading-tight">Dashboard</span>
                            <span class="text-[10px] {{ $isActive ? 'text-sky-100' : 'text-slate-400' }} font-normal">Ringkasan aktivitas BK</span>
                        </div>
                    </a>

                    <!-- Kelas Binaan -->
                    @php $isActive = request()->routeIs('guru.kelas.*'); @endphp
                    <a href="{{ route('guru.kelas.index') }}" class="sidebar-nav-item {{ $isActive ? 'is-active' : '' }}">
                        <div class="nav-icon-box {{ $isActive ? '' : 'bg-teal-50 text-teal-600 border-teal-100' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <span class="block leading-tight">Kelas Binaan</span>
                            <span class="text-[10px] {{ $isActive ? 'text-sky-100' : 'text-slate-400' }} font-normal">Kelola kelas &amp; murid</span>
                        </div>
                    </a>

                    <!-- Paket Asesmen & Soal -->
                    @php $isActive = request()->routeIs('guru.tes.*') || request()->routeIs('guru.soal.*'); @endphp
                    <a href="{{ route('guru.tes.index') }}" class="sidebar-nav-item {{ $isActive ? 'is-active' : '' }}">
                        <div class="nav-icon-box {{ $isActive ? '' : 'bg-indigo-50 text-indigo-600 border-indigo-100' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                        </div>
                        <div>
                            <span class="block leading-tight">Paket Asesmen</span>
                            <span class="text-[10px] {{ $isActive ? 'text-sky-100' : 'text-slate-400' }} font-normal">Bank soal &amp; tes psikotes</span>
                        </div>
                    </a>

                    <!-- Hasil & Rekap Asesmen -->
                    @php $isActive = request()->routeIs('guru.koreksi.*'); @endphp
                    <a href="{{ route('guru.koreksi.index') }}" class="sidebar-nav-item {{ $isActive ? 'is-active' : '' }}">
                        <div class="nav-icon-box {{ $isActive ? '' : 'bg-purple-50 text-purple-600 border-purple-100' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div>
                            <span class="block leading-tight">Hasil &amp; Rekap Asesmen</span>
                            <span class="text-[10px] {{ $isActive ? 'text-sky-100' : 'text-slate-400' }} font-normal">Pemetaan &amp; lembar konseling</span>
                        </div>
                    </a>

                    <!-- Materi Bimbingan -->
                    @php $isActive = request()->routeIs('guru.materi.*'); @endphp
                    <a href="{{ route('guru.materi.index') }}" class="sidebar-nav-item {{ $isActive ? 'is-active' : '' }}">
                        <div class="nav-icon-box {{ $isActive ? '' : 'bg-amber-50 text-amber-600 border-amber-100' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div>
                            <span class="block leading-tight">Materi Bimbingan</span>
                            <span class="text-[10px] {{ $isActive ? 'text-sky-100' : 'text-slate-400' }} font-normal">Modul &amp; materi edukasi</span>
                        </div>
                    </a>

                <!-- 2. JIKA LOGIN SEBAGAI ADMIN -->
                @elseif(Auth::guard('admin')->check())
                    <!-- Dashboard Admin -->
                    @php $isActive = request()->routeIs('admin.dashboard'); @endphp
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-item {{ $isActive ? 'is-active' : '' }}">
                        <div class="nav-icon-box {{ $isActive ? '' : 'bg-sky-50 text-sky-600 border-sky-100' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                        <div>
                            <span class="block leading-tight">Dashboard Admin</span>
                            <span class="text-[10px] {{ $isActive ? 'text-sky-100' : 'text-slate-400' }} font-normal">Ringkasan sistem master</span>
                        </div>
                    </a>

                    <!-- Kelola Guru BK -->
                    @php $isActive = request()->routeIs('admin.guru.*'); @endphp
                    <a href="{{ route('admin.guru.index') }}" class="sidebar-nav-item {{ $isActive ? 'is-active' : '' }}">
                        <div class="nav-icon-box {{ $isActive ? '' : 'bg-teal-50 text-teal-600 border-teal-100' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div>
                            <span class="block leading-tight">Data Guru BK</span>
                            <span class="text-[10px] {{ $isActive ? 'text-sky-100' : 'text-slate-400' }} font-normal">Manajemen akun guru</span>
                        </div>
                    </a>

                    <!-- Kelola Data Siswa -->
                    @php $isActive = request()->routeIs('admin.siswa.*'); @endphp
                    <a href="{{ route('admin.siswa.index') }}" class="sidebar-nav-item {{ $isActive ? 'is-active' : '' }}">
                        <div class="nav-icon-box {{ $isActive ? '' : 'bg-indigo-50 text-indigo-600 border-indigo-100' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <span class="block leading-tight">Data Siswa</span>
                            <span class="text-[10px] {{ $isActive ? 'text-sky-100' : 'text-slate-400' }} font-normal">Import, export &amp; akun siswa</span>
                        </div>
                    </a>

                <!-- 3. JIKA LOGIN SEBAGAI SISWA -->
                @elseif(Auth::guard('siswa')->check())
                    <!-- Dashboard Siswa -->
                    @php $isActive = request()->routeIs('siswa.dashboard'); @endphp
                    <a href="{{ route('siswa.dashboard') }}" class="sidebar-nav-item {{ $isActive ? 'is-active' : '' }}">
                        <div class="nav-icon-box {{ $isActive ? '' : 'bg-sky-50 text-sky-600 border-sky-100' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                        <div>
                            <span class="block leading-tight">Dashboard Siswa</span>
                            <span class="text-[10px] {{ $isActive ? 'text-sky-100' : 'text-slate-400' }} font-normal">Kelas &amp; asesmen aktif</span>
                        </div>
                    </a>
                @endif
            </div>

            <!-- Footer Sidebar: Profil Akun & Logout Cepat -->
            <div class="p-4 border-t border-sky-100 bg-slate-50/80">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2.5 min-w-0">
                        @php
                            $userInitial = 'U';
                            $userName = 'Pengguna';
                            $userRole = 'User';
                            if (Auth::guard('admin')->check()) {
                                $userName = Auth::guard('admin')->user()->nama_admin;
                                $userRole = 'Admin';
                            } elseif (Auth::guard('guru')->check()) {
                                $userName = Auth::guard('guru')->user()->nama_guru;
                                $userRole = 'Guru BK';
                            } elseif (Auth::guard('siswa')->check()) {
                                $userName = Auth::guard('siswa')->user()->nama_siswa;
                                $userRole = 'Siswa';
                            }
                            $userInitial = strtoupper(substr($userName, 0, 1));
                        @endphp
                        <div class="w-8 h-8 rounded-xl bg-sky-100 text-sky-700 font-bold text-xs flex items-center justify-center shrink-0 border border-sky-200">
                            {{ $userInitial }}
                        </div>
                        <div class="truncate">
                            <span class="block text-xs font-bold text-slate-800 truncate" title="{{ $userName }}">{{ $userName }}</span>
                            <span class="block text-[10px] text-slate-400 font-medium">{{ $userRole }}</span>
                        </div>
                    </div>

                    <!-- Tombol Keluar Cepat -->
                    <form action="{{ route('logout') }}" method="POST" class="m-0 shrink-0">
                        @csrf
                        <button 
                            type="submit" 
                            class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer"
                            title="Keluar dari akun"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>
    @endif

    <!-- ========================================================================= -->
    <!-- TOPBAR NAVBAR (DENGAN TOMBOL BUKA/TUTUP SIDEBAR)                          -->
    <!-- ========================================================================= -->
    <header id="main-navbar" class="w-full navbar-glass py-3 px-4 sm:px-6 md:px-12 flex justify-between items-center transition-all">
        
        <!-- Brand Logo & Tombol Buka/Tutup Sidebar -->
        <div class="flex items-center gap-2 sm:gap-3">
            @if($isAuth)
                <!-- Tombol Buka/Tutup Sidebar Fitur Utama -->
                <button
                    id="btn-sidebar-toggle"
                    type="button"
                    onclick="toggleSidebar();"
                    class="p-2 sm:px-3 sm:py-2 rounded-xl bg-white hover:bg-sky-50 text-slate-700 hover:text-sky-700 border border-slate-200 hover:border-sky-300 transition-all shadow-2xs cursor-pointer flex items-center gap-1.5"
                    title="Buka / Tutup Sidebar Fitur Utama"
                    aria-label="Buka Menu Fitur"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:19px;height:19px;color:#0284c7;pointer-events:none;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                    <span class="text-xs font-bold text-slate-800 hidden sm:inline pointer-events-none">Menu</span>
                </button>
            @endif

            <!-- Logo HaloBK -->
            <a href="{{ $brandRoute }}" class="flex items-center gap-2.5 hover:opacity-90 transition-opacity">
                <div class="w-8 h-8 rounded-xl bg-white shadow-xs border border-sky-100 flex items-center justify-center p-1">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo HaloBK" class="w-full h-full object-contain">
                </div>
                <div class="flex flex-col">
                    <span class="text-slate-900 font-bold text-sm tracking-tight leading-tight">HaloBK</span>
                    <span class="text-[10px] text-sky-700 font-semibold tracking-wide">Bimbingan &amp; Konseling</span>
                </div>
            </a>
            <span class="hidden md:inline-block text-slate-300">|</span>
            <span class="hidden md:inline-block text-xs text-slate-500 font-medium">Layanan Terpadu Sekolah</span>
        </div>

        <!-- Profil Pengguna & Tombol Logout -->
        <div class="flex items-center gap-3 sm:gap-4">
            <div class="text-right">
                @if(Auth::guard('admin')->check())
                    <span class="block text-xs font-bold text-slate-800 leading-tight">
                        {{ Auth::guard('admin')->user()->nama_admin }}
                    </span>
                    <span class="inline-block text-[10px] text-sky-700 font-semibold bg-sky-50 px-2 py-0.5 rounded-full border border-sky-200 mt-0.5">
                        Administrator
                    </span>
                @elseif(Auth::guard('guru')->check())
                    <span class="block text-xs font-bold text-slate-800 leading-tight">
                        {{ Auth::guard('guru')->user()->nama_guru }}
                    </span>
                    <span class="inline-block text-[10px] text-teal-700 font-semibold bg-teal-50 px-2 py-0.5 rounded-full border border-teal-200 mt-0.5">
                        Guru BK
                    </span>
                @elseif(Auth::guard('siswa')->check())
                    <span class="block text-xs font-bold text-slate-800 leading-tight">
                        {{ Auth::guard('siswa')->user()->nama_siswa }}
                    </span>
                    <span class="inline-block text-[10px] text-blue-700 font-semibold bg-blue-50 px-2 py-0.5 rounded-full border border-blue-200 mt-0.5">
                        Siswa
                    </span>
                @endif
            </div>

            <!-- Form Logout -->
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button 
                    type="submit" 
                    class="bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 px-3 py-1.5 rounded-xl text-xs font-semibold border border-slate-200 hover:border-rose-200 transition-all duration-200 cursor-pointer flex items-center gap-1.5 shadow-xs"
                    title="Keluar dari sistem"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span class="hidden sm:inline">Keluar</span>
                </button>
            </form>
        </div>
    </header>

    <!-- Kontainer Utama -->
    <main class="flex-1 w-full max-w-7xl mx-auto p-4 md:p-8">
        
        @if(!request()->routeIs('guru.*') && !Auth::guard('guru')->check())
            <!-- Alert Sukses Flash Message -->
            @if(session('success') && !session('login_success'))
                <div class="mb-6 bg-emerald-50/90 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl text-xs font-semibold flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Alert Error Flash Message -->
            @if(session('error'))
                <div class="mb-6 bg-rose-50/90 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl text-xs font-semibold flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Alert Info Flash Message -->
            @if(session('info'))
                <div class="mb-6 bg-sky-50/90 border border-sky-200 text-sky-800 px-4 py-3 rounded-2xl text-xs font-semibold flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-sky-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('info') }}</span>
                    </div>
                </div>
            @endif
        @endif

        @yield('content')
    </main>

    <!-- Footer Bersih -->
    <footer class="w-full bg-white/70 backdrop-blur-md border-t border-sky-100 py-4 text-center mt-auto">
        <p class="text-xs text-slate-500 font-medium">
            &copy; {{ date('Y') }} HaloBK &bull; Sistem Layanan Bimbingan &amp; Konseling Digital
        </p>
    </footer>

    <!-- SweetAlert2 Scripts (Tersedia untuk Semua Akun) -->
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.min.js') }}"></script>
    @if(request()->routeIs('guru.*') || Auth::guard('guru')->check())
        @include('partials.guru-sweetalert')
    @endif

    <!-- Script Kontrol Buka / Tutup Sidebar yang Pasti Berjalan -->
    <script>
        // Definisikan di window scope agar onclick inline pasti bisa akses
        window.openSidebar = function() {
            const sidebar = document.getElementById('main-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            if (sidebar) sidebar.classList.add('is-open');
            if (backdrop) backdrop.classList.add('is-open');
            document.body.style.overflow = 'hidden';
        };

        window.closeSidebar = function() {
            const sidebar = document.getElementById('main-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            if (sidebar) sidebar.classList.remove('is-open');
            if (backdrop) backdrop.classList.remove('is-open');
            document.body.style.overflow = '';
        };

        window.toggleSidebar = function() {
            const sidebar = document.getElementById('main-sidebar');
            if (sidebar && sidebar.classList.contains('is-open')) {
                window.closeSidebar();
            } else {
                window.openSidebar();
            }
        };

        // Pastikan listener terpasang dengan kuat saat DOM siap
        document.addEventListener('DOMContentLoaded', function () {
            const btnToggle = document.getElementById('btn-sidebar-toggle');
            const btnClose  = document.getElementById('btn-sidebar-close');
            const backdrop  = document.getElementById('sidebar-backdrop');

            if (btnToggle) {
                // Hapus event lama & pasang ulang agar tidak double-fire
                btnToggle.replaceWith(btnToggle.cloneNode(true));
                const freshToggle = document.getElementById('btn-sidebar-toggle');
                freshToggle.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    window.toggleSidebar();
                });
            }

            if (btnClose) {
                btnClose.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    window.closeSidebar();
                });
            }

            if (backdrop) {
                backdrop.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    window.closeSidebar();
                });
            }
        });

        // Tutup sidebar jika pengguna menekan tombol ESC
        window.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                window.closeSidebar();
            }
        });

        // Handler Navigasi Browser: Refresh jika halaman dibuka dari bfcache browser
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
