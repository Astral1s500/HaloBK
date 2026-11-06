@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto pb-8">

    <!-- ========================================================================= -->
    <!-- 1. HERO ELEMENT: STUDENT IDENTITY CARD (ORIGINAL DARK NAVY/NEAR-BLACK)    -->
    <!-- Kept in original color as the strong, elevated visual anchor              -->
    <!-- ========================================================================= -->
    <div id="tour-siswa-hero" class="w-full bg-slate-900 text-white rounded-2xl p-6 md:p-7 shadow-md border border-slate-800 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div class="space-y-1.5 min-w-0">
            <div class="flex items-center gap-2 text-xs flex-wrap">
                <span class="px-2.5 py-0.5 rounded-md bg-slate-800 text-slate-300 text-[11px] font-semibold border border-slate-700/50">
                    Siswa
                </span>
                <span class="text-xs text-slate-400 font-mono">NISN: {{ $siswa->nisn }}</span>
                @if($kelas)
                    <span class="text-slate-600">&bull;</span>
                    <span class="text-xs text-slate-300 font-semibold">{{ $kelas->nama_kelas }}</span>
                @endif
            </div>
            <h1 class="text-2xl md:text-3xl font-bold text-white tracking-tight">
                Selamat Datang, {{ $siswa->nama_siswa }}
            </h1>
            <p class="text-xs text-slate-400">
                @if($kelas)
                    Guru BK: <strong class="text-slate-200 font-medium">{{ $kelas->guruBk->nama_guru ?? 'Guru BK' }}</strong>
                @else
                    Silakan bergabung ke kelas untuk membuka paket tes evaluasi dan modul bimbingan konseling.
                @endif
            </p>
        </div>

        <!-- 3 Stat Badges & Tombol Tur Fitur Siswa -->
        <div class="flex items-center gap-3 w-full md:w-auto overflow-x-auto pb-1 md:pb-0 shrink-0">
            <div id="tour-siswa-stats" class="flex items-center gap-3">
                <div class="bg-slate-800/90 border border-slate-700/60 px-4 py-2.5 rounded-xl flex items-center gap-3 shrink-0 shadow-2xs">
                    <div class="w-8 h-8 rounded-lg bg-slate-700/60 flex items-center justify-center text-slate-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <div>
                        <span class="block text-base font-extrabold text-white leading-tight">{{ $stats->total_tes_aktif }}</span>
                        <span class="text-[10px] text-slate-400 font-medium">Perlu Tes</span>
                    </div>
                </div>

                <div class="bg-slate-800/90 border border-slate-700/60 px-4 py-2.5 rounded-xl flex items-center gap-3 shrink-0 shadow-2xs">
                    <div class="w-8 h-8 rounded-lg bg-slate-700/60 flex items-center justify-center text-slate-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <span class="block text-base font-extrabold text-white leading-tight">{{ $stats->total_tes_selesai }}</span>
                        <span class="text-[10px] text-slate-400 font-medium">Tes Selesai</span>
                    </div>
                </div>

                <div class="bg-slate-800/90 border border-slate-700/60 px-4 py-2.5 rounded-xl flex items-center gap-3 shrink-0 shadow-2xs">
                    <div class="w-8 h-8 rounded-lg bg-slate-700/60 flex items-center justify-center text-slate-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div>
                        <span class="block text-base font-extrabold text-white leading-tight">{{ $stats->total_materi }}</span>
                        <span class="text-[10px] text-slate-400 font-medium">Materi BK</span>
                    </div>
                </div>
            </div>

            <!-- Tombol Tour Panduan Fitur Siswa Driver.js -->
            <button 
                type="button" 
                id="btnStartSiswaTour" 
                onclick="startSiswaTour()"
                class="bg-slate-800/90 hover:bg-slate-700 text-slate-200 hover:text-white border border-slate-700/80 px-3.5 py-2.5 rounded-xl flex items-center gap-2.5 shrink-0 transition-all text-xs font-semibold cursor-pointer group shadow-2xs"
                title="Mulai Tur Panduan Fitur Siswa"
            >
                <div class="w-8 h-8 rounded-lg bg-blue-500/20 text-blue-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                </div>
                <div class="text-left hidden sm:block pr-1">
                    <span class="block text-xs font-bold text-white leading-tight">Tur Fitur</span>
                    <span class="text-[10px] text-blue-400 font-medium">Panduan Siswa</span>
                </div>
            </button>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 2. BENTO GRID LAYOUT: CALM PALETTE WITH EMERALD GREEN ACCENT              -->
    <!-- ========================================================================= -->
    <div class="w-full grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- ===================================================================== -->
        <!-- KOLOM UTAMA WIDE (lg:col-span-8 space-y-6)                            -->
        <!-- ===================================================================== -->
        <main class="w-full lg:col-span-8 space-y-6">

            <!-- BENTO CARD 1: PAKET ASESMEN & TUGAS TES (WIDE CARD) -->
            <div id="tour-paket-tes" class="bg-white rounded-2xl border border-[#E5E7EB] shadow-xs p-5 sm:p-6 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-[#F0F1F4]">
                    <div>
                        <h2 class="text-sm sm:text-base font-bold text-[#2B2D33] tracking-tight flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#059669] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            Paket Asesmen &amp; Tugas Tes
                        </h2>
                        <p class="text-xs text-[#6B7280] mt-0.5">Daftar tes peminatan, psikotes, dan tugas evaluasi bimbingan konseling.</p>
                    </div>

                    <!-- Filter Sub-Tab Status Tes (Emerald Green Accent) -->
                    @if($kelas && $tesAktif->isNotEmpty())
                        <div class="flex items-center gap-1 bg-[#F4F5F7] p-1 rounded-xl border border-[#E5E7EB] self-start sm:self-auto" id="testFilterTabs">
                            <button 
                                type="button" 
                                onclick="filterTests('all', this)"
                                class="px-3 py-1 rounded-lg transition-all cursor-pointer bg-[#059669] text-white font-semibold text-xs shadow-xs"
                            >
                                Semua ({{ $tesAktif->count() }})
                            </button>
                            <button 
                                type="button" 
                                onclick="filterTests('pending', this)"
                                class="px-3 py-1 rounded-lg transition-all cursor-pointer text-[#6B7280] hover:text-[#2B2D33] font-medium text-xs"
                            >
                                Belum Selesai ({{ $stats->total_tes_aktif }})
                            </button>
                            <button 
                                type="button" 
                                onclick="filterTests('completed', this)"
                                class="px-3 py-1 rounded-lg transition-all cursor-pointer text-[#6B7280] hover:text-[#2B2D33] font-medium text-xs"
                            >
                                Selesai ({{ $tesAktif->count() - $stats->total_tes_aktif }})
                            </button>
                        </div>
                    @endif
                </div>

                @if(!$kelas)
                    <div class="w-full bg-[#F7F8FA] rounded-xl border border-dashed border-[#D1D5DB] p-8 text-center space-y-2">
                        <div class="w-10 h-10 rounded-xl bg-[#ECEEF2] text-[#8B93A7] mx-auto flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <h3 class="text-xs sm:text-sm font-bold text-[#2B2D33]">Anda Belum Tergabung di Kelas</h3>
                        <p class="text-xs text-[#6B7280] max-w-sm mx-auto">
                            Gunakan panel di sebelah kanan untuk memasukkan kode unik kelas yang diberikan Guru BK agar paket tes terbuka.
                        </p>
                    </div>
                @else
                    @if($tesAktif->isEmpty())
                        <div class="w-full bg-[#F7F8FA] rounded-xl border border-[#E5E7EB] p-8 text-center space-y-2">
                            <div class="w-10 h-10 rounded-xl bg-[#ECEEF2] text-[#8B93A7] mx-auto flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-xs sm:text-sm font-bold text-[#2B2D33]">Belum Ada Tes Aktif</h3>
                            <p class="text-xs text-[#6B7280] max-w-sm mx-auto">
                                Guru BK belum mengaktifkan paket tes untuk kelas {{ $kelas->nama_kelas }}.
                            </p>
                        </div>
                    @else
                        <div class="w-full grid grid-cols-1 sm:grid-cols-2 gap-3.5" id="testCardsContainer">
                            @foreach($tesAktif as $tes)
                                @php
                                    $sesi = $tes->sesi_siswa;
                                    $sudahSelesai = ($sesi && $sesi->status_pengerjaan === 'selesai');
                                    $sedangMengerjakan = ($sesi && $sesi->status_pengerjaan === 'sedang_mengerjakan');
                                    $filterTag = $sudahSelesai ? 'completed' : 'pending';

                                    // Status colors desaturated / muted
                                    if ($sudahSelesai) {
                                        $badgeClass = 'bg-[#F0F7F4] text-[#3F6A54] border-[#C8E3D7]';
                                        $targetUrl = route('siswa.tes.hasil', $tes->id_tes);
                                    } elseif ($sedangMengerjakan) {
                                        $badgeClass = 'bg-[#F4F6FA] text-[#4A5568] border-[#DCE2ED]';
                                        $targetUrl = route('siswa.tes.show', $tes->id_tes);
                                    } else {
                                        $badgeClass = 'bg-[#FDF9F2] text-[#8F5F17] border-[#F5DEC0]';
                                        $targetUrl = route('siswa.tes.show', $tes->id_tes);
                                    }
                                @endphp

                                <div 
                                    data-status="{{ $filterTag }}"
                                    onclick="window.location.href='{{ $targetUrl }}'"
                                    class="test-card w-full p-4 rounded-xl border border-[#E5E7EB] bg-white shadow-2xs hover:border-[#CBD5E1] hover:shadow-xs flex flex-col justify-between transition-all duration-200 cursor-pointer group"
                                    title="Klik untuk melihat rincian paket asesmen"
                                >
                                    <div class="space-y-2.5">
                                        <!-- Header Kategori, Tipe Badge & Status Badge -->
                                        <div class="flex items-center justify-between gap-2">
                                            <div class="flex items-center gap-1.5 flex-wrap">
                                                @if($tes->isForm())
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-semibold bg-[#F4F5F7] text-[#6B7280] border border-[#E5E7EB]">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-[#6B7280]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                        Formulir
                                                    </span>
                                                @endif
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-[#F4F5F7] text-[#6B7280] border border-[#E5E7EB] truncate">
                                                    {{ $tes->kategori->nama_kategori ?? 'Umum' }}
                                                </span>
                                            </div>

                                            @if($sudahSelesai)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-semibold border shrink-0 {{ $badgeClass }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-[#7FB69E]" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                    </svg>
                                                    Selesai
                                                </span>
                                            @elseif($sedangMengerjakan)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-semibold border shrink-0 {{ $badgeClass }}">
                                                    <span class="relative flex h-2 w-2">
                                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#8B93A7] opacity-60"></span>
                                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-[#8B93A7]"></span>
                                                    </span>
                                                    Sedang Berjalan
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-semibold border shrink-0 {{ $badgeClass }}">
                                                    <span class="relative flex h-2 w-2">
                                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#E8B563] opacity-60"></span>
                                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-[#E8B563]"></span>
                                                    </span>
                                                    Belum Selesai
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Judul Tes -->
                                        <h3 class="text-xs sm:text-sm font-bold text-[#2B2D33] group-hover:text-[#059669] transition-colors tracking-tight leading-snug line-clamp-2">
                                            {{ $tes->judul }}
                                        </h3>

                                        <!-- Timestamp Rilis -->
                                        <div class="text-[11px] text-[#9CA3AF] flex items-center gap-1">
                                            <span>Rilis: <strong class="text-[#6B7280] font-medium">{{ $tes->created_at ? $tes->created_at->format('d M Y, H:i') . ' WIB' : '-' }}</strong></span>
                                            @if($tes->created_at)
                                                <span class="truncate text-[#9CA3AF]">({{ $tes->created_at->diffForHumans() }})</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Aksi / Tombol Mulai (Emerald Green Accent) -->
                                    <div class="pt-3 mt-3 border-t border-[#F0F1F4] flex items-center justify-between">
                                        @if($sudahSelesai)
                                            <div class="text-xs flex items-center gap-1.5">
                                                @if($tes->isForm())
                                                    <span class="inline-flex items-center gap-1 text-[11px] font-medium text-[#3F6A54]">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-600" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                        </svg>
                                                        Terkirim
                                                    </span>
                                                @else
                                                    <span class="text-[#9CA3AF] text-[11px]">Skor:</span>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md font-mono font-bold text-xs bg-[#F0F7F4] text-[#3F6A54] border border-[#C8E3D7]">
                                                        {{ number_format($sesi->total_skor, 1) }}
                                                    </span>
                                                @endif
                                            </div>
                                            <a 
                                                href="{{ route('siswa.tes.hasil', $tes->id_tes) }}" 
                                                onclick="event.stopPropagation()"
                                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#2B2D33] hover:text-[#059669] bg-[#F7F8FA] hover:bg-[#ECFDF5] px-3 py-1.5 rounded-lg transition-colors border border-[#E5E7EB] cursor-pointer shadow-2xs"
                                            >
                                                <span>{{ $tes->isForm() ? 'Lihat Data' : 'Lihat Hasil' }}</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-[#9CA3AF]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </a>
                                        @elseif($sedangMengerjakan)
                                            <span class="text-xs text-[#8F5F17] font-medium">{{ $tes->isForm() ? 'Sedang Diisi' : 'Sedang Dikerjakan' }}</span>
                                            <a 
                                                href="{{ route('siswa.tes.kerjakan', $tes->id_tes) }}" 
                                                onclick="event.stopPropagation()"
                                                class="inline-flex items-center gap-1.5 bg-[#059669] hover:bg-[#047857] text-white text-xs font-semibold py-1.5 px-3.5 rounded-lg transition-all shadow-xs cursor-pointer"
                                            >
                                                <span>{{ $tes->isForm() ? 'Lanjutkan Isi' : 'Lanjutkan' }}</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </a>
                                        @else
                                            <span class="text-xs text-[#6B7280] font-medium">{{ $tes->isForm() ? 'Belum Diisi' : 'Belum Dikerjakan' }}</span>
                                            <a 
                                                href="{{ route('siswa.tes.show', $tes->id_tes) }}" 
                                                onclick="event.stopPropagation()"
                                                class="inline-flex items-center gap-1.5 bg-[#059669] hover:bg-[#047857] text-white text-xs font-semibold py-1.5 px-3.5 rounded-lg transition-all shadow-xs cursor-pointer"
                                            >
                                                <span>{{ $tes->isForm() ? 'Isi Formulir' : 'Mulai Kerjakan' }}</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif
            </div>

            <!-- BENTO CARD 2: MATERI & MODUL EDUKASI BK (WIDE CARD) -->
            <div id="tour-materi-edukasi" class="bg-white rounded-2xl border border-[#E5E7EB] shadow-xs p-5 sm:p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-[#F0F1F4]">
                    <div>
                        <h2 class="text-sm sm:text-base font-bold text-[#2B2D33] tracking-tight flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#059669] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            Materi &amp; Modul Edukasi BK
                        </h2>
                        <p class="text-xs text-[#6B7280] mt-0.5">Bahan bacaan dan modul edukasi bimbingan konseling.</p>
                    </div>
                    <span class="text-xs font-semibold text-[#6B7280] bg-[#F4F5F7] px-2.5 py-1 rounded-lg border border-[#E5E7EB] shrink-0">
                        {{ $materiList->count() }} Modul
                    </span>
                </div>

                @if($materiList->isEmpty())
                    <div class="w-full py-8 text-center text-xs text-[#9CA3AF] bg-[#F7F8FA] rounded-xl border border-[#E5E7EB]">
                        Belum ada modul materi edukasi yang tersedia.
                    </div>
                @else
                    <div class="w-full grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        @foreach($materiList as $materi)
                            <div class="w-full p-4 rounded-xl border border-[#E5E7EB] bg-white shadow-2xs hover:border-[#D1D5DB] hover:shadow-xs transition-all duration-200 flex flex-col justify-between gap-3">
                                <div class="space-y-1.5">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-[#F4F5F7] text-[#6B7280] border border-[#E5E7EB]">
                                            {{ $materi->kategori->nama_kategori ?? 'Umum' }}
                                        </span>
                                    </div>
                                    <h4 class="text-xs sm:text-sm font-bold text-[#2B2D33] line-clamp-1">{{ $materi->judul_materi }}</h4>
                                    <p class="text-xs text-[#6B7280] line-clamp-2 leading-relaxed">{{ $materi->deskripsi }}</p>
                                </div>

                                <div class="pt-2.5 border-t border-[#F0F1F4] flex items-center justify-between text-xs text-[#9CA3AF]">
                                    <span class="text-[11px]">{{ $materi->created_at ? $materi->created_at->format('d M Y') : '-' }}</span>
                                    @if($materi->file_path)
                                        <a 
                                            href="{{ asset('storage/' . $materi->file_path) }}" 
                                            target="_blank" 
                                            class="text-xs font-semibold px-2.5 py-1 rounded-lg transition-colors inline-flex items-center gap-1 text-[#2B2D33] hover:text-[#059669] bg-[#F7F8FA] hover:bg-[#ECFDF5] border border-[#E5E7EB] shadow-2xs"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-[#6B7280]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            <span>Buka Modul</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </main>

        <!-- ===================================================================== -->
        <!-- KOLOM SAMPING NARROW BENTO CARDS (lg:col-span-4 space-y-6)             -->
        <!-- ===================================================================== -->
        <aside class="w-full lg:col-span-4 space-y-6">

            <!-- BENTO CARD 3: STATUS KELAS BINAAN & KODE UNIK (NARROW CARD) -->
            <div id="tour-kelas-binaan" class="bg-white rounded-2xl border border-[#E5E7EB] shadow-xs p-5 space-y-3.5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-[#2B2D33] uppercase tracking-wider flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[#059669]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Kelas Binaan
                    </span>

                    <button 
                        type="button" 
                        onclick="toggleJoinForm()"
                        class="inline-flex items-center gap-1 text-[11px] font-semibold text-[#6B7280] hover:text-[#2B2D33] bg-[#F7F8FA] hover:bg-[#ECFDF5] border border-[#E5E7EB] px-2.5 py-1 rounded-lg transition-colors cursor-pointer shadow-2xs shrink-0"
                        title="Gabung kelas baru menggunakan kode unik"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-[#6B7280]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>{{ $kelas ? 'Ganti Kelas' : 'Gabung Kelas' }}</span>
                    </button>
                </div>

                <!-- Form Gabung / Ganti Kelas Dropdown -->
                <div id="joinClassDropdown" class="{{ $kelas ? 'hidden' : '' }} bg-[#F7F8FA] border border-[#E5E7EB] rounded-xl p-3.5 shadow-2xs space-y-2.5 transition-all">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-[#2B2D33] flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[#059669]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                            Kode Unik Kelas
                        </span>
                        @if($kelas)
                            <button type="button" onclick="toggleJoinForm()" class="text-[#9CA3AF] hover:text-[#2B2D33] text-sm cursor-pointer leading-none">&times;</button>
                        @endif
                    </div>
                    <p class="text-[11px] text-[#6B7280] leading-relaxed">
                        Masukkan kode unik yang dibagikan oleh Guru BK untuk bergabung ke kelas.
                    </p>
                    <form action="{{ route('siswa.joinKelas') }}" method="POST" class="space-y-2">
                        @csrf
                        <input 
                            type="text" 
                            name="kode_unik_kelas" 
                            placeholder="Contoh: BK-MIPA1" 
                            class="w-full px-3 py-1.5 bg-white border border-[#D1D5DB] rounded-lg text-xs font-mono font-bold text-[#2B2D33] focus:outline-none focus:ring-1 focus:ring-[#059669] focus:border-[#059669] uppercase tracking-wider"
                            required
                        >
                        <div class="flex items-center gap-2">
                            <button 
                                type="submit" 
                                class="w-full bg-[#059669] hover:bg-[#047857] text-white font-medium text-xs py-1.5 px-3 rounded-lg transition-colors cursor-pointer shadow-2xs text-center"
                            >
                                Gabung Kelas
                            </button>
                            @if($kelas)
                                <button 
                                    type="button" 
                                    onclick="toggleJoinForm()" 
                                    class="px-2.5 py-1.5 text-xs font-medium text-[#6B7280] hover:text-[#2B2D33] bg-[#E5E7EB] hover:bg-[#D1D5DB] rounded-lg transition-colors cursor-pointer"
                                >
                                    Batal
                                </button>
                            @endif
                        </div>
                    </form>
                </div>

                @if(!$kelas)
                    <div class="p-3.5 bg-[#F7F8FA] rounded-xl border border-dashed border-[#D1D5DB] text-center space-y-1">
                        <span class="block text-xs font-bold text-[#2B2D33]">Belum Terdaftar di Kelas</span>
                        <span class="text-[11px] text-[#9CA3AF] block">Klik tombol di atas untuk input kode unik</span>
                    </div>
                @else
                    @php
                        $adaTugas = ($stats->total_tes_aktif > 0);
                        $badgeClass = $adaTugas 
                            ? 'bg-[#FDF9F2] text-[#8F5F17] border-[#F5DEC0]' 
                            : 'bg-[#F0F7F4] text-[#3F6A54] border-[#C8E3D7]';
                    @endphp
                    <div class="w-full bg-[#F7F8FA] rounded-xl border border-[#E5E7EB] p-3.5 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <!-- Inisial Kelas -->
                            <div class="w-9 h-9 rounded-xl border border-[#E5E7EB] bg-white text-[#2B2D33] flex items-center justify-center shrink-0 shadow-2xs">
                                <span class="font-bold text-xs uppercase">
                                    {{ substr($kelas->nama_kelas, 0, 3) }}
                                </span>
                            </div>

                            <!-- Detail Info Kelas -->
                            <div class="min-w-0 space-y-0.5">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <h3 class="text-xs sm:text-sm font-bold text-[#2B2D33] leading-tight truncate max-w-[130px] sm:max-w-[160px]" title="{{ $kelas->nama_kelas }}">
                                        {{ $kelas->nama_kelas }}
                                    </h3>
                                    <button 
                                        type="button" 
                                        onclick="copyClassCode('{{ $kelas->kode_unik_kelas }}')"
                                        class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-mono font-semibold bg-white text-[#2B2D33] border border-[#E5E7EB] hover:bg-[#ECFDF5] hover:text-[#059669] hover:border-[#059669]/30 transition-colors cursor-pointer shrink-0 shadow-2xs"
                                        title="Klik untuk menyalin kode unik kelas"
                                    >
                                        <span>{{ $kelas->kode_unik_kelas }}</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-2.5 w-2.5 text-[#9CA3AF]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                    </button>
                                </div>
                                <p class="text-[11px] text-[#6B7280] truncate">
                                    <span>{{ $kelas->guruBk->nama_guru ?? 'Guru BK' }}</span>
                                    <span class="text-[#D1D5DB] mx-0.5">&bull;</span>
                                    <span>{{ $stats->total_teman }} Siswa</span>
                                </p>
                            </div>
                        </div>

                        <!-- Status Pill (Warm Amber jika ada tugas, Sage Green jika tuntas) -->
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold border shrink-0 {{ $badgeClass }}">
                            @if($adaTugas)
                                <span>{{ $stats->total_tes_aktif }} Tugas</span>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-[#7FB69E]" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>Tuntas</span>
                            @endif
                        </span>
                    </div>
                @endif
            </div>

            <!-- BENTO CARD 4: PROGRES TUGAS & EVALUASI CBT (DAISYUI RADIAL PROGRESS) -->
            <div id="tour-progres-tugas" class="bg-white rounded-2xl border border-[#E5E7EB] shadow-xs p-5 space-y-4">
                <!-- Header Card -->
                <div class="flex items-center justify-between pb-3 border-b border-[#F0F1F4]">
                    <span class="text-xs font-bold text-[#2B2D33] uppercase tracking-wider flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[#059669]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span>Progres Tugas &amp; Evaluasi</span>
                    </span>

                    @if($stats->persen_selesai >= 100)
                        <span class="text-xs font-bold text-[#3F6A54]">
                            Tuntas
                        </span>
                    @elseif($stats->persen_selesai > 0)
                        <span class="text-xs font-bold text-[#2B2D33]">
                            {{ $stats->persen_selesai }}% Selesai
                        </span>
                    @else
                        <span class="text-xs font-medium text-[#8B93A7]">
                            Belum Dimulai
                        </span>
                    @endif
                </div>

                <!-- Showcase DaisyUI Radial Progress (Biru Modern & Elegan) -->
                <div class="py-1 flex items-center justify-center gap-5">
                    <!-- DaisyUI Radial Progress Ring -->
                    <div 
                        class="radial-progress radial-progress-blue text-blue-600 shrink-0 shadow-2xs" 
                        style="--value:{{ $stats->persen_selesai }}; --size:6.5rem; --thickness:7px;" 
                        role="progressbar"
                        aria-valuenow="{{ $stats->persen_selesai }}" 
                        aria-valuemin="0" 
                        aria-valuemax="100"
                    >
                        <div class="flex flex-col items-center justify-center text-center select-none">
                            <span class="text-xl font-extrabold text-[#2B2D33] tracking-tight leading-none font-mono">
                                {{ $stats->persen_selesai }}%
                            </span>
                            <span class="text-[9px] font-bold text-[#8B93A7] uppercase tracking-wider mt-1">
                                Selesai
                            </span>
                        </div>
                    </div>

                    <!-- Keterangan Status Pengerjaan di samping Radial Progress -->
                    <div class="space-y-2.5 min-w-0 flex-1">
                        <div>
                            <h4 class="text-xs font-bold text-[#2B2D33] leading-tight">
                                @if($stats->persen_selesai >= 100)
                                    Seluruh Tugas Selesai 🎉
                                @elseif($stats->persen_selesai > 0)
                                    Tugas Berlangsung
                                @else
                                    Tugas Tersedia
                                @endif
                            </h4>
                        </div>

                        <!-- Mini pills status -->
                        <div class="flex items-center gap-1.5 flex-wrap text-[10px]">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-[#F0F7F4] text-[#3F6A54] border border-[#C8E3D7] font-semibold">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-[#7FB69E]" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                {{ $stats->total_tes_selesai }} Selesai
                            </span>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-[#FDF9F2] text-[#8F5F17] border border-[#F5DEC0] font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#E8B563]"></span>
                                {{ $stats->total_tes_aktif }} Belum
                            </span>
                        </div>
                    </div>
                </div>

                <!-- KPI Mini Grid -->
                <div class="grid grid-cols-2 gap-2 pt-2 border-t border-[#F0F1F4]">
                    <div class="bg-[#F7F8FA] p-2.5 rounded-xl border border-[#E5E7EB] text-center">
                        <span class="block text-[10px] text-[#6B7280] font-medium">Rata-rata Skor</span>
                        <span class="text-base font-extrabold text-[#2B2D33]">{{ number_format($stats->rata_rata_skor, 1) }}</span>
                    </div>
                    <div class="bg-[#F7F8FA] p-2.5 rounded-xl border border-[#E5E7EB] text-center">
                        <span class="block text-[10px] text-[#6B7280] font-medium">Total Paket</span>
                        <span class="text-base font-extrabold text-[#2B2D33]">{{ $stats->total_tes_kelas }}</span>
                    </div>
                </div>

                <p class="text-[11px] text-[#6B7280] leading-relaxed">
                    Selesaikan paket tes tepat waktu untuk membantu Guru BK menyusun rekomendasi karir &amp; peminatan Anda.
                </p>
            </div>

            <!-- BENTO CARD 5: RIWAYAT TES TERAKHIR (NARROW CARD) -->
            @if($riwayatTes->isNotEmpty())
                <div id="tour-riwayat-tes" class="bg-white rounded-2xl border border-[#E5E7EB] shadow-xs p-5 space-y-3">
                    <div class="flex items-center justify-between pb-2 border-b border-[#F0F1F4]">
                        <span class="text-xs font-bold text-[#2B2D33] uppercase tracking-wider flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[#059669]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Riwayat Tes Terakhir
                        </span>
                        <span class="text-[10px] font-semibold text-[#6B7280] bg-[#F4F5F7] px-2 py-0.5 rounded border border-[#E5E7EB]">
                            {{ $riwayatTes->count() }} Selesai
                        </span>
                    </div>

                    <div class="space-y-2">
                        @foreach($riwayatTes as $riwayat)
                            <div class="w-full p-2.5 rounded-xl border border-[#E5E7EB] bg-[#F7F8FA]/70 hover:bg-[#F7F8FA] transition-all flex items-center justify-between gap-2">
                                <div class="space-y-0.5 min-w-0">
                                    <h4 class="text-xs font-bold text-[#2B2D33] truncate">{{ $riwayat->tes->judul ?? 'Tes Asesmen' }}</h4>
                                    <p class="text-[10px] text-[#9CA3AF]">
                                        {{ $riwayat->waktu_submit ? $riwayat->waktu_submit->format('d M Y, H:i') : '-' }} WIB
                                    </p>
                                </div>

                                <div class="flex items-center gap-1.5 shrink-0">
                                    <span class="text-xs font-mono font-bold text-[#3F6A54] bg-[#F0F7F4] border border-[#C8E3D7] px-2 py-0.5 rounded-md shadow-2xs">
                                        {{ number_format($riwayat->total_skor, 1) }}
                                    </span>
                                    <a 
                                        href="{{ route('siswa.tes.hasil', $riwayat->id_tes) }}" 
                                        class="text-[11px] font-semibold text-[#6B7280] hover:text-[#059669] bg-white hover:bg-[#ECFDF5] border border-[#E5E7EB] px-2 py-0.5 rounded-md transition-colors shadow-2xs"
                                        title="Lihat rincian nilai"
                                    >
                                        Rincian
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- BENTO CARD 6: TEMAN SEKELAS (NARROW CARD) -->
            @if($kelas)
                <div id="tour-teman-sekelas" class="bg-white rounded-2xl border border-[#E5E7EB] shadow-xs p-5 space-y-3">
                    <div class="flex items-center justify-between pb-2 border-b border-[#F0F1F4]">
                        <span class="text-xs font-bold text-[#2B2D33] uppercase tracking-wider flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[#059669]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Teman Sekelas
                        </span>
                        <span class="text-[10px] text-[#6B7280] font-semibold bg-[#F4F5F7] px-2 py-0.5 rounded border border-[#E5E7EB]">{{ $temanSekelas->count() }} siswa</span>
                    </div>

                    <div class="max-h-48 overflow-y-auto divide-y divide-[#F0F1F4] pr-1 text-xs">
                        @forelse($temanSekelas as $teman)
                            <div class="py-1.5 flex items-center justify-between gap-2 {{ $teman->id_siswa === $siswa->id_siswa ? 'bg-[#ECFDF5] px-2 rounded-lg font-semibold text-[#2B2D33] border border-[#059669]/30' : 'text-[#6B7280]' }}">
                                <div class="flex items-center gap-2 truncate">
                                    <span class="h-5 w-5 rounded-full bg-[#ECEEF2] border border-[#E5E7EB] text-[#2B2D33] text-[9px] font-bold flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($teman->nama_siswa, 0, 1)) }}
                                    </span>
                                    <span class="truncate text-xs">{{ $teman->nama_siswa }}</span>
                                </div>
                                @if($teman->id_siswa === $siswa->id_siswa)
                                    <span class="text-[9px] font-bold text-white bg-[#059669] px-1.5 py-0.5 rounded shrink-0 shadow-2xs">Saya</span>
                                @else
                                    <span class="text-[10px] text-[#9CA3AF] font-mono shrink-0">{{ substr($teman->nisn, -4) }}</span>
                                @endif
                            </div>
                        @empty
                            <p class="text-xs text-[#9CA3AF] py-2 text-center">Belum ada teman terdaftar.</p>
                        @endforelse
                    </div>
                </div>
            @endif

        </aside>

    </div>

</div>

<!-- Floating Copy Toast Notification (Dark Navy Theme) -->
<div id="copyToast" class="fixed bottom-5 right-5 z-50 bg-slate-900 text-white text-xs font-medium px-4 py-2.5 rounded-xl shadow-md border border-slate-800 hidden transition-all flex items-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#7FB69E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
    </svg>
    <span>Kode kelas berhasil disalin!</span>
</div>

<!-- Script Interaktif Sederhana & Ringan -->
<script>
// 1. Toggle Form Input Kode Kelas
function toggleJoinForm() {
    const el = document.getElementById('joinClassDropdown');
    if (el) {
        el.classList.toggle('hidden');
    }
}

// 2. Salin Kode Kelas ke Clipboard
function copyClassCode(code) {
    if (!code) return;
    navigator.clipboard.writeText(code).then(() => {
        const toast = document.getElementById('copyToast');
        if (toast) {
            toast.classList.remove('hidden');
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 2200);
        }
    }).catch(err => {
        console.error('Gagal menyalin:', err);
    });
}

// 3. Filter Kartu Tes (Semua, Perlu Dikerjakan, Selesai)
function filterTests(type, btn) {
    const tabs = document.querySelectorAll('#testFilterTabs button');
    tabs.forEach(b => {
        b.className = 'px-3 py-1 rounded-lg transition-all cursor-pointer text-[#6B7280] hover:text-[#2B2D33] font-medium text-xs';
    });
    btn.className = 'px-3 py-1 rounded-lg transition-all cursor-pointer bg-[#059669] text-white font-semibold text-xs shadow-xs';

    const cards = document.querySelectorAll('#testCardsContainer .test-card');
    cards.forEach(card => {
        const st = card.getAttribute('data-status');
        if (type === 'all' || st === type) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('vendor/driverjs/driver.css') }}">
<style>
/* Custom driver.js styling to match HaloBK UI */
.driver-popover.halobk-tour-popover {
    background-color: #ffffff;
    color: #1e293b;
    border-radius: 1rem;
    padding: 1.25rem;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
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
    color: #64748b;
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
    background-color: #0f172a;
    color: #ffffff;
}
.driver-popover.halobk-tour-popover .driver-popover-next-btn:hover {
    background-color: #1e293b;
}
.driver-popover.halobk-tour-popover .driver-popover-close-btn {
    color: #94a3b8;
    font-size: 1.25rem;
    top: 0.75rem;
    right: 0.75rem;
}
.driver-popover.halobk-tour-popover .driver-popover-close-btn:hover {
    color: #334155;
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('vendor/driverjs/driver.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const driverFactory = (window.driver && window.driver.js && window.driver.js.driver) ? window.driver.js.driver : (window.driver ? window.driver : null);

    if (!driverFactory) {
        console.warn('Driver.js tidak dapat dimuat.');
        return;
    }

    const siswaTourSteps = [
        {
            element: '#tour-siswa-hero',
            popover: {
                title: '👋 Halo! Selamat Datang di HaloBK',
                description: 'Ini beranda utama kamu. Di sini kamu bisa memantau identitas diri, kelas binaan, Guru BK pembimbing, serta ringkasan aktivitas bimbingan konselingmu.',
                side: 'bottom',
                align: 'start'
            }
        },
        {
            element: '#tour-siswa-stats',
            popover: {
                title: '📊 Ringkasan Tugas & Modul',
                description: 'Tiga kotak info ini menunjukkan berapa banyak tes yang <strong>Perlu Dikerjakan</strong>, tes yang sudah <strong>Selesai</strong>, serta jumlah <strong>Materi Modul</strong> yang bisa kamu baca.',
                side: 'bottom',
                align: 'end'
            }
        },
        {
            element: '#tour-paket-tes',
            popover: {
                title: '📝 Asesmen & Formulir BK',
                description: 'Daftar tugas evaluasi dari Guru BK. Kamu bisa mengerjakan <strong>Tes CBT</strong> (seperti psikotes / minat bakat) atau mengisi <strong>Formulir Pendataan</strong>. Cukup klik tombol hijau untuk mulai mengisi!',
                side: 'top',
                align: 'start'
            }
        },
        {
            element: '#tour-materi-edukasi',
            popover: {
                title: '📚 Modul & Bacaan Edukatif',
                description: 'Kumpulan materi bermanfaat seputar tips belajar efektif, persiapan karir & jurusan kuliah, hingga pembinaan diri. Klik tombol <strong>Buka Modul</strong> untuk langsung membacanya.',
                side: 'top',
                align: 'start'
            }
        },
        {
            element: '#tour-kelas-binaan',
            popover: {
                title: '🏫 Ruang Kelas Binaan',
                description: 'Menampilkan nama kelas dan kode unik kelasmu. Jika kamu perlu bergabung ke kelas baru atau berganti kelas, klik tombol <strong>Ganti / Gabung Kelas</strong> dan masukkan kode dari Guru BK.',
                side: 'top',
                align: 'start'
            }
        },
        {
            element: '#tour-progres-tugas',
            popover: {
                title: '🎯 Indikator Progres Tugas',
                description: 'Grafik lingkaran ini menghitung persentase tugasmu yang sudah selesai. Selesaikan seluruh tugas tepat waktu agar lingkaran terisi penuh <strong>100% Tuntas</strong>!',
                side: 'top',
                align: 'start'
            }
        },
        @if($riwayatTes->isNotEmpty())
        {
            element: '#tour-riwayat-tes',
            popover: {
                title: '⏱️ Riwayat Nilai Asesmen',
                description: 'Setiap tugas atau tes yang telah kamu selesaikan akan tercatat di sini. Kamu bisa klik <strong>Rincian</strong> untuk melihat perolehan skor dan evaluasi nilaimu.',
                side: 'top',
                align: 'start'
            }
        },
        @endif
        @if($kelas)
        {
            element: '#tour-teman-sekelas',
            popover: {
                title: '👥 Teman Sekelasmu',
                description: 'Daftar teman-teman sekelas yang juga terdaftar dan dibimbing oleh Guru BK di kelas binaan ini.',
                side: 'top',
                align: 'start'
            }
        },
        @endif
        {
            element: '#btnStartSiswaTour',
            popover: {
                title: '🎉 Panduan Selesai!',
                description: 'Kamu sudah siap menjelajah dan belajar di HaloBK. Jika sewaktu-waktu butuh panduan ini lagi, cukup klik tombol <strong>Tur Fitur</strong> ini kapan saja. Selamat belajar!',
                side: 'bottom',
                align: 'end'
            }
        }
    ];

    const siswaTourDriver = driverFactory({
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
        steps: siswaTourSteps
    });

    window.startSiswaTour = function () {
        siswaTourDriver.drive();
    };

    // Logika Tur: Muncul sekali per session aplikasi (kecuali ditutup/close browser), atau saat baru login, atau saat klik tombol tur
    const hasSeenTour = sessionStorage.getItem('halobk_tour_shown_siswa');
    const isFreshLogin = {{ session('login_success') ? 'true' : 'false' }};

    if (!hasSeenTour || isFreshLogin) {
        sessionStorage.setItem('halobk_tour_shown_siswa', 'true');

        // Muncul notifikasi masuk menggunakan SweetAlert2 terlebih dahulu
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Selamat Datang!',
                html: '<div class="space-y-1.5 mt-1"><p class="font-bold text-slate-800 text-sm md:text-base">{{ $siswa->nama_siswa }}</p><p class="text-xs text-slate-500">Kamu berhasil masuk ke Dashboard Siswa HaloBK. Mari ikuti tur panduan fitur!</p></div>',
                timer: 2300,
                timerProgressBar: true,
                showConfirmButton: true,
                confirmButtonText: 'Mulai Tur Sekarang &rarr;',
                confirmButtonColor: '#0f172a',
                customClass: {
                    popup: 'rounded-2xl shadow-2xl p-6',
                    title: 'text-lg font-bold text-slate-900',
                    confirmButton: 'rounded-xl px-5 py-2.5 text-xs font-semibold cursor-pointer'
                }
            }).then(() => {
                setTimeout(() => {
                    siswaTourDriver.drive();
                }, 350);
            });
        } else {
            setTimeout(() => {
                siswaTourDriver.drive();
            }, 500);
        }
    }
});
</script>
@endpush

