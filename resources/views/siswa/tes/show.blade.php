@extends('layouts.app')

@section('title', 'Konfirmasi Tes: ' . $tes->judul)

@section('content')
<div class="space-y-5">

    <!-- Header & Navigasi -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('siswa.dashboard') }}" class="hover:text-slate-900 transition-colors">Dashboard</a>
                <span>&rsaquo;</span>
                <span class="text-slate-800 font-semibold">{{ $tes->isForm() ? 'Konfirmasi Pengisian Formulir' : 'Konfirmasi Asesmen' }}</span>
            </div>
            <div class="flex items-center gap-2.5 flex-wrap">
                <h1 class="text-xl md:text-2xl font-bold text-slate-900 tracking-tight">{{ $tes->judul }}</h1>
                @if($tes->isForm())
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200 shadow-2xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Formulir Pendataan
                    </span>
                @endif
            </div>
            <p class="text-xs text-slate-500 mt-0.5">
                Kategori: <strong class="text-slate-700">{{ $tes->kategori->nama_kategori ?? 'Umum' }}</strong>
                &bull; Guru BK: <strong class="text-slate-700">{{ $tes->guruBk->nama_guru ?? 'Guru BK' }}</strong>
            </p>
        </div>

        <a
            href="{{ route('siswa.dashboard') }}"
            class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 py-2.5 px-4 rounded-xl transition-colors cursor-pointer shrink-0"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Summary Box Detail Parameter Tes (Persis Gaya Guru BK) -->
    <div class="bg-slate-900 text-white rounded-2xl p-6 shadow-sm space-y-4">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pb-4 border-b border-slate-800">
            <div>
                <span class="block text-[11px] font-medium text-slate-400">Durasi Pengerjaan</span>
                <span class="text-lg sm:text-xl font-bold text-white mt-0.5 block">{{ $tes->durasi_menit }} Menit</span>
                <span class="text-[10px] text-slate-400 mt-0.5 block">Hitung mundur otomatis</span>
            </div>
            <div>
                <span class="block text-[11px] font-medium text-slate-400">{{ $tes->isForm() ? 'Total Pertanyaan' : 'Total Butir Soal' }}</span>
                <span class="text-lg sm:text-xl font-bold text-white mt-0.5 block">{{ $totalSoal }} Butir</span>
                <span class="text-[10px] text-slate-400 mt-0.5 block">{{ $totalPG }} Pilihan &bull; {{ $totalEsai }} Isian</span>
            </div>
            <div>
                <span class="block text-[11px] font-medium text-slate-400">{{ $tes->isForm() ? 'Tujuan Asesmen' : 'Total Bobot Nilai' }}</span>
                @if($tes->isForm())
                    <span class="text-base sm:text-lg font-bold text-blue-300 mt-0.5 block">Pendataan Siswa</span>
                    <span class="text-[10px] text-slate-400 mt-0.5 block">Bimbingan & Konseling</span>
                @else
                    <span class="text-lg sm:text-xl font-bold text-white mt-0.5 block">{{ $bobotTotal }} Poin</span>
                    <span class="text-[10px] text-slate-400 mt-0.5 block">Akumulasi seluruh butir</span>
                @endif
            </div>
            <div>
                <span class="block text-[11px] font-medium text-slate-400">Target Kelas</span>
                <span class="text-lg sm:text-xl font-bold text-white mt-0.5 block truncate">{{ $tes->kelas->nama_kelas ?? 'Umum' }}</span>
                <span class="text-[10px] text-slate-400 mt-0.5 block">Kelas binaan siswa</span>
            </div>
        </div>

        <div class="flex items-center justify-between text-xs text-slate-400">
            <span>Ditugaskan pada: {{ $tes->created_at ? $tes->created_at->format('d F Y, H:i') . ' WIB' : '-' }}</span>
            @if($sesi && $sesi->status_pengerjaan === 'sedang_mengerjakan')
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                    Sesi Aktif Sedang Berlangsung
                </span>
            @endif
        </div>
    </div>

    <!-- Petunjuk Pengerjaan Asesmen (Format Bersih & Rapi) -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 md:p-6 space-y-4">
        <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ $tes->isForm() ? 'Petunjuk & Ketentuan Pengisian Formulir' : 'Petunjuk & Ketentuan Pengerjaan Asesmen' }}</span>
        </h3>

        <ul class="text-xs text-slate-600 space-y-2 leading-relaxed list-disc list-inside">
            @if($tes->isForm())
                <li>
                    Isilah setiap butir pertanyaan dengan data yang <strong>sebenarnya dan akurat</strong> untuk keperluan pendataan bimbingan konseling.
                </li>
                <li>
                    Sistem dilengkapi fitur <strong>Penyimpanan Otomatis (Auto-Save)</strong> saat Anda memilih opsi atau mengetikkan isian jawaban.
                </li>
                <li>
                    Informasi dan data yang Anda berikan bersifat <strong>rahasia</strong> dan hanya ditinjau oleh Guru BK pengampu.
                </li>
                <li>
                    Pastikan seluruh pertanyaan telah terisi sebelum menekan tombol <strong>Kirim Formulir</strong> di akhir halaman.
                </li>
            @else
                <li>
                    Waktu pengerjaan akan <strong>langsung dihitung mundur</strong> segera setelah Anda menekan tombol mulai.
                </li>
                <li>
                    Sistem dilengkapi fitur <strong>Penyimpanan Otomatis (Auto-Save)</strong> saat Anda memilih opsi atau mengetik jawaban.
                </li>
                <li>
                    Gunakan opsi <strong>Tandai Ragu-ragu</strong> jika ingin meninjau kembali soal sebelum menyerahkan.
                </li>
                <li>
                    Jika durasi habis, sistem akan <strong>secara otomatis mengumpulkan</strong> seluruh jawaban yang tersimpan.
                </li>
                <li>
                    Setiap siswa hanya memiliki <strong>1 kali kesempatan sesi</strong> untuk menyelesaikan paket tes ini.
                </li>
            @endif
        </ul>

        <!-- Form Aksi Mulai / Lanjutkan -->
        <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-xs text-slate-500">
                @if($sesi && $sesi->status_pengerjaan === 'sedang_mengerjakan')
                    <span class="font-semibold text-slate-800">
                        Anda memiliki sesi yang belum diselesaikan.
                    </span>
                @else
                    <span>Pastikan koneksi internet stabil sebelum memulai.</span>
                @endif
            </div>

            <form action="{{ route('siswa.tes.start', $tes->id_tes) }}" method="POST">
                @csrf
                @if($sesi && $sesi->status_pengerjaan === 'sedang_mengerjakan')
                    <button
                        type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold py-2.5 px-5 rounded-xl shadow-sm transition-colors cursor-pointer"
                    >
                        <span>{{ $tes->isForm() ? 'Lanjutkan Pengisian' : 'Lanjutkan Pengerjaan' }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                @else
                    <button
                        type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold py-2.5 px-5 rounded-xl shadow-sm transition-colors cursor-pointer"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ $tes->isForm() ? 'Mulai Isi Formulir' : 'Mulai Kerjakan Tes' }}</span>
                    </button>
                @endif
            </form>
        </div>
    </div>

</div>
@endsection
