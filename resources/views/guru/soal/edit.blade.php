@extends('layouts.app')

@section('title', 'Edit Butir Soal')

@push('styles')
<style>
    .btn-sky-action {
        background-color: #0284c7 !important;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 0.825rem;
        padding: 0.65rem 1.35rem;
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
    .form-card-sky {
        background: #ffffff;
        border: 1px solid #e0f2fe;
        border-radius: 24px;
        box-shadow: 0 4px 20px -2px rgba(2, 132, 199, 0.06);
    }
    .option-card {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 16px;
        padding: 0.85rem 1rem;
        display: flex;
        align-items: center;
        gap: 0.85rem;
        transition: all 0.2s ease;
    }
    .option-card:focus-within {
        background: #ffffff;
        border-color: #0284c7;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.1);
    }
    .option-badge {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        background: #e0f2fe;
        color: #0284c7;
        font-weight: 800;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #bae6fd;
        cursor: pointer;
        user-select: none;
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')
<div class="max-w-3xl mx-auto space-y-6 pb-12">

    <!-- Header & Breadcrumb -->
    <div class="flex items-start justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('guru.dashboard') }}" class="hover:text-sky-600 transition-colors">Dashboard</a>
                <span>&rsaquo;</span>
                <a href="{{ route('guru.tes.index') }}" class="hover:text-sky-600 transition-colors">Asessment</a>
                <span>&rsaquo;</span>
                <a href="{{ route('guru.tes.show', $tes->id_tes) }}" class="hover:text-sky-600 transition-colors">{{ $tes->judul }}</a>
                <span>&rsaquo;</span>
                <span class="text-sky-700 font-semibold">Edit Soal</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Butir Soal</h1>
            <p class="text-xs text-slate-500 mt-0.5">Asessment: <strong>{{ $tes->judul }}</strong> &bull; Tipe: {{ $tipe }}</p>
        </div>

        <a
            href="{{ route('guru.tes.show', $tes->id_tes) }}"
            class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-200 px-3.5 py-2 rounded-xl transition-all shadow-xs shrink-0 mt-1 cursor-pointer"
        >
            <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Card Form -->
    <div class="form-card-sky p-6 md:p-8">
        
        @if($errors->any())
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-2xl text-xs font-medium">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('guru.soal.update', $soal->id_soal) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <input type="hidden" name="tipe_soal" value="PG">
            <input type="hidden" name="bobot_nilai" value="5">

            <!-- 1. Pilihan Dimensi Asesmen Psikometrik -->
            <div>
                <label for="dimensi" class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-1.5 ml-1">
                    Dimensi Pengukuran Soal <span class="text-rose-500">*</span>
                </label>
                <select 
                    id="dimensi" 
                    name="dimensi" 
                    required 
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 focus:bg-white transition-all cursor-pointer"
                >
                    @foreach($dimensiList as $dimKey => $info)
                        <option value="{{ $dimKey }}" {{ old('dimensi', $currentDimensi) == $dimKey ? 'selected' : '' }}>
                            Dimensi {{ $info['nama'] }} &mdash; {{ $info['karakter'] ?? $info['deskripsi'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- 2. Teks Pertanyaan Soal -->
            <div>
                <label for="pertanyaan" class="block text-xs font-bold text-slate-900 mb-1.5 ml-1">
                    Pernyataan / Butir Soal <span class="text-rose-500">*</span>
                </label>
                <textarea 
                    id="pertanyaan" 
                    name="pertanyaan" 
                    rows="4" 
                    required
                    autofocus
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 focus:bg-white transition-all leading-relaxed"
                >{{ old('pertanyaan', $cleanPertanyaan) }}</textarea>
            </div>

            <!-- 3. Bagian Opsi Pilihan Jawaban Skala Likert -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <div>
                    <h3 class="text-xs font-bold text-slate-900 tracking-tight flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;color:#0284c7;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span>Pilihan Jawaban (Skala Likert)</span>
                    </h3>
                </div>

                <div class="space-y-3">
                    @php
                        $opsiHuruf = ['A', 'B', 'C', 'D', 'E'];
                        $existingPilihan = $soal->pilihanJawabans->values();
                        $kunciIndex = $existingPilihan->search(fn($p) => $p->is_benar);
                        if ($kunciIndex === false) $kunciIndex = 0;
                    @endphp

                    @foreach($opsiHuruf as $idx => $huruf)
                        @php
                            $defaultText = isset($existingPilihan[$idx]) ? $existingPilihan[$idx]->teks_pilihan : '';
                        @endphp
                        <div class="option-card">
                            <div class="flex items-center gap-2 shrink-0">
                                <input 
                                    type="radio" 
                                    id="kunci_{{ $idx }}" 
                                    name="kunci_jawaban" 
                                    value="{{ $idx }}" 
                                    {{ old('kunci_jawaban', $kunciIndex) == $idx ? 'checked' : '' }}
                                    class="w-4 h-4 text-sky-600 focus:ring-sky-500 cursor-pointer"
                                >
                                <label for="kunci_{{ $idx }}" class="option-badge">
                                    {{ $huruf }}
                                </label>
                            </div>

                            <div class="flex-1">
                                <input 
                                    type="text" 
                                    name="pilihan[{{ $idx }}]" 
                                    value="{{ old('pilihan.' . $idx, $defaultText) }}" 
                                    placeholder="Ketik teks opsi jawaban {{ $huruf }}..."
                                    required
                                    class="w-full px-3 py-2 bg-transparent border-0 text-xs font-medium text-slate-800 placeholder:text-slate-400 focus:outline-none"
                                >
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Tombol Aksi Simpan Perubahan -->
            <div class="pt-5 border-t border-slate-100 flex items-center justify-end gap-3">
                <a 
                    href="{{ route('guru.tes.show', $tes->id_tes) }}" 
                    class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors cursor-pointer"
                >
                    Batal
                </a>
                <button 
                    type="submit" 
                    class="btn-sky-action"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>

    </div>

</div>
@endsection
