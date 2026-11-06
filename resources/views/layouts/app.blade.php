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
    </style>
</head>
<body class="@yield('body_class', 'app-bg-sky') min-h-screen flex flex-col font-sans antialiased text-[#1e293b]">

    <!-- Topbar Header (Sky Cloud Modern & Soft) -->
    <header class="w-full navbar-glass py-3 px-6 md:px-12 flex justify-between items-center sticky top-0 z-30 transition-all">
        
        <!-- Brand Logo & Nama -->
        <div class="flex items-center gap-3">
            @php
                $brandRoute = route('login');
                if (Auth::guard('admin')->check()) {
                    $brandRoute = route('admin.dashboard');
                } elseif (Auth::guard('guru')->check()) {
                    $brandRoute = route('guru.dashboard');
                } elseif (Auth::guard('siswa')->check()) {
                    $brandRoute = route('siswa.dashboard');
                }
            @endphp
            <a href="{{ $brandRoute }}" class="flex items-center gap-2.5 hover:opacity-90 transition-opacity">
                <div class="w-8 h-8 rounded-xl bg-white shadow-xs border border-sky-100 flex items-center justify-center p-1">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo HaloBK" class="w-full h-full object-contain">
                </div>
                <div class="flex flex-col">
                    <span class="text-slate-900 font-bold text-sm tracking-tight leading-tight">HaloBK</span>
                    <span class="text-[10px] text-sky-700 font-semibold tracking-wide">Bimbingan & Konseling</span>
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
            &copy; {{ date('Y') }} HaloBK &bull; Sistem Layanan Bimbingan & Konseling Digital
        </p>
    </footer>

    <!-- SweetAlert2 Scripts (Tersedia untuk Semua Akun) -->
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.min.js') }}"></script>
    @if(request()->routeIs('guru.*') || Auth::guard('guru')->check())
        @include('partials.guru-sweetalert')
    @endif

    <!-- Handler Navigasi Browser: Refresh jika halaman dibuka dari bfcache browser -->
    <script>
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
