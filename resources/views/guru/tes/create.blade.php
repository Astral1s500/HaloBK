@extends('layouts.app')

@section('title', 'Buat Asessment Baru')

@push('styles')
<style>
    .btn-sky-action {
        background-color: #0284c7 !important;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 0.825rem;
        padding: 0.75rem 1.4rem;
        border-radius: 14px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 14px rgba(2, 132, 199, 0.25);
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
        border-radius: 28px;
        box-shadow: 0 10px 30px -5px rgba(2, 132, 199, 0.06);
    }
    .type-choice-card {
        border: 2px solid #e2e8f0;
        border-radius: 20px;
        padding: 1.15rem;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #ffffff;
        position: relative;
    }
    .type-choice-card:hover {
        border-color: #7dd3fc;
        background: #f0f9ff;
        transform: translateY(-2px);
    }
    .type-choice-card.selected {
        border-color: #0284c7;
        background: #f0f9ff;
        box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.12);
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
                <span class="text-sky-700 font-semibold">Buat Baru</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Buat Asessment Baru</h1>
            <p class="text-xs text-slate-500 mt-0.5">Pilih jenis asesmen psikometrik, target kelas binaan, dan atur durasi pengerjaannya.</p>
        </div>

        <a
            href="{{ route('guru.tes.index') }}"
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

        <form action="{{ route('guru.tes.store') }}" method="POST" id="formBuatAsesmen" class="space-y-6">
            @csrf

            <input type="hidden" name="tipe" value="tes">

            <!-- 1. Pilihan 4 Jenis Asesmen Psikometrik (Interactive Cards) -->
            <div>
                <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-2 ml-1">
                    Pilih Jenis Asesmen Psikometrik <span class="text-rose-500">*</span>
                </label>
                <p class="text-xs text-slate-500 mb-3 ml-1">Pilih salah satu instrumen asesmen yang akan diterapkan pada kelas binaan Anda.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5" id="pilihanJenisContainer">
                    
                    <!-- 1. VARK (Belajar) -->
                    <div 
                        class="type-choice-card selected" 
                        onclick="pilihJenis('Belajar (VARK)', 'Asesmen Gaya Belajar Siswa (VARK)')"
                        data-jenis="Belajar (VARK)"
                    >
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-700 font-extrabold text-sm flex items-center justify-center shrink-0">
                                📚
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-xs font-bold text-slate-900">1. Belajar (VARK)</h3>
                                <p class="text-[11px] text-slate-600 mt-0.5 leading-relaxed">Mengetahui kecenderungan gaya belajar siswa.</p>
                                <div class="flex items-center gap-1 flex-wrap mt-2">
                                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-sky-50 text-sky-700 border border-sky-200">Visual</span>
                                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-sky-50 text-sky-700 border border-sky-200">Auditori</span>
                                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-sky-50 text-sky-700 border border-sky-200">Reading</span>
                                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-sky-50 text-sky-700 border border-sky-200">Kinestetik</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. OCEAN (Pribadi) -->
                    <div 
                        class="type-choice-card" 
                        onclick="pilihJenis('Pribadi (OCEAN)', 'Asesmen Karakter & Kepribadian Siswa (OCEAN)')"
                        data-jenis="Pribadi (OCEAN)"
                    >
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-700 font-extrabold text-sm flex items-center justify-center shrink-0">
                                🌟
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-xs font-bold text-slate-900">2. Pribadi (OCEAN)</h3>
                                <p class="text-[11px] text-slate-600 mt-0.5 leading-relaxed">Gambaran karakter &amp; kecenderungan kepribadian.</p>
                                <div class="flex items-center gap-1 flex-wrap mt-2">
                                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-indigo-50 text-indigo-700 border border-indigo-200">Openness</span>
                                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-indigo-50 text-indigo-700 border border-indigo-200">Conscientious</span>
                                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-indigo-50 text-indigo-700 border border-indigo-200">Extraversion</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. RIASEC (Karier) -->
                    <div 
                        class="type-choice-card" 
                        onclick="pilihJenis('Karier (RIASEC)', 'Asesmen Minat & Arah Karier Siswa (RIASEC)')"
                        data-jenis="Karier (RIASEC)"
                    >
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 font-extrabold text-sm flex items-center justify-center shrink-0">
                                🎯
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-xs font-bold text-slate-900">3. Karier (RIASEC)</h3>
                                <p class="text-[11px] text-slate-600 mt-0.5 leading-relaxed">Membantu siswa mengenali minat &amp; arah karier.</p>
                                <div class="flex items-center gap-1 flex-wrap mt-2">
                                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-amber-50 text-amber-700 border border-amber-200">Realistic</span>
                                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-amber-50 text-amber-700 border border-amber-200">Investigative</span>
                                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-amber-50 text-amber-700 border border-amber-200">Artistic</span>
                                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-amber-50 text-amber-700 border border-amber-200">Social...</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Sosial -->
                    <div 
                        class="type-choice-card" 
                        onclick="pilihJenis('Sosial', 'Asesmen Kecakapan Sosial & Interpersonal Siswa')"
                        data-jenis="Sosial"
                    >
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 font-extrabold text-sm flex items-center justify-center shrink-0">
                                🤝
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-xs font-bold text-slate-900">4. Sosial</h3>
                                <p class="text-[11px] text-slate-600 mt-0.5 leading-relaxed">Komunikasi, empati, kerja sama, &amp; resolusi konflik.</p>
                                <div class="flex items-center gap-1 flex-wrap mt-2">
                                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200">Komunikasi</span>
                                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200">Empati</span>
                                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200">Kerja Sama</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- 2. Judul Asessment -->
            <div>
                <label for="judul" class="block text-xs font-semibold text-slate-700 mb-1.5 ml-1">
                    Judul Asessment <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="judul" 
                    name="judul" 
                    value="{{ old('judul', 'Asesmen Gaya Belajar Siswa (VARK)') }}" 
                    placeholder="Contoh: Asesmen Gaya Belajar Siswa (VARK)"
                    required 
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 focus:bg-white transition-all font-semibold"
                >
            </div>

            <!-- 3. Target Kelas Binaan & Kategori Tes (2 Kolom) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                
                <!-- Target Kelas Binaan -->
                <div>
                    <label for="id_kelas" class="block text-xs font-semibold text-slate-700 mb-1.5 ml-1">
                        Target Kelas Binaan <span class="text-rose-500">*</span>
                    </label>
                    <select 
                        id="id_kelas" 
                        name="id_kelas" 
                        required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 focus:bg-white transition-all cursor-pointer"
                    >
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id_kelas }}" {{ old('id_kelas') == $kelas->id_kelas ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }} ({{ $kelas->kode_unik_kelas }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Kategori Master -->
                <div>
                    <label for="id_kategori" class="block text-xs font-semibold text-slate-700 mb-1.5 ml-1">
                        Kategori Asesmen <span class="text-rose-500">*</span>
                    </label>
                    <select 
                        id="id_kategori" 
                        name="id_kategori" 
                        required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 focus:bg-white transition-all cursor-pointer"
                    >
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id_kategori }}" {{ old('id_kategori') == $kategori->id_kategori || stripos($kategori->nama_kategori, 'VARK') !== false ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- 4. Durasi Pengerjaan & Status Awal -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                
                <!-- Durasi (Menit) -->
                <div>
                    <label for="durasi_menit" class="block text-xs font-semibold text-slate-700 mb-1.5 ml-1">
                        Durasi Pengerjaan (Menit) <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        id="durasi_menit" 
                        name="durasi_menit" 
                        value="{{ old('durasi_menit', 45) }}" 
                        min="1" 
                        max="480" 
                        required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 focus:bg-white transition-all"
                    >
                </div>

                <!-- Status Awal -->
                <div>
                    <label for="status" class="block text-xs font-semibold text-slate-700 mb-1.5 ml-1">
                        Status Awal Asessment <span class="text-rose-500">*</span>
                    </label>
                    <select 
                        id="status" 
                        name="status" 
                        required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 focus:bg-white transition-all cursor-pointer"
                    >
                        <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Draft (Isi &amp; Sesuaikan Soal Dulu)</option>
                        <option value="aktif" {{ old('status') === 'aktif' ? 'selected' : '' }}>Aktif (Dapat Langsung Dikerjakan Siswa)</option>
                    </select>
                </div>
            </div>

            <!-- Tombol Aksi Simpan -->
            <div class="pt-5 border-t border-slate-100 flex items-center justify-end gap-3">
                <a 
                    href="{{ route('guru.tes.index') }}" 
                    class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors cursor-pointer"
                >
                    Batal
                </a>
                <button 
                    type="submit" 
                    class="btn-sky-action"
                >
                    <span>Simpan &amp; Lanjut ke Pengaturan Dimensi &amp; Soal &rarr;</span>
                </button>
            </div>
        </form>

    </div>

</div>

<script>
function pilihJenis(namaJenis, defaultJudul) {
    // Update card styling
    document.querySelectorAll('.type-choice-card').forEach(el => {
        el.classList.remove('selected');
        if (el.dataset.jenis === namaJenis) {
            el.classList.add('selected');
        }
    });

    // Update judul
    const inputJudul = document.getElementById('judul');
    if (inputJudul) {
        inputJudul.value = defaultJudul;
    }

    // Auto-select category in dropdown
    const selectKategori = document.getElementById('id_kategori');
    if (selectKategori) {
        for (let i = 0; i < selectKategori.options.length; i++) {
            const opt = selectKategori.options[i];
            if (opt.text.toLowerCase().includes(namaJenis.toLowerCase()) || 
                (namaJenis.includes('VARK') && opt.text.includes('VARK')) ||
                (namaJenis.includes('OCEAN') && opt.text.includes('OCEAN')) ||
                (namaJenis.includes('RIASEC') && opt.text.includes('RIASEC')) ||
                (namaJenis.includes('Sosial') && opt.text.includes('Sosial'))) {
                selectKategori.selectedIndex = i;
                break;
            }
        }
    }
}
</script>
@endsection
