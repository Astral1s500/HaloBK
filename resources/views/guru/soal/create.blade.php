@extends('layouts.app')

@section('title', 'Tambah Butir Soal')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Header & Breadcrumb -->
    <div class="flex items-start justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('guru.dashboard') }}" class="hover:text-slate-900 transition-colors">Dashboard</a>
                <span>&rsaquo;</span>
                <a href="{{ route('guru.tes.index') }}" class="hover:text-slate-900 transition-colors">Paket Tes</a>
                <span>&rsaquo;</span>
                <a href="{{ route('guru.tes.show', $tes->id_tes) }}" class="hover:text-slate-900 transition-colors">{{ $tes->judul }}</a>
                <span>&rsaquo;</span>
                <span class="text-slate-800 font-semibold">Tambah Soal</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Tambah Butir Soal</h1>
            <p class="text-xs text-slate-500 mt-0.5">Paket: <strong>{{ $tes->judul }}</strong> &bull; Target: {{ $tes->kelas->nama_kelas ?? '-' }}</p>
        </div>

        <a
            href="{{ route('guru.tes.show', $tes->id_tes) }}"
            class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 px-3 py-2 rounded-lg transition-colors shrink-0 mt-1"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    <!-- Card Form -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 md:p-8">
        
        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-xs font-medium">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('guru.tes.soal.store', $tes->id_tes) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            @if($tes->isForm())
                <div class="p-3.5 bg-blue-50 border border-blue-200 rounded-xl text-xs text-blue-900 flex items-start gap-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="space-y-0.5">
                        <strong class="font-bold text-blue-950">Panduan Pertanyaan Formulir:</strong>
                        <p class="text-blue-800 text-[11px] leading-relaxed">
                            Pilih <strong>Pilihan Ganda</strong> untuk pertanyaan beropsi pilihan (misal: skala kepuasan, status tinggal, peminatan), atau pilih <strong>Esai / Uraian</strong> untuk isian teks bebas (misal: data kontak, alasan pemilihan jurusan, kendala belajar).
                        </p>
                    </div>
                </div>
            @endif

            <!-- 1. Pilihan Tipe Soal (Tab Switcher) -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-2">
                    Tipe Butir Soal
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer">
                        <input 
                            type="radio" 
                            name="tipe_soal" 
                            value="PG" 
                            id="tipePG"
                            onchange="switchTipeSoal('PG')"
                            {{ old('tipe_soal', 'PG') === 'PG' ? 'checked' : '' }}
                            class="peer sr-only"
                        >
                        <div class="p-3.5 rounded-xl border-2 border-slate-200 peer-checked:border-slate-900 peer-checked:bg-slate-900 peer-checked:text-white text-slate-700 transition-all text-center">
                            <span class="block text-xs font-bold">Pilihan Ganda (PG)</span>
                            <span class="block text-[11px] opacity-75 mt-0.5">Opsi A, B, C, D, E &amp; Koreksi Otomatis</span>
                        </div>
                    </label>

                    <label class="cursor-pointer">
                        <input 
                            type="radio" 
                            name="tipe_soal" 
                            value="Esai" 
                            id="tipeEsai"
                            onchange="switchTipeSoal('Esai')"
                            {{ old('tipe_soal') === 'Esai' ? 'checked' : '' }}
                            class="peer sr-only"
                        >
                        <div class="p-3.5 rounded-xl border-2 border-slate-200 peer-checked:border-slate-900 peer-checked:bg-slate-900 peer-checked:text-white text-slate-700 transition-all text-center">
                            <span class="block text-xs font-bold">Esai / Uraian</span>
                            <span class="block text-[11px] opacity-75 mt-0.5">Jawaban Terbuka &amp; Penilaian Guru</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- 2. Teks Pertanyaan Soal -->
            <div>
                <label for="pertanyaan" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Pertanyaan / Soal
                </label>
                <textarea 
                    id="pertanyaan" 
                    name="pertanyaan" 
                    rows="4" 
                    placeholder="Tuliskan pertanyaan soal di sini..."
                    required
                    autofocus
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-1 focus:ring-slate-900 focus:border-slate-900 transition-colors"
                >{{ old('pertanyaan') }}</textarea>
            </div>

            <!-- 3. Lampiran Gambar (Opsional) -->
            <div>
                <label for="gambar_lampiran" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Gambar Pendukung Soal <span class="text-slate-400 font-normal">(Opsional &bull; JPG, PNG, WEBP Maks 3MB)</span>
                </label>
                <input 
                    type="file" 
                    id="gambar_lampiran" 
                    name="gambar_lampiran" 
                    accept="image/*"
                    class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium text-slate-900 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800 transition-colors"
                >
            </div>

            <!-- 4. Bobot Nilai & Timer Per Soal Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="bobot_nilai" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Bobot Nilai Soal
                    </label>
                    <input 
                        type="number" 
                        step="0.5"
                        id="bobot_nilai" 
                        name="bobot_nilai" 
                        value="{{ old('bobot_nilai', 10) }}" 
                        min="0"
                        required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium text-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 focus:border-slate-900 transition-colors"
                    >
                </div>

                <div>
                    <label for="timer_per_soal" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Timer Khusus Soal Ini (Detik) <span class="text-slate-400 font-normal">(Opsional)</span>
                    </label>
                    <input 
                        type="number" 
                        id="timer_per_soal" 
                        name="timer_per_soal" 
                        value="{{ old('timer_per_soal') }}" 
                        placeholder="Contoh: 60 (Kosongkan jika ikuti tes)"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-1 focus:ring-slate-900 focus:border-slate-900 transition-colors"
                    >
                </div>
            </div>

            <!-- 5. Bagian Pilihan Jawaban PG (Khusus Pilihan Ganda) -->
            <div id="sectionPilihanGanda" class="space-y-4 pt-4 border-t border-slate-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Opsi Pilihan Jawaban &amp; Kunci Benar</h3>
                        <p class="text-[11px] text-slate-500">Ketikkan teks pilihan dan pilih satu tombol radio sebagai kunci jawaban benar.</p>
                    </div>
                </div>

                <div class="space-y-3">
                    @php
                        $opsiHuruf = ['A', 'B', 'C', 'D', 'E'];
                    @endphp

                    @foreach($opsiHuruf as $idx => $huruf)
                        <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl flex items-start gap-3">
                            <div class="flex items-center gap-2 pt-2 shrink-0">
                                <input 
                                    type="radio" 
                                    id="kunci_{{ $idx }}" 
                                    name="kunci_jawaban" 
                                    value="{{ $idx }}" 
                                    {{ old('kunci_jawaban', 0) == $idx ? 'checked' : '' }}
                                    class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 cursor-pointer"
                                >
                                <label for="kunci_{{ $idx }}" class="w-6 h-6 rounded-md bg-slate-900 text-white font-bold text-xs flex items-center justify-center cursor-pointer">
                                    {{ $huruf }}
                                </label>
                            </div>
                            <div class="flex-1">
                                <input 
                                    type="text" 
                                    name="pilihan[{{ $idx }}]" 
                                    value="{{ old('pilihan.' . $idx) }}" 
                                    placeholder="Teks opsi {{ $huruf }}..."
                                    class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs font-medium text-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 focus:border-slate-900"
                                >
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a 
                    href="{{ route('guru.tes.show', $tes->id_tes) }}" 
                    class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800 transition-colors"
                >
                    Batal
                </a>
                <button 
                    type="submit" 
                    class="bg-slate-900 hover:bg-slate-800 text-white font-semibold py-2 px-5 rounded-lg text-xs tracking-wide transition-colors cursor-pointer"
                >
                    Simpan Butir Soal
                </button>
            </div>
        </form>

    </div>

</div>

<!-- Script Switch Tipe Soal (PG vs Esai) -->
<script>
function switchTipeSoal(tipe) {
    const secPG = document.getElementById('sectionPilihanGanda');
    if (tipe === 'Esai') {
        secPG.classList.add('hidden');
    } else {
        secPG.classList.remove('hidden');
    }
}

// Inisialisasi sesuai kondisi awal
document.addEventListener('DOMContentLoaded', function() {
    const tipe = document.querySelector('input[name="tipe_soal"]:checked').value;
    switchTipeSoal(tipe);
});
</script>
@endsection
