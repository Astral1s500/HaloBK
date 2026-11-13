<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HaloBK ─ Sistem Layanan Bimbingan & Konseling Digital</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ==========================================================================
           HALOBK LANDING PAGE - SATU HALAMAN BERSIH & SELARAS DENGAN SISTEM
           Theme: Sky Cloud Modern (Biru Langit, Lembut, Elegan & Presisi)
           ========================================================================== */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background: linear-gradient(180deg, #dbeafe 0%, #e0f2fe 32%, #f0f9ff 70%, #ffffff 100%);
            color: #0f172a;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow-x: hidden;
        }

        /* Ambient Cloud Elements (Selaras dengan Login & Dashboard) */
        .ambient-cloud-1 {
            position: absolute;
            top: -120px;
            left: -100px;
            width: 480px;
            height: 480px;
            background: radial-gradient(circle, rgba(186, 230, 253, 0.6) 0%, rgba(224, 242, 254, 0) 70%);
            border-radius: 50%;
            filter: blur(60px);
            pointer-events: none;
            z-index: 1;
        }

        .ambient-cloud-2 {
            position: absolute;
            bottom: -100px;
            right: -80px;
            width: 540px;
            height: 540px;
            background: radial-gradient(circle, rgba(191, 219, 254, 0.5) 0%, rgba(240, 249, 255, 0) 70%);
            border-radius: 50%;
            filter: blur(70px);
            pointer-events: none;
            z-index: 1;
        }

        /* Top Header Navbar */
        .landing-header {
            width: 100%;
            max-width: 1240px;
            margin: 0 auto;
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            z-index: 20;
        }

        .brand-box {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            text-decoration: none;
            color: inherit;
        }

        .brand-logo-frame {
            width: 44px;
            height: 44px;
            background: #ffffff;
            border-radius: 14px;
            border: 1.5px solid rgba(186, 230, 253, 0.8);
            box-shadow: 0 4px 14px rgba(2, 132, 199, 0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 7px;
        }

        .brand-logo-frame img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .brand-name {
            font-size: 1.25rem;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: -0.02em;
            line-height: 1.1;
        }

        .brand-sub {
            font-size: 0.75rem;
            font-weight: 600;
            color: #0284c7;
        }

        /* Tombol Masuk di Header */
        .btn-header-login {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #ffffff;
            color: #0369a1;
            font-weight: 700;
            font-size: 0.85rem;
            padding: 0.6rem 1.35rem;
            border-radius: 9999px;
            border: 1.5px solid #bae6fd;
            text-decoration: none;
            box-shadow: 0 4px 14px rgba(2, 132, 199, 0.08);
            transition: all 0.2s ease;
        }

        .btn-header-login:hover {
            background: #0284c7;
            color: #ffffff;
            border-color: #0284c7;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(2, 132, 199, 0.25);
        }

        /* Hero Wrapper (Single Page Container) */
        .hero-container {
            width: 100%;
            max-width: 1140px;
            margin: 0 auto;
            padding: 1.5rem 1.5rem 2.5rem 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            position: relative;
            z-index: 10;
        }

        /* Badge Pills */
        .hero-pill-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 1.15rem;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border: 1.5px solid rgba(186, 230, 253, 0.9);
            border-radius: 9999px;
            font-size: 0.78rem;
            font-weight: 700;
            color: #0369a1;
            box-shadow: 0 2px 10px rgba(2, 132, 199, 0.06);
            margin-bottom: 1.25rem;
        }

        /* Heading Utama */
        .hero-title {
            font-size: 2.75rem;
            font-weight: 900;
            color: #0f172a;
            line-height: 1.18;
            letter-spacing: -0.03em;
            max-width: 860px;
            margin-bottom: 1rem;
        }

        .hero-title .highlight-blue {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-desc {
            font-size: 1rem;
            color: #475569;
            line-height: 1.6;
            max-width: 660px;
            margin-bottom: 2rem;
            font-weight: 500;
        }

        /* Tombol Utama Langsung Menuju Login */
        .cta-action-group {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.25rem;
            width: 100%;
            max-width: 480px;
            margin-bottom: 2.75rem;
        }

        .btn-cta-primary {
            width: 100%;
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            color: #ffffff !important;
            font-size: 1.05rem;
            font-weight: 800;
            padding: 1rem 2rem;
            border-radius: 18px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.65rem;
            text-decoration: none;
            box-shadow: 0 10px 25px -4px rgba(2, 132, 199, 0.38);
            transition: all 0.25s ease;
            cursor: pointer;
        }

        .btn-cta-primary:hover {
            background: linear-gradient(135deg, #0369a1 0%, #075985 100%);
            transform: translateY(-2px);
            box-shadow: 0 14px 30px -4px rgba(2, 132, 199, 0.45);
        }

        /* 3 Role Portal Gateway Cards (Siswa, Guru BK, Admin) */
        .role-cards-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.25rem;
            width: 100%;
            max-width: 980px;
        }

        .role-card-item {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            padding: 1.35rem 1.25rem;
            text-align: left;
            text-decoration: none;
            color: inherit;
            box-shadow: 0 6px 20px -3px rgba(2, 132, 199, 0.05), 0 0 0 1px rgba(186, 230, 253, 0.3);
            transition: all 0.25s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .role-card-item:hover {
            transform: translateY(-3px);
            border-color: #7dd3fc;
            background: #ffffff;
            box-shadow: 0 12px 28px -5px rgba(2, 132, 199, 0.15), 0 0 0 1px rgba(14, 165, 233, 0.4);
        }

        .role-icon-box {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-bottom: 0.9rem;
        }

        .role-siswa .role-icon-box {
            background: #eff6ff;
            color: #0284c7;
            border: 1px solid #bfdbfe;
        }

        .role-guru .role-icon-box {
            background: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
        }

        .role-admin .role-icon-box {
            background: #f5f3ff;
            color: #7c3aed;
            border: 1px solid #ddd6fe;
        }

        .role-badge-tag {
            font-size: 0.68rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 0.2rem 0.55rem;
            border-radius: 6px;
            display: inline-block;
            margin-bottom: 0.4rem;
        }

        .role-siswa .role-badge-tag {
            background: #e0f2fe;
            color: #0369a1;
        }

        .role-guru .role-badge-tag {
            background: #dcfce7;
            color: #15803d;
        }

        .role-admin .role-badge-tag {
            background: #ede9fe;
            color: #6d28d9;
        }

        .role-title {
            font-size: 1rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 0.35rem;
        }

        .role-desc {
            font-size: 0.78rem;
            color: #64748b;
            line-height: 1.45;
            margin-bottom: 1rem;
        }

        .role-action-link {
            font-size: 0.75rem;
            font-weight: 700;
            color: #0284c7;
            display: flex;
            align-items: center;
            gap: 0.35rem;
            margin-top: auto;
        }

        .role-card-item:hover .role-action-link {
            color: #0369a1;
            text-decoration: underline;
        }

        /* Footer Sederhana */
        .landing-footer {
            width: 100%;
            padding: 1.25rem 1.5rem;
            text-align: center;
            font-size: 0.78rem;
            color: #64748b;
            border-top: 1px solid rgba(224, 242, 254, 0.8);
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(8px);
            position: relative;
            z-index: 20;
        }

        /* Responsive Breakpoints */
        @media (max-width: 900px) {
            .hero-title {
                font-size: 2.15rem;
            }
            .role-cards-grid {
                grid-template-columns: 1fr;
                max-width: 420px;
            }
        }

        @media (max-width: 480px) {
            .landing-header {
                padding: 1rem;
            }
            .hero-title {
                font-size: 1.75rem;
            }
            .hero-desc {
                font-size: 0.88rem;
            }
            .brand-name {
                font-size: 1.1rem;
            }
            .btn-cta-primary {
                font-size: 0.95rem;
                padding: 0.85rem 1.5rem;
            }
        }
    </style>
</head>
<body>

    <!-- Ambient Glowing Clouds -->
    <div class="ambient-cloud-1"></div>
    <div class="ambient-cloud-2"></div>

    <!-- 1. HEADER BAR -->
    <header class="landing-header">
        <a href="{{ route('landing') }}" class="brand-box">
            <div class="brand-logo-frame">
                <img src="{{ asset('images/logo.png') }}" alt="HaloBK Logo">
            </div>
            <div>
                <h1 class="brand-name">HaloBK</h1>
                <p class="brand-sub">Layanan Terpadu Bimbingan &amp; Konseling</p>
            </div>
        </a>

        @php
            $isLoggedIn = false;
            $destinationRoute = route('login');
            $buttonText = 'Masuk ke Sistem';

            if (Auth::guard('admin')->check()) {
                $isLoggedIn = true;
                $destinationRoute = route('admin.dashboard');
                $buttonText = 'Dashboard Admin';
            } elseif (Auth::guard('guru')->check()) {
                $isLoggedIn = true;
                $destinationRoute = route('guru.dashboard');
                $buttonText = 'Dashboard Guru BK';
            } elseif (Auth::guard('siswa')->check()) {
                $isLoggedIn = true;
                $destinationRoute = route('siswa.dashboard');
                $buttonText = 'Dashboard Siswa';
            }
        @endphp

        <a href="{{ $destinationRoute }}" class="btn-header-login">
            <span>{{ $buttonText }}</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
        </a>
    </header>

    <!-- 2. HERO CONTENT (SATU HALAMAN LANGSUNG KE LOGIN) -->
    <main class="hero-container">
        <!-- Badge Pill -->
        <div class="hero-pill-badge">
            <span>✨</span>
            <span>Platform Bimbingan &amp; Asesmen Digital Sekolah</span>
        </div>

        <!-- Headline Utama -->
        <h2 class="hero-title">
            Pendampingan Siswa Lebih Nyaman, <span class="highlight-blue">Terarah &amp; Modern</span>
        </h2>

        <!-- Deskripsi Ringkas -->
        <p class="hero-desc">
            Satu ekosistem terpadu untuk asesmen psikologis minat-bakat, materi edukasi bimbingan, serta pemantauan perkembangan siswa secara menyeluruh dan terjaga privasinya.
        </p>

        <!-- Tombol CTA Utama (Langsung Menuju Halaman Login) -->
        <div class="cta-action-group">
            <a href="{{ $destinationRoute }}" class="btn-cta-primary">
                <span>{{ $isLoggedIn ? 'Buka Dashboard Anda' : 'Masuk ke Portal HaloBK' }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
        </div>

        <!-- 3 Role Portal Gateway (Mengarah Langsung ke Login Sesuai Hak Akses) -->
        <div class="role-cards-grid">
            <!-- Role 1: Siswa -->
            <a href="{{ route('login') }}" class="role-card-item role-siswa">
                <div>
                    <div class="role-icon-box">
                        <span>🎓</span>
                    </div>
                    <span class="role-badge-tag">Portal Siswa</span>
                    <h3 class="role-title">Siswa Sekolah</h3>
                    <p class="role-desc">
                        Kerjakan asesmen minat &amp; gaya belajar, pelajari materi bimbingan, dan pantau progres hasil evaluasi.
                    </p>
                </div>
                <div class="role-action-link">
                    <span>Masuk dengan NISN &rarr;</span>
                </div>
            </a>

            <!-- Role 2: Guru BK -->
            <a href="{{ route('login') }}" class="role-card-item role-guru">
                <div>
                    <div class="role-icon-box">
                        <span>👩‍🏫</span>
                    </div>
                    <span class="role-badge-tag">Portal Guru BK</span>
                    <h3 class="role-title">Guru BK Pembimbing</h3>
                    <p class="role-desc">
                        Kelola kelas binaan, buat instrumen kuesioner / tes CBT, unggah modul materi, dan koreksi hasil asesmen.
                    </p>
                </div>
                <div class="role-action-link">
                    <span>Masuk dengan NIP &rarr;</span>
                </div>
            </a>

            <!-- Role 3: Administrator -->
            <a href="{{ route('login') }}" class="role-card-item role-admin">
                <div>
                    <div class="role-icon-box">
                        <span>🛡️</span>
                    </div>
                    <span class="role-badge-tag">Portal Admin</span>
                    <h3 class="role-title">Administrator</h3>
                    <p class="role-desc">
                        Manajemen master data Guru BK, impor &amp; ekspor data siswa via Excel, serta kelola sistem sekolah.
                    </p>
                </div>
                <div class="role-action-link">
                    <span>Masuk Admin &rarr;</span>
                </div>
            </a>
        </div>
    </main>

    <!-- 3. FOOTER -->
    <footer class="landing-footer">
        <p>&copy; {{ date('Y') }} <strong>HaloBK</strong> &bull; Sistem Layanan Bimbingan &amp; Konseling Digital Sekolah. Semua Hak Dilindungi.</p>
    </footer>

</body>
</html>
