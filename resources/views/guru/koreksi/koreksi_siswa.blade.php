@extends('layouts.app')

@section('title', 'Laporan Diagnostik & Konseling: ' . $siswa->nama_siswa)

@push('styles')
<style>
    /* Styling Selaras Dashboard (Sky Cloud Guru BK, Siswa, & Admin) */
    .hero-guru-sky {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 60%, #075985 100%);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .stat-pill {
        background: rgba(255, 255, 255, 0.16);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.25);
        padding: 0.5rem 1rem;
        border-radius: 14px;
        display: flex;
        align-items: center;
        gap: 0.6rem;
        color: #ffffff;
    }

    .card-sky {
        background: #ffffff;
        border: 1px solid #e0f2fe;
        border-radius: 24px;
        box-shadow: 0 4px 18px -2px rgba(2, 132, 199, 0.07);
        transition: all 0.25s ease;
    }

    .card-sky:hover {
        box-shadow: 0 10px 25px -4px rgba(2, 132, 199, 0.12);
    }

    .badge-dominant {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        color: #ffffff;
        font-weight: 700;
        font-size: 0.85rem;
        padding: 0.45rem 1.2rem;
        border-radius: 9999px;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
    }

    .bar-dimensi-fill {
        height: 8px;
        border-radius: 9999px;
    }

    .btn-sky-action {
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
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-sky-action:hover {
        background-color: #0369a1;
        transform: translateY(-1px);
        box-shadow: 0 5px 14px rgba(2, 132, 199, 0.35);
    }
</style>
@endpush

@section('content')
<div class="space-y-6 pb-12">

    <!-- ========================================================================= -->
    <!-- HERO BANNER GURU BK (Selaras Desain Dashboard Siswa, Guru & Admin)        -->
    <!-- ========================================================================= -->
    <div class="hero-guru-sky rounded-3xl p-6 sm:p-8 text-white relative overflow-hidden shadow-lg print:hidden">
        <!-- Dekorasi Aksen Lingkaran Gradien -->
        <div class="absolute -right-12 -bottom-16 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute right-28 -top-12 w-48 h-48 bg-sky-300/15 rounded-full blur-xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-3 max-w-2xl">
                <!-- Breadcrumbs -->
                <div class="flex items-center gap-2 text-xs text-sky-100/90 font-medium flex-wrap">
                    <a href="{{ route('guru.dashboard') }}" class="hover:text-white transition-colors flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                    <span>&rsaquo;</span>
                    <a href="{{ route('guru.koreksi.index') }}" class="hover:text-white transition-colors">Hasil Asessment</a>
                    <span>&rsaquo;</span>
                    <a href="{{ route('guru.koreksi.show', $tes->id_tes) }}" class="hover:text-white transition-colors">{{ $tes->judul }}</a>
                    <span>&rsaquo;</span>
                    <span class="text-white font-bold">{{ $siswa->nama_siswa }}</span>
                </div>

                <!-- Judul & Badge Tipe -->
                <div class="flex items-center gap-3 flex-wrap">
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white leading-tight">
                        Laporan Diagnostik &amp; Lembar Konseling
                    </h1>
                </div>

                <!-- Stat Pills Siswa & Asesmen -->
                <div class="flex flex-wrap items-center gap-2.5 pt-1">
                    <div class="stat-pill text-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;color:#bae6fd;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Siswa: <strong>{{ $siswa->nama_siswa }}</strong></span>
                    </div>

                    <div class="stat-pill text-xs font-mono">
                        <span class="text-sky-200">NISN:</span>
                        <strong>{{ $siswa->nisn }}</strong>
                    </div>

                    <div class="stat-pill text-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;color:#bae6fd;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span>Kelas: <strong>{{ $tes->kelas->nama_kelas ?? 'Umum' }}</strong></span>
                    </div>

                    <div class="stat-pill text-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;color:#bae6fd;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Asesmen: <strong>{{ $tes->judul }}</strong></span>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi Header -->
            <div class="flex flex-wrap items-center gap-3 self-start lg:self-center shrink-0">
                <button
                    type="button"
                    onclick="window.print()"
                    class="inline-flex items-center gap-2 bg-white text-slate-800 hover:bg-sky-50 font-semibold text-xs py-2.5 px-4 rounded-xl shadow-md transition-all cursor-pointer border border-white"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;color:#0284c7;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>Cetak Lembar Konseling</span>
                </button>

                <a
                    href="{{ route('guru.koreksi.show', $tes->id_tes) }}"
                    class="inline-flex items-center gap-2 bg-white/15 hover:bg-white/25 text-white font-semibold text-xs py-2.5 px-4 rounded-xl backdrop-blur-md border border-white/25 transition-all shadow-sm cursor-pointer"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali ke Rekap Kelas</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Official Report Header (Hanya Muncul Saat Print) -->
    <div class="hidden print:block pb-5 border-b border-slate-300 space-y-3">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-lg font-bold text-slate-900">BERKAS HASIL ASESMEN PSIKOMETRIK &amp; REKOMENDASI KONSELING</h1>
                <p class="text-xs text-slate-600">Layanan Bimbingan Konseling Digital HaloBK</p>
            </div>
            <div class="text-right text-xs">
                <span>Tanggal Cetak: {{ now()->format('d F Y, H:i') }} WIB</span>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4 text-xs bg-slate-50 p-3 rounded-lg border border-slate-200">
            <div>
                <p><strong>Nama Siswa:</strong> {{ $siswa->nama_siswa }}</p>
                <p><strong>NISN:</strong> {{ $siswa->nisn }}</p>
                <p><strong>Kelas:</strong> {{ $tes->kelas->nama_kelas ?? 'Umum' }}</p>
            </div>
            <div>
                <p><strong>Paket Asesmen:</strong> {{ $tes->judul }}</p>
                <p><strong>Guru BK:</strong> {{ Auth::guard('guru')->user()->nama_guru }}</p>
                <p><strong>Status Analisis:</strong> Selesai Dianalisis Sistem</p>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 1. KARTU PROFIL NARATIF PSIKOMETRIK SISWA                                 -->
    <!-- Menghasilkan profil naratif, potensi, kekuatan, serta rekomendasi         -->
    <!-- ========================================================================= -->
    <div class="card-sky p-6 sm:p-8 space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-5 border-b border-sky-100">
            <div class="space-y-1.5">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="badge-dominant">
                        ⭐ Tipe Dominan: {{ $laporanPsikometrik['label_primer'] }}
                    </span>
                    <span class="text-xs font-semibold px-3 py-1 rounded-full bg-slate-100 text-slate-700">
                        Didukung: {{ $laporanPsikometrik['label_sekunder'] }}
                    </span>
                </div>
                <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight pt-1">
                    {{ $laporanPsikometrik['judul_profil'] }}
                </h2>
            </div>

            <div class="px-5 py-3 rounded-2xl bg-sky-50 border border-sky-200 text-center shrink-0 shadow-2xs">
                <span class="text-[11px] text-sky-600 font-semibold block">Tingkat Keselarasan</span>
                <span class="text-2xl font-black text-sky-900">
                    {{ $laporanPsikometrik['persentase_dimensi'][$laporanPsikometrik['dimensi_primer']] ?? 100 }}%
                </span>
            </div>
        </div>

        <!-- Uraian Naratif -->
        <div class="p-5 sm:p-6 rounded-2xl bg-slate-50/80 border border-slate-200/80 leading-relaxed text-slate-800 text-sm">
            <p>{{ $laporanPsikometrik['profil_naratif'] }}</p>
        </div>

        <!-- Spektrum Skor per Dimensi -->
        <div class="space-y-3 pt-2">
            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;color:#0284c7;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span>Spektrum Skor per Karakteristik Dimensi</span>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($laporanPsikometrik['persentase_dimensi'] as $dimKey => $persen)
                    @php
                        $infoDim = $laporanPsikometrik['dimensi_info'][$dimKey] ?? null;
                        $isPrimer = ($dimKey === $laporanPsikometrik['dimensi_primer']);
                    @endphp
                    <div class="p-3.5 rounded-2xl border {{ $isPrimer ? 'bg-sky-50/70 border-sky-300 shadow-2xs' : 'bg-white border-slate-200' }} space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold {{ $isPrimer ? 'text-sky-900' : 'text-slate-800' }}">
                                {{ $infoDim['nama'] ?? $dimKey }}
                                @if($isPrimer)
                                    <span class="text-[10px] font-bold text-sky-700 bg-sky-200/60 px-2 py-0.5 rounded-full ml-1">Dominan</span>
                                @endif
                            </span>
                            <span class="font-mono font-bold {{ $isPrimer ? 'text-sky-700' : 'text-slate-600' }}">{{ $persen }}%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                            <div class="bar-dimensi-fill {{ $isPrimer ? 'bg-sky-600' : 'bg-slate-400' }}" style="width: {{ $persen }}%;"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- ===================================================================== -->
        <!-- 2. DASAR KONSELING & TINDAK LANJUT BERSAMA GURU BK & ORANG TUA        -->
        <!-- ===================================================================== -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-4 border-t border-slate-100">
            
            <!-- Strategi Bimbingan Guru BK -->
            <div class="p-5 sm:p-6 rounded-2xl bg-sky-50/70 border border-sky-200 space-y-3">
                <div class="flex items-center gap-2 text-sky-950 font-bold text-xs uppercase tracking-wider">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:#0284c7;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span>Strategi Bimbingan Guru BK</span>
                </div>
                <ul class="space-y-2 text-xs text-sky-900 leading-relaxed list-disc list-inside">
                    @foreach($laporanPsikometrik['strategi_guru_bk'] as $rek)
                        <li>{{ $rek }}</li>
                    @endforeach
                </ul>
            </div>

            <!-- Tindak Lanjut Bersama Orang Tua -->
            <div class="p-5 sm:p-6 rounded-2xl bg-amber-50/70 border border-amber-200 space-y-3">
                <div class="flex items-center gap-2 text-amber-950 font-bold text-xs uppercase tracking-wider">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:#d97706;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Materi Konseling Bersama Orang Tua</span>
                </div>
                <ul class="space-y-2 text-xs text-amber-900 leading-relaxed list-disc list-inside">
                    @foreach($laporanPsikometrik['tindak_lanjut_ortu'] as $rek)
                        <li>{{ $rek }}</li>
                    @endforeach
                </ul>
            </div>

        </div>
    </div>

    <!-- 3. Rincian Respons Jawaban Siswa -->
    <div class="card-sky p-6 sm:p-7 space-y-4">
        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;color:#0284c7;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
            <span>Rincian Butir Jawaban Siswa ({{ $soals->count() }} Butir)</span>
        </h3>

        <div class="space-y-3">
            @foreach($soals as $index => $soal)
                @php
                    $jawaban = $jawabanMap->get($soal->id_soal);
                    $teksPilihan = $jawaban && $jawaban->pilihanJawaban ? $jawaban->pilihanJawaban->teks_pilihan : 'Belum Dijawab';
                @endphp

                <div class="p-4 sm:p-5 rounded-2xl border border-slate-100 bg-slate-50/50 space-y-2 text-xs">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-sky-600 text-white font-bold text-[11px] flex items-center justify-center shadow-xs">
                            {{ $index + 1 }}
                        </span>
                        <span class="text-[10px] font-extrabold px-2.5 py-0.5 rounded-full bg-sky-100 text-sky-800 border border-sky-200">
                            Dimensi: {{ $soal->dimensi }}
                        </span>
                    </div>

                    <p class="font-semibold text-slate-800 leading-relaxed text-xs sm:text-sm pt-0.5">
                        {{ $soal->clean_pertanyaan }}
                    </p>

                    <div class="pt-1 flex items-center gap-2 flex-wrap">
                        <span class="text-[11px] text-slate-500 font-medium">Respons Jawaban Siswa:</span>
                        <span class="font-bold text-slate-900 bg-white px-3 py-1 rounded-xl border border-slate-200 shadow-2xs">
                            {{ $teksPilihan }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
