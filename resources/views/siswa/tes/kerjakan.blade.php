@extends('layouts.app')

@section('title', 'Ruang Asesmen: ' . $tes->judul)

@push('styles')
<style>
    .hero-sky-cbt {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 60%, #075985 100%);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 24px;
        box-shadow: 0 10px 25px -5px rgba(2, 132, 199, 0.25);
        position: relative;
        overflow: hidden;
    }
    .card-sky-soal {
        background: #ffffff;
        border: 1px solid #e0f2fe;
        border-radius: 24px;
        box-shadow: 0 4px 20px -3px rgba(2, 132, 199, 0.05);
    }
    .btn-sky-action {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%) !important;
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.85rem;
        padding: 0.65rem 1.4rem;
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 14px rgba(2, 132, 199, 0.3);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }
    .btn-sky-action:hover {
        background: linear-gradient(135deg, #0369a1 0%, #075985 100%) !important;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(2, 132, 199, 0.4);
    }
</style>
@endpush

@section('content')
<div class="space-y-5" id="cbtApp">

    @if(session('error'))
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold flex items-center gap-2.5 shadow-xs">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#e11d48;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Top Bar: Header & Countdown Timer (Sky Cloud Soft Gradient) -->
    <div class="hero-sky-cbt text-white p-5 md:p-6 shadow-md flex flex-col md:flex-row items-center justify-between gap-4">
        
        <!-- Ambient Decorative Cloud Circles -->
        <div style="position: absolute; right: -30px; top: -30px; width: 160px; height: 160px; border-radius: 50%; background: rgba(255, 255, 255, 0.12); pointer-events: none; filter: blur(20px);"></div>
        <div style="position: absolute; left: -20px; bottom: -20px; width: 140px; height: 140px; border-radius: 50%; background: rgba(186, 230, 253, 0.15); pointer-events: none; filter: blur(18px);"></div>

        <div class="flex items-center gap-3 w-full md:w-auto justify-between md:justify-start relative z-10">
            <div>
                <span class="text-[10px] font-bold tracking-wider text-sky-100 uppercase block">Ruang Asesmen Siswa &bull; {{ $tes->jenis_psikometrik }}</span>
                <h1 class="text-base md:text-xl font-black text-white leading-tight truncate max-w-md">{{ $tes->judul }}</h1>
                <span class="text-[11px] text-sky-100 mt-0.5 block">
                    Guru Pembimbing: {{ $tes->guruBk->nama_guru ?? 'Guru BK' }} &bull; Target: {{ $tes->kelas->nama_kelas ?? 'Umum' }}
                </span>
            </div>

            <!-- Auto-save notification pill (Desktop) -->
            <div id="saveIndicator" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold bg-white/15 backdrop-blur-xs border border-white/20 text-white transition-all">
                <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span id="saveText">Tersimpan</span>
            </div>
        </div>

        <!-- Countdown Timer & Tombol Selesai Topbar -->
        <div class="flex items-center gap-3 w-full md:w-auto justify-between md:justify-end border-t md:border-t-0 pt-3 md:pt-0 border-white/15 relative z-10">
            <div class="text-right">
                <span class="block text-[10px] font-medium text-sky-100 uppercase tracking-wider">Sisa Waktu Pengerjaan</span>
                <div class="flex items-center gap-1.5 font-mono text-xl md:text-2xl font-black text-white tracking-wide" id="timerDisplay">
                    <span id="hours">00</span>:<span id="minutes">00</span>:<span id="seconds">00</span>
                </div>
            </div>

            <button
                type="button"
                onclick="openSubmitModal()"
                class="inline-flex items-center gap-1.5 bg-white hover:bg-sky-50 text-sky-900 text-xs font-bold py-2.5 px-4 rounded-xl shadow-xs transition-colors cursor-pointer border border-white/60"
            >
                <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>Kumpulkan Asesmen</span>
            </button>
        </div>
    </div>

    <!-- Main Exam Container: 2 Columns -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- Kolom Kiri: Area Soal & Pilihan Jawaban (8 Kolom) -->
        <div class="lg:col-span-8 card-sky-soal p-6 sm:p-7 space-y-6">
            @foreach($soals as $index => $soal)
                @php
                    $jawaban = $jawabanMap->get($soal->id_soal);
                    $sudahJawab = ($jawaban && !is_null($jawaban->id_pilihan));
                    $isRagu = (bool) ($jawaban->is_ragu_ragu ?? false);
                @endphp

                <div 
                    id="soalCard_{{ $index }}" 
                    class="soal-container space-y-6 {{ $index === 0 ? '' : 'hidden' }}"
                    data-soal-id="{{ $soal->id_soal }}"
                    data-soal-index="{{ $index }}"
                    data-tipe="PG"
                >
                    <!-- Header Soal -->
                    <div class="flex flex-wrap items-center justify-between gap-2 pb-4 border-b border-sky-100">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-black bg-sky-600 text-white shadow-xs">
                                Soal No. {{ $index + 1 }}
                            </span>
                            @if($soal->dimensi)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-[11px] font-bold bg-sky-50 text-sky-700 border border-sky-200">
                                    Dimensi: {{ $soal->dimensi }}
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2 text-xs text-slate-500">
                            <span class="font-medium text-slate-600">Bobot: <strong class="text-sky-800">{{ $soal->bobot_nilai }}</strong> poin</span>
                        </div>
                    </div>

                    <!-- Teks Pertanyaan -->
                    <div class="text-slate-900 text-sm md:text-base leading-relaxed font-semibold">
                        {!! nl2br(e($soal->clean_pertanyaan ?? $soal->pertanyaan)) !!}
                    </div>

                    <!-- Pilihan Jawaban PG Skala / Pilihan -->
                    <div class="space-y-3 pt-2">
                        @foreach($soal->pilihanJawabans as $pIndex => $pilihan)
                            @php
                                $huruf = chr(65 + $pIndex);
                                $isSelected = ($jawaban && $jawaban->id_pilihan == $pilihan->id_pilihan);
                            @endphp
                            <label 
                                class="option-card flex items-start gap-3.5 p-3.5 rounded-xl border transition-all cursor-pointer select-none {{ $isSelected ? 'border-sky-500 bg-sky-50/80 shadow-xs' : 'border-slate-200 bg-white hover:border-sky-300 hover:bg-sky-50/30' }}"
                                onclick="selectPgOption({{ $index }}, {{ $soal->id_soal }}, {{ $pilihan->id_pilihan }}, this)"
                            >
                                <input 
                                    type="radio" 
                                    name="pg_{{ $soal->id_soal }}" 
                                    value="{{ $pilihan->id_pilihan }}" 
                                    class="sr-only"
                                    {{ $isSelected ? 'checked' : '' }}
                                >
                                <span class="option-badge flex items-center justify-center h-6 w-6 rounded-lg text-xs font-bold shrink-0 transition-colors {{ $isSelected ? 'bg-sky-600 text-white shadow-xs' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $huruf }}
                                </span>
                                <span class="text-xs md:text-sm text-slate-800 pt-0.5 leading-normal font-medium">
                                    {{ $pilihan->teks_pilihan }}
                                </span>
                            </label>
                        @endforeach
                    </div>

                    <!-- Kontrol Navigasi Soal Bawah -->
                    <div class="pt-6 border-t border-slate-100 flex items-center justify-between gap-3">
                        <button 
                            type="button" 
                            onclick="prevQuestion({{ $index }})" 
                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 py-2.5 px-4 rounded-xl transition-colors cursor-pointer {{ $index === 0 ? 'opacity-40 cursor-not-allowed pointer-events-none' : '' }}"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            <span>Sebelumnya</span>
                        </button>

                        <!-- Tombol Ragu-ragu -->
                        <button 
                            type="button" 
                            onclick="toggleRaguRagu({{ $index }}, {{ $soal->id_soal }})"
                            id="btnRagu_{{ $index }}"
                            class="inline-flex items-center gap-1.5 text-xs font-bold py-2.5 px-4 rounded-xl border transition-colors cursor-pointer {{ $isRagu ? 'bg-amber-100 border-amber-300 text-amber-900 shadow-xs' : 'bg-white border-slate-300 text-slate-600 hover:bg-slate-50' }}"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" class="{{ $isRagu ? 'text-amber-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
                            </svg>
                            <span id="textRagu_{{ $index }}">{{ $isRagu ? 'Ragu-ragu (Aktif)' : 'Ragu-ragu' }}</span>
                        </button>

                        @if($index < count($soals) - 1)
                            <button 
                                type="button" 
                                onclick="nextQuestion({{ $index }})" 
                                class="btn-sky-action"
                            >
                                <span>Selanjutnya</span>
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        @else
                            <button 
                                type="button" 
                                onclick="openSubmitModal()" 
                                class="btn-sky-action"
                            >
                                <span>Selesaikan Asesmen</span>
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Kolom Kanan: Palet Navigasi Nomor Soal (4 Kolom) -->
        <div class="lg:col-span-4 card-sky-soal p-5 space-y-5">
            <div class="flex items-center justify-between border-b border-sky-100 pb-3">
                <h3 class="text-xs font-bold text-sky-900 uppercase tracking-wider">Daftar Nomor Soal</h3>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-sky-800 font-mono bg-sky-50 border border-sky-100 px-2 py-0.5 rounded-md" id="sidebarTimer">00:00:00</span>
                    <span class="text-xs font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded-md" id="answeredCount">
                        0 / {{ count($soals) }}
                    </span>
                </div>
            </div>

            <!-- Petunjuk Indikator Status Warna -->
            <div class="grid grid-cols-3 gap-2 text-[10px] text-slate-500 pb-1">
                <div class="flex items-center gap-1.5">
                    <span class="h-3 w-3 rounded-md bg-emerald-500 shrink-0"></span>
                    <span>Terjawab</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="h-3 w-3 rounded-md bg-amber-400 shrink-0"></span>
                    <span>Ragu-ragu</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="h-3 w-3 rounded-md bg-slate-200 shrink-0"></span>
                    <span>Belum Diisi</span>
                </div>
            </div>

            <!-- Grid Tombol Nomor Soal -->
            <div class="grid grid-cols-5 gap-2" id="palletGrid">
                @foreach($soals as $index => $soal)
                    @php
                        $jawaban = $jawabanMap->get($soal->id_soal);
                        $isTerjawab = ($jawaban && !is_null($jawaban->id_pilihan));
                        $isRagu = (bool) ($jawaban->is_ragu_ragu ?? false);
                    @endphp

                    <button
                        type="button"
                        id="palletBtn_{{ $index }}"
                        onclick="jumpToQuestion({{ $index }})"
                        data-answered="{{ $isTerjawab ? '1' : '0' }}"
                        data-ragu="{{ $isRagu ? '1' : '0' }}"
                        class="pallet-btn relative h-10 w-full rounded-xl text-xs font-bold transition-all cursor-pointer flex items-center justify-center
                            {{ $isRagu ? 'bg-amber-400 text-slate-900 shadow-xs' : ($isTerjawab ? 'bg-emerald-500 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200') }}
                            {{ $index === 0 ? 'ring-2 ring-sky-500 ring-offset-2' : '' }}
                        "
                    >
                        {{ $index + 1 }}
                    </button>
                @endforeach
            </div>

            <!-- Catatan Aturan Pengumpulan -->
            <div class="p-3 rounded-xl bg-sky-50 border border-sky-100 text-sky-800 text-[11px] leading-relaxed">
                <strong>Ketentuan Pengumpulan:</strong> Semua butir soal wajib terisi dan tidak ada nomor yang bertanda kuning (ragu-ragu) saat mengumpulkan.
            </div>
        </div>

    </div>

    <!-- Modal Konfirmasi Selesai Ujian / Pengumpulan Asesmen -->
    <div id="submitModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-sky-100 space-y-5 animate-scale-in">
            <div class="text-center space-y-1.5">
                <div class="h-12 w-12 rounded-2xl bg-sky-100 text-sky-600 mx-auto flex items-center justify-center mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-base font-extrabold text-slate-900">Konfirmasi Pengumpulan Asesmen</h3>
                <p class="text-xs text-slate-500">Periksa ringkasan jawaban Anda sebelum mengakhiri sesi pengerjaan.</p>
            </div>

            <!-- Ringkasan Status Jawaban -->
            <div class="grid grid-cols-3 gap-2 text-center p-3.5 bg-slate-50 rounded-2xl border border-slate-200">
                <div class="bg-white p-2.5 rounded-xl border border-slate-200">
                    <span class="block text-[10px] text-slate-500 font-bold uppercase">Terjawab</span>
                    <span class="text-lg font-black text-slate-900 mt-0.5 block" id="modalAnswered">0</span>
                </div>
                <div class="bg-white p-2.5 rounded-xl border border-slate-200" id="boxModalRagu">
                    <span class="block text-[10px] text-amber-600 font-bold uppercase">Ragu-ragu</span>
                    <span class="text-lg font-black text-amber-600 mt-0.5 block" id="modalRagu">0</span>
                </div>
                <div class="bg-white p-2.5 rounded-xl border border-slate-200" id="boxModalUnanswered">
                    <span class="block text-[10px] text-rose-600 font-bold uppercase">Belum Diisi</span>
                    <span class="text-lg font-black text-rose-600 mt-0.5 block" id="modalUnanswered">0</span>
                </div>
            </div>

            <!-- Alert Box Ketika Belum Bisa Dikumpulkan (Ada yang kosong atau ragu-ragu) -->
            <div id="incompleteWarningBox" class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs space-y-2 hidden">
                <div class="flex items-center gap-1.5 font-bold text-rose-950">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;color:#e11d48;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>Asesmen Belum Dapat Dikumpulkan</span>
                </div>
                <p class="text-[11px] leading-relaxed text-rose-800" id="incompleteMessageText">
                    Seluruh butir soal harus terjawab dan tidak boleh ada soal yang masih ditandai ragu-ragu.
                </p>
                <!-- Daftar Tombol Pintas ke Nomor Soal yang Belum Selesai -->
                <div class="pt-1">
                    <span class="text-[10px] font-bold text-slate-600 block mb-1">Klik nomor soal untuk melengkapi:</span>
                    <div class="flex flex-wrap gap-1.5 max-h-28 overflow-y-auto" id="pendingQuestionsChips">
                        <!-- Diisi secara dinamis via JS -->
                    </div>
                </div>
            </div>

            <!-- Alert Box Ketika SUDAH LENGKAP & YAKIN SEMUA -->
            <div id="completeSuccessBox" class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs space-y-1 hidden">
                <div class="flex items-center gap-1.5 font-bold text-emerald-950">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;color:#059669;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Semua Soal Lengkap &amp; Siap Dikumpulkan!</span>
                </div>
                <p class="text-[11px] leading-relaxed text-emerald-800">
                    Seluruh butir soal telah terjawab tuntas tanpa tanda ragu-ragu. Anda dapat mengumpulkan lembar asesmen sekarang.
                </p>
            </div>

            <form action="{{ route('siswa.tes.selesai', $tes->id_tes) }}" method="POST" id="formFinalSelesai" class="flex items-center gap-2 pt-2">
                @csrf
                <input type="hidden" name="force_timeout" id="inputForceTimeout" value="0">
                <button
                    type="button"
                    onclick="closeSubmitModal()"
                    class="w-1/2 py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors cursor-pointer"
                >
                    Tinjau Kembali
                </button>
                <button
                    type="submit"
                    id="btnFinalSubmit"
                    class="w-1/2 py-2.5 px-4 btn-sky-action justify-center"
                >
                    Ya, Kumpulkan
                </button>
            </form>
        </div>
    </div>

</div>

<!-- Script Pengerjaan CBT Siswa -->
<script>
const TOTAL_SOAL = {{ count($soals) }};
let currentIndex = 0;
let remainingSeconds = {{ $sisaDetik }};
const csrfToken = '{{ csrf_token() }}';
const saveUrl = '{{ route('siswa.tes.simpanJawaban', $tes->id_tes) }}';

// 1. Inisialisasi Timer Hitung Mundur
function startTimer() {
    updateTimerDisplay(remainingSeconds);
    const interval = setInterval(function() {
        remainingSeconds--;
        if (remainingSeconds <= 0) {
            clearInterval(interval);
            alert('Waktu ujian telah berakhir! Lembar jawaban Anda akan otomatis dikumpulkan.');
            const forceInput = document.getElementById('inputForceTimeout');
            if (forceInput) forceInput.value = '1';
            const form = document.getElementById('formFinalSelesai');
            if (form) form.submit();
        } else {
            updateTimerDisplay(remainingSeconds);
            // Visual alert jika sisa waktu < 5 menit (300 detik)
            if (remainingSeconds <= 300) {
                const timerEl = document.getElementById('timerDisplay');
                if (timerEl) {
                    timerEl.classList.remove('text-white');
                    timerEl.classList.add('text-amber-300');
                }
            }
        }
    }, 1000);
}

function updateTimerDisplay(totalSeconds) {
    const hours = Math.floor(totalSeconds / 3600);
    const minutes = Math.floor((totalSeconds % 3600) / 60);
    const seconds = totalSeconds % 60;

    const hStr = String(hours).padStart(2, '0');
    const mStr = String(minutes).padStart(2, '0');
    const sStr = String(seconds).padStart(2, '0');

    const hEl = document.getElementById('hours');
    const mEl = document.getElementById('minutes');
    const sEl = document.getElementById('seconds');
    if (hEl && mEl && sEl) {
        hEl.innerText = hStr;
        mEl.innerText = mStr;
        sEl.innerText = sStr;
    }

    const sbTimer = document.getElementById('sidebarTimer');
    if (sbTimer) {
        sbTimer.innerText = `${hStr}:${mStr}:${sStr}`;
    }
}

// 2. Navigasi Soal (Pindah Soal)
function showQuestion(index) {
    document.querySelectorAll('.soal-container').forEach(el => el.classList.add('hidden'));
    const target = document.getElementById('soalCard_' + index);
    if (target) {
        target.classList.remove('hidden');
        currentIndex = index;
    }

    // Highlight nomor aktif di palet
    document.querySelectorAll('.pallet-btn').forEach((btn, idx) => {
        if (idx === index) {
            btn.classList.add('ring-2', 'ring-sky-500', 'ring-offset-2');
        } else {
            btn.classList.remove('ring-2', 'ring-sky-500', 'ring-offset-2');
        }
    });

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function nextQuestion(index) {
    if (index < TOTAL_SOAL - 1) {
        showQuestion(index + 1);
    }
}

function prevQuestion(index) {
    if (index > 0) {
        showQuestion(index - 1);
    }
}

function jumpToQuestion(index) {
    showQuestion(index);
}

// 3. Pemilihan Opsi PG & Auto-Save
function selectPgOption(soalIndex, soalId, pilihanId, labelEl) {
    const container = document.getElementById('soalCard_' + soalIndex);

    // Styling kartu opsi
    container.querySelectorAll('.option-card').forEach(card => {
        card.classList.remove('border-sky-500', 'bg-sky-50/80', 'shadow-xs');
        card.classList.add('border-slate-200', 'bg-white');
        const badge = card.querySelector('.option-badge');
        if (badge) {
            badge.classList.remove('bg-sky-600', 'text-white', 'shadow-xs');
            badge.classList.add('bg-slate-100', 'text-slate-700');
        }
    });

    labelEl.classList.remove('border-slate-200', 'bg-white');
    labelEl.classList.add('border-sky-500', 'bg-sky-50/80', 'shadow-xs');
    const badge = labelEl.querySelector('.option-badge');
    if (badge) {
        badge.classList.remove('bg-slate-100', 'text-slate-700');
        badge.classList.add('bg-sky-600', 'text-white', 'shadow-xs');
    }

    // Ambil status ragu-ragu saat ini
    const palletBtn = document.getElementById('palletBtn_' + soalIndex);
    const isRagu = palletBtn ? (palletBtn.getAttribute('data-ragu') === '1') : false;

    // Kirim AJAX save
    saveToServer(soalId, pilihanId, null, isRagu, function(data) {
        updatePalletButton(soalIndex, true, isRagu);
    });
}

// 4. Toggle Ragu-ragu
function toggleRaguRagu(soalIndex, soalId) {
    const palletBtn = document.getElementById('palletBtn_' + soalIndex);
    const currentlyRagu = palletBtn ? (palletBtn.getAttribute('data-ragu') === '1') : false;
    const isNowRagu = !currentlyRagu;

    const container = document.getElementById('soalCard_' + soalIndex);
    let pilihanId = null;

    const checked = container.querySelector('input[type="radio"]:checked');
    if (checked) pilihanId = checked.value;

    const isAnswered = palletBtn ? (palletBtn.getAttribute('data-answered') === '1') : false;

    // Update UI tombol ragu-ragu
    const btn = document.getElementById('btnRagu_' + soalIndex);
    const textEl = document.getElementById('textRagu_' + soalIndex);
    if (isNowRagu) {
        btn.classList.remove('bg-white', 'border-slate-300', 'text-slate-600');
        btn.classList.add('bg-amber-100', 'border-amber-300', 'text-amber-900', 'shadow-xs');
        textEl.innerText = 'Ragu-ragu (Aktif)';
    } else {
        btn.classList.remove('bg-amber-100', 'border-amber-300', 'text-amber-900', 'shadow-xs');
        btn.classList.add('bg-white', 'border-slate-300', 'text-slate-600');
        textEl.innerText = 'Ragu-ragu';
    }

    saveToServer(soalId, pilihanId, null, isNowRagu, function() {
        updatePalletButton(soalIndex, isAnswered, isNowRagu);
    });
}

// 5. Helper: Kirim Data Jawaban ke Server (AJAX)
function saveToServer(soalId, pilihanId, esaiText, isRagu, callback) {
    showSavingIndicator();

    fetch(saveUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            id_soal: soalId,
            id_pilihan: pilihanId,
            jawaban_esai: null,
            is_ragu_ragu: isRagu ? 1 : 0,
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.expired) {
            window.location.href = data.redirect;
            return;
        }
        showSavedIndicator();
        if (callback) callback(data);
        updateProgressSummary();
    })
    .catch(err => {
        console.error('Save error:', err);
        showErrorIndicator();
    });
}

// 6. Update Warna Palet Button
function updatePalletButton(soalIndex, isAnswered, isRagu) {
    const btn = document.getElementById('palletBtn_' + soalIndex);
    if (!btn) return;

    btn.setAttribute('data-answered', isAnswered ? '1' : '0');
    btn.setAttribute('data-ragu', isRagu ? '1' : '0');

    btn.className = 'pallet-btn relative h-10 w-full rounded-xl text-xs font-bold transition-all cursor-pointer flex items-center justify-center';

    if (isRagu) {
        btn.classList.add('bg-amber-400', 'text-slate-900', 'shadow-xs');
    } else if (isAnswered) {
        btn.classList.add('bg-emerald-500', 'text-white', 'shadow-xs');
    } else {
        btn.classList.add('bg-slate-100', 'text-slate-700', 'hover:bg-slate-200');
    }

    if (currentIndex === soalIndex) {
        btn.classList.add('ring-2', 'ring-sky-500', 'ring-offset-2');
    }

    updateProgressSummary();
}

function updateProgressSummary() {
    let answered = 0;
    document.querySelectorAll('.pallet-btn').forEach(btn => {
        if (btn.getAttribute('data-answered') === '1') {
            answered++;
        }
    });
    const el = document.getElementById('answeredCount');
    if (el) el.innerText = `${answered} / ${TOTAL_SOAL}`;
}

// 7. Visual Indicator Auto-Save
function showSavingIndicator() {
    const el = document.getElementById('saveIndicator');
    const txt = document.getElementById('saveText');
    if (el && txt) {
        el.className = 'hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold bg-white/20 backdrop-blur-xs border border-white/30 text-white';
        txt.innerText = 'Menyimpan...';
    }
}

function showSavedIndicator() {
    const el = document.getElementById('saveIndicator');
    const txt = document.getElementById('saveText');
    if (el && txt) {
        el.className = 'hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold bg-white/15 backdrop-blur-xs border border-white/20 text-white';
        txt.innerText = 'Tersimpan';
    }
}

function showErrorIndicator() {
    const el = document.getElementById('saveIndicator');
    const txt = document.getElementById('saveText');
    if (el && txt) {
        el.className = 'hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold bg-rose-500/80 backdrop-blur-xs border border-rose-300 text-white';
        txt.innerText = 'Gagal simpan (Cek koneksi)';
    }
}

// 8. Modal Submit Final (Validasi Keras Tidak Boleh Ada Soal Kosong atau Ragu-ragu)
function openSubmitModal() {
    let answered = 0;
    let ragu = 0;
    let pendingItems = [];

    document.querySelectorAll('.pallet-btn').forEach((btn, idx) => {
        const isAns = (btn.getAttribute('data-answered') === '1');
        const isRg = (btn.getAttribute('data-ragu') === '1');

        if (isAns) {
            answered++;
        }
        if (isRg) {
            ragu++;
            pendingItems.push({ index: idx, nomor: idx + 1, type: 'ragu' });
        } else if (!isAns) {
            pendingItems.push({ index: idx, nomor: idx + 1, type: 'kosong' });
        }
    });

    const unanswered = TOTAL_SOAL - answered;

    document.getElementById('modalAnswered').innerText = answered;
    document.getElementById('modalRagu').innerText = ragu;
    document.getElementById('modalUnanswered').innerText = unanswered;

    const cannotSubmit = (unanswered > 0 || ragu > 0);
    const warnBox = document.getElementById('incompleteWarningBox');
    const successBox = document.getElementById('completeSuccessBox');
    const submitBtn = document.getElementById('btnFinalSubmit');
    const chipsContainer = document.getElementById('pendingQuestionsChips');
    const messageText = document.getElementById('incompleteMessageText');

    if (cannotSubmit) {
        warnBox.classList.remove('hidden');
        successBox.classList.add('hidden');

        submitBtn.disabled = true;
        submitBtn.className = 'w-1/2 py-2.5 px-4 bg-slate-200 text-slate-400 text-xs font-bold rounded-xl cursor-not-allowed shadow-none justify-center';
        submitBtn.innerText = 'Belum Bisa Dikumpulkan';

        let msgParts = [];
        if (unanswered > 0) msgParts.push(`<strong>${unanswered} butir soal belum diisi</strong>`);
        if (ragu > 0) msgParts.push(`<strong>${ragu} butir soal masih ditandai ragu-ragu</strong>`);
        messageText.innerHTML = `Asesmen tidak dapat dikumpulkan karena masih ada ${msgParts.join(' dan ')}. Harap selesaikan seluruh butir soal dan hilangkan tanda centang ragu-ragu.`;

        // Render tombol pintas ke butir soal yang belum tuntas
        chipsContainer.innerHTML = '';
        pendingItems.forEach(item => {
            const chip = document.createElement('button');
            chip.type = 'button';
            chip.className = item.type === 'ragu' 
                ? 'px-2.5 py-1 rounded-lg text-[11px] font-bold bg-amber-100 text-amber-900 border border-amber-300 hover:bg-amber-200 cursor-pointer transition-colors'
                : 'px-2.5 py-1 rounded-lg text-[11px] font-bold bg-rose-100 text-rose-900 border border-rose-300 hover:bg-rose-200 cursor-pointer transition-colors';
            chip.innerText = (item.type === 'ragu') ? `Soal #${item.nomor} (Ragu)` : `Soal #${item.nomor} (Kosong)`;
            chip.onclick = function() {
                closeSubmitModal();
                showQuestion(item.index);
            };
            chipsContainer.appendChild(chip);
        });

    } else {
        warnBox.classList.add('hidden');
        successBox.classList.remove('hidden');

        submitBtn.disabled = false;
        submitBtn.className = 'w-1/2 py-2.5 px-4 btn-sky-action justify-center';
        submitBtn.innerText = 'Ya, Kumpulkan';
    }

    document.getElementById('submitModal').classList.remove('hidden');
}

function closeSubmitModal() {
    document.getElementById('submitModal').classList.add('hidden');
}

// Jalankan saat dokumen siap
document.addEventListener('DOMContentLoaded', function() {
    startTimer();
    updateProgressSummary();
});
</script>
@endsection
