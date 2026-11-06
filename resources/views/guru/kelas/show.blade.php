@extends('layouts.app')

@section('title', 'Detail Kelas: ' . $kelas->nama_kelas)

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('guru.dashboard') }}" class="hover:text-slate-900 transition-colors">Dashboard</a>
                <span>&rsaquo;</span>
                <a href="{{ route('guru.kelas.index') }}" class="hover:text-slate-900 transition-colors">Kelas Binaan</a>
                <span>&rsaquo;</span>
                <span class="text-slate-800 font-semibold">{{ $kelas->nama_kelas }}</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Kelas: {{ $kelas->nama_kelas }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">Daftar siswa yang telah bergabung ke kelas ini.</p>
        </div>

        <div class="flex items-center gap-2">
            <a
                href="{{ route('guru.kelas.index') }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 py-2.5 px-4 rounded-xl transition-colors cursor-pointer"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
            <a 
                href="{{ route('guru.kelas.edit', $kelas->id_kelas) }}" 
                class="inline-flex items-center gap-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold py-2.5 px-4 rounded-xl shadow-sm transition-colors cursor-pointer"
            >
                <span>Edit Kelas</span>
            </a>
        </div>
    </div>

    <!-- Banner Info Kode Unik Kelas -->
    <div class="bg-slate-900 text-white rounded-2xl p-6 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Kode kelas</span>
            <div class="flex items-center gap-3 mt-1">
                <span class="text-2xl md:text-3xl font-mono font-black text-white tracking-widest">{{ $kelas->kode_unik_kelas }}</span>
                <button 
                    type="button" 
                    onclick="copyCode('{{ $kelas->kode_unik_kelas }}', this)" 
                    class="text-slate-300 hover:text-white bg-slate-800 hover:bg-slate-700 text-xs font-semibold border border-slate-700 px-3 py-1.5 rounded-lg transition-colors cursor-pointer"
                >
                    Salin Kode
                </button>
            </div>
            <p class="text-xs text-slate-400 mt-2">
                Bagikan kode unik ini kepada siswa agar mereka dapat mengetikkannya di dashboard Siswa dan bergabung secara mandiri.
            </p>
        </div>

        <div class="bg-slate-800 border border-slate-700 px-5 py-3.5 rounded-xl text-center shrink-0">
            <span class="block text-2xl font-extrabold text-white">{{ $kelas->siswas->count() }}</span>
            <span class="text-[11px] text-slate-400 font-medium">Siswa Terdaftar</span>
        </div>
    </div>

    <!-- Tabel Daftar Siswa di Kelas -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-900">Daftar Anggota Siswa ({{ $kelas->siswas->count() }})</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold text-slate-600 uppercase tracking-wider">
                        <th class="py-3 px-4 text-center w-12">No</th>
                        <th class="py-3 px-4">Nama Siswa</th>
                        <th class="py-3 px-4">NISN</th>
                        <th class="py-3 px-4">Waktu Bergabung</th>
                        <th class="py-3 px-4 text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                    @forelse($kelas->siswas as $index => $siswa)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3.5 px-4 text-center font-medium text-slate-400">{{ $index + 1 }}</td>
                            <td class="py-3.5 px-4 font-semibold text-slate-900">{{ $siswa->nama_siswa }}</td>
                            <td class="py-3.5 px-4 font-mono text-slate-600">{{ $siswa->nisn }}</td>
                            <td class="py-3.5 px-4 text-slate-500">{{ $siswa->updated_at ? $siswa->updated_at->format('d M Y, H:i') : '-' }}</td>
                            <td class="py-3.5 px-4 text-center">
                                <form action="{{ route('guru.kelas.kickStudent', ['kelas' => $kelas->id_kelas, 'siswa' => $siswa->id_siswa]) }}" method="POST" class="inline form-delete" data-confirm-title="Keluarkan Siswa dari Kelas" data-confirm-message="Keluarkan siswa '{{ $siswa->nama_siswa }}' dari kelas ini? Siswa harus memasukkan kode kelas kembali jika ingin bergabung.">
                                    @csrf
                                    <button 
                                        type="submit" 
                                        class="px-2.5 py-1 text-[11px] font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors cursor-pointer"
                                    >
                                        Keluarkan
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-xs text-slate-400">
                                Belum ada siswa yang bergabung ke kelas ini. Bagikan kode <strong>{{ $kelas->kode_unik_kelas }}</strong> kepada siswa Anda.
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
        setTimeout(function() {
            btn.innerText = originalText;
        }, 1500);
    });
}
</script>
@endsection
