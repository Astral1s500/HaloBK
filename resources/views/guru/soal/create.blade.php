@extends('layouts.app')

@section('title', 'Kelola Soal per Dimensi - ' . $tes->judul)

@push('styles')
<style>
    .btn-sky-action {
        background-color: #0284c7 !important;
        color: #ffffff !important;
        font-weight: 700;
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
        box-shadow: 0 6px 18px rgba(2, 132, 199, 0.35);
    }
    .btn-secondary-action {
        background: #ffffff;
        color: #0284c7 !important;
        font-weight: 600;
        font-size: 0.8rem;
        padding: 0.6rem 1.1rem;
        border-radius: 12px;
        border: 1.5px solid #bae6fd;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-secondary-action:hover {
        background: #f0f9ff;
        border-color: #0284c7;
    }
    .form-card-sky {
        background: #ffffff;
        border: 1px solid #e0f2fe;
        border-radius: 24px;
        box-shadow: 0 6px 25px -4px rgba(2, 132, 199, 0.06);
    }
    .tab-dimensi-btn {
        padding: 0.75rem 1.25rem;
        border-radius: 16px;
        font-weight: 700;
        font-size: 0.825rem;
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        border: 2px solid transparent;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #f8fafc;
        color: #475569;
        white-space: nowrap;
    }
    .tab-dimensi-btn:hover {
        background: #f0f9ff;
        color: #0284c7;
    }
    .tab-dimensi-btn.active {
        background: #ffffff;
        color: #0284c7;
        border-color: #0284c7;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.12);
    }
    .statement-row {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 18px;
        padding: 1rem 1.15rem;
        transition: all 0.2s ease;
    }
    .statement-row:focus-within {
        background: #ffffff;
        border-color: #0284c7;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.1);
    }
</style>
@endpush

@section('content')
<div class="space-y-6 max-w-5xl mx-auto pb-16">

    <!-- Header & Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('guru.dashboard') }}" class="hover:text-sky-600 transition-colors">Dashboard</a>
                <span>&rsaquo;</span>
                <a href="{{ route('guru.tes.index') }}" class="hover:text-sky-600 transition-colors">Asessment</a>
                <span>&rsaquo;</span>
                <a href="{{ route('guru.tes.show', $tes->id_tes) }}" class="hover:text-sky-600 transition-colors">{{ $tes->judul }}</a>
                <span>&rsaquo;</span>
                <span class="text-sky-700 font-semibold">Kelola Soal per Dimensi</span>
            </div>
            <div class="flex items-center gap-2.5 flex-wrap">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Kelola Soal per Dimensi</h1>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-sky-50 text-sky-700 border border-sky-200">
                    {{ $tipe }}
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-0.5">
                Pilih salah satu dimensi untuk membuat beberapa butir pernyataan sekaligus tanpa harus memilih dimensi berulang-ulang.
            </p>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <a
                href="{{ route('guru.tes.show', $tes->id_tes) }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-200 px-4 py-2.5 rounded-xl transition-all shadow-xs cursor-pointer"
            >
                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Selesai &amp; Kembali ke Asesmen</span>
            </a>
        </div>
    </div>

    <!-- Alert Sukses / Validasi -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2.5 shadow-2xs">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:#059669;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium space-y-1">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- 1. LIVE BAR KESEIMBANGAN DIMENSI                                          -->
    <!-- Memastikan guru dapat melihat sebaran soal secara langsung                -->
    <!-- ========================================================================= -->
    <div class="form-card-sky p-5 space-y-3">
        <div class="flex items-center justify-between flex-wrap gap-2">
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-slate-900 uppercase tracking-wider">Status Keseimbangan Dimensi:</span>
                @if($keseimbangan['is_seimbang'])
                    <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Seimbang (Masing-masing {{ $keseimbangan['min_count'] }} soal)
                    </span>
                @elseif($keseimbangan['is_kosong'])
                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                        Belum Ada Soal
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        Perlu Penyeimbangan
                    </span>
                @endif
            </div>

            <span class="text-xs font-bold text-sky-700 bg-sky-50 px-3 py-1 rounded-xl border border-sky-200">
                Total: {{ $tes->soals->count() }} Butir Soal Terpasang
            </span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2 pt-1">
            @foreach($dimensiList as $dimKey => $info)
                @php
                    $cnt = $keseimbangan['counts'][$dimKey] ?? 0;
                    $target = $keseimbangan['target_per_dimensi'];
                    $isOk = ($cnt > 0 && $cnt === $target);
                @endphp
                <div class="p-2.5 rounded-xl border {{ $isOk ? 'bg-emerald-50/60 border-emerald-200' : ($cnt > 0 ? 'bg-amber-50/60 border-amber-200' : 'bg-slate-50 border-slate-200') }} flex items-center justify-between text-xs">
                    <span class="font-bold text-slate-700 truncate pr-1">{{ $info['nama'] }}</span>
                    <span class="font-extrabold {{ $isOk ? 'text-emerald-700' : ($cnt > 0 ? 'text-amber-700' : 'text-slate-400') }}">
                        {{ $cnt }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 2. TAB NAVIGASI DIMENSI (PILIH 1 KALI, BUAT BANYAK SOAL)                  -->
    <!-- ========================================================================= -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none" id="dimensiTabBar">
        @foreach($dimensiList as $dimKey => $info)
            @php
                $cnt = count($soalsByDimensi[$dimKey] ?? []);
                $isActive = ($dimKey === $activeDimensi);
            @endphp
            <button 
                type="button" 
                onclick="switchDimensiTab('{{ $dimKey }}')"
                class="tab-dimensi-btn {{ $isActive ? 'active' : '' }}"
                id="tabBtn_{{ $dimKey }}"
            >
                <span>{{ $info['nama'] }}</span>
                <span class="px-2 py-0.5 rounded-full text-xs font-black {{ $isActive ? 'bg-sky-100 text-sky-800' : 'bg-slate-200 text-slate-700' }}" id="badgeCount_{{ $dimKey }}">
                    {{ $cnt }}
                </span>
            </button>
        @endforeach
    </div>

    <!-- ========================================================================= -->
    <!-- 3. KONTEN TAB DIMENSI (PANEL MASING-MASING DIMENSI)                       -->
    <!-- ========================================================================= -->
    @foreach($dimensiList as $dimKey => $info)
        @php
            $cntExisting = count($soalsByDimensi[$dimKey] ?? []);
            $isPanelActive = ($dimKey === $activeDimensi);
        @endphp
        <div 
            id="panelDimensi_{{ $dimKey }}" 
            class="space-y-6 {{ $isPanelActive ? '' : 'hidden' }}"
        >
            
            <!-- Box Deskripsi Dimensi Terpilih -->
            <div class="p-4 sm:p-5 rounded-2xl bg-sky-50/70 border border-sky-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-sky-800 uppercase tracking-wider">Fokus Pengukuran:</span>
                        <h3 class="text-sm font-extrabold text-slate-900">{{ $info['nama'] }}</h3>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">{{ $info['deskripsi'] }}</p>
                    <p class="text-[11px] text-sky-700 font-medium pt-0.5">Karakteristik siswa: {{ $info['karakter'] ?? '' }}</p>
                </div>
                <div class="text-right shrink-0">
                    <span class="text-xs font-bold text-slate-500 block">Total Soal Dimensi Ini:</span>
                    <span class="text-xl font-black text-sky-900 block" id="displayCount_{{ $dimKey }}">{{ $cntExisting }} Butir</span>
                </div>
            </div>

            <!-- Bagian A: Butir Soal yang SUDAH TERSIMPAN pada Dimensi Ini -->
            @if($cntExisting > 0)
                <div class="form-card-sky p-5 sm:p-6 space-y-3">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;color:#059669;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Soal yang Sudah Ada di Dimensi Ini ({{ $cntExisting }})</span>
                        </h3>
                    </div>

                    <div class="space-y-2.5">
                        @foreach($soalsByDimensi[$dimKey] as $sIdx => $sItem)
                            <div class="p-3.5 rounded-xl border border-slate-200 bg-white hover:border-sky-300 transition-colors flex items-start justify-between gap-3 text-xs">
                                <div class="flex items-start gap-2.5 flex-1">
                                    <span class="w-6 h-6 rounded-lg bg-sky-100 text-sky-800 font-extrabold text-[11px] flex items-center justify-center shrink-0 mt-0.5">
                                        {{ $sIdx + 1 }}
                                    </span>
                                    <div class="space-y-1">
                                        <p class="font-medium text-slate-800 leading-relaxed">{{ $sItem->clean_pertanyaan }}</p>
                                        <span class="text-[10px] text-slate-400 font-medium block">Skala Likert (5 Opsi Jawaban) &bull; Bobot: 5 poin</span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-1.5 shrink-0">
                                    <a 
                                        href="{{ route('guru.soal.edit', $sItem->id_soal) }}"
                                        class="px-2.5 py-1 rounded-lg text-xs font-semibold text-sky-700 bg-sky-50 hover:bg-sky-100 transition-colors"
                                    >
                                        Edit
                                    </a>
                                    <form action="{{ route('guru.soal.destroy', $sItem->id_soal) }}" method="POST" class="inline form-delete" data-confirm-title="Hapus Butir Soal" data-confirm-message="Hapus butir pernyataan nomor {{ $sIdx + 1 }} ini?">
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit" 
                                            class="px-2.5 py-1 rounded-lg text-xs font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 transition-colors cursor-pointer"
                                        >
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Bagian B: FORM BATCH INPUT BEBERAPA PERNYATAAN SEKALIGUS -->
            <div class="form-card-sky p-6 sm:p-7 space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 tracking-tight flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:17px;height:17px;color:#0284c7;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Tambah Butir Pernyataan Baru (Dimensi: {{ $info['nama'] }})</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Ketik satu atau beberapa butir pernyataan sekaligus. Skala jawaban Likert <strong>otomatis terpasang</strong>.
                        </p>
                    </div>

                    <button 
                        type="button" 
                        onclick="tambahBarisPernyataan('{{ $dimKey }}')"
                        class="btn-secondary-action shrink-0"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>+ Tambah Baris Pernyataan</span>
                    </button>
                </div>

                <form action="{{ route('guru.tes.soal.batchStore', $tes->id_tes) }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="dimensi" value="{{ $dimKey }}">

                    <!-- Container Baris Pernyataan Dinamis -->
                    <div id="containerRows_{{ $dimKey }}" class="space-y-3">
                        
                        <!-- Baris Pernyataan 1 (Default) -->
                        <div class="statement-row flex items-start gap-3" id="row_{{ $dimKey }}_0">
                            <span class="w-6 h-6 rounded-lg bg-sky-600 text-white font-black text-xs flex items-center justify-center shrink-0 mt-2 row-number">
                                1
                            </span>
                            <div class="flex-1">
                                <textarea 
                                    name="pernyataan[]" 
                                    rows="2" 
                                    required
                                    placeholder="Ketik pernyataan untuk dimensi {{ $info['nama'] }} (contoh: Saat belajar, saya lebih mudah memahami materi jika...)"
                                    class="w-full px-3 py-2 bg-transparent border-0 text-xs font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none resize-y leading-relaxed"
                                ></textarea>
                            </div>
                            <button 
                                type="button" 
                                onclick="hapusBaris(this, '{{ $dimKey }}')"
                                class="text-slate-400 hover:text-rose-600 p-1.5 transition-colors cursor-pointer mt-1"
                                title="Hapus baris ini"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>

                        <!-- Baris Pernyataan 2 (Default Siap Diisi) -->
                        <div class="statement-row flex items-start gap-3" id="row_{{ $dimKey }}_1">
                            <span class="w-6 h-6 rounded-lg bg-sky-600 text-white font-black text-xs flex items-center justify-center shrink-0 mt-2 row-number">
                                2
                            </span>
                            <div class="flex-1">
                                <textarea 
                                    name="pernyataan[]" 
                                    rows="2" 
                                    placeholder="Ketik butir pernyataan berikutnya untuk dimensi {{ $info['nama'] }}..."
                                    class="w-full px-3 py-2 bg-transparent border-0 text-xs font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none resize-y leading-relaxed"
                                ></textarea>
                            </div>
                            <button 
                                type="button" 
                                onclick="hapusBaris(this, '{{ $dimKey }}')"
                                class="text-slate-400 hover:text-rose-600 p-1.5 transition-colors cursor-pointer mt-1"
                                title="Hapus baris ini"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>

                    </div>

                    <!-- Info Penilaian Otomatis (Skala Likert Transparan) -->
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between flex-wrap gap-2 text-xs">
                        <div class="flex items-center gap-2 text-slate-600">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;color:#0284c7;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Format Jawaban Siswa Otomatis: <strong>Skala Likert 5 Opsi</strong> (Sangat Sesuai s/d Tidak Sesuai)</span>
                        </div>
                    </div>

                    <!-- Tombol Aksi Simpan Batch Dimensi -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
                        <button 
                            type="button" 
                            onclick="tambahBarisPernyataan('{{ $dimKey }}')"
                            class="text-xs font-semibold text-sky-600 hover:text-sky-800 transition-colors cursor-pointer"
                        >
                            + Tambah Baris Lainnya
                        </button>

                        <button 
                            type="submit" 
                            class="btn-sky-action"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Simpan Semua Butir Dimensi {{ $info['nama'] }} Ini</span>
                        </button>
                    </div>
                </form>

            </div>

        </div>
    @endforeach

</div>

<!-- Script Dynamic Tab & Dynamic Row Addition -->
<script>
function switchDimensiTab(dimKey) {
    // 1. Update Tab Buttons
    document.querySelectorAll('.tab-dimensi-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    const activeBtn = document.getElementById('tabBtn_' + dimKey);
    if (activeBtn) activeBtn.classList.add('active');

    // 2. Switch Visible Panel
    document.querySelectorAll('[id^="panelDimensi_"]').forEach(panel => {
        panel.classList.add('hidden');
    });
    const targetPanel = document.getElementById('panelDimensi_' + dimKey);
    if (targetPanel) {
        targetPanel.classList.remove('hidden');
    }
}

function tambahBarisPernyataan(dimKey) {
    const container = document.getElementById('containerRows_' + dimKey);
    if (!container) return;

    const currentCount = container.querySelectorAll('.statement-row').length;
    const nextNumber = currentCount + 1;

    const newRow = document.createElement('div');
    newRow.className = 'statement-row flex items-start gap-3';
    newRow.id = 'row_' + dimKey + '_' + currentCount;

    newRow.innerHTML = `
        <span class="w-6 h-6 rounded-lg bg-sky-600 text-white font-black text-xs flex items-center justify-center shrink-0 mt-2 row-number">
            ${nextNumber}
        </span>
        <div class="flex-1">
            <textarea 
                name="pernyataan[]" 
                rows="2" 
                placeholder="Ketik butir pernyataan berikutnya untuk dimensi ${dimKey}..."
                class="w-full px-3 py-2 bg-transparent border-0 text-xs font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none resize-y leading-relaxed"
                autofocus
            ></textarea>
        </div>
        <button 
            type="button" 
            onclick="hapusBaris(this, '${dimKey}')"
            class="text-slate-400 hover:text-rose-600 p-1.5 transition-colors cursor-pointer mt-1"
            title="Hapus baris ini"
        >
            <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </button>
    `;

    container.appendChild(newRow);
    renumberRows(dimKey);
}

function hapusBaris(btn, dimKey) {
    const container = document.getElementById('containerRows_' + dimKey);
    if (!container) return;

    const rows = container.querySelectorAll('.statement-row');
    if (rows.length <= 1) {
        alert('Minimal harus ada satu baris pernyataan.');
        return;
    }

    const row = btn.closest('.statement-row');
    if (row) {
        row.remove();
        renumberRows(dimKey);
    }
}

function renumberRows(dimKey) {
    const container = document.getElementById('containerRows_' + dimKey);
    if (!container) return;

    const rows = container.querySelectorAll('.statement-row');
    rows.forEach((r, idx) => {
        const badge = r.querySelector('.row-number');
        if (badge) badge.textContent = (idx + 1);
    });
}
</script>
@endsection
