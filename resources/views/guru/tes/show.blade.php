@extends('layouts.app')

@section('title', 'Detail Paket Tes: ' . $tes->judul)

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('guru.dashboard') }}" class="hover:text-slate-900 transition-colors">Dashboard</a>
                <span>&rsaquo;</span>
                <a href="{{ route('guru.tes.index') }}" class="hover:text-slate-900 transition-colors">Paket Tes dan Soal</a>
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
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                        Tes CBT
                    </span>
                @endif
            </div>
            <p class="text-xs text-slate-500 mt-0.5">
                {{ $tes->isForm() ? 'Kelola butir-butir pertanyaan angket / formulir pendataan ini.' : 'Kelola butir-butir soal Pilihan Ganda & Esai untuk paket tes ini.' }}
            </p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <a
                href="{{ route('guru.tes.index') }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 py-2.5 px-4 rounded-xl transition-colors cursor-pointer"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
            <a 
                href="{{ route('guru.tes.edit', $tes->id_tes) }}" 
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-700 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 py-2.5 px-4 rounded-xl transition-colors cursor-pointer border border-slate-200"
            >
                <span>Edit Pengaturan {{ $tes->isForm() ? 'Formulir' : 'Tes' }}</span>
            </a>
            <a 
                href="{{ route('guru.tes.soal.create', $tes->id_tes) }}" 
                class="inline-flex items-center gap-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold py-2.5 px-4 rounded-xl shadow-sm transition-colors cursor-pointer"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>{{ $tes->isForm() ? '+ Tambah Pertanyaan' : '+ Tambah Butir Soal' }}</span>
            </a>
        </div>
    </div>

    <!-- Summary Box Detail Paket Tes -->
    <div class="bg-slate-900 text-white rounded-2xl p-6 shadow-sm">
        <div class="grid grid-cols-2 sm:grid-cols-6 gap-4 pb-4 border-b border-slate-800">
            <div>
                <span class="block text-[11px] font-medium text-slate-400">Tipe Asesmen</span>
                <span class="text-sm font-bold text-white mt-0.5 block">
                    {{ $tes->isForm() ? 'Formulir Pendataan' : 'Tes CBT' }}
                </span>
            </div>
            <div>
                <span class="block text-[11px] font-medium text-slate-400">Target Kelas</span>
                <span class="text-sm font-bold text-white mt-0.5 block">{{ $tes->kelas->nama_kelas ?? '-' }}</span>
            </div>
            <div>
                <span class="block text-[11px] font-medium text-slate-400">Kategori</span>
                <span class="text-sm font-bold text-white mt-0.5 block">{{ $tes->kategori->nama_kategori ?? '-' }}</span>
            </div>
            <div>
                <span class="block text-[11px] font-medium text-slate-400">Durasi Pengerjaan</span>
                <span class="text-sm font-bold text-white mt-0.5 block">{{ $tes->durasi_menit }} Menit</span>
            </div>
            <div>
                <span class="block text-[11px] font-medium text-slate-400">{{ $tes->isForm() ? 'Total Pertanyaan' : 'Total Butir Soal' }}</span>
                <span class="text-sm font-bold text-white mt-0.5 block">{{ $tes->soals->count() }} Butir</span>
            </div>
            <div>
                <span class="block text-[11px] font-medium text-slate-400">Tanggal Dibuat</span>
                <span class="text-sm font-bold text-white mt-0.5 block">{{ $tes->created_at ? $tes->created_at->format('d M Y') : '-' }}</span>
                @if($tes->created_at)
                    <span class="text-[11px] text-slate-400">{{ $tes->created_at->format('H:i') }} WIB</span>
                @endif
            </div>
        </div>

        <!-- Quick Status Toggle Form -->
        <div class="pt-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-400">Status Saat Ini:</span>
                @if($tes->status === 'aktif')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                        Aktif (Dapat Dikerjakan Siswa)
                    </span>
                @elseif($tes->status === 'draft')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                        Draft (Belum Tampil di Siswa)
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-700 text-slate-300">
                        Selesai / Ditutup
                    </span>
                @endif
            </div>

            <form action="{{ route('guru.tes.toggleStatus', $tes->id_tes) }}" method="POST" class="flex items-center gap-2">
                @csrf
                <select 
                    name="status" 
                    onchange="this.form.submit()"
                    class="bg-slate-800 text-white text-xs px-3 py-1.5 rounded-lg border border-slate-700 focus:outline-none focus:ring-1 focus:ring-slate-400 cursor-pointer"
                >
                    <option value="draft" {{ $tes->status == 'draft' ? 'selected' : '' }}>Ubah ke: Draft</option>
                    <option value="aktif" {{ $tes->status == 'aktif' ? 'selected' : '' }}>Ubah ke: Aktif</option>
                    <option value="selesai" {{ $tes->status == 'selesai' ? 'selected' : '' }}>Ubah ke: Selesai</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Daftar Butir Soal -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-base font-bold text-slate-900 tracking-tight">
                Daftar Butir Soal ({{ $tes->soals->count() }})
            </h2>
        </div>

        @forelse($tes->soals as $index => $soal)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 md:p-6 space-y-4 hover:border-slate-300 transition-colors">
                <!-- Header Soal: Nomor, Tipe Soal, Bobot, Aksi -->
                <div class="flex items-center justify-between gap-4 pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-lg bg-slate-900 text-white font-bold text-xs flex items-center justify-center">
                            {{ $index + 1 }}
                        </span>
                        @if($soal->tipe_soal === 'PG')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                Pilihan Ganda
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                Esai
                            </span>
                        @endif
                        <span class="text-xs text-slate-500 font-medium">Bobot: <strong>{{ $soal->bobot_nilai }}</strong></span>
                        @if($soal->timer_per_soal)
                            <span class="text-[11px] text-slate-400">&bull; Timer: {{ $soal->timer_per_soal }} dtk</span>
                        @endif
                        @if($soal->created_at)
                            <span class="text-[11px] text-slate-400">&bull; Ditambahkan: {{ $soal->created_at->format('d M Y, H:i') }}</span>
                        @endif
                    </div>

                    <!-- Tombol Edit & Hapus Soal -->
                    <div class="flex items-center gap-1.5">
                        <a 
                            href="{{ route('guru.soal.edit', $soal->id_soal) }}" 
                            class="px-2.5 py-1 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors"
                        >
                            Edit
                        </a>
                        <form action="{{ route('guru.soal.destroy', $soal->id_soal) }}" method="POST" class="inline form-delete" data-confirm-title="Hapus Butir Soal" data-confirm-message="Yakin ingin menghapus butir soal nomor {{ $index + 1 }} ini?">
                            @csrf
                            @method('DELETE')
                            <button 
                                type="submit" 
                                class="px-2.5 py-1 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors cursor-pointer"
                            >
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Teks Pertanyaan & Lampiran -->
                <div class="space-y-3">
                    <p class="text-sm font-medium text-slate-800 leading-relaxed whitespace-pre-line">
                        {{ $soal->pertanyaan }}
                    </p>

                    @if($soal->gambar_lampiran)
                        <div class="mt-2">
                            <img 
                                src="{{ asset('storage/' . $soal->gambar_lampiran) }}" 
                                alt="Lampiran Soal" 
                                class="max-h-64 rounded-xl border border-slate-200 object-contain"
                            >
                        </div>
                    @endif
                </div>

                <!-- Opsi Jawaban (Jika Pilihan Ganda) -->
                @if($soal->tipe_soal === 'PG' && $soal->pilihanJawabans->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5 pt-2">
                        @foreach($soal->pilihanJawabans as $pIndex => $pilihan)
                            @php
                                $huruf = chr(65 + $pIndex); // A, B, C, D, E
                            @endphp
                            <div class="p-3 rounded-xl border {{ $pilihan->is_benar ? 'bg-emerald-50/70 border-emerald-300 text-emerald-950 font-semibold' : 'bg-slate-50 border-slate-200 text-slate-700' }} flex items-start gap-2.5 text-xs">
                                <span class="w-5 h-5 rounded-md flex items-center justify-center font-bold {{ $pilihan->is_benar ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-700' }} shrink-0 text-[11px]">
                                    {{ $huruf }}
                                </span>
                                <span class="flex-1 leading-relaxed">{{ $pilihan->teks_pilihan }}</span>
                                @if($pilihan->is_benar)
                                    <span class="text-[10px] font-bold text-emerald-700 uppercase bg-emerald-100 px-2 py-0.5 rounded shrink-0">
                                        Kunci Benar
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-12 text-center space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">Paket Tes Ini Belum Memiliki Butir Soal</h3>
                <p class="text-xs text-slate-500 max-w-md mx-auto">
                    Mulai tambahkan soal Pilihan Ganda atau Esai agar tes ini dapat dikerjakan oleh siswa di kelas yang dituju.
                </p>
                <div class="pt-2">
                    <a 
                        href="{{ route('guru.tes.soal.create', $tes->id_tes) }}" 
                        class="inline-flex items-center gap-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold py-2.5 px-5 rounded-xl shadow-sm transition-colors cursor-pointer"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>+ Tambah Butir Soal Pertama</span>
                    </a>
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection
