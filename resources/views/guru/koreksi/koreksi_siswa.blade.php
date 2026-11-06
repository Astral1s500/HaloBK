@extends('layouts.app')

@section('title', 'Koreksi Jawaban: ' . $siswa->nama_siswa)

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('guru.dashboard') }}" class="hover:text-slate-900 transition-colors">Dashboard</a>
                <span>&rsaquo;</span>
                <a href="{{ route('guru.koreksi.index') }}" class="hover:text-slate-900 transition-colors">Koreksi & Nilai</a>
                <span>&rsaquo;</span>
                <a href="{{ route('guru.koreksi.show', $tes->id_tes) }}" class="hover:text-slate-900 transition-colors">{{ $tes->judul }}</a>
                <span>&rsaquo;</span>
                <span class="text-slate-800 font-semibold">{{ $siswa->nama_siswa }}</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Koreksi Jawaban Siswa</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Siswa: <strong>{{ $siswa->nama_siswa }}</strong> (NISN: {{ $siswa->nisn }})
                &bull; Tes: <strong>{{ $tes->judul }}</strong>
            </p>
        </div>

        <a
            href="{{ route('guru.koreksi.show', $tes->id_tes) }}"
            class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 py-2.5 px-4 rounded-xl transition-colors cursor-pointer shrink-0"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Rekapitulasi
        </a>
    </div>

    <!-- Info Card Siswa -->
    <div class="bg-slate-900 text-white rounded-2xl p-5 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="space-y-1">
            <h3 class="text-sm font-bold text-white">{{ $siswa->nama_siswa }}</h3>
            <p class="text-xs text-slate-400">
                NISN: <span class="font-mono text-slate-300">{{ $siswa->nisn }}</span>
                &bull; Submit: {{ $sesi->waktu_submit ? $sesi->waktu_submit->format('d M Y, H:i') : '-' }}
            </p>
        </div>
        <div class="flex items-center gap-4">
            <div class="text-center">
                <span class="block text-2xl font-extrabold text-white">{{ number_format($sesi->total_skor, 1) }}</span>
                <span class="block text-[10px] text-slate-400 font-medium">Total Skor</span>
            </div>
            <div class="text-center border-l border-slate-700 pl-4">
                <span class="block text-2xl font-extrabold text-slate-300">{{ $soals->sum('bobot_nilai') }}</span>
                <span class="block text-[10px] text-slate-400 font-medium">Bobot Maksimal</span>
            </div>
        </div>
    </div>

    <!-- Form Batch Penilaian -->
    <form action="{{ route('guru.koreksi.simpanSemua', $sesi->id_sesi) }}" method="POST">
        @csrf

        <!-- Daftar Soal & Jawaban Siswa -->
        <div class="space-y-4">
            @foreach($soals as $index => $soal)
                @php
                    $jawaban = $jawabanMap->get($soal->id_soal);
                    $isPG = ($soal->tipe_soal === 'PG');
                @endphp

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 md:p-6 space-y-4">
                    <!-- Header Soal -->
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-lg bg-slate-900 text-white font-bold text-xs flex items-center justify-center">
                                {{ $index + 1 }}
                            </span>
                            @if($isPG)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                    Pilihan Ganda
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                    Esai
                                </span>
                            @endif
                            <span class="text-xs text-slate-500 font-medium">Bobot: <strong>{{ $soal->bobot_nilai }}</strong></span>
                        </div>

                        <!-- Nilai untuk soal ini -->
                        <div class="flex items-center gap-2">
                            @if($jawaban)
                                @if($isPG)
                                    @if($jawaban->pilihanJawaban && $jawaban->pilihanJawaban->is_benar)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-100 text-emerald-800">
                                            ✓ Benar ({{ number_format($jawaban->nilai_soal, 1) }})
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-red-100 text-red-700">
                                            ✗ Salah (0)
                                        </span>
                                    @endif
                                @else
                                    {{-- Esai: Input Penilaian Manual --}}
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-[11px] text-slate-500 font-medium">Nilai:</span>
                                        <input 
                                            type="number" 
                                            name="nilai[{{ $jawaban->id_jawaban_siswa }}]" 
                                            value="{{ old('nilai.' . $jawaban->id_jawaban_siswa, $jawaban->nilai_soal) }}" 
                                            step="0.5" 
                                            min="0" 
                                            max="{{ $soal->bobot_nilai }}"
                                            placeholder="0"
                                            class="w-20 px-2 py-1 bg-slate-50 border border-slate-300 rounded-lg text-xs font-bold text-slate-900 text-center focus:outline-none focus:ring-1 focus:ring-slate-900 focus:border-slate-900"
                                        >
                                        <span class="text-[11px] text-slate-400">/ {{ $soal->bobot_nilai }}</span>
                                    </div>
                                @endif
                            @else
                                <span class="text-xs text-slate-400 italic">Tidak Dijawab</span>
                            @endif
                        </div>
                    </div>

                    <!-- Teks Pertanyaan -->
                    <p class="text-sm font-medium text-slate-800 leading-relaxed whitespace-pre-line">{{ $soal->pertanyaan }}</p>

                    @if($soal->gambar_lampiran)
                        <div>
                            <img 
                                src="{{ asset('storage/' . $soal->gambar_lampiran) }}" 
                                alt="Lampiran Soal" 
                                class="max-h-48 rounded-xl border border-slate-200 object-contain"
                            >
                        </div>
                    @endif

                    <!-- Jawaban Siswa -->
                    @if($jawaban)
                        @if($isPG)
                            {{-- Tampilkan opsi PG dengan highlight jawaban siswa dan kunci benar --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 pt-2">
                                @foreach($soal->pilihanJawabans as $pIndex => $pilihan)
                                    @php
                                        $huruf = chr(65 + $pIndex);
                                        $isJawabanSiswa = ($jawaban->id_pilihan == $pilihan->id_pilihan);
                                        $isKunciBenar = $pilihan->is_benar;
                                    @endphp
                                    <div class="p-2.5 rounded-xl border text-xs flex items-start gap-2.5 
                                        @if($isJawabanSiswa && $isKunciBenar)
                                            bg-emerald-50/70 border-emerald-300 text-emerald-950 font-semibold
                                        @elseif($isJawabanSiswa && !$isKunciBenar)
                                            bg-red-50/70 border-red-300 text-red-900 font-semibold
                                        @elseif($isKunciBenar)
                                            bg-emerald-50/50 border-emerald-200 text-emerald-800
                                        @else
                                            bg-slate-50 border-slate-200 text-slate-600
                                        @endif
                                    ">
                                        <span class="w-5 h-5 rounded-md flex items-center justify-center font-bold shrink-0 text-[11px]
                                            @if($isJawabanSiswa && $isKunciBenar)
                                                bg-emerald-600 text-white
                                            @elseif($isJawabanSiswa && !$isKunciBenar)
                                                bg-red-500 text-white
                                            @elseif($isKunciBenar)
                                                bg-emerald-200 text-emerald-800
                                            @else
                                                bg-slate-200 text-slate-600
                                            @endif
                                        ">
                                            {{ $huruf }}
                                        </span>
                                        <span class="flex-1 leading-relaxed">{{ $pilihan->teks_pilihan }}</span>

                                        @if($isJawabanSiswa)
                                            <span class="text-[9px] font-bold uppercase px-1.5 py-0.5 rounded shrink-0
                                                @if($isKunciBenar) bg-emerald-100 text-emerald-700
                                                @else bg-red-100 text-red-600 @endif
                                            ">
                                                Jawaban Siswa
                                            </span>
                                        @endif
                                        @if($isKunciBenar && !$isJawabanSiswa)
                                            <span class="text-[9px] font-bold uppercase bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded shrink-0">
                                                Kunci Benar
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            {{-- Tampilkan jawaban esai siswa --}}
                            <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl space-y-2">
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <span class="text-xs font-bold text-amber-800">Jawaban Esai Siswa:</span>
                                </div>
                                <p class="text-xs text-slate-800 leading-relaxed whitespace-pre-line bg-white p-3 rounded-lg border border-amber-100">{{ $jawaban->jawaban_esai ?? '(Kosong / Tidak Dijawab)' }}</p>
                            </div>
                        @endif
                    @else
                        <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl">
                            <span class="text-xs text-slate-400 italic">Siswa tidak menjawab soal ini.</span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Tombol Simpan Semua Nilai Esai -->
        @if($soals->where('tipe_soal', 'Esai')->count() > 0)
            <div class="mt-6 flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
                <a 
                    href="{{ route('guru.koreksi.show', $tes->id_tes) }}" 
                    class="px-4 py-2.5 text-xs font-semibold text-slate-600 hover:text-slate-800 transition-colors"
                >
                    Batal
                </a>
                <button 
                    type="submit" 
                    class="bg-slate-900 hover:bg-slate-800 text-white font-semibold py-2.5 px-6 rounded-xl text-xs tracking-wide transition-colors cursor-pointer shadow-sm"
                >
                    Simpan Semua Nilai Esai &amp; Hitung Ulang Skor
                </button>
            </div>
        @endif
    </form>

</div>
@endsection
