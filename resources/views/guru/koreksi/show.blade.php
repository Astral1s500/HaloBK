@extends('layouts.app')

@section('title', 'Rekapitulasi Nilai & Komparasi: ' . $tes->judul)

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 print:hidden">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('guru.dashboard') }}" class="hover:text-slate-900 transition-colors">Dashboard</a>
                <span>&rsaquo;</span>
                <a href="{{ route('guru.koreksi.index') }}" class="hover:text-slate-900 transition-colors">Koreksi & Nilai</a>
                <span>&rsaquo;</span>
                <span class="text-slate-800 font-semibold">{{ $tes->judul }}</span>
            </div>
            <div class="flex items-center gap-2.5 flex-wrap">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $tes->judul }}</h1>
                @if($tes->isForm())
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Formulir Pendataan
                    </span>
                @endif
            </div>
            <p class="text-xs text-slate-500 mt-0.5">
                Target Kelas: <strong>{{ $tes->kelas->nama_kelas ?? 'Semua Kelas (Materi/Tes Terbuka)' }}</strong>
                &bull; {{ $komparasi->total_soal }} Butir Pertanyaan ({{ $komparasi->total_esai }} Esai)
                &bull; Bobot Total: {{ $komparasi->bobot_total }}
            </p>
        </div>

        <div class="flex items-center gap-2">
            <!-- Tombol Cetak / Export PDF -->
            <button 
                type="button" 
                onclick="window.print()" 
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-300 py-2.5 px-4 rounded-xl shadow-sm transition-colors cursor-pointer"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Cetak Rekapitulasi</span>
            </button>

            <!-- Tombol Kembali -->
            <a
                href="{{ route('guru.koreksi.index') }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 py-2.5 px-4 rounded-xl transition-colors cursor-pointer shrink-0"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Judul Header Cetak (Hanya tampil saat dicetak) -->
    <div class="hidden print:block mb-6 pb-4 border-b border-slate-400">
        <h1 class="text-xl font-bold text-slate-900">REKAPITULASI & KOMPARASI NILAI ASESMEN SISWA</h1>
        <p class="text-xs text-slate-600 mt-1">
            Paket Tes: <strong>{{ $tes->judul }}</strong> | 
            Kelas: <strong>{{ $tes->kelas->nama_kelas ?? 'Umum' }}</strong> | 
            Guru BK: <strong>{{ Auth::guard('guru')->user()->nama_guru }}</strong> | 
            Tanggal Cetak: {{ now()->format('d F Y, H:i') }} WIB
        </p>
    </div>

    <!-- Kartu Komparasi Statistik Berdampingan (Historis vs Terkini) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        
        <!-- 1. Statistik Historis Lengkap -->
        <div class="bg-slate-900 text-white rounded-2xl p-5 shadow-sm border border-slate-800">
            <div class="flex items-center justify-between gap-2 mb-3">
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-white/10 text-white border border-white/20">
                        VERSI LAMA
                    </span>
                    <span class="text-xs font-bold text-slate-200">Seluruh Peserta Selesai</span>
                </div>
            </div>
            <div class="grid grid-cols-4 gap-2 pt-2 border-t border-slate-800 text-center">
                <div>
                    <span class="block text-[10px] font-medium text-slate-400">Peserta</span>
                    <span class="text-xl font-extrabold text-white mt-0.5 block">{{ $statsHistoris->total_peserta }}</span>
                </div>
                <div>
                    <span class="block text-[10px] font-medium text-slate-400">Rata-rata</span>
                    <span class="text-xl font-extrabold text-white mt-0.5 block">{{ number_format($statsHistoris->rata_rata_skor, 1) }}</span>
                </div>
                <div>
                    <span class="block text-[10px] font-medium text-slate-400">Tertinggi</span>
                    <span class="text-xl font-extrabold text-white mt-0.5 block">{{ number_format($statsHistoris->skor_tertinggi, 1) }}</span>
                </div>
                <div>
                    <span class="block text-[10px] font-medium text-slate-400">Terendah</span>
                    <span class="text-xl font-extrabold text-white mt-0.5 block">{{ number_format($statsHistoris->skor_terendah, 1) }}</span>
                </div>
            </div>
        </div>

        <!-- 2. Statistik Terkini (Hanya Siswa Aktif) -->
        <div class="bg-slate-900 text-white rounded-2xl p-5 shadow-sm border border-slate-800">
            <div class="flex items-center justify-between gap-2 mb-3">
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-white/10 text-white border border-white/20">
                        VERSI BARU
                    </span>
                    <span class="text-xs font-bold text-slate-200">Hanya Siswa Aktif Saat Ini</span>
                </div>
            </div>
            <div class="grid grid-cols-4 gap-2 pt-2 border-t border-slate-800 text-center">
                <div>
                    <span class="block text-[10px] font-medium text-slate-400">Peserta Aktif</span>
                    <span class="text-xl font-extrabold text-white mt-0.5 block">{{ $statsTerkini->total_peserta }}</span>
                </div>
                <div>
                    <span class="block text-[10px] font-medium text-slate-400">Rata-rata</span>
                    <span class="text-xl font-extrabold text-white mt-0.5 block">{{ number_format($statsTerkini->rata_rata_skor, 1) }}</span>
                </div>
                <div>
                    <span class="block text-[10px] font-medium text-slate-400">Tertinggi</span>
                    <span class="text-xl font-extrabold text-white mt-0.5 block">{{ number_format($statsTerkini->skor_tertinggi, 1) }}</span>
                </div>
                <div>
                    <span class="block text-[10px] font-medium text-slate-400">Terendah</span>
                    <span class="text-xl font-extrabold text-white mt-0.5 block">{{ number_format($statsTerkini->skor_terendah, 1) }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Banner Indikator Dampak & Selisih Nilai (Hanya Muncul Jika Ada Siswa Keluar) -->
    @if($komparasi->total_siswa_keluar > 0)
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-3.5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs">
            <div class="text-amber-900">
                <strong>Catatan Perubahan:</strong> Terdapat {{ $komparasi->total_siswa_keluar }} siswa keluar/pindah/arsip yang memengaruhi rata-rata nilai sebesar {{ $komparasi->delta_rata_rata > 0 ? '+' : '' }}{{ number_format($komparasi->delta_rata_rata, 1) }} poin.
            </div>
            <div class="text-amber-800 font-bold shrink-0">
                Selisih: {{ $komparasi->delta_rata_rata > 0 ? '+' : '' }}{{ number_format($komparasi->delta_rata_rata, 1) }}
            </div>
        </div>
    @endif

    <!-- 4. Analisis Butir Soal (Ringkas & To-the-Point) -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-base font-bold text-slate-900">Analisis Butir Soal</h3>
            </div>

            <!-- Mini Status Ringkas -->
            <div class="flex items-center gap-2 text-xs">
                <span class="px-2.5 py-0.5 rounded-md bg-slate-100 border border-slate-200 text-slate-700">
                    Sangat Rawan: <strong>{{ $totalSoalKritis }}</strong>
                </span>
                <span class="px-2.5 py-0.5 rounded-md bg-slate-100 border border-slate-200 text-slate-700">
                    Cukup Rawan: <strong>{{ $totalSoalCukupRawan }}</strong>
                </span>
                <span class="px-2.5 py-0.5 rounded-md bg-slate-100 border border-slate-200 text-slate-700">
                    Dikuasai: <strong>{{ $totalSoalAman }}</strong>
                </span>
            </div>
        </div>

        @if($statsHistoris->total_peserta == 0)
            <div class="py-6 text-center text-slate-400 text-xs">
                Belum ada data pengerjaan siswa.
            </div>
        @else
            <!-- Sorotan Singkat Soal Paling Rawan -->
            @if($soalPalingRawanUtama)
                <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-4 space-y-2">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500 shrink-0" title="Soal Rawan"></span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-900 text-white uppercase tracking-wider">
                                Paling Rawan
                            </span>
                            <span class="text-xs font-bold text-slate-900">
                                Soal #{{ $soalPalingRawanUtama->nomor }} ({{ $soalPalingRawanUtama->tipe_soal }})
                            </span>
                        </div>
                        <div class="text-xs font-bold text-slate-900">
                            {{ $soalPalingRawanUtama->persentase_salah }}% Salah ({{ $soalPalingRawanUtama->jumlah_salah }} dari {{ $statsHistoris->total_peserta }} siswa)
                        </div>
                    </div>
                    
                    <p class="text-xs text-slate-700 font-medium">
                        "{{ $soalPalingRawanUtama->pertanyaan }}"
                    </p>

                    @if($soalPalingRawanUtama->tipe_soal === 'Pilihan Ganda')
                        <div class="text-[11px] text-slate-600 flex flex-wrap items-center gap-x-3 gap-y-1 pt-1 border-t border-slate-200/60">
                            <span><strong>Kunci Benar:</strong> <span class="text-emerald-700 font-semibold">{{ $soalPalingRawanUtama->pilihan_benar ? $soalPalingRawanUtama->pilihan_benar->teks_pilihan : '-' }}</span></span>
                            @if($soalPalingRawanUtama->pengecoh_utama && $soalPalingRawanUtama->pengecoh_utama->total_pemilih > 0)
                                <span class="text-slate-300">|</span>
                                <span><strong>Pengecoh Terbanyak:</strong> "{{ $soalPalingRawanUtama->pengecoh_utama->teks_pilihan }}" ({{ $soalPalingRawanUtama->pengecoh_utama->total_pemilih }} siswa)</span>
                            @endif
                        </div>
                    @else
                        <div class="text-[11px] text-slate-600 pt-1 border-t border-slate-200/60">
                            Rata-rata Skor: <strong>{{ $soalPalingRawanUtama->rata_rata_skor }}</strong> / {{ $soalPalingRawanUtama->bobot_nilai }} Poin.
                        </div>
                    @endif
                </div>
            @endif

            <!-- Rincian Seluruh Soal -->
            <div class="space-y-3 pt-1">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Daftar Soal ({{ $analisisSoal->count() }})
                    </span>
                    <!-- Filter Sederhana -->
                    <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-lg text-xs print:hidden">
                        <button 
                            type="button" 
                            onclick="filterSoal('all', this)"
                            class="soal-filter-btn active px-2.5 py-1 font-semibold rounded-md bg-white text-slate-900 shadow-2xs transition-all cursor-pointer"
                        >
                            Semua ({{ $analisisSoal->count() }})
                        </button>
                        <button 
                            type="button" 
                            onclick="filterSoal('sangat_rawan', this)"
                            class="soal-filter-btn px-2.5 py-1 font-semibold rounded-md text-slate-600 hover:text-slate-900 transition-all cursor-pointer"
                        >
                            <span class="w-2 h-2 rounded-full bg-rose-500 inline-block mr-1"></span>Sangat Rawan ({{ $totalSoalKritis }})
                        </button>
                        <button 
                            type="button" 
                            onclick="filterSoal('cukup_rawan', this)"
                            class="soal-filter-btn px-2.5 py-1 font-semibold rounded-md text-slate-600 hover:text-slate-900 transition-all cursor-pointer"
                        >
                            Cukup Rawan ({{ $totalSoalCukupRawan }})
                        </button>
                        <button 
                            type="button" 
                            onclick="filterSoal('aman', this)"
                            class="soal-filter-btn px-2.5 py-1 font-semibold rounded-md text-slate-600 hover:text-slate-900 transition-all cursor-pointer"
                        >
                            Dikuasai ({{ $totalSoalAman }})
                        </button>
                    </div>
                </div>

                <div class="space-y-2" id="daftarSoalContainer">
                    @foreach($analisisSoal as $item)
                        <div class="soal-item-card border border-slate-200 rounded-xl p-3.5 bg-white transition-all hover:border-slate-300" data-tingkat="{{ $item->tingkat_kerawanan }}">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    {{-- Indikator Bulatan Merah untuk Soal Rawan --}}
                                    @if($item->tingkat_kerawanan === 'sangat_rawan')
                                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500 shrink-0" title="Soal Rawan (Banyak Siswa Salah)"></span>
                                    @elseif($item->tingkat_kerawanan === 'cukup_rawan')
                                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400 shrink-0" title="Cukup Rawan"></span>
                                    @else
                                        <span class="w-2.5 h-2.5 rounded-full bg-transparent shrink-0"></span>
                                    @endif

                                    <span class="w-5 h-5 rounded bg-slate-900 text-white font-bold text-xs flex items-center justify-center shrink-0">
                                        {{ $item->nomor }}
                                    </span>
                                    <span class="text-xs font-semibold text-slate-700">
                                        {{ $item->tipe_soal }} ({{ $item->bobot_nilai }} Poin)
                                    </span>
                                    
                                    @if($item->tingkat_kerawanan === 'sangat_rawan')
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[10px] font-semibold border bg-slate-100 text-slate-800 border-slate-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            <span>Sangat Rawan</span>
                                        </span>
                                    @elseif($item->tingkat_kerawanan === 'cukup_rawan')
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[10px] font-medium border bg-slate-100 text-slate-700 border-slate-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                            <span>Cukup Rawan</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium border bg-emerald-50 text-emerald-700 border-emerald-200">
                                            Dikuasai Baik
                                        </span>
                                    @endif
                                </div>

                                <div class="flex items-center gap-3 text-xs shrink-0 font-medium">
                                    <span class="text-slate-800">Salah: <strong>{{ $item->persentase_salah }}%</strong> ({{ $item->jumlah_salah }})</span>
                                    <span class="text-slate-300">|</span>
                                    <span class="text-emerald-700">Benar: <strong>{{ $item->persentase_benar }}%</strong> ({{ $item->jumlah_benar }})</span>
                                </div>
                            </div>

                            <!-- Pertanyaan Soal -->
                            <div class="mt-2 text-xs text-slate-800 leading-relaxed font-medium">
                                {{ $item->pertanyaan }}
                                
                                @if($item->gambar_lampiran)
                                    <span class="ml-2">
                                        <a href="{{ asset('storage/' . $item->gambar_lampiran) }}" target="_blank" class="text-[11px] text-slate-500 hover:underline">
                                            [Lihat Gambar]
                                        </a>
                                    </span>
                                @endif
                            </div>

                            @if($item->tipe_soal === 'Pilihan Ganda')
                                <div class="mt-1.5 text-[11px] text-slate-500 flex flex-wrap items-center gap-x-2">
                                    <span>Kunci: <strong class="text-emerald-700">{{ $item->pilihan_benar ? $item->pilihan_benar->teks_pilihan : '-' }}</strong></span>
                                    @if($item->pengecoh_utama && $item->pengecoh_utama->total_pemilih > 0)
                                        <span>&bull; Paling banyak terkecoh: "{{ $item->pengecoh_utama->teks_pilihan }}" ({{ $item->pengecoh_utama->total_pemilih }} siswa)</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Tabel Rekapitulasi Nilai Per Siswa -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        
        <!-- Filter Tabs & Judul Tabel -->
        <div class="p-5 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Daftar Rekapitulasi Nilai Peserta</h3>
                <p class="text-[11px] text-slate-500 mt-0.5">Urutan ranking berdasarkan skor akhir. Klik "Koreksi" untuk penilaian jawaban esai.</p>
            </div>

            <!-- Tab Filter Tampilan (Hanya di layar) -->
            <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl print:hidden text-xs">
                <button 
                    type="button" 
                    onclick="filterTable('all', this)"
                    class="filter-tab-btn active px-3 py-1.5 font-semibold rounded-lg bg-white text-slate-900 shadow-xs transition-all cursor-pointer"
                >
                    Semua Peserta ({{ $statsHistoris->total_peserta }})
                </button>
                <button 
                    type="button" 
                    onclick="filterTable('active', this)"
                    class="filter-tab-btn px-3 py-1.5 font-semibold rounded-lg text-slate-600 hover:text-slate-900 transition-all cursor-pointer"
                >
                    Hanya Aktif ({{ $statsTerkini->total_peserta }})
                </button>
                <button 
                    type="button" 
                    onclick="filterTable('departed', this)"
                    class="filter-tab-btn px-3 py-1.5 font-semibold rounded-lg text-slate-600 hover:text-slate-900 transition-all cursor-pointer"
                >
                    Siswa Keluar / Arsip ({{ $komparasi->total_siswa_keluar }})
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="rekapTable">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold text-slate-600 uppercase tracking-wider">
                        <th class="py-3 px-4 text-center w-12">No</th>
                        <th class="py-3 px-4">Nama Siswa</th>
                        <th class="py-3 px-4">NISN</th>
                        <th class="py-3 px-4 text-center">Status Siswa</th>
                        <th class="py-3 px-4 text-center">Waktu Submit</th>
                        <th class="py-3 px-4 text-center">Total Skor</th>
                        <th class="py-3 px-4 text-center">Status Esai</th>
                        <th class="py-3 px-4 text-center w-32 print:hidden">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                    @forelse($sesiList as $index => $sesi)
                        <tr 
                            class="hover:bg-slate-50/60 transition-colors sesi-row"
                            data-is-active="{{ $sesi->is_aktif ? '1' : '0' }}"
                        >
                            <td class="py-3.5 px-4 text-center font-medium text-slate-400">{{ $index + 1 }}</td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-900 block">{{ $sesi->siswa->nama_siswa ?? 'Siswa Terhapus' }}</span>
                                @if(!$sesi->is_aktif)
                                    <span class="text-[10px] text-amber-700 font-medium block">
                                        {{ $sesi->status_label }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 font-mono">{{ $sesi->siswa->nisn ?? '-' }}</td>
                            
                            <!-- Badge Status Keanggotaan Siswa -->
                            <td class="py-3.5 px-4 text-center">
                                @if($sesi->is_aktif)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Aktif di Kelas
                                    </span>
                                @elseif($sesi->status_keanggotaan === 'soft_deleted')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800" title="Akun siswa telah dinonaktifkan/diarsipkan di sistem">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                        Akun Nonaktif / Arsip
                                    </span>
                                @elseif($sesi->status_keanggotaan === 'keluar_kelas')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-orange-100 text-orange-800" title="Siswa tidak lagi terdaftar di kelas ini">
                                        <span class="h-1.5 w-1.5 rounded-full bg-orange-500"></span>
                                        Keluar dari Kelas
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-200 text-slate-700">
                                        Data Dihapus Permanen
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-center text-slate-500">
                                {{ $sesi->waktu_submit ? $sesi->waktu_submit->format('d M Y, H:i') : '-' }} WIB
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                <span class="text-sm font-extrabold text-slate-900">{{ number_format($sesi->total_skor, 1) }}</span>
                                <span class="text-slate-400"> / {{ $komparasi->bobot_total }}</span>
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                @if($komparasi->total_esai === 0)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-500">
                                        Tidak ada Esai
                                    </span>
                                @elseif($sesi->esai_belum_dinilai > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                        {{ $sesi->esai_belum_dinilai }} Belum Dinilai
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        ✓ Sudah Lengkap
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-center print:hidden">
                                <a 
                                    href="{{ route('guru.koreksi.siswa', $sesi->id_sesi) }}"
                                    class="inline-flex items-center gap-1 bg-slate-900 hover:bg-slate-800 text-white text-[11px] font-semibold py-1.5 px-3 rounded-lg transition-colors cursor-pointer"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                    Koreksi
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-xs text-slate-400">
                                Belum ada siswa yang menyelesaikan tes ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Script Filter Tab Interaktif -->
<script>
function filterTable(type, btn) {
    // Update styling tombol tab
    document.querySelectorAll('.filter-tab-btn').forEach(function(b) {
        b.classList.remove('bg-white', 'text-slate-900', 'shadow-xs');
        b.classList.add('text-slate-600');
    });
    btn.classList.add('bg-white', 'text-slate-900', 'shadow-xs');
    btn.classList.remove('text-slate-600');

    // Filter baris tabel
    const rows = document.querySelectorAll('.sesi-row');
    rows.forEach(function(row) {
        const isActive = row.getAttribute('data-is-active') === '1';
        if (type === 'all') {
            row.style.display = '';
        } else if (type === 'active') {
            row.style.display = isActive ? '' : 'none';
        } else if (type === 'departed') {
            row.style.display = !isActive ? '' : 'none';
        }
    });
}

function filterSoal(type, btn) {
    // Update styling tombol filter soal
    document.querySelectorAll('.soal-filter-btn').forEach(function(b) {
        b.classList.remove('bg-white', 'text-slate-900', 'shadow-xs');
        b.classList.add('text-slate-600');
    });
    btn.classList.add('bg-white', 'text-slate-900', 'shadow-xs');
    btn.classList.remove('text-slate-600');

    // Filter elemen kartu soal
    const cards = document.querySelectorAll('.soal-item-card');
    cards.forEach(function(card) {
        const tingkat = card.getAttribute('data-tingkat');
        if (type === 'all' || tingkat === type) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>

<style>
@media print {
    /* Sembunyikan elemen navigasi dan tombol interaktif saat mencetak */
    nav, header, aside, .print\:hidden {
        display: none !important;
    }
    body {
        background: white !important;
        color: black !important;
    }
    .sesi-row {
        display: table-row !important;
    }
}
</style>
@endsection
