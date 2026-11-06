@extends('layouts.app')

@section('title', 'Edit Asessment')

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
    .form-card-sky {
        background: #ffffff;
        border: 1px solid #e0f2fe;
        border-radius: 24px;
        box-shadow: 0 4px 20px -2px rgba(2, 132, 199, 0.06);
    }
</style>
@endpush

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Header & Breadcrumb -->
    <div class="flex items-start justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('guru.dashboard') }}" class="hover:text-sky-600 transition-colors">Dashboard</a>
                <span>&rsaquo;</span>
                <a href="{{ route('guru.tes.index') }}" class="hover:text-sky-600 transition-colors">Asessment</a>
                <span>&rsaquo;</span>
                <span class="text-sky-700 font-semibold">Edit</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Asessment</h1>
            <p class="text-xs text-slate-500 mt-0.5">Perbarui pengaturan asesmen, target kelas, durasi, atau status aktif.</p>
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

        <form action="{{ route('guru.tes.update', $tes->id_tes) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Tipe Otomatis 'tes' (Asessment) -->
            <input type="hidden" name="tipe" value="tes">

            <!-- Banner Penanda Asessment -->
            <div class="p-4 rounded-2xl bg-sky-50/80 border border-sky-100 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-sky-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-800">Paket Asessment BK</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Tes bimbingan konseling dengan timer otomatis dan penilaian skor.</p>
                </div>
            </div>

            <!-- 1. Judul Asessment -->
            <div>
                <label for="judul" class="block text-xs font-semibold text-slate-700 mb-1.5 ml-1">
                    Judul Asessment <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="judul" 
                    name="judul" 
                    value="{{ old('judul', $tes->judul) }}" 
                    placeholder="Contoh: Asesmen Gaya Belajar &amp; Minat Karir 2026"
                    required 
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 focus:bg-white transition-all"
                >
            </div>

            <!-- 2. Target Kelas Binaan & Kategori Tes -->
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
                            <option value="{{ $kelas->id_kelas }}" {{ old('id_kelas', $tes->id_kelas) == $kelas->id_kelas ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }} ({{ $kelas->kode_unik_kelas }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Kategori Asessment -->
                <div>
                    <div class="flex items-center justify-between mb-1.5 ml-1">
                        <label for="id_kategori" class="text-xs font-semibold text-slate-700">
                            Kategori Asessment <span class="text-rose-500">*</span>
                        </label>
                        <button 
                            type="button" 
                            onclick="openKategoriModal()"
                            class="text-[11px] font-semibold text-sky-600 hover:text-sky-800 transition-colors cursor-pointer"
                        >
                            + Kategori Baru
                        </button>
                    </div>
                    <select 
                        id="id_kategori" 
                        name="id_kategori" 
                        required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 focus:bg-white transition-all cursor-pointer"
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

            <!-- 3. Durasi Pengerjaan & Status Asessment -->
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
                        value="{{ old('durasi_menit', $tes->durasi_menit ?? 60) }}" 
                        min="1" 
                        max="480" 
                        required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 focus:bg-white transition-all"
                    >
                </div>

                <!-- Status Asessment -->
                <div>
                    <label for="status" class="block text-xs font-semibold text-slate-700 mb-1.5 ml-1">
                        Status Asessment <span class="text-rose-500">*</span>
                    </label>
                    <select 
                        id="status" 
                        name="status" 
                        required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 focus:bg-white transition-all cursor-pointer"
                    >
                        <option value="draft" {{ old('status', $tes->status) === 'draft' ? 'selected' : '' }}>Draft (Belum Tampil ke Siswa)</option>
                        <option value="aktif" {{ old('status', $tes->status) === 'aktif' ? 'selected' : '' }}>Aktif (Dapat Dikerjakan Siswa)</option>
                        <option value="selesai" {{ old('status', $tes->status) === 'selesai' ? 'selected' : '' }}>Selesai / Ditutup</option>
                    </select>
                </div>
            </div>

            <!-- 4. Jadwal Mulai & Selesai (Opsional) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="waktu_mulai" class="block text-xs font-semibold text-slate-700 mb-1.5 ml-1">
                        Jadwal Mulai <span class="text-[10px] text-slate-400 font-normal">(Opsional)</span>
                    </label>
                    <input 
                        type="datetime-local" 
                        id="waktu_mulai" 
                        name="waktu_mulai" 
                        value="{{ old('waktu_mulai', $tes->waktu_mulai ? date('Y-m-d\TH:i', strtotime($tes->waktu_mulai)) : '') }}" 
                        class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 focus:bg-white transition-all cursor-pointer"
                    >
                </div>
                <div>
                    <label for="waktu_selesai" class="block text-xs font-semibold text-slate-700 mb-1.5 ml-1">
                        Jadwal Selesai <span class="text-[10px] text-slate-400 font-normal">(Opsional)</span>
                    </label>
                    <input 
                        type="datetime-local" 
                        id="waktu_selesai" 
                        name="waktu_selesai" 
                        value="{{ old('waktu_selesai', $tes->waktu_selesai ? date('Y-m-d\TH:i', strtotime($tes->waktu_selesai)) : '') }}" 
                        class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 focus:bg-white transition-all cursor-pointer"
                    >
                </div>
            </div>

            <!-- 5. Checkbox Acak Soal -->
            <div class="pt-1">
                <label class="flex items-center gap-2 cursor-pointer text-slate-700 text-xs font-medium select-none ml-1">
                    <input 
                        type="checkbox" 
                        name="acak_soal" 
                        id="acak_soal" 
                        value="1" 
                        {{ old('acak_soal', $tes->acak_soal) ? 'checked' : '' }}
                        class="w-4 h-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500 cursor-pointer"
                    >
                    <span>Acak urutan butir soal saat asesmen dikerjakan oleh siswa</span>
                </label>
            </div>

            <!-- Tombol Submit -->
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
                    <span>Simpan Perubahan &rarr;</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tambah Kategori -->
<div id="modalKategori" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
    <div class="w-full max-w-sm bg-white rounded-3xl shadow-xl border border-sky-100 p-6 space-y-4">
        <div>
            <h3 class="text-sm font-bold text-slate-900">Tambah Kategori Asessment</h3>
            <p class="text-xs text-slate-500 mt-0.5">Buat kategori baru untuk mengelompokkan paket asesmen.</p>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Kategori</label>
            <input 
                type="text" 
                id="modalNamaKategori" 
                placeholder="Contoh: Asesmen Bakat / Gaya Belajar" 
                class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500"
            >
        </div>
        <div class="flex items-center justify-end gap-2 pt-2">
            <button type="button" onclick="closeKategoriModal()" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-100">
                Batal
            </button>
            <button type="button" onclick="submitKategoriBaru()" class="btn-sky-action">
                Simpan
            </button>
        </div>
    </div>
</div>

<script>
function openKategoriModal() {
    document.getElementById('modalKategori').classList.remove('hidden');
    document.getElementById('modalNamaKategori').focus();
}

function closeKategoriModal() {
    document.getElementById('modalKategori').classList.add('hidden');
    document.getElementById('modalNamaKategori').value = '';
}

function submitKategoriBaru() {
    const nama = document.getElementById('modalNamaKategori').value.trim();
    if (!nama) return alert('Nama kategori wajib diisi.');

    fetch("{{ route('guru.kategori.store') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ nama_kategori: nama })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const select = document.getElementById('id_kategori');
            const opt = document.createElement('option');
            opt.value = data.kategori.id_kategori;
            opt.textContent = data.kategori.nama_kategori;
            opt.selected = true;
            select.appendChild(opt);
            closeKategoriModal();
        } else {
            alert(data.message || 'Gagal menyimpan kategori.');
        }
    })
    .catch(() => alert('Terjadi kesalahan saat menyimpan kategori.'));
}
</script>
@endsection
