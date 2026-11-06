@extends('layouts.app')

@section('title', 'Detail Asessment: ' . $tes->judul)

@push('styles')
<style>
    .btn-sky-action {
        background-color: #0284c7 !important;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 0.825rem;
        padding: 0.65rem 1.25rem;
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
        background-color: #0369a1 !important;
        transform: translateY(-1px);
    }
    .btn-template-action {
        background-color: #ffffff !important;
        color: #0284c7 !important;
        font-weight: 600;
        font-size: 0.825rem;
        padding: 0.65rem 1.25rem;
        border-radius: 12px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        border: 1.5px solid #bae6fd;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(2, 132, 199, 0.08);
    }
    .btn-template-action:hover {
        background-color: #f0f9ff !important;
        border-color: #0284c7;
        transform: translateY(-1px);
    }
    .card-sky {
        background: #ffffff;
        border: 1px solid #e0f2fe;
        border-radius: 20px;
        box-shadow: 0 4px 16px -2px rgba(2, 132, 199, 0.05);
    }
    .dimensi-chip {
        padding: 0.75rem 1rem;
        border-radius: 16px;
        border: 1.5px solid #e2e8f0;
        background: #f8fafc;
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        transition: all 0.2s ease;
    }
    .dimensi-chip.balanced {
        border-color: #a7f3d0;
        background: #f0fdf4;
    }
    .dimensi-chip.unbalanced {
        border-color: #fde68a;
        background: #fffbeb;
    }
</style>
@endpush

@php
    $analisisKeseimbangan = $tes->analisis_keseimbangan;
    $infoPsikometrik = $tes->info_psikometrik;
@endphp

@section('content')
<div class="space-y-6 pb-12">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('guru.dashboard') }}" class="hover:text-sky-600 transition-colors">Dashboard</a>
                <span>&rsaquo;</span>
                <a href="{{ route('guru.tes.index') }}" class="hover:text-sky-600 transition-colors">Asessment</a>
                <span>&rsaquo;</span>
                <span class="text-sky-700 font-semibold">{{ $tes->judul }}</span>
            </div>
            <div class="flex items-center gap-2.5 flex-wrap">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $tes->judul }}</h1>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-sky-50 text-sky-700 border border-sky-200">
                    {{ $infoPsikometrik['label'] }}
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-1">
                {{ $infoPsikometrik['deskripsi'] }}
            </p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <a
                href="{{ route('guru.tes.index') }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-200 py-2.5 px-4 rounded-xl transition-all shadow-xs cursor-pointer"
            >
                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Kembali</span>
            </a>
            <a 
                href="{{ route('guru.tes.edit', $tes->id_tes) }}" 
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-700 hover:text-sky-700 bg-white hover:bg-sky-50/50 py-2.5 px-4 rounded-xl transition-colors cursor-pointer border border-slate-200"
            >
                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span>Edit Pengaturan</span>
            </a>
            <a 
                href="{{ route('guru.tes.soal.create', $tes->id_tes) }}" 
                class="btn-sky-action"
            >
                <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>+ Tambah Butir Soal</span>
            </a>
        </div>
    </div>

    <!-- Summary Box Detail Asessment (Sky Cloud Accent) -->
    <div class="relative overflow-hidden rounded-3xl p-6 text-white shadow-lg" style="background: linear-gradient(135deg, #0369a1 0%, #0284c7 50%, #0ea5e9 100%);">
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-4 pb-4 border-b border-white/15">
            <div>
                <span class="block text-[11px] font-medium text-sky-100">Fokus Asesmen</span>
                <span class="text-sm font-bold text-white mt-0.5 block">{{ $infoPsikometrik['fokus'] }}</span>
            </div>
            <div>
                <span class="block text-[11px] font-medium text-sky-100">Target Kelas</span>
                <span class="text-sm font-bold text-white mt-0.5 block">{{ $tes->kelas->nama_kelas ?? '-' }}</span>
            </div>
            <div>
                <span class="block text-[11px] font-medium text-sky-100">Jumlah Dimensi</span>
                <span class="text-sm font-bold text-white mt-0.5 block">{{ count($infoPsikometrik['dimensi']) }} Dimensi Ukur</span>
            </div>
            <div>
                <span class="block text-[11px] font-medium text-sky-100">Durasi Pengerjaan</span>
                <span class="text-sm font-bold text-white mt-0.5 block">{{ $tes->durasi_menit }} Menit</span>
            </div>
            <div>
                <span class="block text-[11px] font-medium text-sky-100">Total Butir Soal</span>
                <span class="text-sm font-bold text-white mt-0.5 block">{{ $tes->soals->count() }} Butir PG</span>
            </div>
        </div>

        <!-- Quick Status Toggle Form -->
        <div class="pt-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="text-xs text-sky-100">Status Publikasi:</span>
                @if($tes->status === 'aktif')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-white text-emerald-700 shadow-xs">
                        &bull; Aktif (Dapat Dikerjakan Siswa)
                    </span>
                @elseif($tes->status === 'draft')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-white text-amber-700 shadow-xs">
                        &bull; Draft (Belum Tampil di Siswa)
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-white text-slate-700 shadow-xs">
                        &bull; Selesai / Ditutup
                    </span>
                @endif
            </div>

            <form action="{{ route('guru.tes.toggleStatus', $tes->id_tes) }}" method="POST" class="flex items-center gap-2">
                @csrf
                <select 
                    name="status" 
                    onchange="this.form.submit()"
                    class="bg-white/90 hover:bg-white text-slate-800 font-semibold text-xs px-3.5 py-1.5 rounded-xl border-0 shadow-xs focus:outline-none focus:ring-2 focus:ring-sky-300 cursor-pointer transition-all"
                >
                    <option value="draft" {{ $tes->status == 'draft' ? 'selected' : '' }}>Ubah ke: Draft</option>
                    <option value="aktif" {{ $tes->status == 'aktif' ? 'selected' : '' }}>Ubah ke: Aktif</option>
                    <option value="selesai" {{ $tes->status == 'selesai' ? 'selected' : '' }}>Ubah ke: Selesai</option>
                </select>
            </form>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- KONTROL KESEIMBANGAN DIMENSI ASESMEN                                      -->
    <!-- Sistem otomatis memastikan jumlah soal setiap dimensi seimbang           -->
    <!-- ========================================================================= -->
    <div class="card-sky p-6 space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 pb-3 border-b border-slate-100">
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-sm font-bold text-slate-900 tracking-tight">Keseimbangan Dimensi Asesmen Psikometrik</h2>
                    @if($analisisKeseimbangan['is_seimbang'])
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Seimbang ({{ $analisisKeseimbangan['min_count'] }} soal/dimensi)
                        </span>
                    @elseif($analisisKeseimbangan['is_kosong'])
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                            Belum Ada Soal
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            Perlu Penyeimbangan
                        </span>
                    @endif
                </div>
                <p class="text-xs text-slate-500 mt-0.5">
                    Sistem otomatis menghitung sebaran soal agar setiap dimensi memiliki bobot seimbang, konsisten, dan tidak berat sebelah.
                </p>
            </div>

            <!-- Tombol Terapkan Bank Soal Standar (1-Click Auto Balanced) -->
            @if($tes->soals->count() === 0)
                <form action="{{ route('guru.tes.terapkanTemplate', $tes->id_tes) }}" method="POST" onsubmit="return confirm('Gunakan paket soal psikometrik standar seimbang untuk asesmen ini?');">
                    @csrf
                    <button type="submit" class="btn-template-action">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <span>Gunakan Soal Rekomendasi Standar (Seimbang Otomatis)</span>
                    </button>
                </form>
            @endif
        </div>

        <!-- Grid Chips Dimensi -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
            @foreach($analisisKeseimbangan['dimensi_list'] as $dimKey => $meta)
                @php
                    $cnt = $analisisKeseimbangan['counts'][$dimKey] ?? 0;
                    $isOk = ($cnt > 0 && $cnt === $analisisKeseimbangan['target_per_dimensi']);
                @endphp
                <a 
                    href="{{ route('guru.tes.soal.create', ['te' => $tes->id_tes, 'dimensi' => $dimKey]) }}" 
                    class="dimensi-chip {{ $isOk ? 'balanced' : ($cnt > 0 ? 'unbalanced' : '') }} hover:scale-102 hover:shadow-xs transition-all cursor-pointer block no-underline"
                    title="Klik untuk kelola soal dimensi {{ $meta['nama'] }}"
                >
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-800">{{ $meta['nama'] }}</span>
                        <span class="text-xs font-black {{ $isOk ? 'text-emerald-700' : ($cnt > 0 ? 'text-amber-700' : 'text-slate-400') }}">
                            {{ $cnt }} Butir
                        </span>
                    </div>
                    <span class="text-[10px] text-slate-500 truncate block mt-0.5" title="{{ $meta['deskripsi'] }}">
                        {{ $meta['karakter'] ?? $meta['deskripsi'] }}
                    </span>
                    <span class="text-[9px] font-bold text-sky-600 block mt-1">+ Kelola Soal &rarr;</span>
                </a>
            @endforeach
        </div>

        <!-- Rekomendasi Penyeimbangan Dimensi (Jika Ada) -->
        @if(!empty($analisisKeseimbangan['rekomendasi']))
            <div class="p-3.5 rounded-xl bg-amber-50/80 border border-amber-200 text-amber-900 text-xs space-y-1">
                <div class="font-bold flex items-center gap-1.5 text-amber-950">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>Panduan Keseimbangan Butir Soal:</span>
                </div>
                @foreach($analisisKeseimbangan['rekomendasi'] as $rek)
                    <p class="text-[11px] leading-relaxed">{!! $rek !!}</p>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Daftar Butir Soal -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-base font-bold text-slate-900 tracking-tight flex items-center gap-2">
                <span>Daftar Butir Soal Asesmen</span>
                <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-sky-100 text-sky-700">
                    {{ $tes->soals->count() }} Butir
                </span>
            </h2>
        </div>

        @forelse($tes->soals as $index => $soal)
            <div class="card-sky p-5 md:p-6 space-y-4 hover:shadow-md transition-all">
                <!-- Header Soal: Nomor, Dimensi, Poin, Aksi -->
                <div class="flex items-center justify-between gap-4 pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <span class="w-7 h-7 rounded-lg bg-sky-600 text-white font-bold text-xs flex items-center justify-center shadow-xs">
                            {{ $index + 1 }}
                        </span>
                        
                        <!-- Badge Dimensi Pengukuran -->
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[11px] font-extrabold bg-sky-100 text-sky-800 border border-sky-300">
                            Dimensi: {{ $soal->dimensi }}
                        </span>

                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold bg-slate-100 text-slate-700">
                            Skala Likert (Otomatis)
                        </span>
                    </div>

                    <!-- Tombol Edit & Hapus Soal -->
                    <div class="flex items-center gap-2">
                        <a 
                            href="{{ route('guru.soal.edit', $soal->id_soal) }}" 
                            class="px-3 py-1.5 text-xs font-semibold text-sky-700 bg-sky-50 hover:bg-sky-100 rounded-lg transition-colors"
                        >
                            Edit
                        </a>
                        <form action="{{ route('guru.soal.destroy', $soal->id_soal) }}" method="POST" class="inline form-delete" data-confirm-title="Hapus Butir Soal" data-confirm-message="Yakin ingin menghapus butir soal nomor {{ $index + 1 }} ini?">
                            @csrf
                            @method('DELETE')
                            <button 
                                type="submit" 
                                class="px-3 py-1.5 text-xs font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-lg transition-colors cursor-pointer"
                            >
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Teks Pertanyaan Bersih -->
                <div class="space-y-3">
                    <p class="text-sm font-medium text-slate-800 leading-relaxed whitespace-pre-line">
                        {{ $soal->clean_pertanyaan }}
                    </p>
                </div>

                <!-- Opsi Pilihan Jawaban Skala Likert -->
                @if($soal->pilihanJawabans->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2 pt-2">
                        @foreach($soal->pilihanJawabans as $pIndex => $pilihan)
                            @php
                                $huruf = chr(65 + $pIndex);
                            @endphp
                            <div class="p-2.5 rounded-xl border bg-slate-50/70 border-slate-200 text-slate-700 flex items-center gap-2 text-xs">
                                <span class="w-5 h-5 rounded-md flex items-center justify-center font-bold bg-sky-100 text-sky-700 shrink-0 text-[10px]">
                                    {{ $huruf }}
                                </span>
                                <span class="flex-1 text-[11px] font-medium leading-tight">{{ $pilihan->teks_pilihan }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <div class="card-sky p-12 text-center space-y-4">
                <div class="w-14 h-14 rounded-2xl bg-sky-50 text-sky-600 mx-auto flex items-center justify-center shadow-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:28px;height:28px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Asessment Ini Belum Memiliki Butir Soal</h3>
                    <p class="text-xs text-slate-500 max-w-md mx-auto mt-1">
                        Gunakan bank soal rekomendasi terstruktur untuk mengisi seluruh dimensi secara seimbang dalam 1 kali klik, atau tambahkan butir soal secara manual.
                    </p>
                </div>
                
                <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-3">
                    <form action="{{ route('guru.tes.terapkanTemplate', $tes->id_tes) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-sky-action">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <span>Gunakan Soal Rekomendasi Standar (Seimbang)</span>
                        </button>
                    </form>

                    <a 
                        href="{{ route('guru.tes.soal.create', $tes->id_tes) }}" 
                        class="btn-template-action"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>+ Tambah Soal Manual</span>
                    </a>
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection
