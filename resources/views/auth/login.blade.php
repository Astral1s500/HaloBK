<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk ke HaloBK — Layanan Bimbingan & Konseling</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    
    <!-- Fonts & Compiled Assets via Vite -->
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Base Setup & Background Sky Cloud */
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background: linear-gradient(180deg, #dbeafe 0%, #e0f2fe 30%, #f0f9ff 65%, #ffffff 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: #1e293b;
            position: relative;
            overflow-x: hidden;
        }

        /* Ambient Cloud Elements */
        .ambient-cloud-1 {
            position: absolute;
            top: -120px;
            left: -100px;
            width: 480px;
            height: 480px;
            background: radial-gradient(circle, rgba(186, 230, 253, 0.55) 0%, rgba(224, 242, 254, 0) 70%);
            border-radius: 50%;
            filter: blur(50px);
            pointer-events: none;
            z-index: 1;
        }
        .ambient-cloud-2 {
            position: absolute;
            bottom: -80px;
            right: -80px;
            width: 520px;
            height: 520px;
            background: radial-gradient(circle, rgba(191, 219, 254, 0.45) 0%, rgba(240, 249, 255, 0) 70%);
            border-radius: 50%;
            filter: blur(60px);
            pointer-events: none;
            z-index: 1;
        }

        /* Header Bar */
        .login-header {
            width: 100%;
            padding: 1.25rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 10;
        }
        .brand-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: inherit;
        }
        .brand-icon-box {
            width: 40px;
            height: 40px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.12);
            border: 1px solid rgba(224, 242, 254, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 6px;
        }
        .brand-icon-box img {
            width: 100%;
            height: 100%;
            object-contain: contain;
        }
        .brand-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.2;
        }
        .brand-subtitle {
            font-size: 0.75rem;
            font-weight: 500;
            color: #0284c7;
        }
        .portal-badge {
            font-size: 0.75rem;
            font-weight: 600;
            color: #0369a1;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(186, 230, 253, 0.7);
            padding: 0.4rem 0.9rem;
            border-radius: 9999px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
        }

        /* Main Container */
        .login-main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem 1rem;
            position: relative;
            z-index: 10;
        }

        /* Glassmorphism Card (Centered, Fixed Proportional Max-Width) */
        .login-card {
            width: 100%;
            max-width: 420px;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.9);
            box-shadow: 0 20px 40px -15px rgba(2, 132, 199, 0.12), 0 0 0 1px rgba(186, 230, 253, 0.35);
            border-radius: 28px;
            padding: 2.25rem 2rem;
            margin: 0 auto;
        }

        /* Card Header Logo */
        .card-logo-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .card-logo-box {
            width: 64px;
            height: 64px;
            background: #ffffff;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
            margin-bottom: 1rem;
            box-shadow: 0 8px 24px -4px rgba(2, 132, 199, 0.15);
            border: 1px solid rgba(224, 242, 254, 0.95);
        }
        .card-logo-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .card-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            letter-spacing: -0.02em;
        }
        .card-desc {
            font-size: 0.8rem;
            color: #64748b;
            margin-top: 0.35rem;
            line-height: 1.45;
        }

        /* Alerts */
        .alert-box {
            padding: 0.75rem 1rem;
            border-radius: 12px;
            font-size: 0.8rem;
            margin-bottom: 1rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .alert-error {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
        }
        .alert-info {
            background-color: #f0f9ff;
            border: 1px solid #bae6fd;
            color: #0369a1;
        }

        /* Form Inputs */
        .form-group {
            margin-bottom: 1.1rem;
        }
        .form-label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.4rem;
            margin-left: 0.2rem;
        }
        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-icon {
            position: absolute;
            left: 14px;
            width: 18px;
            height: 18px;
            color: #94a3b8;
            pointer-events: none;
        }
        .form-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.65rem;
            background-color: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            font-size: 0.875rem;
            color: #0f172a;
            transition: all 0.2s ease;
            outline: none;
        }
        .form-input:focus {
            background-color: #ffffff;
            border-color: #0284c7;
            box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.12);
        }
        .form-input::placeholder {
            color: #94a3b8;
            font-size: 0.825rem;
        }
        .password-toggle-btn {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.15s;
        }
        .password-toggle-btn:hover {
            color: #0284c7;
        }

        /* Remember Me & Help */
        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.75rem;
            margin-bottom: 1.25rem;
            color: #64748b;
        }
        .remember-checkbox {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            cursor: pointer;
            user-select: none;
            font-weight: 500;
        }
        .remember-checkbox input[type="checkbox"] {
            accent-color: #0284c7;
            width: 15px;
            height: 15px;
            cursor: pointer;
        }
        .help-text {
            color: #64748b;
            text-decoration: none;
            font-size: 0.72rem;
            cursor: help;
            transition: color 0.15s;
        }
        .help-text:hover {
            color: #0284c7;
        }

        /* Submit Button */
        .submit-btn {
            width: 100%;
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            color: #ffffff;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 0.75rem 1.25rem;
            border-radius: 14px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 4px 14px rgba(2, 132, 199, 0.3);
            transition: all 0.2s ease;
        }
        .submit-btn:hover {
            background: linear-gradient(135deg, #0369a1 0%, #075985 100%);
            box-shadow: 0 6px 18px rgba(2, 132, 199, 0.4);
            transform: translateY(-1px);
        }
        .submit-btn:active {
            transform: translateY(0);
        }

        /* Role Guidance Info */
        .role-hint {
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #f1f5f9;
            text-align: center;
            font-size: 0.72rem;
            color: #64748b;
            line-height: 1.4;
        }
        .role-hint strong {
            color: #334155;
        }

        /* Footer */
        .login-footer {
            width: 100%;
            padding: 1rem;
            text-align: center;
            font-size: 0.75rem;
            color: #64748b;
            position: relative;
            z-index: 10;
        }

        @media (max-width: 640px) {
            .portal-badge {
                display: none;
            }
            .login-header {
                padding: 1rem;
            }
            .login-card {
                padding: 1.75rem 1.25rem;
                border-radius: 24px;
            }
        }
    </style>
</head>
<body>

    <!-- Ambient Ambient Blur Elements -->
    <div class="ambient-cloud-1"></div>
    <div class="ambient-cloud-2"></div>

    <!-- Header Bersih -->
    <header class="login-header">
        <a href="{{ url('/') }}" class="brand-link">
            <div class="brand-icon-box">
                <img src="{{ asset('images/logo.png') }}" alt="Logo HaloBK">
            </div>
            <div>
                <div class="brand-title">HaloBK</div>
                <div class="brand-subtitle">Bimbingan & Konseling</div>
            </div>
        </a>
        <div class="portal-badge">
            Portal Akses Terpadu
        </div>
    </header>

    <!-- Konten Utama Tengah -->
    <main class="login-main">
        <div class="login-card">
            
            <!-- Box Logo Tengah -->
            <div class="card-logo-container">
                <div class="card-logo-box">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo HaloBK">
                </div>
                <h1 class="card-title">
                    Masuk dengan NISN
                </h1>
                <p class="card-desc">
                    Silakan masukkan akun Anda untuk mengakses layanan bimbingan konseling digital.
                </p>
            </div>

            <!-- Notifikasi Error / Info -->
            @if(session('error'))
                <div class="alert-box alert-error">
                    <svg style="width:16px;height:16px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('info'))
                <div class="alert-box alert-info">
                    <svg style="width:16px;height:16px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="alert-box alert-error" style="display:block;">
                    <ul style="margin:0;padding-left:1.2rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Login -->
            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                <!-- Input Identifier -->
                <div class="form-group">
                    <label for="identifier" class="form-label">
                        NISN / Identitas Akun
                    </label>
                    <div class="input-wrapper">
                        <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <input 
                            type="text" 
                            id="identifier" 
                            name="identifier" 
                            value="{{ old('identifier') }}" 
                            placeholder="Masukkan NISN / NIP / Username"
                            required 
                            autofocus
                            class="form-input"
                        >
                    </div>
                </div>

                <!-- Input Password -->
                <div class="form-group">
                    <label for="password" class="form-label">
                        Kata Sandi
                    </label>
                    <div class="input-wrapper">
                        <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="Masukkan kata sandi"
                            required
                            class="form-input"
                            style="padding-right: 2.75rem;"
                        >
                        <button type="button" id="togglePasswordBtn" class="password-toggle-btn" title="Lihat kata sandi">
                            <svg id="eyeIcon" style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Bantuan -->
                <div class="form-options">
                    <label class="remember-checkbox">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span>Ingat saya</span>
                    </label>
                    <span class="help-text" title="Hubungi Guru BK atau Admin sekolah jika kendala akun">
                        Bantuan masuk?
                    </span>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="submit-btn">
                    <span>Masuk ke Akun</span>
                    <svg style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>

            <!-- Petunjuk Role -->
            <div class="role-hint">
                Siswa: <strong>NISN</strong> &bull; Guru: <strong>NIP</strong> &bull; Admin: <strong>Username</strong>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="login-footer">
        &copy; {{ date('Y') }} HaloBK &bull; Sistem Layanan Bimbingan & Konseling Digital
    </footer>

    <!-- Script Toggle Password & Session Reset -->
    <script>
        const togglePasswordBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('password');

        if (togglePasswordBtn && passwordInput) {
            togglePasswordBtn.addEventListener('click', function () {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                this.style.color = isPassword ? '#0284c7' : '#94a3b8';
            });
        }

        try {
            sessionStorage.removeItem('halobk_tour_shown_guru');
            sessionStorage.removeItem('halobk_tour_shown_siswa');
            sessionStorage.removeItem('halobk_tour_shown_admin');
        } catch (e) {}
    </script>
</body>
</html>
