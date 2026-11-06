@extends('layouts.app')

@section('title', 'Hasil Asesmen: ' . $tes->judul)

@push('styles')
<style>
    .hero-sky-gradient {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 60%, #075985 100%);
        border-radius: 28px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 12px 30px -5px rgba(2, 132, 199, 0.2);
    }
    .card-sky {
        background: #ffffff;
        border: 1px solid #e0f2fe;
        border-radius: 24px;
        box-shadow: 0 4px 20px -2px rgba(2, 132, 199, 0.06);
    }
    .badge-dominant {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        color: #ffffff;
        font-weight: 700;
        font-size: 0.85rem;
        padding: 0.4rem 1.1rem;
        border-radius: 9999px;
        box-shadow: 0 3px 10px rgba(2, 132, 199, 0.25);
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }
    .bar-dimensi-fill {
        height: 10px;
        border-radius: 9999px;
        transition: width 1s ease-in-out;
    }
    .btn-sky-action {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%) !important;
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.85rem;
        padding: 0.75rem 1.6rem;
        border-radius: 14px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 14px rgba(2, 132, 199, 0.3);
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-sky-action:hover {
        background: linear-gradient(135deg, #0369a1 0%, #075985 100%) !important;
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(2, 132, 199, 0.4);
    }
</style>
@endpush

@section('content')
@php
    $isOCEAN = ($laporanPsikometrik['tipe_asesmen'] === 'Pribadi (OCEAN)');
@endphp

<div class="space-y-6 max-w-5xl mx-auto pb-12">

    {{-- ============================================================ --}}
    {{-- HEADER ATAS: Breadcrumb, judul, dan tombol aksi              --}}
    {{-- ============================================================ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 print:hidden">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('siswa.dashboard') }}" class="hover:text-sky-600 transition-colors">Dashboard</a>
                <span>&rsaquo;</span>
                <span class="text-sky-700 font-semibold">Hasil Asesmen</span>
            </div>
            <div class="flex items-center gap-2.5 flex-wrap">
                <h1 class="text-xl md:text-2xl font-bold text-slate-900 tracking-tight">{{ $tes->judul }}</h1>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-sky-50 text-sky-700 border border-sky-200">
                    {{ $laporanPsikometrik['tipe_asesmen'] }}
                </span>
            </div>
            <div class="mt-2 pt-2 border-t border-slate-200 text-xs text-slate-500 flex flex-wrap items-center gap-x-4 gap-y-1">
                <span>Guru BK: <strong class="text-slate-700">{{ $tes->guruBk->nama_guru ?? 'Guru BK' }}</strong></span>
                <span class="w-px h-3 bg-slate-300 hidden sm:inline-block"></span>
                <span>Waktu Pengerjaan: <strong class="text-slate-700">{{ $durasiPengerjaan }}</strong></span>
            </div>
        </div>

        <div class="flex items-center gap-2">
            @if(!$isOCEAN)
                <button
                    type="button"
                    onclick="window.print()"
                    class="inline-flex items-center gap-1.5 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold py-2.5 px-4 rounded-xl border border-slate-300 shadow-2xs transition-colors cursor-pointer"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>Cetak Lembar Profil</span>
                </button>
            @endif

            <a
                href="{{ route('siswa.dashboard') }}"
                class="btn-sky-action"
            >
                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Kembali ke Dashboard</span>
            </a>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- OCEAN — Halaman Terima Kasih (Hasil Disembunyikan)           --}}
    {{-- ============================================================ --}}
    @if($isOCEAN)

        {{-- Hero Sky Gradient --}}
        <div class="hero-sky-gradient text-white p-8 md:p-12 text-center space-y-5">
            <div style="position: absolute; right: -50px; top: -50px; width: 250px; height: 250px; border-radius: 50%; background: rgba(255,255,255,0.1); pointer-events: none; filter: blur(30px);"></div>
            <div style="position: absolute; left: -40px; bottom: -40px; width: 200px; height: 200px; border-radius: 50%; background: rgba(186,230,253,0.15); pointer-events: none; filter: blur(25px);"></div>

            <div class="relative z-10 space-y-5">
                {{-- Ikon Sukses --}}
                <div class="h-20 w-20 mx-auto rounded-full bg-white/20 backdrop-blur-xs flex items-center justify-center border border-white/30 shadow-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:40px;height:40px;color:#ffffff;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <div class="space-y-2">
                    <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                        Terima Kasih, {{ $siswa->nama_siswa }}!
                    </h2>
                    <p class="text-sky-100 text-sm leading-relaxed max-w-lg mx-auto">
                        Asesmen <strong class="text-white">Kepribadian (OCEAN)</strong> berhasil diselesaikan dengan baik.
                        Jawaban kamu telah tersimpan dan akan dianalisis oleh Guru BK.
                    </p>
                </div>

                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/20 border border-white/30 text-white text-xs font-bold">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span>Hasil bersifat Rahasia & Hanya untuk Guru BK</span>
                </div>
            </div>
        </div>

        {{-- Penjelasan Tambahan --}}
        <div class="card-sky p-7 space-y-5">
            <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:#0284c7;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Mengapa Hasil Tidak Ditampilkan?</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="p-4 rounded-2xl bg-sky-50 border border-sky-100 space-y-2 text-center">
                    <div class="h-10 w-10 mx-auto rounded-xl bg-sky-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#0284c7;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <p class="text-xs font-bold text-sky-900">Data Sangat Pribadi</p>
                    <p class="text-[11px] text-sky-700 leading-relaxed">Profil kepribadian bersifat sensitif dan hanya digunakan oleh Guru BK untuk keperluan bimbingan.</p>
                </div>

                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100 space-y-2 text-center">
                    <div class="h-10 w-10 mx-auto rounded-xl bg-emerald-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#059669;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <p class="text-xs font-bold text-emerald-900">Dibaca oleh Ahlinya</p>
                    <p class="text-[11px] text-emerald-700 leading-relaxed">Guru BK terlatih menafsirkan profil kepribadian secara benar dan tidak menyimpulkan secara keliru.</p>
                </div>

                <div class="p-4 rounded-2xl bg-amber-50 border border-amber-100 space-y-2 text-center">
                    <div class="h-10 w-10 mx-auto rounded-xl bg-amber-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#d97706;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <p class="text-xs font-bold text-amber-900">Digunakan untuk Mendukungmu</p>
                    <p class="text-[11px] text-amber-700 leading-relaxed">Hasilnya dipakai Guru BK untuk merancang strategi bimbingan yang paling sesuai untukmu.</p>
                </div>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-600 leading-relaxed flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;color:#0284c7;flex-shrink:0;margin-top:2px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <span>
                    Jika kamu ingin mengetahui lebih lanjut mengenai hasil asesmenmu, kamu dapat <strong class="text-slate-800">menghubungi langsung Guru BK-mu</strong> atau minta jadwal sesi bimbingan. Guru BK siap mendampingi kamu!
                </span>
            </div>
        </div>

    {{-- ============================================================ --}}
    {{-- NON-OCEAN — Tampilkan Laporan Lengkap                        --}}
    {{-- ============================================================ --}}
    @else

        {{-- Official Report Header (Hanya Muncul Saat Print) --}}
        <div class="hidden print:block pb-5 border-b border-slate-300 space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo HaloBK" class="w-12 h-12 object-contain">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block">Layanan Bimbingan &amp; Konseling</span>
                        <h1 class="text-lg font-bold text-slate-900">LAPORAN PROFIL ASESMEN PSIKOMETRIK SISWA</h1>
                        <p class="text-xs text-slate-600">Sistem Bimbingan Konseling HaloBK</p>
                    </div>
                </div>
                <div class="text-right text-xs">
                    <span class="font-mono text-slate-500">#SESI-{{ str_pad($sesi->id_sesi, 5, '0', STR_PAD_LEFT) }}</span>
                    <span class="block font-bold text-emerald-700">Telah Dianalisis Otomatis</span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 text-xs bg-slate-50 p-3 rounded-lg border border-slate-200">
                <div>
                    <p><strong>Nama Siswa:</strong> {{ $siswa->nama_siswa }}</p>
                    <p><strong>NISN:</strong> {{ $siswa->nisn }}</p>
                    <p><strong>Kelas:</strong> {{ $tes->kelas->nama_kelas ?? 'Umum' }}</p>
                </div>
                <div>
                    <p><strong>Asesmen:</strong> {{ $tes->judul }}</p>
                    <p><strong>Guru BK:</strong> {{ $tes->guruBk->nama_guru ?? '-' }}</p>
                    <p><strong>Waktu Selesai:</strong> {{ $sesi->waktu_submit ? $sesi->waktu_submit->format('d M Y, H:i') : '-' }} WIB</p>
                </div>
            </div>
        </div>

        {{-- ========================================================================= --}}
        {{-- 1. HERO LAPORAN PROFIL NARATIF PSIKOMETRIK                                --}}
        {{-- ========================================================================= --}}
        <div class="card-sky p-6 sm:p-8 space-y-6 border-sky-100">
            
            {{-- Header Profil --}}
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

                <div class="px-4 py-2.5 rounded-2xl bg-sky-50 border border-sky-200 text-center shrink-0">
                    <span class="text-[11px] text-sky-600 font-medium block">Tingkat Keselarasan</span>
                    <span class="text-xl font-black text-sky-900">
                        {{ $laporanPsikometrik['persentase_dimensi'][$laporanPsikometrik['dimensi_primer']] ?? 100 }}%
                    </span>
                </div>
            </div>

            {{-- Uraian Narasi Deskriptif --}}
            <div class="p-5 rounded-2xl bg-slate-50/80 border border-slate-200/80 leading-relaxed text-slate-800 text-sm">
                <p>{{ $laporanPsikometrik['profil_naratif'] }}</p>
            </div>

            {{-- ===================================================================== --}}
            {{-- 2. DIAGRAM SKOR CAPAIAN SETIAP DIMENSI                                --}}
            {{-- ===================================================================== --}}
            <div class="space-y-3 pt-2">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;color:#0284c7;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span>Spektrum Capaian Dimensi Ukur</span>
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($laporanPsikometrik['persentase_dimensi'] as $dimKey => $persen)
                        @php
                            $infoDim = $laporanPsikometrik['dimensi_info'][$dimKey] ?? null;
                            $isPrimer = ($dimKey === $laporanPsikometrik['dimensi_primer']);
                        @endphp
                        <div class="p-3.5 rounded-2xl border {{ $isPrimer ? 'bg-sky-50/60 border-sky-300' : 'bg-white border-slate-200' }} space-y-2">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold {{ $isPrimer ? 'text-sky-900' : 'text-slate-800' }}">
                                    {{ $infoDim['nama'] ?? $dimKey }}
                                    @if($isPrimer)
                                        <span class="text-[10px] font-bold text-sky-700 bg-sky-200/60 px-1.5 py-0.5 rounded ml-1">Dominan</span>
                                    @endif
                                </span>
                                <span class="font-mono font-bold {{ $isPrimer ? 'text-sky-700' : 'text-slate-600' }}">{{ $persen }}%</span>
                            </div>
                            
                            {{-- Progress Bar Fill --}}
                            <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                <div 
                                    class="bar-dimensi-fill {{ $isPrimer ? 'bg-sky-600' : 'bg-slate-400' }}" 
                                    style="width: {{ $persen }}%;"
                                ></div>
                            </div>

                            <p class="text-[10px] text-slate-500 leading-tight">
                                {{ $infoDim['deskripsi'] ?? '' }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ===================================================================== --}}
            {{-- 3. POTENSI, KEKUATAN & REKOMENDASI PENGEMBANGAN                       --}}
            {{-- ===================================================================== --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-4 border-t border-slate-100">
                
                {{-- Potensi & Kekuatan --}}
                <div class="p-5 rounded-2xl bg-emerald-50/60 border border-emerald-200 space-y-2.5">
                    <div class="flex items-center gap-2 text-emerald-950 font-bold text-xs uppercase tracking-wider">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;color:#059669;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Potensi &amp; Kekuatan Karakter Anda</span>
                    </div>
                    <ul class="space-y-2 text-xs text-emerald-900 leading-relaxed list-disc list-inside">
                        @foreach($laporanPsikometrik['potensi_kekuatan'] as $kekuatan)
                            <li>{{ $kekuatan }}</li>
                        @endforeach
                    </ul>
                </div>

                {{-- Rekomendasi Belajar / Pengembangan Diri --}}
                <div class="p-5 rounded-2xl bg-sky-50/60 border border-sky-200 space-y-2.5">
                    <div class="flex items-center gap-2 text-sky-950 font-bold text-xs uppercase tracking-wider">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;color:#0284c7;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span>Rekomendasi Strategi Belajar &amp; Pengembangan</span>
                    </div>
                    <ul class="space-y-2 text-xs text-sky-900 leading-relaxed list-disc list-inside">
                        @foreach($laporanPsikometrik['rekomendasi_siswa'] as $rek)
                            <li>{{ $rek }}</li>
                        @endforeach
                    </ul>
                </div>

            </div>

        </div>

        {{-- 4. Ringkasan Butir Pernyataan yang Dikerjakan --}}
        <div class="card-sky p-6 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">
                    Daftar Jawaban Anda ({{ $tes->soals->count() }} Butir)
                </h3>
                <span class="text-xs text-slate-500">Skala Likert Responsif</span>
            </div>

            <div class="space-y-3">
                @foreach($tes->soals as $idx => $soal)
                    @php
                        $jawaban = $jawabanList->get($soal->id_soal);
                        $teksPilihan = $jawaban && $jawaban->pilihanJawaban ? $jawaban->pilihanJawaban->teks_pilihan : 'Belum dijawab';
                    @endphp
                    <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 space-y-1.5 text-xs">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-md bg-sky-600 text-white font-bold text-[10px] flex items-center justify-center">
                                    {{ $idx + 1 }}
                                </span>
                                <span class="text-[10px] font-extrabold px-2 py-0.5 rounded bg-sky-100 text-sky-800">
                                    {{ $soal->dimensi }}
                                </span>
                            </div>
                        </div>
                        <p class="font-medium text-slate-800 leading-relaxed">
                            {{ $soal->clean_pertanyaan }}
                        </p>
                        <div class="pt-1 flex items-center gap-1.5 text-slate-600">
                            <span class="text-[11px] font-semibold text-slate-400">Respons Anda:</span>
                            <span class="font-bold text-sky-800 bg-white px-2.5 py-0.5 rounded-lg border border-sky-200">
                                {{ $teksPilihan }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    @endif {{-- end if isOCEAN --}}

</div>
@endsection
