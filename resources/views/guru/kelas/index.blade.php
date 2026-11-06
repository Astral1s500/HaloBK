@extends('layouts.app')

@section('title', 'Kelola Kelas Binaan')

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('guru.dashboard') }}" class="hover:text-slate-900 transition-colors">Dashboard</a>
                <span>&rsaquo;</span>
                <span class="text-slate-800 font-semibold">Kelas Binaan</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Daftar Kelas Binaan</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola kelas bimbingan dan bagikan kode unik kepada siswa untuk bergabung.</p>
        </div>

        <div class="flex items-center gap-2">
            <!-- Tombol Kembali ke Dashboard Guru -->
            <a
                href="{{ route('guru.dashboard') }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 py-2.5 px-4 rounded-xl transition-colors cursor-pointer"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
            <!-- Tombol Buat Kelas Baru -->
            <a 
                href="{{ route('guru.kelas.create') }}" 
                class="inline-flex items-center gap-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold py-2.5 px-4 rounded-xl shadow-sm transition-colors cursor-pointer"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Buat Kelas Baru</span>
            </a>
        </div>
    </div>

    <!-- Tabel Daftar Kelas -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold text-slate-600 uppercase tracking-wider">
                        <th class="py-3.5 px-4 text-center w-12">No</th>
                        <th class="py-3.5 px-4">Nama Kelas</th>
                        <th class="py-3.5 px-4">Kode Unik Kelas (Share ke Siswa)</th>
                        <th class="py-3.5 px-4 text-center">Jumlah Siswa</th>
                        <th class="py-3.5 px-4 text-center w-48">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                    @forelse($kelasList as $index => $kelas)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3.5 px-4 text-center font-medium text-slate-400">{{ $index + 1 }}</td>
                            <td class="py-3.5 px-4 font-bold text-slate-900">{{ $kelas->nama_kelas }}</td>
                            <td class="py-3.5 px-4">
                                <div class="inline-flex items-center gap-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-mono font-bold bg-slate-900 text-white tracking-widest">
                                        {{ $kelas->kode_unik_kelas }}
                                    </span>
                                    <button 
                                        type="button" 
                                        onclick="copyCode('{{ $kelas->kode_unik_kelas }}', this)" 
                                        class="text-slate-400 hover:text-slate-700 text-[11px] font-semibold border border-slate-200 px-2 py-0.5 rounded transition-colors"
                                        title="Salin Kode Unik"
                                    >
                                        Salin
                                    </button>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700">
                                    {{ $kelas->siswas_count }} Siswa
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a 
                                        href="{{ route('guru.kelas.show', $kelas->id_kelas) }}" 
                                        class="px-2.5 py-1 text-[11px] font-semibold text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors"
                                    >
                                        Lihat Siswa
                                    </a>
                                    <a 
                                        href="{{ route('guru.kelas.edit', $kelas->id_kelas) }}" 
                                        class="px-2.5 py-1 text-[11px] font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors"
                                    >
                                        Edit
                                    </a>
                                    <form action="{{ route('guru.kelas.destroy', $kelas->id_kelas) }}" method="POST" class="inline form-delete" data-confirm-title="Hapus Kelas Binaan" data-confirm-message="Yakin ingin menghapus kelas '{{ $kelas->nama_kelas }}'? Seluruh data siswa akan dilepaskan dari kelas ini.">
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit" 
                                            class="px-2.5 py-1 text-[11px] font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors cursor-pointer"
                                        >
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-xs text-slate-400">
                                Anda belum memiliki kelas binaan. Silakan klik tombol <strong>+ Buat Kelas Baru</strong>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Script Salin Kode Unik -->
<script>
function copyCode(text, btn) {
    navigator.clipboard.writeText(text).then(function() {
        const originalText = btn.innerText;
        btn.innerText = 'Tersalin!';
        btn.classList.add('bg-slate-900', 'text-white');
        setTimeout(function() {
            btn.innerText = originalText;
            btn.classList.remove('bg-slate-900', 'text-white');
        }, 1500);
    });
}
</script>
@endsection
