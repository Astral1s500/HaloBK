@extends('layouts.app')

@section('title', 'Konfirmasi Asesmen: ' . $tes->judul)

@push('styles')
<style>
    .hero-sky-gradient {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 60%, #075985 100%);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 28px;
        box-shadow: 0 12px 30px -5px rgba(2, 132, 199, 0.2);
        position: relative;
        overflow: hidden;
    }
    .card-sky {
        background: #ffffff;
        border: 1px solid #e0f2fe;
        border-radius: 24px;
        box-shadow: 0 6px 25px -4px rgba(2, 132, 199, 0.06);
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
<div class="space-y-6 max-w-5xl mx-auto pb-12">

    <!-- Header & Navigasi Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('siswa.dashboard') }}" class="hover:text-sky-600 transition-colors">Dashboard</a>
                <span>&rsaquo;</span>
                <span class="text-sky-700 font-semibold">Konfirmasi Asesmen</span>
            </div>
            <div class="flex items-center gap-2.5 flex-wrap">
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $tes->judul }}</h1>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-sky-50 text-sky-700 border border-sky-200">
                    {{ $tes->jenis_psikometrik }}
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-1">
                Kategori: <strong class="text-slate-700">{{ $tes->kategori->nama_kategori ?? 'Umum' }}</strong>
                &bull; Guru BK: <strong class="text-slate-700">{{ $tes->guruBk->nama_guru ?? 'Guru BK' }}</strong>
            </p>
        </div>

        <a
            href="{{ route('siswa.dashboard') }}"
            class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-200 py-2.5 px-4 rounded-xl transition-all shadow-xs cursor-pointer shrink-0"
        >
            <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Summary Box Parameter Tes (Sky Cloud Soft Gradient Hero) -->
    <div class="hero-sky-gradient text-white p-6 md:p-8 space-y-5">
        
        <!-- Ambient Decorative Cloud Circles -->
        <div style="position: absolute; right: -40px; top: -40px; width: 220px; height: 220px; border-radius: 50%; background: rgba(255, 255, 255, 0.12); pointer-events: none; filter: blur(25px);"></div>
        <div style="position: absolute; left: -30px; bottom: -30px; width: 180px; height: 180px; border-radius: 50%; background: rgba(186, 230, 253, 0.18); pointer-events: none; filter: blur(20px);"></div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-5 pb-5 border-b border-white/20 relative z-10">
            <div>
                <span class="block text-[11px] font-medium text-sky-100">Durasi Pengerjaan</span>
                <span class="text-xl sm:text-2xl font-black text-white mt-0.5 block">{{ $tes->durasi_menit }} Menit</span>
                <span class="text-[10px] text-sky-100 mt-0.5 block">Hitung mundur otomatis</span>
            </div>
            <div>
                <span class="block text-[11px] font-medium text-sky-100">Total Butir Soal</span>
                <span class="text-xl sm:text-2xl font-black text-white mt-0.5 block">{{ $totalSoal }} Butir</span>
                <span class="text-[10px] text-sky-100 mt-0.5 block">Format Pilihan Ganda</span>
            </div>
            <div>
                <span class="block text-[11px] font-medium text-sky-100">Total Bobot Nilai</span>
                <span class="text-xl sm:text-2xl font-black text-white mt-0.5 block">{{ $bobotTotal }} Poin</span>
                <span class="text-[10px] text-sky-100 mt-0.5 block">Skala penilaian terstandar</span>
            </div>
            <div>
                <span class="block text-[11px] font-medium text-sky-100">Target Kelas</span>
                <span class="text-xl sm:text-2xl font-black text-white mt-0.5 block truncate">{{ $tes->kelas->nama_kelas ?? 'Umum' }}</span>
                <span class="text-[10px] text-sky-100 mt-0.5 block">Kelas binaan siswa</span>
            </div>
        </div>

        <div class="flex items-center justify-between text-xs text-sky-100 relative z-10 flex-wrap gap-2">
            <span>Ditugaskan pada: {{ $tes->created_at ? $tes->created_at->format('d F Y, H:i') . ' WIB' : '-' }}</span>
            @if($sesi && $sesi->status_pengerjaan === 'sedang_mengerjakan')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-white text-sky-800 shadow-xs">
                    &bull; Sesi Aktif Sedang Berlangsung
                </span>
            @endif
        </div>
    </div>

    <!-- Petunjuk Pengerjaan Asesmen (Card Sky Clean & User-Friendly) -->
    <div class="card-sky p-6 sm:p-7 space-y-5">
        <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:#0284c7;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Petunjuk &amp; Ketentuan Pengerjaan Asesmen</span>
        </h3>

        <ul class="text-xs text-slate-600 space-y-2.5 leading-relaxed list-disc list-inside">
            <li>
                Waktu pengerjaan akan <strong>langsung dihitung mundur</strong> segera setelah Anda menekan tombol mulai.
            </li>
            <li>
                Sistem dilengkapi fitur <strong>Penyimpanan Otomatis (Auto-Save)</strong> setiap kali Anda memilih salah satu opsi jawaban.
            </li>
            <li>
                Gunakan opsi <strong>Tandai Ragu-ragu</strong> jika ingin meninjau kembali soal sebelum menyerahkan.
            </li>
            <li>
                <strong>Penting:</strong> Asesmen <strong>hanya dapat dikumpulkan jika seluruh butir soal telah dijawab dan tidak ada soal yang masih ditandai ragu-ragu</strong>.
            </li>
            <li>
                Jika durasi waktu habis (00:00:00), sistem akan secara otomatis mengumpulkan jawaban yang tersimpan.
            </li>
            <li>
                Setiap siswa memiliki <strong>1 kali kesempatan sesi</strong> untuk menyelesaikan paket asesmen ini.
            </li>
        </ul>

        <!-- Form Aksi Mulai / Lanjutkan -->
        <div class="pt-5 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-xs text-slate-500">
                @if($sesi && $sesi->status_pengerjaan === 'sedang_mengerjakan')
                    <span class="font-bold text-sky-800">
                        Anda memiliki sesi asesmen yang sedang berjalan. Klik tombol di kanan untuk melanjutkan.
                    </span>
                @else
                    <span>Pastikan koneksi internet stabil sebelum menekan tombol mulai.</span>
                @endif
            </div>

            <form action="{{ route('siswa.tes.start', $tes->id_tes) }}" method="POST">
                @csrf
                @if($sesi && $sesi->status_pengerjaan === 'sedang_mengerjakan')
                    <button
                        type="submit"
                        class="btn-sky-action w-full sm:w-auto"
                    >
                        <span>Lanjutkan Pengerjaan</span>
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                @else
                    <button
                        type="submit"
                        class="btn-sky-action w-full sm:w-auto"
                    >
                        <span>Mulai Kerjakan Asesmen</span>
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                @endif
            </form>
        </div>
    </div>

</div>
@endsection
