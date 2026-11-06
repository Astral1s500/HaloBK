@extends('layouts.app')

@section('title', 'Edit Paket Tes')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Header & Breadcrumb -->
    <div class="flex items-start justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('guru.dashboard') }}" class="hover:text-slate-900 transition-colors">Dashboard</a>
                <span>&rsaquo;</span>
                <a href="{{ route('guru.tes.index') }}" class="hover:text-slate-900 transition-colors">Paket Tes</a>
                <span>&rsaquo;</span>
                <span class="text-slate-800 font-semibold">Edit</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Paket Asesmen / Tes</h1>
            <p class="text-xs text-slate-500 mt-0.5">Perbarui pengaturan paket tes, target kelas, atau durasi.</p>
        </div>

        <a
            href="{{ route('guru.tes.index') }}"
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

        <form action="{{ route('guru.tes.update', $tes->id_tes) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- 0. Pilihan Tipe Asesmen (Tes CBT vs Formulir Pendataan) -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-2">
                    Tipe Asesmen <span class="text-rose-500">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label class="cursor-pointer">
                        <input 
                            type="radio" 
                            name="tipe" 
                            value="tes" 
                            id="tipeTes"
                            onchange="switchTipeAsesmen('tes')"
                            {{ old('tipe', $tes->tipe ?? 'tes') === 'tes' ? 'checked' : '' }}
                            class="peer sr-only"
                        >
                        <div class="p-3.5 rounded-xl border-2 border-slate-200 peer-checked:border-slate-900 peer-checked:bg-slate-900 peer-checked:text-white text-slate-700 transition-all">
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                                <span class="text-xs font-bold">Tes / Ujian CBT</span>
                            </div>
                            <span class="block text-[11px] opacity-75 mt-1">Ujian minat/bakat, psikotes, kuis evaluasi berkala dengan penskoran & grade nilai.</span>
                        </div>
                    </label>

                    <label class="cursor-pointer">
                        <input 
                            type="radio" 
                            name="tipe" 
                            value="form" 
                            id="tipeForm"
                            onchange="switchTipeAsesmen('form')"
                            {{ old('tipe', $tes->tipe ?? 'tes') === 'form' ? 'checked' : '' }}
                            class="peer sr-only"
                        >
                        <div class="p-3.5 rounded-xl border-2 border-slate-200 peer-checked:border-blue-600 peer-checked:bg-blue-600 peer-checked:text-white text-slate-700 transition-all">
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="text-xs font-bold">Formulir Pendataan (Form)</span>
                            </div>
                            <span class="block text-[11px] opacity-75 mt-1">Untuk mendata siswa: angket, kuesioner, screening konseling, atau biodata siswa.</span>
                        </div>
                    </label>
                </div>

                <!-- Helper note dinamis -->
                <div id="formHelperNote" class="{{ old('tipe', $tes->tipe ?? 'tes') === 'form' ? '' : 'hidden' }} mt-2.5 p-3 rounded-xl bg-blue-50 border border-blue-200 text-blue-900 text-xs flex items-start gap-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span><strong>Mode Formulir Aktif:</strong> Asesmen ini akan muncul dengan tanda khusus <strong>"Formulir"</strong> pada akun siswa. Siswa dapat mengisi butir pertanyaan formulir untuk melengkapi pendataan Anda.</span>
                </div>
            </div>

            <!-- Judul Tes -->
            <div>
                <label for="judul" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Judul Paket Asesmen / Formulir
                </label>
                <input 
                    type="text" 
                    id="judul" 
                    name="judul" 
                    value="{{ old('judul', $tes->judul) }}" 
                    required 
                    autofocus
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-1 focus:ring-slate-900 focus:border-slate-900 transition-colors"
                >
            </div>

            <!-- Target Kelas & Kategori Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="id_kelas" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Target Kelas Binaan
                    </label>
                    <select 
                        id="id_kelas" 
                        name="id_kelas" 
                        required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium text-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 focus:border-slate-900 transition-colors"
                    >
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id_kelas }}" {{ old('id_kelas', $tes->id_kelas) == $kelas->id_kelas ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="id_kategori" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Kategori Tes
                    </label>
                    <select 
                        id="id_kategori" 
                        name="id_kategori" 
                        required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium text-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 focus:border-slate-900 transition-colors"
                    >
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id_kategori }}" {{ old('id_kategori', $tes->id_kategori) == $kategori->id_kategori ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Durasi & Status Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="durasi_menit" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Durasi Pengerjaan (Menit)
                    </label>
                    <input 
                        type="number" 
                        id="durasi_menit" 
                        name="durasi_menit" 
                        value="{{ old('durasi_menit', $tes->durasi_menit) }}" 
                        min="1" 
                        max="360"
                        required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium text-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 focus:border-slate-900 transition-colors"
                    >
                </div>

                <div>
                    <label for="status" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Status Tes
                    </label>
                    <select 
                        id="status" 
                        name="status" 
                        required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium text-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 focus:border-slate-900 transition-colors"
                    >
                        <option value="draft" {{ old('status', $tes->status) == 'draft' ? 'selected' : '' }}>Draft (Belum Tampil ke Siswa)</option>
                        <option value="aktif" {{ old('status', $tes->status) == 'aktif' ? 'selected' : '' }}>Aktif (Siap Dikerjakan Siswa)</option>
                        <option value="selesai" {{ old('status', $tes->status) == 'selesai' ? 'selected' : '' }}>Selesai / Ditutup</option>
                    </select>
                </div>
            </div>

            <!-- Waktu Jadwal Pelaksanaan -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="waktu_mulai" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Jadwal Mulai
                    </label>
                    <input 
                        type="datetime-local" 
                        id="waktu_mulai" 
                        name="waktu_mulai" 
                        value="{{ old('waktu_mulai', $tes->waktu_mulai ? $tes->waktu_mulai->format('Y-m-d\TH:i') : '') }}"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium text-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 focus:border-slate-900 transition-colors"
                    >
                </div>

                <div>
                    <label for="waktu_selesai" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Jadwal Selesai
                    </label>
                    <input 
                        type="datetime-local" 
                        id="waktu_selesai" 
                        name="waktu_selesai" 
                        value="{{ old('waktu_selesai', $tes->waktu_selesai ? $tes->waktu_selesai->format('Y-m-d\TH:i') : '') }}"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium text-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 focus:border-slate-900 transition-colors"
                    >
                </div>
            </div>

            <!-- Opsi Acak Urutan Soal -->
            <div class="flex items-center gap-2 p-3 bg-slate-50 border border-slate-200 rounded-xl">
                <input 
                    type="checkbox" 
                    id="acak_soal" 
                    name="acak_soal" 
                    value="1" 
                    {{ old('acak_soal', $tes->acak_soal) ? 'checked' : '' }}
                    class="w-4 h-4 text-slate-900 border-slate-300 rounded focus:ring-slate-900"
                >
                <label for="acak_soal" class="text-xs font-medium text-slate-800 cursor-pointer">
                    Acak urutan butir soal saat tes dikerjakan oleh siswa
                </label>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a 
                    href="{{ route('guru.tes.index') }}" 
                    class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800 transition-colors"
                >
                    Batal
                </a>
                <button 
                    type="submit" 
                    class="bg-slate-900 hover:bg-slate-800 text-white font-semibold py-2 px-5 rounded-lg text-xs tracking-wide transition-colors cursor-pointer"
                >
                    Perbarui Paket Tes
                </button>
            </div>
        </form>

    </div>

</div>

<script>
function switchTipeAsesmen(tipe) {
    const note = document.getElementById('formHelperNote');
    const acakCheck = document.getElementById('acak_soal');
    if (tipe === 'form') {
        if (note) note.classList.remove('hidden');
        if (acakCheck) acakCheck.checked = false;
    } else {
        if (note) note.classList.add('hidden');
    }
}
</script>
@endsection
