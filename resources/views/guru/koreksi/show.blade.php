@extends('layouts.app')

@section('title', 'Rekapitulasi Asesmen: ' . $tes->judul)

@push('styles')
<style>
    .card-sky {
        background: #ffffff;
        border: 1px solid #e0f2fe;
        border-radius: 20px;
        box-shadow: 0 4px 16px -2px rgba(2, 132, 199, 0.05);
    }
    .badge-dominant {
        background: #e0f2fe;
        color: #0369a1;
        font-weight: 700;
        font-size: 0.75rem;
        padding: 0.25rem 0.65rem;
        border-radius: 9999px;
        border: 1px solid #bae6fd;
    }
</style>
@endpush

@section('content')
<div class="space-y-6 pb-12">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 print:hidden">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('guru.dashboard') }}" class="hover:text-sky-600 transition-colors">Dashboard</a>
                <span>&rsaquo;</span>
                <a href="{{ route('guru.koreksi.index') }}" class="hover:text-sky-600 transition-colors">Hasil Asessment</a>
                <span>&rsaquo;</span>
                <span class="text-sky-700 font-semibold">{{ $tes->judul }}</span>
            </div>
            <div class="flex items-center gap-2.5 flex-wrap">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $tes->judul }}</h1>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-sky-50 text-sky-700 border border-sky-200">
                    {{ $analisisKelas['tipe_asesmen'] }}
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-1">
                Target Kelas: <strong>{{ $tes->kelas->nama_kelas ?? 'Umum' }}</strong>
                &bull; {{ $komparasi->total_soal }} Butir Pertanyaan Terstruktur
                &bull; Total Peserta: <strong>{{ $statsHistoris->total_peserta }} Siswa</strong>
            </p>
        </div>

        <div class="flex items-center gap-2">
            <!-- Tombol Cetak / Export PDF -->
            <button 
                type="button" 
                onclick="window.print()" 
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-300 py-2.5 px-4 rounded-xl shadow-xs transition-colors cursor-pointer"
            >
                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Cetak Rekapitulasi Kelas</span>
            </button>

            <!-- Tombol Kembali -->
            <a
                href="{{ route('guru.koreksi.index') }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-200 py-2.5 px-4 rounded-xl transition-all shadow-xs cursor-pointer shrink-0"
            >
                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <!-- Judul Header Cetak (Hanya tampil saat dicetak) -->
    <div class="hidden print:block mb-6 pb-4 border-b border-slate-400">
        <h1 class="text-xl font-bold text-slate-900">REKAPITULASI PROFIL ASESMEN PSIKOMETRIK KELAS</h1>
        <p class="text-xs text-slate-600 mt-1">
            Asesmen: <strong>{{ $tes->judul }}</strong> | 
            Kelas: <strong>{{ $tes->kelas->nama_kelas ?? 'Umum' }}</strong> | 
            Guru BK: <strong>{{ Auth::guard('guru')->user()->nama_guru }}</strong> | 
            Tanggal Cetak: {{ now()->format('d F Y, H:i') }} WIB
        </p>
    </div>

    <!-- ========================================================================= -->
    <!-- 1. PETA KONDISI KELAS SECARA KESELURUHAN (CLASS OVERVIEW)                 -->
    <!-- Mengetahui distribusi dominan siswa & menentukan strategi bimbingan kelas -->
    <!-- ========================================================================= -->
    <div class="card-sky p-6 sm:p-7 space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
            <div>
                <h2 class="text-base font-bold text-slate-900 tracking-tight flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:#0284c7;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Pemetaan Kondisi Kelas &amp; Distribusi Dominan</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Membantu Guru BK dan wali kelas merancang strategi bimbingan kelompok dan pembelajaran yang sesuai dengan karakteristik kelas.
                </p>
            </div>

            @if($analisisKelas['total_siswa_selesai'] > 0)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold bg-sky-50 text-sky-800 border border-sky-200">
                    Karakteristik Mayoritas: <strong>{{ $analisisKelas['label_mayoritas'] }}</strong>
                </span>
            @endif
        </div>

        <!-- Sebaran Persentase Tipe di Kelas -->
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-3">
            @foreach($analisisKelas['dimensi_info'] as $dimKey => $info)
                @php
                    $count = $analisisKelas['distribusi_dominan'][$dimKey] ?? 0;
                    $persen = $analisisKelas['persentase_distribusi'][$dimKey] ?? 0;
                    $isTop = ($dimKey === $analisisKelas['tipe_mayoritas'] && $count > 0);
                @endphp
                <div class="p-4 rounded-2xl border {{ $isTop ? 'bg-sky-50/70 border-sky-300 shadow-2xs' : 'bg-slate-50/60 border-slate-200' }} space-y-1">
                    <span class="text-[11px] font-bold {{ $isTop ? 'text-sky-900' : 'text-slate-700' }} block truncate">
                        {{ $info['nama'] }}
                    </span>
                    <div class="flex items-baseline gap-1">
                        <span class="text-xl font-black {{ $isTop ? 'text-sky-700' : 'text-slate-800' }}">{{ $count }}</span>
                        <span class="text-[10px] text-slate-500 font-medium">siswa ({{ $persen }}%)</span>
                    </div>
                    <div class="w-full bg-slate-200/80 rounded-full h-1.5 overflow-hidden mt-1">
                        <div class="h-full {{ $isTop ? 'bg-sky-600' : 'bg-slate-400' }}" style="width: {{ $persen }}%;"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 2. TABEL DAFTAR SISWA & LEMBAR KONSELING ORANG TUA                        -->
    <!-- ========================================================================= -->
    <div class="card-sky overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Daftar Hasil &amp; Profil Siswa</h3>
                <p class="text-xs text-slate-500 mt-0.5">
                    Klik <strong>"Buka Lembar Konseling &amp; Ortu"</strong> untuk melihat profil naratif, potensi, strategi bimbingan, dan materi tindak lanjut.
                </p>
            </div>
            <span class="text-xs font-semibold text-sky-700 bg-sky-50 px-3 py-1 rounded-xl border border-sky-200">
                {{ $sesiList->count() }} Siswa Telah Selesai
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-[11px] font-bold text-slate-600 uppercase tracking-wider">
                        <th class="py-3 px-4 text-center w-12">No</th>
                        <th class="py-3 px-4">Nama Siswa</th>
                        <th class="py-3 px-4">NISN</th>
                        <th class="py-3 px-4 text-center">Tipe Dominan</th>
                        <th class="py-3 px-4 text-center">Waktu Submit</th>
                        <th class="py-3 px-4 text-center w-48 print:hidden">Aksi &amp; Konseling</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                    @forelse($sesiList as $index => $sesi)
                        @php
                            $analisisSiswa = \App\Services\PsikometrikService::analisisHasilSiswa($sesi);
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3.5 px-4 text-center font-medium text-slate-400">{{ $index + 1 }}</td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-900 block">{{ $sesi->siswa->nama_siswa ?? 'Siswa Terhapus' }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-slate-500">{{ $sesi->siswa->nisn ?? '-' }}</td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="badge-dominant">
                                    {{ $analisisSiswa['label_primer'] }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center text-slate-500">
                                {{ $sesi->waktu_submit ? $sesi->waktu_submit->format('d/m/Y, H:i') : '-' }} WIB
                            </td>
                            <td class="py-3.5 px-4 text-center print:hidden">
                                <a 
                                    href="{{ route('guru.koreksi.siswa', $sesi->id_sesi) }}" 
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-sky-700 bg-sky-50 hover:bg-sky-100 border border-sky-200 transition-colors shadow-2xs"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span>Lembar Konseling &amp; Ortu &rarr;</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 text-xs">
                                Belum ada siswa yang menyelesaikan asesmen ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
