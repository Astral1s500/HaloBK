<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HaloBK ─ Sistem Bimbingan & Konseling Sekolah</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- Google Fonts: Instrument Serif & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Fonts & Compiled Assets via Vite -->
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- GSAP & ScrollTrigger CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

    <style>
        /* Murni Hitam Putih Monokrom (High-Contrast Editorial) */
        :root {
            --black: #000000;
            --dark-card: #0d0d0d;
            --dark-border: #222222;
            --white: #ffffff;
            --light-card: #f7f7f7;
            --light-border: #e2e2e2;
            --gray-text-dark: #888888;
            --gray-text-light: #666666;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--black);
            color: var(--white);
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        .font-editorial {
            font-family: 'Instrument Serif', Georgia, serif;
        }

        /* Vertical Writing Mode untuk Rail Samping */
        .writing-vertical {
            writing-mode: vertical-rl;
            transform: rotate(180deg);
        }

        /* Hover Line Draw Effect */
        .hover-line-sweep {
            position: relative;
        }
        .hover-line-sweep::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 1px;
            background-color: currentColor;
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .hover-line-sweep:hover::after {
            transform: scaleX(1);
            transform-origin: left;
        }

        /* Split Typography Animations & 3D Rendering */
        .char-split-item {
            display: inline-block;
            will-change: transform, opacity, filter;
            transform-style: preserve-3d;
            backface-visibility: hidden;
        }
        .char-split-word {
            display: inline-block;
            white-space: nowrap;
        }
        .char-split-space {
            display: inline-block;
        }
    </style>
</head>
<body class="antialiased selection:bg-white selection:text-black">

    <!-- ==================== 1. FIXED LEFT RAIL (DESKTOP MONOKROM HITAM PUTIH) ==================== -->
    <aside class="fixed inset-y-0 left-0 z-50 hidden md:flex w-20 flex-col items-center justify-between py-8 border-r border-[#222222] bg-[#000000] text-[#ffffff]">
        
        <!-- Progress Bar Vertikal Dinamis -->
        <div class="absolute right-0 top-0 w-[2px] h-full bg-[#1a1a1a] pointer-events-none">
            <div id="verticalScrollProgress" class="w-full h-full bg-[#ffffff] origin-top scale-y-0"></div>
        </div>

        <!-- Brand Top Logo -->
        <a href="{{ route('landing') }}" class="group flex flex-col items-center gap-2 focus:outline-none">
            <img src="{{ asset('images/logo-white.png') }}" alt="HaloBK" class="h-7 w-auto object-contain transition-transform group-hover:scale-110">
            <span class="text-[9px] font-mono tracking-[0.25em] text-[#888888] group-hover:text-white uppercase">HALO BK</span>
        </a>

        <!-- Middle: Rotated Label Monokrom (Tanpa Tanggal/Edisi) -->
        <div class="writing-vertical flex items-center gap-3 text-[10px] font-mono tracking-[0.3em] text-[#888888] uppercase select-none">
            <span>SISTEM BIMBINGAN KONSELING</span>
            <span class="w-1 h-1 rounded-full bg-[#888888]"></span>
            <span>PORTAL RESMI</span>
        </div>

        <!-- Bottom: Indikator Monokrom Sederhana -->
        <div class="flex flex-col items-center gap-1.5 text-[9px] text-[#888888] font-mono">
        </div>
    </aside>

    <!-- Topbar Mobile (Layar HP < 768px Monokrom) -->
    <header class="sticky top-0 z-50 flex md:hidden w-full items-center justify-between px-5 py-4 bg-[#000000]/95 backdrop-blur-md border-b border-[#222222] text-[#ffffff]">
        <a href="{{ route('landing') }}" class="flex items-center gap-2.5">
            <img src="{{ asset('images/logo-white.png') }}" alt="HaloBK" class="h-6 w-auto">
            <span class="font-bold text-sm tracking-tight text-[#ffffff]">HaloBK</span>
        </a>
        <div class="flex items-center gap-3">
            @php
                $isLoggedIn = false;
                $dashboardRoute = route('login');
                $userRoleLabel = 'Masuk Portal';

                if (Auth::guard('admin')->check()) {
                    $isLoggedIn = true;
                    $dashboardRoute = route('admin.dashboard');
                    $userRoleLabel = 'Admin';
                } elseif (Auth::guard('guru')->check()) {
                    $isLoggedIn = true;
                    $dashboardRoute = route('guru.dashboard');
                    $userRoleLabel = 'Guru BK';
                } elseif (Auth::guard('siswa')->check()) {
                    $isLoggedIn = true;
                    $dashboardRoute = route('siswa.dashboard');
                    $userRoleLabel = 'Siswa';
                }
            @endphp
            <a href="{{ $dashboardRoute }}" class="text-xs font-mono font-semibold tracking-wider px-3.5 py-1.5 rounded-full border border-white text-white hover:border-[#888888] transition-colors">
                {{ $userRoleLabel }} &rarr;
            </a>
        </div>
        <!-- Progress bar horizontal di HP -->
        <div class="absolute bottom-0 left-0 w-full h-[2px] bg-[#1a1a1a]">
            <div id="mobileScrollProgress" class="h-full bg-[#ffffff] origin-left scale-x-0"></div>
        </div>
    </header>


    <!-- ==================== 2. HORIZONTAL STORY CANVAS (GSAP SCRUBBED SEQUENCE) ==================== -->
    <main class="md:pl-20 w-full overflow-hidden">
        
        <!-- Pinned Wrapper -->
        <div id="horizontalStoryPin" class="relative w-full md:h-screen">
            
            <!-- Horizontal Track yang Bergeser ke Samping saat di-scroll di Desktop -->
            <div id="horizontalTrack" class="flex flex-col md:flex-row md:flex-nowrap md:h-screen w-full">


                <!-- ==================== PANEL 1: HERO IDENTITAS (HITAM PEKAT #000000) ==================== -->
                <section id="panel-hero" class="horizontal-panel w-full md:w-[calc(100vw-5rem)] md:min-w-[calc(100vw-5rem)] shrink-0 h-auto md:h-screen bg-[#000000] text-[#ffffff] p-6 sm:p-12 md:p-16 md:pl-20 flex flex-col justify-between border-b md:border-b-0 md:border-r border-[#222222] relative overflow-hidden">
                    
                    <!-- Top Info Row (Tanpa Tanggal & Tanpa Tulisan 'Aktif Terintegrasi') -->
                    <div class="flex items-center justify-between w-full pt-4 md:pt-0">
                        <div class="p1-anim flex items-center gap-3 text-xs tracking-wider uppercase text-[#888888] font-mono">
                            <span>LAYANAN BIMBINGAN KONSELING</span>
                        </div>
                    </div>

                    <!-- Center Headline: Anggun & Jelas Saat Masuk -->
                    <div class="my-16 md:my-0 max-w-5xl space-y-6">
                        <p class="p1-anim font-mono text-xs md:text-sm uppercase tracking-[0.3em] text-[#888888]">
                            SISTEM INFORMASI PENDAMPINGAN SISWA
                        </p>
                        
                        <h1 class="text-4xl sm:text-6xl md:text-7xl lg:text-8xl font-normal leading-[0.95] tracking-tight text-[#ffffff]">
                            <span class="char-split-hero-serif font-editorial italic block text-5xl sm:text-7xl md:text-8xl lg:text-9xl text-[#cccccc]">Mendampingi</span>
                            <span class="char-split-hero-sans font-extrabold uppercase tracking-tight block text-[#ffffff]">Potensi Siswa.</span>
                        </h1>

                        <p class="p1-anim text-sm sm:text-base md:text-lg text-[#aaaaaa] max-w-2xl font-light leading-relaxed pt-4">
                            Platform bimbingan konseling digital terpadu untuk asesmen minat bakat terstandar, sesi konseling rahasia yang aman, serta evaluasi diagnostik butir soal secara presisi.
                        </p>
                    </div>

                    <!-- Bottom CTA -->
                    <div class="p1-anim flex flex-col sm:flex-row sm:items-end justify-between gap-6 pb-4 md:pb-0 pt-8 border-t border-[#222222]">
                        <div class="flex items-center gap-4">
                            <a href="{{ $dashboardRoute }}" class="group inline-flex items-center gap-4 px-8 py-4 rounded-full border border-white text-white text-xs font-mono uppercase tracking-[0.2em] hover:border-[#888888] transition-all">
                                <span>{{ $userRoleLabel }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>

                        <div class="flex items-center gap-3 text-xs text-[#888888] font-mono">
                            <span>GULIR KE BAWAH UNTUK MELIHAT FITUR &rarr;</span>
                        </div>
                    </div>

                </section>


                <!-- ==================== PANEL 2: CHAPTER I ─ FILOSOFI & RUANG AMAN (PUTIH PEKAT #FFFFFF) ==================== -->
                <section id="panel-chapter-1" class="horizontal-panel w-full md:w-[calc(100vw-5rem)] md:min-w-[calc(100vw-5rem)] shrink-0 h-auto md:h-screen bg-[#ffffff] text-[#000000] p-6 sm:p-12 md:p-16 flex flex-col justify-between border-b md:border-b-0 md:border-r border-[#e2e2e2] relative overflow-hidden">
                    
                    <!-- Header Bab -->
                    <div class="flex items-center justify-between w-full pt-4 md:pt-0">
                        <span class="p2-anim font-editorial text-2xl md:text-3xl text-[#000000]">
                            Chapter I ─ Filosofi & Ruang Aman
                        </span>
                        <span class="p2-anim font-mono text-xs uppercase tracking-widest text-[#666666]">
                            Kerahasiaan Penuh
                        </span>
                    </div>

                    <!-- Konten Tengah: Teks & Card Muncul Satu per Satu Mengikuti Scroll -->
                    <div class="my-12 md:my-0 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                        <div class="lg:col-span-7 space-y-6">
                            <span class="p2-anim text-xs font-mono font-bold uppercase tracking-[0.2em] text-[#666666] block">
                                ETIKA BIMBINGAN KONSELING
                            </span>
                            <h2 class="char-split-wave text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight leading-[1.15] text-[#000000]">
                                Ruang bimbingan yang memanusiakan, bebas rasa cemas, dan beretika.
                            </h2>
                            <p class="p2-anim text-sm md:text-base text-[#555555] leading-relaxed max-w-xl">
                                Setiap siswa berhak mendapatkan ruang aman untuk mengekspresikan kendala belajar, perencanaan karir, maupun permasalahan personal tanpa takut dihakimi. HaloBK memproteksi seluruh rekam konsultasi hanya antara Guru BK dan siswa yang bersangkutan.
                            </p>
                        </div>

                        <!-- Card Komponen Filosofi: Muncul Satu per Satu -->
                        <div class="lg:col-span-5 space-y-4">
                            <div class="p2-anim p-6 rounded-2xl border border-[#e2e2e2] hover:border-black transition-colors space-y-2">
                                <span class="font-mono text-xs text-[#888888] block tracking-wider">[ 01 ] PRIVASI KONSELI</span>
                                <h3 class="text-base font-bold text-[#000000]">Enkripsi Catatan & Akses Terbatas</h3>
                                <p class="text-xs text-[#555555] leading-relaxed">
                                    Catatan kasus dan bimbingan hanya dapat dilihat oleh Guru BK pembina kelas, melindungi integritas psikologis siswa.
                                </p>
                            </div>

                            <div class="p2-anim p-6 rounded-2xl border border-[#e2e2e2] hover:border-black transition-colors space-y-2">
                                <span class="font-mono text-xs text-[#888888] block tracking-wider">[ 02 ] AKOMODATIF & OBJEKTIF</span>
                                <h3 class="text-base font-bold text-[#000000]">Pendekatan Berbasis Data Siswa</h3>
                                <p class="text-xs text-[#555555] leading-relaxed">
                                    Konseling tidak berlandaskan asumsi sepihak, melainkan didukung data hasil asesmen minat, bakat, dan gaya belajar yang valid.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Bab -->
                    <div class="flex items-center justify-between text-xs text-[#888888] border-t border-[#e2e2e2] pt-4 font-mono">
                        <span>PRINSIP: ASAS KERAHASIAAN BK</span>
                        <span>01 / 05</span>
                    </div>

                </section>


                <!-- ==================== PANEL 3: CHAPTER II ─ CBT & ASESMEN DIGITAL (HITAM PEKAT #000000) ==================== -->
                <section id="panel-chapter-2" class="horizontal-panel w-full md:w-[calc(100vw-5rem)] md:min-w-[calc(100vw-5rem)] shrink-0 h-auto md:h-screen bg-[#000000] text-[#ffffff] p-6 sm:p-12 md:p-16 flex flex-col justify-between border-b md:border-b-0 md:border-r border-[#222222] relative overflow-hidden">
                    
                    <!-- Header Bab -->
                    <div class="flex items-center justify-between w-full pt-4 md:pt-0">
                        <span class="p3-anim font-editorial text-2xl md:text-3xl text-[#ffffff]">
                            Chapter II ─ Asesmen Digital & CBT
                        </span>
                        <span class="p3-anim font-mono text-xs uppercase tracking-widest text-[#888888]">
                            Auto-Grading & Auto-Save
                        </span>
                    </div>

                    <!-- Konten Tengah: Interface CBT Muncul Elemen per Elemen -->
                    <div class="my-12 md:my-0 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                        <div class="lg:col-span-5 space-y-6">
                            <span class="p3-anim text-xs font-mono font-bold uppercase tracking-[0.2em] text-[#888888] block">
                                UJIAN MINAT & BAKAT DIGITAL
                            </span>
                            <h2 class="char-split-flip text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight leading-[1.15] text-[#ffffff]">
                                Antarmuka tes minim distraksi dengan proteksi real-time.
                            </h2>
                            <p class="p3-anim text-sm text-[#aaaaaa] leading-relaxed">
                                Siswa mengerjakan kuesioner dan asesmen psikologis dengan antarmuka yang bersih. Dilengkapi fitur penyimpanan instan (*instant auto-save*) per butir jawaban dan batas waktu pengerjaan otomatis.
                            </p>
                        </div>

                        <!-- Mockup Panel CBT Siswa Monokrom -->
                        <div class="lg:col-span-7">
                            <div class="p3-anim rounded-3xl p-6 sm:p-8 border border-[#222222] space-y-5">
                                <div class="flex items-center justify-between border-b border-[#222222] pb-4">
                                    <div>
                                        <span class="text-[11px] font-mono text-[#888888] uppercase tracking-wider block">// ASESMEN PEMINATAN</span>
                                        <h4 class="text-base font-bold text-[#ffffff]">Kuesioner Minat Karir & Penjurusan Siswa</h4>
                                    </div>
                                    <span class="text-xs font-mono text-white font-bold tracking-widest border border-[#444444] px-3 py-1 rounded-full flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full border border-white"></span>
                                        WAKTU 42:18
                                    </span>
                                </div>

                                <div class="p-5 rounded-2xl border border-[#222222] space-y-3">
                                    <div class="p3-anim flex items-center justify-between text-xs text-[#888888] font-mono">
                                        <span>SOAL NO. 2 DARI 10</span>
                                        <span class="text-[#ffffff] font-bold tracking-wider">● TERSIMPAN</span>
                                    </div>
                                    <p class="p3-anim text-xs sm:text-sm font-semibold text-[#ffffff] leading-relaxed">
                                        "Ketika merencanakan masa depan setelah lulus sekolah, aspek mana yang menjadi prioritas utama pertimbangan Anda?"
                                    </p>
                                    <div class="space-y-2.5 text-xs pt-1">
                                        <!-- Opsi A (Border putih tegas & elegan, tanpa background) -->
                                        <div class="p3-anim p-3.5 rounded-xl border-2 border-white text-white font-semibold flex items-center justify-between">
                                            <span>A. Minat bakat alami dan kesesuaian potensi pribadi</span>
                                            <span class="w-5 h-5 rounded-full border border-white text-white flex items-center justify-center text-xs">✓</span>
                                        </div>
                                        <!-- Opsi B (Border abu halus, tanpa background) -->
                                        <div class="p3-anim p-3.5 rounded-xl border border-[#333333] text-[#888888] font-medium flex items-center justify-between">
                                            <span>B. Peluang kerja dan tren kebutuhan industri masa depan</span>
                                            <span class="w-5 h-5 rounded-full border border-[#444444]"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Bab -->
                    <div class="flex items-center justify-between text-xs text-[#888888] border-t border-[#222222] pt-4 font-mono">
                        <span>MODUL: COMPUTER-BASED TEST</span>
                        <span>02 / 05</span>
                    </div>

                </section>


                <!-- ==================== PANEL 4: CHAPTER III ─ DIAGNOSTIK SOAL RAWAN GURU BK (PUTIH PEKAT #FFFFFF) ==================== -->
                <section id="panel-chapter-3" class="horizontal-panel w-full md:w-[calc(100vw-5rem)] md:min-w-[calc(100vw-5rem)] shrink-0 h-auto md:h-screen bg-[#ffffff] text-[#000000] p-6 sm:p-12 md:p-16 flex flex-col justify-between border-b md:border-b-0 md:border-r border-[#e2e2e2] relative overflow-hidden">
                    
                    <!-- Header Bab -->
                    <div class="flex items-center justify-between w-full pt-4 md:pt-0">
                        <span class="p4-anim font-editorial text-2xl md:text-3xl text-[#000000]">
                            Chapter III ─ Diagnostik & Koreksi
                        </span>
                        <span class="p4-anim font-mono text-xs uppercase tracking-widest text-[#000000] font-bold">
                            Deteksi Soal Rawan
                        </span>
                    </div>

                    <!-- Konten Tengah: Fitur Unggulan Guru BK Muncul Satu per Satu -->
                    <div class="my-12 md:my-0 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                        <div class="lg:col-span-5 space-y-6">
                            <span class="p4-anim text-xs font-mono font-bold uppercase tracking-[0.2em] text-[#666666] block">
                                ANALISIS BUTIR SOAL CERDAS
                            </span>
                            <h2 class="char-split-focus text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight leading-[1.15] text-[#000000]">
                                Deteksi soal yang paling banyak salah untuk evaluasi materi.
                            </h2>
                            <p class="p4-anim text-sm text-[#555555] leading-relaxed">
                                Guru BK dapat melihat indikator bulatan pada butir soal yang paling banyak dijawab salah oleh siswa. Fitur ini mempermudah evaluasi remedial dan penyusunan modul bimbingan yang tepat sasaran.
                            </p>
                        </div>

                        <!-- Mockup Panel Diagnostik Soal Rawan Monokrom -->
                        <div class="lg:col-span-7">
                            <div class="p4-anim rounded-3xl p-6 sm:p-8 border border-[#e2e2e2] space-y-4">
                                <div class="flex items-center justify-between border-b border-[#e2e2e2] pb-3.5">
                                    <h4 class="text-sm font-bold text-[#000000]">Analisis Butir Soal Ujian (Kelas Binaan)</h4>
                                    <span class="text-[11px] font-mono font-bold text-[#000000] tracking-wider border border-black px-2.5 py-1 rounded">
                                        [ 1 SOAL RAWAN ]
                                    </span>
                                </div>

                                <div class="space-y-2.5 text-xs">
                                    <!-- Baris 1: Soal Rawan (Tanpa background, border hitam tegas) -->
                                    <div class="p4-anim p-4 rounded-xl border-2 border-[#000000] flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <span class="w-3 h-3 rounded-full border-2 border-[#000000] shrink-0"></span>
                                            <div>
                                                <span class="font-bold text-[#000000] block text-xs sm:text-sm">Soal #4: Pemetaan Minat Karir Saintek</span>
                                                <span class="text-[11px] text-[#666666]">Tingkat salah: 65% siswa &bull; Distraktor dominan: Opsi C</span>
                                            </div>
                                        </div>
                                        <span class="font-mono font-bold px-2.5 py-1 rounded border border-[#000000] text-[#000000] text-[10px] tracking-wider">
                                            RAWAN
                                        </span>
                                    </div>

                                    <!-- Baris 2: Soal Tuntas (Tanpa background, border halus) -->
                                    <div class="p4-anim p-4 rounded-xl border border-[#e2e2e2] flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <span class="w-3 h-3 rounded-full border border-[#888888] shrink-0"></span>
                                            <div>
                                                <span class="font-bold text-[#000000] block text-xs sm:text-sm">Soal #1: Pengenalan Karakter Belajar Mandiri</span>
                                                <span class="text-[11px] text-[#666666]">Tingkat benar: 94% siswa</span>
                                            </div>
                                        </div>
                                        <span class="font-mono font-medium px-2.5 py-1 rounded border border-[#cccccc] text-[#666666] text-[10px] tracking-wider">
                                            TUNTAS
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Bab -->
                    <div class="flex items-center justify-between text-xs text-[#888888] border-t border-[#e2e2e2] pt-4 font-mono">
                        <span>MODUL: EVALUASI & KOREKSI BK</span>
                        <span>03 / 05</span>
                    </div>

                </section>


                <!-- ==================== PANEL 5: CHAPTER IV ─ MODUL BIMBINGAN & ALUR SISWA (HITAM PEKAT #000000) ==================== -->
                <section id="panel-chapter-4" class="horizontal-panel w-full md:w-[calc(100vw-5rem)] md:min-w-[calc(100vw-5rem)] shrink-0 h-auto md:h-screen bg-[#000000] text-[#ffffff] p-6 sm:p-12 md:p-16 flex flex-col justify-between border-b md:border-b-0 md:border-r border-[#222222] relative overflow-hidden">
                    
                    <!-- Header Bab -->
                    <div class="flex items-center justify-between w-full pt-4 md:pt-0">
                        <span class="char-split-slide font-editorial text-2xl md:text-3xl text-[#ffffff] inline-block overflow-hidden">
                            Chapter IV ─ Modul & Alur Layanan
                        </span>
                        <span class="p5-anim font-mono text-xs uppercase tracking-widest text-[#888888]">
                            3 Langkah Sederhana
                        </span>
                    </div>

                    <!-- Konten Tengah: 3 Kartu Alur Muncul Satu per Satu Mengikuti Scroll -->
                    <div class="my-12 md:my-0 grid grid-cols-1 md:grid-cols-3 gap-6">
                        
                        <!-- Card Langkah 01 -->
                        <div class="p5-anim rounded-3xl p-8 border border-[#222222] hover:border-white transition-colors space-y-4">
                            <span class="font-mono text-3xl font-bold text-[#ffffff] block">01</span>
                            <h3 class="text-lg font-bold text-[#ffffff]">Masuk Akun Sekolah</h3>
                            <p class="text-xs sm:text-sm text-[#aaaaaa] leading-relaxed">
                                Siswa mengakses portal menggunakan NISN resmi dan kata sandi yang telah diverifikasi, langsung terhubung ke kelas binaan Guru BK.
                            </p>
                        </div>

                        <!-- Card Langkah 02 -->
                        <div class="p5-anim rounded-3xl p-8 border border-[#222222] hover:border-white transition-colors space-y-4">
                            <span class="font-mono text-3xl font-bold text-[#ffffff] block">02</span>
                            <h3 class="text-lg font-bold text-[#ffffff]">Ujian & Modul Edukasi</h3>
                            <p class="text-xs sm:text-sm text-[#aaaaaa] leading-relaxed">
                                Mengerjakan asesmen minat bakat secara mandiri dan membaca materi pembinaan seputar manajemen stres serta tips belajar efektif.
                            </p>
                        </div>

                        <!-- Card Langkah 03 -->
                        <div class="p5-anim rounded-3xl p-8 border border-[#222222] hover:border-white transition-colors space-y-4">
                            <span class="font-mono text-3xl font-bold text-[#ffffff] block">03</span>
                            <h3 class="text-lg font-bold text-[#ffffff]">Konseling & Arahan Karir</h3>
                            <p class="text-xs sm:text-sm text-[#aaaaaa] leading-relaxed">
                                Berkonsultasi tatap muka dengan Guru BK berlandaskan rekam jejak capaian nilai dan potensi diri yang terukur.
                            </p>
                        </div>

                    </div>

                    <!-- Footer Bab -->
                    <div class="flex items-center justify-between text-xs text-[#888888] border-t border-[#222222] pt-4 font-mono">
                        <span>ALUR PENGGUNAAN TERPADU</span>
                        <span>04 / 05</span>
                    </div>

                </section>


                <!-- ==================== PANEL 6: CHAPTER V ─ PENUTUP, FAQ & AKSES PORTAL (PUTIH PEKAT #FFFFFF) ==================== -->
                <section id="panel-chapter-5" class="horizontal-panel w-full md:w-[calc(100vw-5rem)] md:min-w-[calc(100vw-5rem)] shrink-0 h-auto md:h-screen bg-[#ffffff] text-[#000000] p-6 sm:p-12 md:p-16 flex flex-col justify-between relative overflow-hidden">
                    
                    <!-- Header Bab -->
                    <div class="flex items-center justify-between w-full pt-4 md:pt-0">
                        <span class="p6-anim font-editorial text-2xl md:text-3xl text-[#000000]">
                            Chapter V ─ Informasi & Akses
                        </span>
                        <span class="p6-anim font-mono text-xs uppercase tracking-widest text-[#666666]">
                            HaloBK
                        </span>
                    </div>

                    <!-- Konten Tengah: FAQ & Call to Action Muncul Satu per Satu -->
                    <div class="my-12 md:my-0 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                        
                        <!-- Sisi Kiri: Tanya Jawab Penting -->
                        <div class="lg:col-span-6 space-y-4">
                            <span class="p6-anim text-xs font-mono font-bold uppercase tracking-[0.2em] text-[#666666] block">
                                TANYA JAWAB UMUM
                            </span>
                            
                            <div class="p6-anim p-5 rounded-2xl border border-[#e2e2e2] hover:border-black transition-colors space-y-2">
                                <h4 class="text-sm font-bold text-[#000000]">Apakah hasil ujian langsung keluar?</h4>
                                <p class="text-xs text-[#555555] leading-relaxed">
                                    Ya, untuk soal pilihan ganda sistem auto-grading langsung mengkalkulasi skor akhir dan lembar nilai siap diunduh setelah sesi ditutup.
                                </p>
                            </div>

                            <div class="p6-anim p-5 rounded-2xl border border-[#e2e2e2] hover:border-black transition-colors space-y-2">
                                <h4 class="text-sm font-bold text-[#000000]">Bagaimana jika koneksi internet terputus?</h4>
                                <p class="text-xs text-[#555555] leading-relaxed">
                                    Setiap jawaban otomatis tersimpan ke server. Siswa dapat login kembali dan meneruskan pengerjaan tanpa mengulang dari awal.
                                </p>
                            </div>
                        </div>

                        <!-- Sisi Kanan: Call to Action Akses Portal -->
                        <div class="lg:col-span-6 flex flex-col items-start lg:items-end text-left lg:text-right space-y-6">
                            <h2 class="text-4xl sm:text-5xl md:text-6xl font-normal leading-[1.05] tracking-tight">
                                <span class="char-split-cta-serif font-editorial italic block text-[#555555]">Mulai Sekarang.</span>
                                <span class="char-split-cta-sans font-extrabold uppercase text-[#000000] block">Masuk ke Portal.</span>
                            </h2>
                            <p class="p6-anim text-xs sm:text-sm text-[#666666] max-w-md font-light leading-relaxed">
                                Hubungkan potensi siswa dengan bimbingan terbaik hari ini. Masuk dengan akun resmi yang telah terdaftar.
                            </p>
                            <div class="p6-anim">
                                <a href="{{ $dashboardRoute }}" class="group inline-flex items-center gap-4 px-8 py-4 rounded-full border-2 border-black text-black text-xs font-mono uppercase tracking-[0.2em] hover:border-[#666666] transition-all">
                                    <span>{{ $userRoleLabel }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>

                    </div>

                    <!-- Footer Resmi (Tanpa Tahun/Tanggal) -->
                    <div class="flex flex-col sm:flex-row items-center justify-between text-xs text-[#888888] border-t border-[#e2e2e2] pt-4 font-mono gap-2">
                        <span>HALOBK &bull; SISTEM BIMBINGAN KONSELING DIGITAL</span>
                        <span>SELESAI</span>
                    </div>

                </section>


            </div>

        </div>

    </main>


    <!-- ==================== 3. SCRIPT ANIMASI GSAP HITAM PUTIH (PER-HURUF SCRUBBED & KINETIC) ==================== -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

            gsap.registerPlugin(ScrollTrigger);

            // Helper: Pecah teks menjadi kata & huruf per karakter (preserve layout & word-wrapping)
            function splitTextIntoChars(el) {
                if (!el) return [];
                if (el.dataset.charsSplit === 'true') {
                    return Array.from(el.querySelectorAll('.char-split-item'));
                }
                el.dataset.charsSplit = 'true';
                const text = el.textContent.trim();
                el.innerHTML = '';
                const words = text.split(/\s+/);
                const chars = [];

                words.forEach((word, wIdx) => {
                    const wordSpan = document.createElement('span');
                    wordSpan.className = 'char-split-word inline-block whitespace-nowrap';

                    Array.from(word).forEach((char) => {
                        const charSpan = document.createElement('span');
                        charSpan.className = 'char-split-item inline-block will-change-transform';
                        charSpan.textContent = char;
                        wordSpan.appendChild(charSpan);
                        chars.push(charSpan);
                    });

                    el.appendChild(wordSpan);

                    if (wIdx < words.length - 1) {
                        const spaceSpan = document.createElement('span');
                        spaceSpan.className = 'char-split-space inline-block';
                        spaceSpan.innerHTML = '&nbsp;';
                        el.appendChild(spaceSpan);
                    }
                });

                return chars;
            }

            const mm = gsap.matchMedia();

            // =========================================================================
            // KONDISI DESKTOP (> 768px):
            // 1. Horizontal Scroll Track Pinned (Awwwards / Khanh Nguyen Style)
            // 2. Animasi Per-Huruf Bervariasi Mengikuti Putaran Scroll Pengguna (Scrubbed)
            // 3. Kartu & Teks Pendukung Muncul Berurutan (Staggered)
            // =========================================================================
            mm.add("(min-width: 769px)", () => {
                const track = document.getElementById("horizontalTrack");
                const pinSection = document.getElementById("horizontalStoryPin");
                const verticalBar = document.getElementById("verticalScrollProgress");

                if (!track || !pinSection) return;

                const getScrollAmount = () => -(track.scrollWidth - track.clientWidth);

                // Inisialisasi Karakter Tiap Judul
                const heroSerifChars = splitTextIntoChars(document.querySelector('.char-split-hero-serif'));
                const heroSansChars = splitTextIntoChars(document.querySelector('.char-split-hero-sans'));
                const waveChars = splitTextIntoChars(document.querySelector('.char-split-wave'));
                const flipChars = splitTextIntoChars(document.querySelector('.char-split-flip'));
                const focusChars = splitTextIntoChars(document.querySelector('.char-split-focus'));
                const slideChars = splitTextIntoChars(document.querySelector('.char-split-slide'));
                const ctaSerifChars = splitTextIntoChars(document.querySelector('.char-split-cta-serif'));
                const ctaSansChars = splitTextIntoChars(document.querySelector('.char-split-cta-sans'));

                // =====================================================================
                // 1. HORIZONTAL MASTER SCROLL TWEEN
                // =====================================================================
                const horizontalTween = gsap.to(track, {
                    x: getScrollAmount,
                    ease: "none",
                    scrollTrigger: {
                        trigger: pinSection,
                        pin: true,
                        scrub: 1, // Mengikuti putaran scroll user secara real-time
                        start: "top top",
                        end: () => "+=" + (track.scrollWidth - track.clientWidth) * 1.25,
                        invalidateOnRefresh: true,
                        onUpdate: (self) => {
                            if (verticalBar) {
                                verticalBar.style.transform = `scaleY(${self.progress})`;
                            }
                        }
                    }
                });

                // =====================================================================
                // 2. PANEL 1 (HERO)
                // - Masuk saat halaman pertama dibuka
                // - Keluar (exit) saat user geser menuju Chapter I:
                //   Teks headline, paragraf, dan tombol CTA melayang ke atas & memudar elegan
                // =====================================================================
                if (heroSerifChars.length || heroSansChars.length) {
                    const heroTl = gsap.timeline({ delay: 0.1 });
                    if (heroSerifChars.length) {
                        heroTl.from(heroSerifChars, {
                            opacity: 0,
                            y: 40,
                            stagger: 0.03,
                            duration: 0.7,
                            ease: "power3.out"
                        });
                    }
                    if (heroSansChars.length) {
                        heroTl.from(heroSansChars, {
                            opacity: 0,
                            y: 35,
                            stagger: 0.025,
                            duration: 0.65,
                            ease: "power2.out"
                        }, "-=0.35");
                    }
                }

                // Elemen pendukung Hero (Badge, Paragraf, Tombol CTA)
                gsap.from(".p1-anim", {
                    opacity: 0,
                    y: 30,
                    stagger: 0.1,
                    duration: 0.8,
                    ease: "power3.out",
                    delay: 0.2
                });

                // Transisi Keluar Panel 1 saat user mulai menggeser menuju Chapter I
                // (Headline huruf, paragraf, dan tombol melayang ke atas & memudar halus dengan blur elegan)
                const heroChars = [...heroSerifChars, ...heroSansChars];
                if (heroChars.length) {
                    gsap.to(heroChars, {
                        opacity: 0,
                        y: -50,
                        filter: "blur(4px)",
                        stagger: 0.012,
                        ease: "power2.in",
                        immediateRender: false,
                        scrollTrigger: {
                            trigger: "#panel-hero",
                            containerAnimation: horizontalTween,
                            start: "left left",
                            end: "left -42%",
                            scrub: true
                        }
                    });
                }

                gsap.to(["#panel-hero p", "#panel-hero .p1-anim", "#panel-hero a"], {
                    opacity: 0,
                    y: -35,
                    filter: "blur(3px)",
                    stagger: 0.03,
                    ease: "power2.in",
                    immediateRender: false,
                    scrollTrigger: {
                        trigger: "#panel-hero",
                        containerAnimation: horizontalTween,
                        start: "left left",
                        end: "left -38%",
                        scrub: true
                    }
                });

                // =====================================================================
                // 3. PANEL 2 (CHAPTER I ─ FILOSOFI & RUANG AMAN)
                // - Enter: Huruf wave & tilt kinetik masuk, kartu & teks masuk
                // - Rest: Diam, jernih & nyaman dibaca saat di tengah layar
                // - Exit: Saat user geser melewatinya, huruf & kartu melayang ke atas,
                //         memudar dan soft blur menghilang sebelum menyentuh tepi kiri
                // =====================================================================
                if (waveChars.length) {
                    // Masuk
                    gsap.from(waveChars, {
                        opacity: 0,
                        y: 70,
                        rotateZ: 10,
                        scale: 0.88,
                        stagger: { each: 0.02, ease: "power1.out" },
                        ease: "power2.out",
                        scrollTrigger: {
                            trigger: "#panel-chapter-1",
                            containerAnimation: horizontalTween,
                            start: "left 85%",
                            end: "left 15%",
                            scrub: true
                        }
                    });

                    // Keluar saat dilewati
                    gsap.to(waveChars, {
                        opacity: 0,
                        y: -45,
                        rotateZ: -6,
                        filter: "blur(4px)",
                        stagger: 0.012,
                        ease: "power2.in",
                        immediateRender: false,
                        scrollTrigger: {
                            trigger: "#panel-chapter-1",
                            containerAnimation: horizontalTween,
                            start: "left 2%",
                            end: "left -42%",
                            scrub: true
                        }
                    });
                }

                // Komponen & Card Chapter 1: Masuk
                gsap.from("#panel-chapter-1 .p2-anim", {
                    opacity: 0,
                    y: 45,
                    stagger: 0.12,
                    ease: "power2.out",
                    scrollTrigger: {
                        trigger: "#panel-chapter-1",
                        containerAnimation: horizontalTween,
                        start: "left 75%",
                        end: "left 20%",
                        scrub: true
                    }
                });

                // Komponen & Card Chapter 1: Keluar saat dilewati
                gsap.to("#panel-chapter-1 .p2-anim", {
                    opacity: 0,
                    y: -38,
                    filter: "blur(4px)",
                    stagger: 0.05,
                    ease: "power2.in",
                    immediateRender: false,
                    scrollTrigger: {
                        trigger: "#panel-chapter-1",
                        containerAnimation: horizontalTween,
                        start: "left 2%",
                        end: "left -40%",
                        scrub: true
                    }
                });

                // =====================================================================
                // 4. PANEL 3 (CHAPTER II ─ CBT & ASESMEN DIGITAL)
                // - Enter: 3D flip chars & mockup CBT masuk
                // - Rest: Stabil di tengah
                // - Exit: Melayang ke atas, memudar halus saat digeser lewat
                // =====================================================================
                if (flipChars.length) {
                    // Masuk
                    gsap.from(flipChars, {
                        opacity: 0,
                        y: 50,
                        rotateX: -90,
                        transformOrigin: "50% 100% -25px",
                        filter: "blur(6px)",
                        stagger: { each: 0.02, ease: "power1.out" },
                        ease: "power2.out",
                        scrollTrigger: {
                            trigger: "#panel-chapter-2",
                            containerAnimation: horizontalTween,
                            start: "left 85%",
                            end: "left 15%",
                            scrub: true
                        }
                    });

                    // Keluar saat dilewati
                    gsap.to(flipChars, {
                        opacity: 0,
                        y: -45,
                        rotateX: 45,
                        filter: "blur(4px)",
                        stagger: 0.012,
                        ease: "power2.in",
                        immediateRender: false,
                        scrollTrigger: {
                            trigger: "#panel-chapter-2",
                            containerAnimation: horizontalTween,
                            start: "left 2%",
                            end: "left -42%",
                            scrub: true
                        }
                    });
                }

                // Mockup & Opsi Soal CBT Chapter 2: Masuk
                gsap.from("#panel-chapter-2 .p3-anim", {
                    opacity: 0,
                    y: 45,
                    stagger: 0.12,
                    ease: "power2.out",
                    scrollTrigger: {
                        trigger: "#panel-chapter-2",
                        containerAnimation: horizontalTween,
                        start: "left 75%",
                        end: "left 20%",
                        scrub: true
                    }
                });

                // Mockup & Opsi Soal CBT Chapter 2: Keluar saat dilewati
                gsap.to("#panel-chapter-2 .p3-anim", {
                    opacity: 0,
                    y: -38,
                    filter: "blur(4px)",
                    stagger: 0.05,
                    ease: "power2.in",
                    immediateRender: false,
                    scrollTrigger: {
                        trigger: "#panel-chapter-2",
                        containerAnimation: horizontalTween,
                        start: "left 2%",
                        end: "left -40%",
                        scrub: true
                    }
                });

                // =====================================================================
                // 5. PANEL 4 (CHAPTER III ─ DIAGNOSTIK SOAL RAWAN)
                // - Enter: Analytic focus zoom & snap
                // - Rest: Stabil di tengah
                // - Exit: Melayang ke atas, memudar halus saat digeser lewat
                // =====================================================================
                if (focusChars.length) {
                    // Masuk
                    gsap.from(focusChars, {
                        opacity: 0,
                        scale: 1.8,
                        y: -20,
                        filter: "blur(8px)",
                        letterSpacing: "0.1em",
                        stagger: { each: 0.02, ease: "power2.inOut" },
                        ease: "power2.out",
                        scrollTrigger: {
                            trigger: "#panel-chapter-3",
                            containerAnimation: horizontalTween,
                            start: "left 85%",
                            end: "left 15%",
                            scrub: true
                        }
                    });

                    // Keluar saat dilewati
                    gsap.to(focusChars, {
                        opacity: 0,
                        y: -45,
                        scale: 0.85,
                        filter: "blur(4px)",
                        stagger: 0.012,
                        ease: "power2.in",
                        immediateRender: false,
                        scrollTrigger: {
                            trigger: "#panel-chapter-3",
                            containerAnimation: horizontalTween,
                            start: "left 2%",
                            end: "left -42%",
                            scrub: true
                        }
                    });
                }

                // Baris Soal Rawan & Tuntas: Masuk
                gsap.from("#panel-chapter-3 .p4-anim", {
                    opacity: 0,
                    y: 45,
                    stagger: 0.12,
                    ease: "power2.out",
                    scrollTrigger: {
                        trigger: "#panel-chapter-3",
                        containerAnimation: horizontalTween,
                        start: "left 75%",
                        end: "left 20%",
                        scrub: true
                    }
                });

                // Baris Soal Rawan & Tuntas: Keluar saat dilewati
                gsap.to("#panel-chapter-3 .p4-anim", {
                    opacity: 0,
                    y: -38,
                    filter: "blur(4px)",
                    stagger: 0.05,
                    ease: "power2.in",
                    immediateRender: false,
                    scrollTrigger: {
                        trigger: "#panel-chapter-3",
                        containerAnimation: horizontalTween,
                        start: "left 2%",
                        end: "left -40%",
                        scrub: true
                    }
                });

                // =====================================================================
                // 6. PANEL 5 (CHAPTER IV ─ ALUR MODUL & LAYANAN)
                // - Enter: Isometric clip slide-up & 3 kartu langkah
                // - Rest: Stabil di tengah
                // - Exit: Melayang ke atas, memudar halus saat digeser menuju Chapter V
                // =====================================================================
                if (slideChars.length) {
                    // Masuk
                    gsap.from(slideChars, {
                        opacity: 0,
                        yPercent: 120,
                        rotateY: -35,
                        stagger: { each: 0.025, ease: "power1.inOut" },
                        ease: "power3.out",
                        scrollTrigger: {
                            trigger: "#panel-chapter-4",
                            containerAnimation: horizontalTween,
                            start: "left 85%",
                            end: "left 20%",
                            scrub: true
                        }
                    });

                    // Keluar saat dilewati
                    gsap.to(slideChars, {
                        opacity: 0,
                        yPercent: -100,
                        filter: "blur(4px)",
                        stagger: 0.015,
                        ease: "power2.in",
                        immediateRender: false,
                        scrollTrigger: {
                            trigger: "#panel-chapter-4",
                            containerAnimation: horizontalTween,
                            start: "left 2%",
                            end: "left -42%",
                            scrub: true
                        }
                    });
                }

                // 3 Card Langkah Alur: Masuk
                gsap.from("#panel-chapter-4 .p5-anim", {
                    opacity: 0,
                    y: 55,
                    stagger: 0.15,
                    ease: "power2.out",
                    scrollTrigger: {
                        trigger: "#panel-chapter-4",
                        containerAnimation: horizontalTween,
                        start: "left 75%",
                        end: "left 20%",
                        scrub: true
                    }
                });

                // 3 Card Langkah Alur: Keluar saat dilewati
                gsap.to("#panel-chapter-4 .p5-anim", {
                    opacity: 0,
                    y: -42,
                    filter: "blur(4px)",
                    stagger: 0.08,
                    ease: "power2.in",
                    immediateRender: false,
                    scrollTrigger: {
                        trigger: "#panel-chapter-4",
                        containerAnimation: horizontalTween,
                        start: "left 2%",
                        end: "left -40%",
                        scrub: true
                    }
                });

                // =====================================================================
                // 7. PANEL 6 (CHAPTER V ─ PENUTUP & CTA)
                // - Panel final di ujung track horizontal, masuk elegan dan menetap
                // =====================================================================
                if (ctaSerifChars.length) {
                    gsap.from(ctaSerifChars, {
                        opacity: 0,
                        y: 50,
                        skewX: -20,
                        stagger: { each: 0.03, ease: "sine.inOut" },
                        ease: "power2.out",
                        scrollTrigger: {
                            trigger: "#panel-chapter-5",
                            containerAnimation: horizontalTween,
                            start: "left 85%",
                            end: "left 25%",
                            scrub: true
                        }
                    });
                }

                if (ctaSansChars.length) {
                    gsap.from(ctaSansChars, {
                        opacity: 0,
                        scale: 0.35,
                        y: -25,
                        stagger: { each: 0.03, from: "start" },
                        ease: "back.out(1.5)",
                        scrollTrigger: {
                            trigger: "#panel-chapter-5",
                            containerAnimation: horizontalTween,
                            start: "left 75%",
                            end: "left 15%",
                            scrub: true
                        }
                    });
                }

                gsap.from("#panel-chapter-5 .p6-anim", {
                    opacity: 0,
                    y: 45,
                    stagger: 0.14,
                    ease: "power2.out",
                    scrollTrigger: {
                        trigger: "#panel-chapter-5",
                        containerAnimation: horizontalTween,
                        start: "left 75%",
                        end: "left 20%",
                        scrub: true
                    }
                });

                window.addEventListener('resize', () => {
                    ScrollTrigger.refresh();
                });

            });


            // =========================================================================
            // KONDISI MOBILE (<= 768px):
            // 1. Scroll horizontal dimatikan (vertikal alami 1-kolom)
            // 2. Animasi teks per-huruf vertikal mengikuti scroll (scrubbed)
            // 3. Teks & kartu lama memudar keluar saat digulir melewati layar atas
            // =========================================================================
            mm.add("(max-width: 768px)", () => {
                const track = document.getElementById("horizontalTrack");
                if (track) {
                    gsap.set(track, { clearProps: "all" });
                }

                // Inisialisasi Karakter untuk Mobile
                const waveChars = splitTextIntoChars(document.querySelector('.char-split-wave'));
                const flipChars = splitTextIntoChars(document.querySelector('.char-split-flip'));
                const focusChars = splitTextIntoChars(document.querySelector('.char-split-focus'));
                const slideChars = splitTextIntoChars(document.querySelector('.char-split-slide'));
                const ctaSerifChars = splitTextIntoChars(document.querySelector('.char-split-cta-serif'));
                const ctaSansChars = splitTextIntoChars(document.querySelector('.char-split-cta-sans'));

                const mobileSplitItems = [
                    { chars: waveChars, trigger: '.char-split-wave', props: { opacity: 0, y: 35, rotateZ: 8, stagger: 0.015 } },
                    { chars: flipChars, trigger: '.char-split-flip', props: { opacity: 0, y: 30, rotateX: -60, filter: "blur(4px)", stagger: 0.015 } },
                    { chars: focusChars, trigger: '.char-split-focus', props: { opacity: 0, scale: 1.4, filter: "blur(6px)", stagger: 0.015 } },
                    { chars: slideChars, trigger: '.char-split-slide', props: { opacity: 0, yPercent: 100, stagger: 0.02 } },
                    { chars: ctaSerifChars, trigger: '.char-split-cta-serif', props: { opacity: 0, y: 30, skewX: -15, stagger: 0.02 } },
                    { chars: ctaSansChars, trigger: '.char-split-cta-sans', props: { opacity: 0, scale: 0.5, stagger: 0.02 } },
                ];

                mobileSplitItems.forEach(item => {
                    if (item.chars && item.chars.length) {
                        const triggerEl = document.querySelector(item.trigger);
                        if (triggerEl) {
                            // Masuk
                            gsap.from(item.chars, {
                                ...item.props,
                                ease: "power2.out",
                                scrollTrigger: {
                                    trigger: triggerEl,
                                    start: "top 85%",
                                    end: "top 45%",
                                    scrub: true
                                }
                            });

                            // Keluar saat digulir melewati atas layar
                            gsap.to(item.chars, {
                                opacity: 0,
                                y: -25,
                                filter: "blur(3px)",
                                stagger: 0.01,
                                ease: "power1.inOut",
                                scrollTrigger: {
                                    trigger: triggerEl,
                                    start: "bottom 30%",
                                    end: "bottom -15%",
                                    scrub: true
                                }
                            });
                        }
                    }
                });

                // Kartu dan teks lainnya muncul bertahap saat scroll di mobile
                const mobileElements = [
                    ".p1-anim", ".p2-anim", ".p3-anim", ".p4-anim", ".p5-anim", ".p6-anim"
                ];

                mobileElements.forEach(selector => {
                    gsap.utils.toArray(selector).forEach(el => {
                        gsap.from(el, {
                            scrollTrigger: {
                                trigger: el,
                                start: "top 88%",
                                toggleActions: "play reverse play reverse"
                            },
                            opacity: 0,
                            y: 20,
                            duration: 0.45,
                            ease: "power1.out"
                        });
                    });
                });

                // Progress bar mobile
                window.addEventListener('scroll', () => {
                    const winScroll = document.documentElement.scrollTop || document.body.scrollTop;
                    const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                    const scrolled = height > 0 ? (winScroll / height) : 0;

                    const mobileBar = document.getElementById("mobileScrollProgress");
                    if (mobileBar) {
                        mobileBar.style.transform = `scaleX(${scrolled})`;
                    }
                }, { passive: true });

            });

        });
    </script>
</body>
</html>
