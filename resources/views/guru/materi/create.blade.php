@extends('layouts.app')

@section('title', 'Unggah Materi Bimbingan')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Header & Breadcrumb -->
    <div class="flex items-start justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('guru.dashboard') }}" class="hover:text-slate-900 transition-colors">Dashboard</a>
                <span>&rsaquo;</span>
                <a href="{{ route('guru.materi.index') }}" class="hover:text-slate-900 transition-colors">Materi Bimbingan</a>
                <span>&rsaquo;</span>
                <span class="text-slate-800 font-semibold">Unggah Baru</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Unggah Materi Bimbingan</h1>
            <p class="text-xs text-slate-500 mt-0.5">Unggah dokumen materi, panduan karir, atau artikel edukatif untuk siswa.</p>
        </div>

        <a
            href="{{ route('guru.materi.index') }}"
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

        <form action="{{ route('guru.materi.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label for="judul_materi" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Judul Materi Bimbingan
                </label>
                <input 
                    type="text" 
                    id="judul_materi" 
                    name="judul_materi" 
                    value="{{ old('judul_materi') }}" 
                    placeholder="Contoh: Panduan Memilih Jurusan Kuliah Sesuai Minat Bakat"
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
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium text-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 focus:border-slate-900 transition-colors"
                    >
                        <option value="">Semua Kelas Binaan (Materi Terbuka / Umum)</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id_kelas }}" {{ old('id_kelas') == $kelas->id_kelas ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }} (Kode: {{ $kelas->kode_unik_kelas }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="id_kategori" class="block text-xs font-semibold text-slate-700">
                            Kategori Materi
                        </label>
                        <button 
                            type="button" 
                            onclick="toggleNewKategoriModal()" 
                            class="text-[11px] font-semibold text-slate-900 hover:text-slate-700 underline flex items-center gap-1 cursor-pointer"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Kategori Baru</span>
                        </button>
                    </div>

                    <!-- Box Input Kategori Baru Cepat (Tersembunyi Awalnya) -->
                    <div id="newKategoriBox" class="hidden mb-3 p-3 bg-slate-50 border border-slate-300 rounded-xl space-y-2">
                        <span class="block text-[11px] font-bold text-slate-800">Buat Kategori Baru:</span>
                        <div class="flex items-center gap-2">
                            <input 
                                type="text" 
                                id="inputNamaKategori" 
                                placeholder="Isi Kategori baru di sini" 
                                class="flex-1 px-3 py-1.5 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 focus:border-slate-900"
                            >
                            <button 
                                type="button" 
                                id="btnSaveKategori"
                                onclick="saveNewKategori()" 
                                class="bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors cursor-pointer shrink-0"
                            >
                                Simpan
                            </button>
                            <button 
                                type="button" 
                                onclick="toggleNewKategoriModal()" 
                                class="text-xs text-slate-500 hover:text-slate-800 px-2 py-1.5 rounded-lg transition-colors"
                            >
                                Batal
                            </button>
                        </div>
                        <div id="kategoriError" class="text-[11px] text-red-600 hidden font-medium"></div>
                    </div>

                    <select 
                        id="id_kategori" 
                        name="id_kategori" 
                        required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium text-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 focus:border-slate-900 transition-colors"
                    >
                        <option value="" disabled {{ old('id_kategori') ? '' : 'selected' }}>Pilih Kategori</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id_kategori }}" {{ old('id_kategori') == $kategori->id_kategori ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="file_materi" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    File Dokumen Materi <span class="text-slate-400 font-normal">(PDF, DOC, DOCX, PPT, PPTX &bull; Maks 10MB)</span>
                </label>
                <input 
                    type="file" 
                    id="file_materi" 
                    name="file_materi" 
                    accept=".pdf,.doc,.docx,.ppt,.pptx"
                    required
                    class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium text-slate-900 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800 transition-colors"
                >
            </div>

            <div>
                <label for="deskripsi" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Deskripsi / Ringkasan Materi <span class="text-slate-400 font-normal">(Opsional)</span>
                </label>
                <textarea 
                    id="deskripsi" 
                    name="deskripsi" 
                    rows="4" 
                    placeholder="Tuliskan catatan pengantar atau poin utama materi ini..."
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-1 focus:ring-slate-900 focus:border-slate-900 transition-colors"
                >{{ old('deskripsi') }}</textarea>
            </div>

            <!-- Pilihan Warna dihapus -->

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a 
                    href="{{ route('guru.materi.index') }}" 
                    class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800 transition-colors"
                >
                    Batal
                </a>
                <button 
                    type="submit" 
                    class="bg-slate-900 hover:bg-slate-800 text-white font-semibold py-2 px-5 rounded-lg text-xs tracking-wide transition-colors cursor-pointer"
                >
                    Unggah & Simpan Materi
                </button>
            </div>
        </form>

    </div>

</div>

<!-- Script Tambah Kategori Baru Cepat -->
<script>
function toggleNewKategoriModal() {
    const box = document.getElementById('newKategoriBox');
    const input = document.getElementById('inputNamaKategori');
    const err = document.getElementById('kategoriError');
    err.classList.add('hidden');
    box.classList.toggle('hidden');
    if (!box.classList.contains('hidden')) {
        input.focus();
    }
}

function saveNewKategori() {
    const input = document.getElementById('inputNamaKategori');
    const btn = document.getElementById('btnSaveKategori');
    const err = document.getElementById('kategoriError');
    const val = input.value.trim();

    if (!val) {
        err.innerText = 'Silakan ketikkan nama kategori terlebih dahulu.';
        err.classList.remove('hidden');
        input.focus();
        return;
    }

    err.classList.add('hidden');
    btn.disabled = true;
    btn.innerText = 'Menyimpan...';

    fetch("{{ route('guru.kategori.store') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
        },
        body: JSON.stringify({ nama_kategori: val })
    })
    .then(response => response.json())
    .then(data => {
        btn.disabled = false;
        btn.innerText = 'Simpan';

        if (data.success && data.kategori) {
            // Tambahkan option baru ke select dropdown dan pilih
            const select = document.getElementById('id_kategori');
            const newOption = document.createElement('option');
            newOption.value = data.kategori.id_kategori;
            newOption.text = data.kategori.nama_kategori;
            newOption.selected = true;
            select.appendChild(newOption);

            input.value = '';
            document.getElementById('newKategoriBox').classList.add('hidden');
        } else {
            err.innerText = data.message || (data.errors ? Object.values(data.errors)[0][0] : 'Gagal menyimpan kategori.');
            err.classList.remove('hidden');
        }
    })
    .catch(error => {
        btn.disabled = false;
        btn.innerText = 'Simpan';
        err.innerText = 'Terjadi kesalahan jaringan atau server.';
        err.classList.remove('hidden');
    });
}

// Script Tambah Kategori Baru Cepat
</script>
@endsection
