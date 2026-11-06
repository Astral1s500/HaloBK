@extends('layouts.app')

@section('title', 'Kelola Akun Siswa')

@push('styles')
<style>
    .btn-sky-solid {
        background-color: #0284c7 !important;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 0.825rem;
        padding: 0.6rem 1.1rem;
        border-radius: 12px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        box-shadow: 0 2px 8px rgba(2, 132, 199, 0.25);
        border: none;
        transition: all 0.2s;
    }
    .btn-sky-solid:hover {
        background-color: #0369a1 !important;
        transform: translateY(-1px);
    }
    .btn-emerald-solid {
        background-color: #059669 !important;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 0.825rem;
        padding: 0.6rem 1.1rem;
        border-radius: 12px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        box-shadow: 0 2px 8px rgba(5, 150, 105, 0.25);
        border: none;
        transition: all 0.2s;
    }
    .btn-emerald-solid:hover {
        background-color: #047857 !important;
        transform: translateY(-1px);
    }
    .btn-sky-light {
        background-color: #f0f9ff !important;
        color: #0369a1 !important;
        border: 1px solid #bae6fd !important;
        font-weight: 600;
        font-size: 0.825rem;
        padding: 0.6rem 1.1rem;
        border-radius: 12px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        transition: all 0.2s;
    }
    .btn-sky-light:hover {
        background-color: #e0f2fe !important;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-sky-600 transition-colors">Dashboard</a>
                <span>&rsaquo;</span>
                <span class="text-sky-700 font-semibold">Akun Siswa</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Kelola Akun Siswa</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola seluruh akun peserta didik, registrasi NISN, dan import massal Excel.</p>
        </div>

        <!-- Tombol Aksi Lengkap -->
        <div class="flex items-center gap-2 flex-wrap">
            <!-- Tombol Kembali -->
            <a
                href="{{ route('admin.dashboard') }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-200 py-2.5 px-3.5 rounded-xl transition-all shadow-xs cursor-pointer"
            >
                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Kembali</span>
            </a>

            <!-- Tombol Export Excel -->
            <a 
                href="{{ route('admin.siswa.exportExcel') }}" 
                class="btn-emerald-solid"
                title="Ekspor seluruh data siswa ke format Excel / CSV"
            >
                <svg style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                <span>Export Excel</span>
            </a>

            <!-- Tombol Buka Modal Import Excel -->
            <button 
                type="button" 
                onclick="openImportModal()"
                class="btn-sky-light cursor-pointer"
                title="Import banyak siswa sekaligus dari file Excel"
            >
                <svg style="width:16px;height:16px;color:#0284c7;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l4-4m0 0l4 4m-4-4v12" />
                </svg>
                <span>Import Excel</span>
            </button>

            <!-- Tombol Tambah Siswa Manual -->
            <a 
                href="{{ route('admin.siswa.create') }}" 
                class="btn-sky-solid"
            >
                <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Akun Siswa</span>
            </a>
        </div>
    </div>

    <!-- Tab Filter: Siswa Aktif vs Siswa Arsip -->
    <div class="flex items-center gap-2 border-b border-sky-100">
        <a 
            href="{{ route('admin.siswa.index', ['status' => 'aktif']) }}" 
            class="flex items-center gap-2 px-4 py-2.5 text-xs font-semibold border-b-2 transition-colors {{ $status === 'aktif' ? 'border-sky-600 text-sky-700' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}"
        >
            <span>Siswa Aktif</span>
            <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $status === 'aktif' ? 'bg-sky-600 text-white' : 'bg-slate-100 text-slate-600' }}">
                {{ $totalAktif }}
            </span>
        </a>
        <a 
            href="{{ route('admin.siswa.index', ['status' => 'arsip']) }}" 
            class="flex items-center gap-2 px-4 py-2.5 text-xs font-semibold border-b-2 transition-colors {{ $status === 'arsip' ? 'border-amber-600 text-amber-700' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}"
        >
            <span>Siswa Keluar / Nonaktif (Arsip)</span>
            <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $status === 'arsip' ? 'bg-amber-600 text-white' : 'bg-slate-100 text-slate-600' }}">
                {{ $totalArsip }}
            </span>
        </a>
    </div>

    @if($status === 'arsip')
        <div class="bg-amber-50/90 border border-amber-200 rounded-2xl p-4 text-xs text-amber-800 flex items-start gap-3 shadow-xs">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <p class="font-bold">Informasi Perlindungan Data Siswa (Soft Delete)</p>
                <p class="mt-0.5 text-amber-700 leading-relaxed">
                    Siswa pada tab ini telah dinonaktifkan (diarsipkan). Seluruh rekam jejak nilai dan histori asesmen siswa tetap tersimpan aman di basis data. Anda dapat mengaktifkan kembali akun siswa sewaktu-waktu dengan menekan tombol <strong>Pulihkan</strong>.
                </p>
            </div>
        </div>
    @endif

    <!-- Tabel Data Siswa -->
    <div class="bg-white rounded-3xl border border-sky-100 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-sky-50/60 border-b border-sky-100 text-[11px] font-bold text-slate-600 uppercase tracking-wider">
                        <th class="py-3.5 px-4 text-center w-12">No</th>
                        <th class="py-3.5 px-4">Nama Siswa</th>
                        <th class="py-3.5 px-4">NISN</th>
                        <th class="py-3.5 px-4 text-center">Kelas</th>
                        @if($status === 'aktif')
                            <th class="py-3.5 px-4">Didaftarkan Oleh</th>
                            <th class="py-3.5 px-4 text-center w-36">Aksi</th>
                        @else
                            <th class="py-3.5 px-4 text-center">Tanggal Dihapus</th>
                            <th class="py-3.5 px-4 text-center w-48">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-sky-50 text-xs text-slate-700">
                    @forelse($siswas as $index => $siswa)
                        <tr class="hover:bg-sky-50/40 transition-colors">
                            <td class="py-3.5 px-4 text-center font-medium text-slate-400">{{ $index + 1 }}</td>
                            <td class="py-3.5 px-4 font-semibold text-slate-900">
                                {{ $siswa->nama_siswa }}
                                @if($status === 'arsip')
                                    <span class="inline-flex items-center ml-1 px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">
                                        Nonaktif / Arsip
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 font-mono text-slate-600 font-medium">{{ $siswa->nisn }}</td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-sky-50 text-sky-800 border border-sky-100">
                                    {{ $siswa->kelas->nama_kelas ?? 'Belum Ditentukan' }}
                                </span>
                            </td>

                            @if($status === 'aktif')
                                <td class="py-3.5 px-4 text-slate-500">{{ $siswa->admin->nama_admin ?? 'Admin' }}</td>
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a 
                                            href="{{ route('admin.siswa.edit', $siswa->id_siswa) }}" 
                                            class="px-2.5 py-1 text-[11px] font-semibold text-sky-700 bg-sky-50 hover:bg-sky-100 rounded-lg transition-colors border border-sky-100"
                                        >
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.siswa.destroy', $siswa->id_siswa) }}" method="POST" class="inline" onsubmit="return confirm('Nonaktifkan siswa ini? Akun akan dipindahkan ke tab Arsip dan seluruh riwayat nilainya tetap aman.');">
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                type="submit" 
                                                class="px-2.5 py-1 text-[11px] font-semibold text-amber-700 bg-amber-50 hover:bg-amber-100 rounded-lg transition-colors cursor-pointer border border-amber-100"
                                                title="Pindahkan ke Arsip (Soft Delete)"
                                            >
                                                Nonaktifkan
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @else
                                <td class="py-3.5 px-4 text-center text-slate-500">
                                    {{ $siswa->deleted_at ? $siswa->deleted_at->format('d M Y, H:i') : '-' }} WIB
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Tombol Pulihkan (Restore) -->
                                        <form action="{{ route('admin.siswa.restore', $siswa->id_siswa) }}" method="POST" class="inline">
                                            @csrf
                                            <button 
                                                type="submit" 
                                                class="px-2.5 py-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-lg transition-colors cursor-pointer border border-emerald-100"
                                            >
                                                Pulihkan
                                            </button>
                                        </form>

                                        <!-- Tombol Hapus Permanen (Force Delete) -->
                                        <form action="{{ route('admin.siswa.forceDelete', $siswa->id_siswa) }}" method="POST" class="inline" onsubmit="return confirm('PERINGATAN: Menghapus permanen akan menghapus SELURUH riwayat nilai, jawaban, dan sesi ujian siswa ini tanpa bisa dikembalikan lagi! Lanjutkan?');">
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                type="submit" 
                                                class="px-2.5 py-1 text-[11px] font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 rounded-lg transition-colors cursor-pointer border border-rose-100"
                                            >
                                                Hapus Permanen
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-xs text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-400 flex items-center justify-center mb-2">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    </div>
                                    @if($status === 'aktif')
                                        <p class="font-semibold text-slate-700">Belum Ada Siswa Terdaftar</p>
                                        <p class="text-[11px] text-slate-400 mt-0.5">Tambahkan siswa manual atau gunakan fitur <strong>Import Excel</strong>.</p>
                                    @else
                                        <p class="font-semibold text-slate-700">Tidak Ada Siswa di Arsip</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal Dialog Import Excel Massal -->
<div id="importExcelModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm transition-opacity">
    <div class="w-full max-w-lg bg-white rounded-3xl shadow-2xl border border-sky-100 overflow-hidden transform transition-all">
        
        <!-- Header Modal -->
        <div class="p-6 bg-gradient-to-r from-sky-50 to-white border-b border-sky-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-sky-600 text-white flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l4-4m0 0l4 4m-4-4v12" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Import Siswa dari Excel</h3>
                    <p class="text-xs text-slate-500">Tambah banyak akun siswa sekaligus secara otomatis</p>
                </div>
            </div>
            <button type="button" onclick="closeImportModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Body Form Modal -->
        <form action="{{ route('admin.siswa.importExcel') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf

            <!-- Panduan & Download Template -->
            <div class="p-4 bg-sky-50/70 border border-sky-100 rounded-2xl text-xs space-y-2">
                <div class="flex items-center justify-between">
                    <span class="font-bold text-sky-900">1. Unduh Template Excel / CSV</span>
                    <a 
                        href="{{ route('admin.siswa.downloadTemplate') }}" 
                        class="inline-flex items-center gap-1 text-[11px] font-bold text-sky-700 hover:text-sky-800 bg-white px-2.5 py-1 rounded-lg border border-sky-200 shadow-2xs hover:shadow-xs transition-all"
                    >
                        <svg class="w-3.5 h-3.5 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        <span>Unduh Template</span>
                    </a>
                </div>
                <p class="text-sky-800 text-[11px] leading-relaxed">
                    Gunakan template di atas. Format kolom: <code>NISN</code>, <code>Nama Siswa</code>, dan <code>Password</code> (default: <em>password123</em> jika dikosongkan).
                </p>
            </div>

            <!-- Upload File Input -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-2">
                    2. Pilih File Excel / CSV yang Telah Diisi
                </label>
                <div class="border-2 border-dashed border-sky-200 rounded-2xl p-6 text-center hover:bg-sky-50/30 transition-colors">
                    <svg class="w-8 h-8 text-sky-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <input 
                        type="file" 
                        name="file_excel" 
                        accept=".csv,.txt"
                        required 
                        class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-sky-100 file:text-sky-700 hover:file:bg-sky-200 cursor-pointer"
                    >
                    <p class="text-[11px] text-slate-400 mt-2">Mendukung format file <code>.csv</code> (Maks. 5 MB)</p>
                </div>
            </div>

            <!-- Tombol Aksi Modal -->
            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button 
                    type="button" 
                    onclick="closeImportModal()"
                    class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors cursor-pointer"
                >
                    Batal
                </button>
                <button 
                    type="submit" 
                    class="px-4 py-2.5 rounded-xl text-xs font-semibold bg-sky-600 hover:bg-sky-500 text-white shadow-sm transition-all cursor-pointer flex items-center gap-1.5"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Mulai Import Siswa</span>
                </button>
            </div>
        </form>

    </div>
</div>

<script>
function openImportModal() {
    const modal = document.getElementById('importExcelModal');
    if (modal) {
        modal.classList.remove('hidden');
    }
}

function closeImportModal() {
    const modal = document.getElementById('importExcelModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}
</script>
@endsection
