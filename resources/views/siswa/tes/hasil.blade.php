@extends('layouts.app')

@section('title', 'Laporan Hasil Asesmen: ' . $tes->judul)

@section('content')
<div class="space-y-4 sm:space-y-5">

    <!-- Top Action Bar (Print Hidden) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 print:hidden">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('siswa.dashboard') }}" class="hover:text-slate-900 transition-colors">Dashboard</a>
                <span>&rsaquo;</span>
                <span class="text-slate-800 font-semibold">Hasil Asesmen</span>
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
            <div class="mt-2 pt-2 border-t border-slate-200 text-xs text-slate-500 flex flex-wrap items-center gap-x-4 gap-y-1">
                <span>Kategori: <strong class="text-slate-700">{{ $tes->kategori->nama_kategori ?? 'Umum' }}</strong></span>
                <span class="w-px h-3 bg-slate-300 hidden sm:inline-block"></span>
                <span>Guru BK: <strong class="text-slate-700">{{ $tes->guruBk->nama_guru ?? 'Guru BK' }}</strong></span>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <!-- Tombol Cetak Dokumen -->
            <button
                type="button"
                onclick="window.print()"
                class="inline-flex items-center gap-1.5 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold py-2 px-3.5 rounded-lg border border-slate-300/90 ring-1 ring-slate-900/5 shadow-2xs transition-colors cursor-pointer"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Cetak Lembar Hasil</span>
            </button>

            <!-- Tombol Kembali -->
            <a
                href="{{ route('siswa.dashboard') }}"
                class="inline-flex items-center gap-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold py-2 px-3.5 rounded-lg shadow-2xs transition-colors cursor-pointer"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Dashboard</span>
            </a>
        </div>
    </div>

    <!-- Official Report Header (Hanya Muncul Saat Print) -->
    <div class="hidden print:block pb-5 border-b border-slate-300 space-y-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo HaloBK" class="w-12 h-12 object-contain">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block">Layanan Bimbingan &amp; Konseling</span>
                    <h1 class="text-lg font-bold text-slate-900">LEMBAR HASIL ASESMEN DIGITAL</h1>
                    <p class="text-xs text-slate-600">Sistem Bimbingan Konseling HaloBK</p>
                </div>
            </div>
            <div class="text-right text-xs">
                <span class="font-mono text-slate-500">#SESI-{{ str_pad($sesi->id_sesi, 5, '0', STR_PAD_LEFT) }}</span>
                <span class="block font-bold text-emerald-700">Telah Diserahkan</span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 text-xs bg-slate-50 p-3 rounded-lg border border-slate-200">
            <div>
                <p><strong>Nama:</strong> {{ $siswa->nama_siswa }}</p>
                <p><strong>NISN:</strong> {{ $siswa->nisn }}</p>
                <p><strong>Kelas:</strong> {{ $tes->kelas->nama_kelas ?? 'Umum' }}</p>
            </div>
            <div>
                <p><strong>Asesmen:</strong> {{ $tes->judul }}</p>
                <p><strong>Guru BK:</strong> {{ $tes->guruBk->nama_guru ?? '-' }}</p>
                <p><strong>Waktu Penyerahan:</strong> {{ $sesi->waktu_submit ? $sesi->waktu_submit->format('d M Y, H:i') : '-' }} WIB</p>
            </div>
        </div>
    </div>

    <!-- 1. Executive Summary Box (Format Bersih, Tanpa Catatan & Menggunakan Garis Horizontal) -->
    <div class="bg-slate-900 text-white rounded-2xl p-5 md:p-6 shadow-sm border border-slate-800 space-y-4">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 {{ ($stats->total_esai > 0 && $stats->esai_belum_dinilai > 0) ? 'pb-4 border-b border-slate-800' : '' }}">
            <div>
                <span class="block text-[11px] font-medium text-slate-400">Total Skor Akhir</span>
                <div class="flex items-baseline gap-1 mt-0.5">
                    <span class="text-2xl sm:text-3xl font-extrabold text-white leading-tight">
                        {{ number_format($sesi->total_skor, 1) }}
                    </span>
                    <span class="text-xs text-slate-400 font-medium">/ {{ $stats->bobot_total }}</span>
                </div>
                <div class="w-full border-t border-slate-800 my-1.5"></div>
                <span class="text-[10px] text-slate-400 block">Capaian: {{ $stats->persentase }}%</span>
            </div>

            <div>
                <span class="block text-[11px] font-medium text-slate-400">Predikat</span>
                <div class="mt-0.5">
                    <span class="text-2xl sm:text-3xl font-extrabold text-white leading-tight block">
                        Grade {{ $predikat->grade }}
                    </span>
                </div>
            </div>

            <div>
                <span class="block text-[11px] font-medium text-slate-400">Hasil Pilihan Ganda</span>
                <div class="flex items-center gap-2 text-xs font-semibold mt-1">
                    <span class="text-emerald-400">{{ $stats->pg_benar }} Benar</span>
                    <span class="text-slate-600">/</span>
                    <span class="text-rose-400">{{ $stats->pg_salah + $stats->pg_kosong }} Salah</span>
                </div>
                <div class="w-full border-t border-slate-800 my-1.5"></div>
                <span class="text-[10px] text-slate-400 block">Akurasi Soal: {{ $stats->akurasi_pg }}%</span>
            </div>

            <div>
                <span class="block text-[11px] font-medium text-slate-400">Durasi Pengerjaan</span>
                <span class="text-sm sm:text-base font-bold text-white mt-1 block">{{ $durasiPengerjaan }}</span>
                <div class="w-full border-t border-slate-800 my-1.5"></div>
                <span class="text-[10px] text-slate-400 block">
                    {{ $sesi->waktu_submit ? $sesi->waktu_submit->format('d/m/Y, H:i') : '-' }} WIB
                </span>
            </div>
        </div>

        @if($stats->total_esai > 0 && $stats->esai_belum_dinilai > 0)
            <div class="flex items-center justify-between text-xs text-slate-400 pt-0.5">
                <span class="text-slate-400">Pemeriksaan Esai:</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-500/20 text-amber-300 border border-amber-500/30 shrink-0">
                    {{ $stats->esai_belum_dinilai }} Esai Menunggu Penilaian Guru
                </span>
            </div>
        @endif
    </div>

    <!-- 2. Peta Nomor Soal Visual (Question Map + Stroke Elegan Tipis) -->
    <div class="bg-white rounded-2xl border border-slate-200/90 ring-1 ring-slate-900/5 shadow-sm p-4.5 space-y-3 print:hidden">
        <div class="flex items-center justify-between flex-wrap gap-2">
            <div>
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">
                    Peta Butir Soal (Klik nomor untuk tinjau soal)
                </h3>
                <p class="text-[11px] text-slate-400">Lihat ringkasan jawaban tanpa harus membaca teks panjang ke bawah.</p>
            </div>

            <div class="flex items-center gap-3 text-[11px] text-slate-500">
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded bg-emerald-600"></span> Benar ({{ $stats->pg_benar }})
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded bg-rose-600"></span> Salah ({{ $stats->pg_salah + $stats->pg_kosong }})
                </span>
                @if($stats->total_esai > 0)
                    <span class="inline-flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded bg-slate-800"></span> Esai ({{ $stats->total_esai }})
                    </span>
                @endif
            </div>
        </div>

        <div class="flex flex-wrap gap-1.5 pt-0.5">
            @foreach($tes->soals as $index => $soal)
                @php
                    $jawaban = $jawabanList->get($soal->id_soal);
                    $isPG = ($soal->tipe_soal === 'PG');
                    $isCorrect = $isPG && $jawaban && (float)$jawaban->nilai_soal > 0;
                    
                    if ($isPG) {
                        $btnStyle = $isCorrect 
                            ? 'bg-emerald-50 text-emerald-800 border-emerald-300 ring-1 ring-emerald-500/10 hover:bg-emerald-100' 
                            : 'bg-rose-50 text-rose-800 border-rose-300 ring-1 ring-rose-500/10 hover:bg-rose-100';
                    } else {
                        $btnStyle = ($jawaban && !is_null($jawaban->nilai_soal))
                            ? 'bg-slate-800 text-white hover:bg-slate-700'
                            : 'bg-slate-100 text-slate-700 border-slate-200 hover:bg-slate-200';
                    }
                @endphp

                <button
                    type="button"
                    onclick="scrollToQuestion({{ $soal->id_soal }})"
                    class="w-7 h-7 rounded-lg font-bold text-xs flex items-center justify-center border transition-colors cursor-pointer {{ $btnStyle }}"
                    title="Soal #{{ $index + 1 }} ({{ $isPG ? ($isCorrect ? 'Benar' : 'Salah') : 'Esai' }})"
                >
                    {{ $index + 1 }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- 3. Rincian Butir Soal dengan Filter Cepat (Setiap Kartu Memiliki Stroke Elegan Tipis) -->
    <div class="bg-white rounded-2xl border border-slate-200/90 ring-1 ring-slate-900/5 shadow-sm p-5 md:p-6 space-y-4">
        
        <!-- Header & Tab Filter -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-sm font-bold text-slate-900 tracking-tight">Rincian Lembar Jawaban</h3>
                <p class="text-[11px] text-slate-500">Pilih tab filter untuk fokus memeriksa jawaban tertentu.</p>
            </div>

            <!-- Tab Filter Soal Cepat -->
            <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl text-xs font-semibold print:hidden border border-slate-200/60" id="soalFilterTabs">
                <button 
                    type="button" 
                    onclick="filterSoals('all', this)"
                    class="px-3 py-1.5 rounded-lg transition-all cursor-pointer bg-slate-900 text-white font-bold text-[11px] shadow-2xs"
                >
                    Semua ({{ $stats->total_soal }})
                </button>
                <button 
                    type="button" 
                    onclick="filterSoals('benar', this)"
                    class="px-3 py-1.5 rounded-lg transition-all cursor-pointer text-slate-600 hover:text-slate-900 font-semibold text-[11px]"
                >
                    Benar ({{ $stats->pg_benar }})
                </button>
                <button 
                    type="button" 
                    onclick="filterSoals('salah', this)"
                    class="px-3 py-1.5 rounded-lg transition-all cursor-pointer text-slate-600 hover:text-slate-900 font-semibold text-[11px]"
                >
                    Salah ({{ $stats->pg_salah + $stats->pg_kosong }})
                </button>
                @if($stats->total_esai > 0)
                    <button 
                        type="button" 
                        onclick="filterSoals('esai', this)"
                        class="px-3 py-1.5 rounded-lg transition-all cursor-pointer text-slate-600 hover:text-slate-900 font-semibold text-[11px]"
                    >
                        Esai ({{ $stats->total_esai }})
                    </button>
                @endif
            </div>
        </div>

        <!-- Daftar Butir Soal dengan Stroke Elegan Tipis Sesuai Status -->
        <div class="space-y-3.5" id="soalsContainer">
            @foreach($tes->soals as $index => $soal)
                @php
                    $jawaban = $jawabanList->get($soal->id_soal);
                    $isPG = ($soal->tipe_soal === 'PG');
                    $isCorrect = $isPG && $jawaban && (float)$jawaban->nilai_soal > 0;
                    $filterType = $isPG ? ($isCorrect ? 'benar' : 'salah') : 'esai';

                    if ($isPG) {
                        $soalBorder = $isCorrect 
                            ? 'border-emerald-200/90 ring-1 ring-emerald-500/15 hover:border-emerald-300' 
                            : 'border-rose-200/90 ring-1 ring-rose-500/15 hover:border-rose-300';
                    } else {
                        $soalBorder = 'border-indigo-200/90 ring-1 ring-indigo-500/15 hover:border-indigo-300';
                    }
                @endphp

                <div 
                    id="soal-item-{{ $soal->id_soal }}"
                    data-type="{{ $filterType }}"
                    class="soal-card bg-white rounded-2xl border {{ $soalBorder }} shadow-xs p-4.5 space-y-3 transition-colors"
                >
                    <!-- Header Soal: Nomor, Tipe, Bobot, Status -->
                    <div class="flex items-center justify-between gap-4 pb-2.5 border-b border-slate-100">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-lg bg-slate-900 text-white font-bold text-xs flex items-center justify-center shrink-0 shadow-2xs">
                                {{ $index + 1 }}
                            </span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                {{ $isPG ? 'Pilihan Ganda' : 'Esai' }}
                            </span>
                            <span class="text-xs text-slate-500">Bobot: <strong class="text-slate-700">{{ $soal->bobot_nilai }}</strong> Poin</span>
                        </div>

                        <!-- Status Jawaban -->
                        <div>
                            @if($isPG)
                                @if($isCorrect)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        Benar (+{{ $soal->bobot_nilai }})
                                    </span>
                                @elseif($jawaban && !is_null($jawaban->id_pilihan))
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                        Salah (0)
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-500 border border-slate-200">
                                        Kosong (0)
                                    </span>
                                @endif
                            @else
                                @if($jawaban && !is_null($jawaban->nilai_soal))
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[11px] font-semibold bg-slate-100 text-slate-800 border border-slate-200">
                                        Nilai: {{ number_format($jawaban->nilai_soal, 1) }} / {{ $soal->bobot_nilai }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[11px] font-medium bg-slate-50 text-slate-500 border border-slate-200">
                                        Menunggu Koreksi
                                    </span>
                                @endif
                            @endif
                        </div>
                    </div>

                    <!-- Teks Pertanyaan -->
                    <p class="text-xs md:text-sm text-slate-800 leading-relaxed font-normal">
                        {{ $soal->pertanyaan }}
                    </p>

                    <!-- Jawaban Anda (Ringkas & Bersih) -->
                    <div class="pt-0.5">
                        @if($isPG)
                            @if($jawaban && $jawaban->pilihanJawaban)
                                <div class="p-2.5 rounded-lg border flex items-center gap-2.5 {{ $isCorrect ? 'bg-emerald-50/50 border-emerald-200' : 'bg-rose-50/50 border-rose-200' }}">
                                    <span class="w-5 h-5 rounded font-bold text-[10px] flex items-center justify-center shrink-0 {{ $isCorrect ? 'bg-emerald-700 text-white' : 'bg-rose-700 text-white' }}">
                                        {{ chr(65 + $soal->pilihanJawabans->search(fn($p) => $p->id_pilihan == $jawaban->id_pilihan)) }}
                                    </span>
                                    <span class="text-xs font-semibold {{ $isCorrect ? 'text-emerald-900' : 'text-rose-900' }}">
                                        {{ $jawaban->pilihanJawaban->teks_pilihan }}
                                    </span>
                                </div>
                            @else
                                <div class="p-2.5 rounded-lg border border-slate-200 bg-slate-50 text-xs text-slate-400 italic">
                                    Tidak menjawab butir ini.
                                </div>
                            @endif
                        @else
                            <div class="p-3 rounded-lg border border-slate-200 bg-slate-50 space-y-1">
                                <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider block">Jawaban Esai Anda:</span>
                                @if($jawaban && !empty(trim((string)$jawaban->jawaban_esai)))
                                    <p class="text-xs text-slate-800 whitespace-pre-wrap leading-relaxed">{{ $jawaban->jawaban_esai }}</p>
                                @else
                                    <span class="text-xs text-slate-400 italic">Tidak mengisi jawaban esai.</span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    <!-- Footer Lembar Pengesahan Cetak Resmi (Hanya Muncul Saat Print) -->
    <div class="hidden print:flex items-center justify-between pt-10 text-xs text-slate-700 border-t border-slate-300">
        <div>
            <span class="block">Mengetahui,</span>
            <span class="block">Guru Bimbingan &amp; Konseling</span>
            <div class="h-16"></div>
            <span class="font-bold underline">{{ $tes->guruBk->nama_guru ?? 'Guru BK' }}</span>
            <span class="block text-[10px] text-slate-500">NIP: {{ $tes->guruBk->nip ?? '-' }}</span>
        </div>

        <div class="text-right">
            <span class="block">Dicetak secara digital oleh sistem HaloBK</span>
            <span class="block">Tanggal: {{ now()->format('d F Y, H:i') }} WIB</span>
            <div class="h-16"></div>
            <span class="font-bold underline">{{ $siswa->nama_siswa }}</span>
            <span class="block text-[10px] text-slate-500">NISN: {{ $siswa->nisn }}</span>
        </div>
    </div>

</div>

<!-- Client-Side Vanilla JS Tab Filter & Scroll -->
<script>
function filterSoals(type, btn) {
    const tabs = document.querySelectorAll('#soalFilterTabs button');
    tabs.forEach(b => {
        b.className = 'px-3 py-1.5 rounded-lg transition-all cursor-pointer text-slate-600 hover:text-slate-900 font-semibold text-[11px]';
    });
    btn.className = 'px-3 py-1.5 rounded-lg transition-all cursor-pointer bg-slate-900 text-white font-bold text-[11px] shadow-2xs';

    const cards = document.querySelectorAll('#soalsContainer .soal-card');
    cards.forEach(card => {
        const cardType = card.getAttribute('data-type');
        if (type === 'all' || cardType === type) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

function scrollToQuestion(soalId) {
    const el = document.getElementById('soal-item-' + soalId);
    if (el) {
        // Reset tab ke semua agar soal terlihat
        const allTab = document.querySelector('#soalFilterTabs button');
        if (allTab) {
            filterSoals('all', allTab);
        }
        
        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        // Highlight border
        el.classList.add('ring-2', 'ring-slate-900');
        setTimeout(() => {
            el.classList.remove('ring-2', 'ring-slate-900');
        }, 1500);
    }
}
</script>

<style>
@media print {
    nav, header, aside, .print\:hidden {
        display: none !important;
    }
    body {
        background: white !important;
        color: black !important;
    }
}
</style>
@endsection
