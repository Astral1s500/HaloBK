@extends('layouts.app')

@section('title', 'Materi Bimbingan')

@push('styles')
<style>
    /* ===== Sky Cloud Theme — Halaman Materi Siswa ===== */
    .hero-materi-siswa {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 60%, #075985 100%);
        border: 1px solid rgba(255,255,255,0.15);
    }

    .materi-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #e0f2fe;
        box-shadow: 0 4px 20px -4px rgba(2,132,199,0.08);
        transition: transform 0.22s cubic-bezier(0.34,1.56,0.64,1), box-shadow 0.22s ease, border-color 0.2s;
        display: flex;
        flex-direction: column;
    }
    .materi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 32px -6px rgba(2,132,199,0.18);
        border-color: #7dd3fc;
    }

    .materi-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* File type icon colors */
    .ftype-pdf  { background: #fff1f2; color: #e11d48; border: 1px solid #fecdd3; }
    .ftype-ppt  { background: #fff7ed; color: #ea580c; border: 1px solid #fed7aa; }
    .ftype-doc  { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
    .ftype-other{ background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }

    .badge-kategori {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.25rem 0.65rem;
        border-radius: 999px;
        background: #f0f9ff;
        color: #0284c7;
        border: 1px solid #bae6fd;
    }

    .badge-kelas-all {
        font-size: 0.65rem;
        font-weight: 600;
        padding: 0.2rem 0.6rem;
        border-radius: 999px;
        background: #f0fdf4;
        color: #16a34a;
        border: 1px solid #bbf7d0;
    }

    .btn-download {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 12px;
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        color: #ffffff;
        border: none;
        text-decoration: none;
        box-shadow: 0 4px 14px -3px rgba(2,132,199,0.3);
        transition: transform 0.22s cubic-bezier(0.34,1.56,0.64,1), box-shadow 0.22s ease;
    }
    .btn-download:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -4px rgba(2,132,199,0.4);
        color: #ffffff;
    }

    .empty-state-box {
        background: #ffffff;
        border: 1.5px dashed #bae6fd;
        border-radius: 24px;
        padding: 3.5rem 2rem;
        text-align: center;
        margin: 1.5rem 0;
    }

    /* Filter bar */
    .filter-input {
        height: 40px;
        padding: 0 0.9rem;
        border: 1px solid #e0f2fe;
        border-radius: 12px;
        font-size: 0.82rem;
        background: #ffffff;
        color: #1e293b;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .filter-input:focus {
        border-color: #7dd3fc;
        box-shadow: 0 0 0 3px rgba(125,211,252,0.18);
    }

    .btn-filter-clear {
        height: 40px;
        padding: 0 0.9rem;
        border: 1px solid #fca5a5;
        border-radius: 12px;
        font-size: 0.78rem;
        font-weight: 600;
        background: #fff1f2;
        color: #e11d48;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        transition: background 0.2s, border-color 0.2s;
    }
    .btn-filter-clear:hover { background: #fee2e2; }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 space-y-6">

    {{-- ====================== HERO HEADER ====================== --}}
    <div class="hero-materi-siswa rounded-3xl p-6 md:p-8 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 rounded-xl bg-white/15 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-sky-200 uppercase tracking-wider">Materi Bimbingan</span>
                </div>
                <h1 class="text-2xl md:text-3xl font-extrabold leading-tight">Materi Bimbingan Konseling</h1>
                <p class="text-sky-200 text-sm mt-1">
                    @if($kelas)
                        Materi untuk kelas <strong class="text-white">{{ $kelas->nama_kelas }}</strong>
                        dari Guru BK <strong class="text-white">{{ $kelas->guruBk->nama_guru ?? '-' }}</strong>
                    @else
                        Bergabunglah ke kelas terlebih dahulu untuk melihat materi.
                    @endif
                </p>
            </div>

            @if($kelas)
            <div class="flex items-center gap-3">
                <div class="bg-white/15 backdrop-blur-sm border border-white/20 rounded-2xl px-5 py-3 text-center">
                    <div class="text-2xl font-extrabold text-white">{{ $materis->count() }}</div>
                    <div class="text-sky-200 text-xs font-medium mt-0.5">Materi Tersedia</div>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- ====================== BELUM PUNYA KELAS ====================== --}}
    @if(!$kelas)
        <div class="empty-state-box">
            <div class="w-16 h-16 rounded-2xl bg-sky-50 border border-sky-100 flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <h3 class="text-slate-800 font-bold text-lg mb-2">Belum Terdaftar di Kelas</h3>
            <p class="text-slate-500 text-sm mb-4 max-w-sm mx-auto">Masukkan kode kelas dari Guru BK Anda di dashboard untuk melihat materi bimbingan.</p>
            <a href="{{ route('siswa.dashboard') }}" class="btn-download">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Ke Dashboard
            </a>
        </div>

    @else
        {{-- ====================== FILTER BAR ====================== --}}
        <form method="GET" action="{{ route('siswa.materi.index') }}" class="bg-white border border-sky-100 rounded-2xl p-4 shadow-xs">
            <div class="flex flex-wrap gap-3 items-end">
                {{-- Search --}}
                <div class="flex-1 min-w-[200px]">
                    <label class="text-xs font-semibold text-slate-500 mb-1 block">Cari Materi</label>
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Judul materi..." class="filter-input pl-9 w-full">
                    </div>
                </div>

                {{-- Kategori --}}
                <div class="min-w-[180px]">
                    <label class="text-xs font-semibold text-slate-500 mb-1 block">Kategori</label>
                    <select name="kategori" class="filter-input w-full">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id_kategori }}" {{ request('kategori') == $kat->id_kategori ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tombol --}}
                <div class="flex items-end gap-2">
                    <button type="submit" class="btn-download h-10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                        </svg>
                        Filter
                    </button>
                    @if(request('search') || request('kategori'))
                        <a href="{{ route('siswa.materi.index') }}" class="btn-filter-clear">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>

        {{-- ====================== GRID MATERI ====================== --}}
        @if($materis->isEmpty())
            <div class="empty-state-box">
                <div class="w-16 h-16 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <h3 class="text-slate-800 font-bold text-lg mb-1">Belum Ada Materi</h3>
                <p class="text-slate-500 text-sm">
                    @if(request('search') || request('kategori'))
                        Tidak ada materi yang sesuai dengan filter. Coba ubah pencarian.
                    @else
                        Guru BK belum mengunggah materi untuk kelasmu. Nantikan ya!
                    @endif
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($materis as $materi)
                    @php
                        $ext = strtolower(pathinfo($materi->file_path ?? '', PATHINFO_EXTENSION));
                        $iconClass = match($ext) {
                            'pdf'          => 'ftype-pdf',
                            'ppt', 'pptx'  => 'ftype-ppt',
                            'doc', 'docx'  => 'ftype-doc',
                            default        => 'ftype-other',
                        };
                        $extLabel = strtoupper($ext ?: 'FILE');
                    @endphp
                    <div class="materi-card p-5">
                        {{-- Top Row: Icon + Badge --}}
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <div class="materi-icon-box {{ $iconClass }}">
                                @if($ext === 'pdf')
                                    {{-- PDF --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                @elseif(in_array($ext, ['ppt','pptx']))
                                    {{-- PPT --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                @elseif(in_array($ext, ['doc','docx']))
                                    {{-- DOC --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                @endif
                            </div>
                            <div class="flex flex-wrap gap-1.5">
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full border" style="background:#f8fafc;color:#64748b;border-color:#e2e8f0;">
                                    {{ $extLabel }}
                                </span>
                                @if(is_null($materi->id_kelas))
                                    <span class="badge-kelas-all">Semua Kelas</span>
                                @endif
                            </div>
                        </div>

                        {{-- Judul & Deskripsi --}}
                        <div class="flex-1">
                            <h3 class="font-bold text-slate-800 text-sm leading-snug mb-1">{{ $materi->judul_materi }}</h3>
                            @if($materi->deskripsi)
                                <p class="text-slate-500 text-xs leading-relaxed line-clamp-2 mb-3">{{ $materi->deskripsi }}</p>
                            @else
                                <p class="text-slate-400 text-xs italic mb-3">Tidak ada deskripsi.</p>
                            @endif
                        </div>

                        {{-- Kategori & Guru --}}
                        <div class="flex flex-wrap items-center gap-2 mb-4">
                            @if($materi->kategori)
                                <span class="badge-kategori">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    {{ $materi->kategori->nama_kategori }}
                                </span>
                            @endif
                        </div>

                        {{-- Footer: Guru & Upload date --}}
                        <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                            <div class="flex items-center gap-2 min-w-0">
                                <div class="w-7 h-7 rounded-full bg-sky-100 flex items-center justify-center flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-xs font-semibold text-slate-700 truncate">{{ $materi->guruBk->nama_guru ?? 'Guru BK' }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $materi->created_at->diffForHumans() }}</div>
                                </div>
                            </div>

                            {{-- Tombol Aksi Buka & Unduh --}}
                            <div class="flex items-center gap-2">
                                <a href="{{ route('siswa.materi.buka', $materi->id_materi) }}" target="_blank" class="px-3 py-1.5 rounded-xl border border-sky-200 text-sky-700 bg-sky-50 hover:bg-sky-100 text-xs font-semibold inline-flex items-center gap-1 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Lihat
                                </a>
                                <a href="{{ route('siswa.materi.download', $materi->id_materi) }}" class="btn-download">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Unduh
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endif

</div>
@endsection

