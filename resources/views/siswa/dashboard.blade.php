@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@push('styles')
<link rel="stylesheet" href="{{ asset('vendor/driverjs/driver.css') }}">
<style>
    /* Styling Solid Sky Cloud Siswa (Anti-Gagal & Ramah Siswa) */
    .hero-siswa-sky {
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

    /* Card Onboarding Khusus Siswa Belum Punya Kelas */
    .onboarding-card {
        background: #ffffff;
        border: 1.5px solid #e0f2fe;
        border-radius: 28px;
        box-shadow: 0 20px 45px -10px rgba(2, 132, 199, 0.12), 0 0 0 1px rgba(186, 230, 253, 0.4);
        padding: 2.75rem 2.25rem;
        max-width: 540px;
        margin: 1.5rem auto 3rem auto;
        text-align: center;
        position: relative;
    }

    .onboarding-icon-box {
        width: 76px;
        height: 76px;
        border-radius: 24px;
        background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
        color: #0284c7;
        border: 1.5px solid #7dd3fc;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem auto;
        box-shadow: 0 10px 25px -5px rgba(2, 132, 199, 0.2);
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
        transition: all 0.2s ease;
        outline: none;
    }
    .input-code-huge:focus {
        border-color: #0284c7;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.15);
    }
    .input-code-huge::placeholder {
        color: #94a3b8;
        font-weight: 500;
        letter-spacing: 0.05em;
        text-transform: none;
        font-size: 0.95rem;
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
        transition: all 0.2s ease;
    }
    .btn-join-huge:hover {
        background: linear-gradient(135deg, #0369a1 0%, #075985 100%);
        box-shadow: 0 8px 24px rgba(2, 132, 199, 0.4);
        transform: translateY(-1px);
    }

    /* Cards untuk Siswa yang Sudah Punya Kelas */
    .bento-card-clean {
        background: #ffffff;
        border: 1px solid #e0f2fe;
        border-radius: 24px;
        box-shadow: 0 4px 18px -3px rgba(2, 132, 199, 0.06);
        padding: 1.5rem;
        transition: all 0.25s ease;
    }
    .bento-card-clean:hover {
        border-color: #bae6fd;
        box-shadow: 0 10px 25px -4px rgba(2, 132, 199, 0.1);
    }

    .test-card-item {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 1.25rem;
        transition: all 0.2s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .test-card-item:hover {
        border-color: #7dd3fc;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -3px rgba(2, 132, 199, 0.12);
    }

    .btn-action-primary {
        background-color: #0284c7;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 0.8rem;
        padding: 0.55rem 1.15rem;
        border-radius: 12px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        box-shadow: 0 2px 8px rgba(2, 132, 199, 0.25);
        border: none;
        transition: all 0.2s;
    }
    .btn-action-primary:hover {
        background-color: #0369a1;
        transform: translateY(-1px);
    }

    .btn-action-secondary {
        background-color: #f1f5f9;
        color: #334155 !important;
        font-weight: 600;
        font-size: 0.8rem;
        padding: 0.55rem 1rem;
        border-radius: 12px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        border: 1px solid #e2e8f0;
        transition: all 0.2s;
    }
    .btn-action-secondary:hover {
        background-color: #e2e8f0;
        color: #0f172a !important;
    }
</style>
@endpush

@section('content')
<div class="space-y-6 max-w-7xl mx-auto pb-8">

    <!-- ========================================================================= -->
    <!-- 1. HERO BANNER SISWA (SKY CLOUD & SOFT MODERN)                            -->
    <!-- ========================================================================= -->
    <div id="tour-siswa-hero" class="w-full text-white rounded-3xl p-6 md:p-8 shadow-lg shadow-sky-900/10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative overflow-hidden hero-siswa-sky">
        
        <!-- Ambient Decorative Cloud Circles -->
        <div style="position: absolute; right: -50px; top: -50px; width: 260px; height: 260px; border-radius: 50%; background: rgba(255, 255, 255, 0.12); pointer-events: none; filter: blur(30px);"></div>
        <div style="position: absolute; left: -40px; bottom: -40px; width: 200px; height: 200px; border-radius: 50%; background: rgba(186, 230, 253, 0.18); pointer-events: none; filter: blur(25px);"></div>

        <div class="space-y-2 min-w-0 relative z-10">
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.35rem 0.9rem; border-radius: 9999px; background: rgba(255, 255, 255, 0.18); backdrop-filter: blur(8px); border: 1px solid rgba(255, 255, 255, 0.25); font-size: 0.75rem; font-weight: 600;">
                <span style="width: 8px; height: 8px; border-radius: 50%; background-color: #7dd3fc; display: inline-block;"></span>
                <span>Siswa &bull; NISN: {{ $siswa->nisn }}</span>
                @if($kelas)
                    <span>&bull;</span>
                    <span style="color: #e0f2fe; font-weight: 700;">{{ $kelas->nama_kelas }}</span>
                @endif
            </div>

            <h1 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight leading-tight">
                Selamat Datang, {{ $siswa->nama_siswa }}
            </h1>

            <p class="text-xs md:text-sm text-sky-100 max-w-xl leading-relaxed">
                @if($kelas)
                    Kelas Binaan: <strong>{{ $kelas->nama_kelas }}</strong> &bull; Guru BK: <strong>{{ $kelas->guruBk->nama_guru ?? 'Guru BK' }}</strong>
                @else
                    Untuk mulai mengakses bimbingan konseling dan pengerjaan asesmen, silakan bergabung ke kelas Anda.
                @endif
            </p>
        </div>

        @if($kelas)
            <!-- Stat Badges (Hanya Muncul Jika Siswa SUDAH Bergabung Kelas) -->
            <div class="flex items-center gap-3 w-full md:w-auto overflow-x-auto pb-1 md:pb-0 shrink-0 relative z-10">
                <div class="flex items-center gap-2.5">
                    <!-- Stat 1: Perlu Tes -->
                    <div class="stat-pill">
                        <div style="width: 30px; height: 30px; border-radius: 10px; background: rgba(255, 255, 255, 0.2); display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        <div>
                            <span class="block text-base font-extrabold text-white leading-tight">{{ $stats->total_tes_aktif }}</span>
                            <span class="text-[10px] text-sky-100 font-medium">Perlu Dikerjakan</span>
                        </div>
                    </div>

                    <!-- Stat 2: Selesai -->
                    <div class="stat-pill">
                        <div style="width: 30px; height: 30px; border-radius: 10px; background: rgba(255, 255, 255, 0.2); display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width: 15px; height: 15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <span class="block text-base font-extrabold text-white leading-tight">{{ $stats->total_tes_selesai }}</span>
                            <span class="text-[10px] text-sky-100 font-medium">Selesai</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- ========================================================================= -->
    <!-- 2. KONTEN UTAMA: PENGECEKAN STATUS GABUNG KELAS                           -->
    <!-- ========================================================================= -->

    @if(!$kelas)
        <!-- ===================================================================== -->
        <!-- KONDISI 1: SISWA BELUM BERGABUNG KELAS                                -->
        <!-- HANYA MENAMPILKAN CARD UNTUK BERGABUNG KELAS (TIDAK ADA TAMPILAN LAIN)-->
        <!-- ===================================================================== -->
        <div class="onboarding-card">
            
            <!-- Ikon Kelas Elegan -->
            <div class="onboarding-icon-box">
                <svg xmlns="http://www.w3.org/2000/svg" style="width: 38px; height: 38px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>

            <!-- Teks Ajakan Bergabung -->
            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                Bergabung ke Kelas Binaan
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-2 max-w-md mx-auto leading-relaxed">
                Anda belum terdaftar di kelas binaan manapun. Masukkan <strong>Kode Unik Kelas</strong> dari Guru BK Anda untuk membuka paket asesmen dan layanan bimbingan konseling.
            </p>

            <!-- Form Input Kode Unik -->
            <form action="{{ route('siswa.joinKelas') }}" method="POST" class="mt-7 space-y-4 max-w-sm mx-auto">
                @csrf
                <div>
                    <label for="kode_unik_kelas" class="block text-xs font-bold text-slate-700 mb-2 text-left ml-1">
                        Masukkan Kode Unik Kelas
                    </label>
                    <input 
                        type="text" 
                        name="kode_unik_kelas" 
                        id="kode_unik_kelas" 
                        placeholder="Contoh: BK-IPA1"
                        required
                        autofocus
                        class="input-code-huge"
                    >
                </div>

                <button type="submit" class="btn-join-huge">
                    <span>Gabung ke Kelas Sekarang</span>
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>

            <!-- Info Tambahan / Bantuan Guru BK -->
            <div class="mt-8 pt-6 border-t border-sky-100 flex items-center justify-center gap-2 text-xs text-slate-500">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;color:#0284c7;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Belum memiliki kode kelas? Silakan tanyakan kepada <strong>Guru BK</strong> di sekolah Anda.</span>
            </div>

        </div>

    @else
        <!-- ===================================================================== -->
        <!-- KONDISI 2: SISWA SUDAH BERGABUNG KELAS                                -->
        <!-- TAMPILAN DASHBOARD LENGKAP: ASSESMENT & INFORMASI KELAS BINAAN        -->
        <!-- ===================================================================== -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- KOLOM KIRI (UTAMA): DAFTAR ASSESMENT (lg:col-span-8) -->
            <main class="w-full lg:col-span-8 space-y-6">

                <!-- CARD ASSESMENT -->
                <div class="bento-card-clean space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-sky-50">
                        <div>
                            <h2 class="text-base font-bold text-slate-900 tracking-tight flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:#0284c7;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                                Paket Assesment &amp; Kuesioner
                            </h2>
                            <p class="text-xs text-slate-500 mt-0.5">Daftar tes peminatan, psikotes, dan tugas evaluasi bimbingan konseling.</p>
                        </div>

                        <!-- Filter Status Assesment -->
                        @if($tesAktif->isNotEmpty())
                            <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl text-xs font-semibold" id="testFilterTabs">
                                <button 
                                    type="button" 
                                    onclick="filterTests('all', this)"
                                    class="px-3 py-1 rounded-lg transition-all cursor-pointer bg-white text-sky-700 shadow-xs"
                                >
                                    Semua ({{ $tesAktif->count() }})
                                </button>
                                <button 
                                    type="button" 
                                    onclick="filterTests('pending', this)"
                                    class="px-3 py-1 rounded-lg transition-all cursor-pointer text-slate-600 hover:text-slate-900"
                                >
                                    Perlu Dikerjakan ({{ $stats->total_tes_aktif }})
                                </button>
                                <button 
                                    type="button" 
                                    onclick="filterTests('completed', this)"
                                    class="px-3 py-1 rounded-lg transition-all cursor-pointer text-slate-600 hover:text-slate-900"
                                >
                                    Selesai ({{ $tesAktif->count() - $stats->total_tes_aktif }})
                                </button>
                            </div>
                        @endif
                    </div>

                    @if($tesAktif->isEmpty())
                        <div class="w-full py-12 text-center text-xs text-slate-400 bg-sky-50/50 rounded-2xl border border-sky-100">
                            <div style="width:48px;height:48px;border-radius:14px;background:#e0f2fe;color:#0284c7;display:flex;align-items:center;justify-content:center;margin:0 auto 0.75rem auto;">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-slate-800">Belum Ada Assesment Aktif</h3>
                            <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1">
                                Guru BK Anda belum mengaktifkan paket asesmen untuk kelas {{ $kelas->nama_kelas }}.
                            </p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" id="testCardsContainer">
                            @foreach($tesAktif as $tes)
                                @php
                                    $sesi = $tes->sesi_siswa;
                                    $sudahSelesai = ($sesi && $sesi->status_pengerjaan === 'selesai');
                                    $sedangMengerjakan = ($sesi && $sesi->status_pengerjaan === 'sedang_mengerjakan');
                                    $filterTag = $sudahSelesai ? 'completed' : 'pending';
                                @endphp

                                <div 
                                    data-status="{{ $filterTag }}"
                                    class="test-card-item group"
                                >
                                    <div class="space-y-2.5">
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-sky-50 text-sky-800 border border-sky-100">
                                                {{ $tes->kategori->nama_kategori ?? 'Umum' }}
                                            </span>

                                            @if($sudahSelesai)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    Selesai
                                                </span>
                                            @elseif($sedangMengerjakan)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                    Sedang Dikerjakan
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-sky-50 text-sky-700 border border-sky-200">
                                                    Belum Dikerjakan
                                                </span>
                                            @endif
                                        </div>

                                        <h3 class="text-sm font-bold text-slate-900 group-hover:text-sky-700 transition-colors line-clamp-2">
                                            {{ $tes->judul }}
                                        </h3>

                                        <p class="text-[11px] text-slate-400">
                                            Durasi: <strong>{{ $tes->durasi_menit ?? 30 }} Menit</strong> &bull; {{ $tes->soals_count ?? 0 }} Soal
                                        </p>
                                    </div>

                                    <div class="pt-3 mt-3 border-t border-slate-100 flex items-center justify-between">
                                        @if($sudahSelesai)
                                            <span class="text-xs font-bold text-emerald-700">
                                                Skor: {{ number_format($sesi->total_skor, 1) }}
                                            </span>
                                            <a href="{{ route('siswa.tes.hasil', $tes->id_tes) }}" class="btn-action-secondary">
                                                <span>Lihat Hasil</span>
                                            </a>
                                        @elseif($sedangMengerjakan)
                                            <span class="text-xs text-amber-700 font-medium">Lanjutkan pengerjaan</span>
                                            <a href="{{ route('siswa.tes.kerjakan', $tes->id_tes) }}" class="btn-action-primary">
                                                <span>Lanjutkan</span>
                                            </a>
                                        @else
                                            <span class="text-xs text-slate-500 font-medium">Tersedia</span>
                                            <a href="{{ route('siswa.tes.show', $tes->id_tes) }}" class="btn-action-primary">
                                                <span>Mulai Kerjakan</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- CARD RIWAYAT ASSESMENT -->
                @if($riwayatTes->isNotEmpty())
                    <div class="bento-card-clean space-y-3">
                        <h3 class="text-sm font-bold text-slate-900 tracking-tight flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;color:#0d9488;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Riwayat Assesment Selesai
                        </h3>
                        <div class="divide-y divide-slate-100">
                            @foreach($riwayatTes as $riwayat)
                                <div class="py-3 flex items-center justify-between gap-3 text-xs">
                                    <div>
                                        <p class="font-bold text-slate-800">{{ $riwayat->tes->judul ?? 'Assesment' }}</p>
                                        <p class="text-slate-400 text-[11px]">Selesai pada: {{ $riwayat->waktu_submit ? \Carbon\Carbon::parse($riwayat->waktu_submit)->format('d M Y, H:i') : '-' }} WIB</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg font-mono font-bold text-xs bg-emerald-50 text-emerald-800 border border-emerald-200">
                                            Skor: {{ number_format($riwayat->total_skor, 1) }}
                                        </span>
                                        <a href="{{ route('siswa.tes.hasil', $riwayat->id_tes) }}" class="text-sky-600 hover:text-sky-800 font-semibold text-xs">
                                            Rincian &rarr;
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </main>

            <!-- KOLOM KANAN (SIDEBAR): INFORMASI KELAS BINAAN (lg:col-span-4) -->
            <aside class="w-full lg:col-span-4 space-y-6">

                <!-- CARD KELAS BINAAN -->
                <div class="bento-card-clean space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-sky-50">
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;color:#0284c7;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Kelas Binaan Anda
                        </h3>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Aktif
                        </span>
                    </div>

                    <div class="p-4 bg-sky-50/70 border border-sky-100 rounded-2xl space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-500 font-medium">Nama Kelas:</span>
                            <span class="text-sm font-bold text-slate-900">{{ $kelas->nama_kelas }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-500 font-medium">Guru BK:</span>
                            <span class="text-xs font-bold text-sky-800">{{ $kelas->guruBk->nama_guru ?? 'Guru BK' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-500 font-medium">Teman Sekelas:</span>
                            <span class="text-xs font-bold text-slate-700">{{ $stats->total_teman }} Siswa</span>
                        </div>
                    </div>

                    <!-- Tombol Keluar / Ganti Kelas -->
                    <form action="{{ route('siswa.leaveKelas') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin keluar dari kelas {{ $kelas->nama_kelas }}? Anda harus memasukkan kode kelas baru untuk mengakses soal kembali.');">
                        @csrf
                        <button 
                            type="submit" 
                            class="w-full text-center py-2 px-3 rounded-xl text-xs font-semibold text-rose-600 hover:text-rose-700 bg-rose-50/70 hover:bg-rose-100 border border-rose-100 transition-colors cursor-pointer"
                        >
                            Keluar dari Kelas Ini
                        </button>
                    </form>
                </div>

                <!-- CARD PROGRES EVALUASI -->
                <div class="bento-card-clean space-y-3">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;color:#0d9488;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Progres Assesment
                    </h3>

                    <div class="grid grid-cols-2 gap-3 text-center">
                        <div class="p-3 bg-slate-50 border border-slate-100 rounded-xl">
                            <span class="block text-lg font-extrabold text-slate-900">{{ number_format($stats->rata_rata_skor, 1) }}</span>
                            <span class="text-[10px] text-slate-400 font-medium">Rata-rata Skor</span>
                        </div>
                        <div class="p-3 bg-slate-50 border border-slate-100 rounded-xl">
                            <span class="block text-lg font-extrabold text-slate-900">{{ $stats->total_tes_selesai }}</span>
                            <span class="text-[10px] text-slate-400 font-medium">Assesment Selesai</span>
                        </div>
                    </div>
                </div>

            </aside>

        </div>
    @endif

</div>

<script>
function filterTests(status, btn) {
    const tabs = document.querySelectorAll('#testFilterTabs button');
    tabs.forEach(t => {
        t.classList.remove('bg-white', 'text-sky-700', 'shadow-xs');
        t.classList.add('text-slate-600');
    });
    btn.classList.add('bg-white', 'text-sky-700', 'shadow-xs');
    btn.classList.remove('text-slate-600');

    const cards = document.querySelectorAll('#testCardsContainer .test-card-item');
    cards.forEach(c => {
        if (status === 'all') {
            c.style.display = 'flex';
        } else {
            c.style.display = (c.getAttribute('data-status') === status) ? 'flex' : 'none';
        }
    });
}
</script>
@endsection
