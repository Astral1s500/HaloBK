@extends('layouts.app')

@section('title', 'Dashboard Administrator')

@push('styles')
<link rel="stylesheet" href="{{ asset('vendor/driverjs/driver.css') }}">
<style>
    /* Styling Solid Anti-Gagal (Bypass Keterbatasan Kompilasi CSS) */
    .hero-sky-gradient {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 60%, #075985 100%);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .stat-card {
        background: #ffffff;
        border: 1px solid #e0f2fe;
        border-radius: 20px;
        box-shadow: 0 4px 15px -2px rgba(2, 132, 199, 0.06);
        transition: all 0.25s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px -4px rgba(2, 132, 199, 0.12);
        border-color: #bae6fd;
    }

    .account-card {
        background: #ffffff;
        border: 1px solid #e0f2fe;
        border-radius: 24px;
        box-shadow: 0 4px 18px -3px rgba(2, 132, 199, 0.07);
        transition: all 0.25s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .account-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px -5px rgba(2, 132, 199, 0.14);
        border-color: #7dd3fc;
    }

    /* Avatar Icon Box (Pasti Berwarna & Terlihat) */
    .icon-box-guru {
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
    }
    .icon-box-siswa {
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
    }

    /* Tombol Aksi Solid (Pasti Muncul Warna & Teks Putih Terbaca) */
    .btn-action-primary {
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
        transition: all 0.2s ease;
        border: none;
    }
    .btn-action-primary:hover {
        background-color: #0369a1;
        transform: translateY(-1px);
        box-shadow: 0 5px 14px rgba(2, 132, 199, 0.35);
    }

    .btn-action-teal {
        background-color: #0d9488;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 0.825rem;
        padding: 0.6rem 1.15rem;
        border-radius: 12px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        box-shadow: 0 3px 10px rgba(13, 148, 136, 0.25);
        transition: all 0.2s ease;
        border: none;
    }
    .btn-action-teal:hover {
        background-color: #0f766e;
        transform: translateY(-1px);
        box-shadow: 0 5px 14px rgba(13, 148, 136, 0.35);
    }

    .btn-action-emerald {
        background-color: #ecfdf5;
        color: #047857 !important;
        border: 1px solid #a7f3d0;
        font-weight: 600;
        font-size: 0.825rem;
        padding: 0.55rem 0.95rem;
        border-radius: 12px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        transition: all 0.2s ease;
    }
    .btn-action-emerald:hover {
        background-color: #d1fae5;
        color: #065f46 !important;
        border-color: #6ee7b7;
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

    <!-- 1. Hero / Welcome Card (Sky Cloud & Soft Modern) -->
    <div id="tour-admin-hero" class="w-full text-white rounded-3xl p-6 md:p-8 shadow-lg shadow-sky-900/10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative overflow-hidden hero-sky-gradient">
        
        <!-- Ambient Decorative Cloud Circles -->
        <div style="position: absolute; right: -50px; top: -50px; width: 260px; height: 260px; border-radius: 50%; background: rgba(255, 255, 255, 0.12); pointer-events: none; filter: blur(30px);"></div>
        <div style="position: absolute; left: -40px; bottom: -40px; width: 200px; height: 200px; border-radius: 50%; background: rgba(186, 230, 253, 0.18); pointer-events: none; filter: blur(25px);"></div>

        <div class="space-y-2 min-w-0 relative z-10">
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.35rem 0.9rem; border-radius: 9999px; background: rgba(255, 255, 255, 0.18); backdrop-filter: blur(8px); border: 1px solid rgba(255, 255, 255, 0.25); font-size: 0.75rem; font-weight: 600;">
                <span style="width: 8px; height: 8px; border-radius: 50%; background-color: #7dd3fc; display: inline-block;"></span>
                <span>Kendali Utama Administrator</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight leading-tight">
                Selamat Datang, {{ Auth::guard('admin')->user()->nama_admin }}
            </h1>
            <p class="text-xs md:text-sm text-sky-100 max-w-xl leading-relaxed">
                Kelola akun pengguna, akun Guru BK, dan akun Siswa untuk kelancaran bimbingan konseling digital.
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0 relative z-10">
            <!-- Tombol Tour Fitur Administrator Driver.js -->
            <button 
                type="button" 
                id="btnStartAdminTour" 
                onclick="startAdminTour()"
                style="background: #ffffff; color: #0f172a; padding: 0.65rem 1.15rem; border-radius: 16px; border: none; cursor: pointer; display: flex; align-items: center; gap: 0.65rem; font-size: 0.8rem; font-weight: 700; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); transition: all 0.2s;"
                title="Mulai Tur Panduan Fitur Administrator"
            >
                <div style="width: 28px; height: 28px; border-radius: 10px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                </div>
                <div class="text-left hidden sm:block">
                    <span style="display: block; font-size: 0.75rem; font-weight: 800; color: #0f172a; line-height: 1.1;">Tur Fitur</span>
                    <span style="font-size: 0.65rem; color: #0284c7; font-weight: 600;">Panduan Admin</span>
                </div>
            </button>
        </div>
    </div>

    <!-- 2. Ringkasan Statistik Singkat -->
    <div id="tour-admin-stats" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <!-- Stat 1: Total Guru BK -->
        <div class="stat-card p-5 flex items-center justify-between">
            <div>
                <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Akun Guru</span>
                <span class="text-3xl font-extrabold text-slate-900 mt-1 block">{{ $totalGuru ?? 0 }}</span>
                <span class="text-[11px] text-sky-700 font-medium mt-0.5 block">Pendidik Bimbingan Konseling</span>
            </div>
            <div class="icon-box-guru">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
            </div>
        </div>

        <!-- Stat 2: Total Siswa -->
        <div class="stat-card p-5 flex items-center justify-between">
            <div>
                <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Akun Siswa</span>
                <span class="text-3xl font-extrabold text-slate-900 mt-1 block">{{ $totalSiswa ?? 0 }}</span>
                <span class="text-[11px] text-teal-700 font-medium mt-0.5 block">Peserta Didik Aktif</span>
            </div>
            <div class="icon-box-siswa">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- 3. Kartu Menu Kelola Akun Pengguna -->
    <div>
        <div class="flex items-center justify-between mb-3.5">
            <div>
                <h2 class="text-base font-bold text-slate-900 tracking-tight">
                    Kelola Akun Pengguna
                </h2>
                <p class="text-xs text-slate-500">Pusat registrasi dan pengaturan akun Guru BK serta Siswa</p>
            </div>
            <span class="text-xs text-sky-700 font-semibold bg-sky-50 px-3 py-1 rounded-full border border-sky-100">
                Sistem Terpadu
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            
            <!-- Menu 1: Akun Guru -->
            <div id="tour-master-guru" class="account-card p-6">
                <div>
                    <!-- Icon Avatar Guru yang Pasti Terlihat -->
                    <div class="icon-box-guru mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:26px;height:26px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-base font-bold text-slate-900 tracking-tight">Akun Guru</h3>
                        <span class="badge-soft-sky">Pendidik</span>
                    </div>
                    <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                        Kelola data guru bimbingan konseling, nama lengkap, NIP akun, serta pengaturan kata sandi guru.
                    </p>
                </div>

                <div class="pt-5 mt-5 border-t border-slate-100 flex items-center justify-between">
                    <span class="badge-soft-sky">
                        {{ $totalGuru ?? 0 }} Guru Terdaftar
                    </span>
                    <a 
                        href="{{ route('admin.guru.index') }}" 
                        class="btn-action-primary"
                    >
                        <span>Kelola Akun Guru</span>
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Menu 2: Akun Siswa (Dilengkapi Fitur Excel Langsung) -->
            <div id="tour-master-siswa" class="account-card p-6">
                <div>
                    <!-- Icon Avatar Siswa yang Pasti Terlihat -->
                    <div class="icon-box-siswa mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:26px;height:26px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-base font-bold text-slate-900 tracking-tight">Akun Siswa</h3>
                        <span class="badge-soft-teal">Excel Ready</span>
                    </div>
                    <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                        Kelola data peserta didik, registrasi NISN, serta fasilitas ekspor dan impor massal siswa menggunakan Excel.
                    </p>
                </div>

                <div class="pt-5 mt-5 border-t border-slate-100 flex items-center justify-between gap-2 flex-wrap">
                    <span class="badge-soft-teal">
                        {{ $totalSiswa ?? 0 }} Siswa Terdaftar
                    </span>
                    
                    <div class="flex items-center gap-2">
                        <a 
                            href="{{ route('admin.siswa.exportExcel') }}" 
                            class="btn-action-emerald"
                            title="Unduh seluruh data siswa ke format Excel / CSV"
                        >
                            <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            <span>Export</span>
                        </a>

                        <a 
                            href="{{ route('admin.siswa.index') }}" 
                            class="btn-action-teal"
                        >
                            <span>Kelola Akun Siswa</span>
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
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

    const adminTourDriver = driverFactory({
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
                element: '#tour-admin-hero',
                popover: {
                    title: 'Selamat Datang di HaloBK Admin',
                    description: 'Di panel ini, Anda memiliki kendali penuh atas master akun Guru BK dan Siswa.',
                    side: 'bottom',
                    align: 'start'
                }
            },
            {
                element: '#tour-admin-stats',
                popover: {
                    title: 'Statistik Ringkas',
                    description: 'Pantau total akun Guru BK dan total akun Siswa aktif yang terdaftar.',
                    side: 'bottom',
                    align: 'center'
                }
            },
            {
                element: '#tour-master-guru',
                popover: {
                    title: 'Kelola Akun Guru',
                    description: 'Tambahkan akun guru baru, kelola NIP, serta lakukan reset password guru.',
                    side: 'top',
                    align: 'start'
                }
            },
            {
                element: '#tour-master-siswa',
                popover: {
                    title: 'Kelola Akun Siswa & Excel',
                    description: 'Kelola peserta didik, tambah akun siswa manual, serta ekspor dan impor massal via Excel.',
                    side: 'top',
                    align: 'start'
                }
            }
        ]
    });

    window.startAdminTour = function () {
        adminTourDriver.drive();
    };

    // Tampilkan otomatis sekali jika belum pernah melihat tur
    if (!sessionStorage.getItem('halobk_tour_shown_admin')) {
        setTimeout(function () {
            adminTourDriver.drive();
            sessionStorage.setItem('halobk_tour_shown_admin', 'true');
        }, 600);
    }
});
</script>
@endpush
