@extends('layouts.app')

@section('title', 'Ujian CBT: ' . $tes->judul)

@section('content')
<div class="space-y-4" id="cbtApp">

    <!-- Top Bar: Header & Countdown Timer (Statis - Tidak Melayang Mengikuti Scroll) -->
    <div class="bg-slate-900 text-white rounded-2xl p-4 md:p-5 shadow-md flex flex-col md:flex-row items-center justify-between gap-4 border border-slate-800">
        <div class="flex items-center gap-3 w-full md:w-auto justify-between md:justify-start">
            <div>
                <span class="text-[10px] font-bold tracking-wider text-slate-400 uppercase block">{{ $tes->isForm() ? 'Ruang Formulir Pendataan Siswa' : 'Ruang Asesmen Online' }}</span>
                <h1 class="text-base md:text-lg font-extrabold text-white leading-tight truncate max-w-md">{{ $tes->judul }}</h1>
                <span class="text-[11px] text-slate-400">
                    Diunggah: {{ $tes->created_at ? $tes->created_at->format('d M Y, H:i') . ' WIB' : '-' }}
                </span>
            </div>

            <!-- Auto-save notification pill (Desktop) -->
            <div id="saveIndicator" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold bg-slate-800 border border-slate-700 text-slate-300 transition-all">
                <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                <span id="saveText">Tersimpan</span>
            </div>
        </div>

        <!-- Countdown Timer -->
        <div class="flex items-center gap-3 w-full md:w-auto justify-between md:justify-end border-t md:border-t-0 pt-3 md:pt-0 border-slate-800">
            <div class="text-right">
                <span class="block text-[10px] font-medium text-slate-400 uppercase tracking-wider">{{ $tes->isForm() ? 'Sisa Waktu Pengisian' : 'Sisa Waktu Ujian' }}</span>
                <div class="flex items-center gap-1.5 font-mono text-xl md:text-2xl font-black text-white" id="timerDisplay">
                    <span id="hours">00</span>:<span id="minutes">00</span>:<span id="seconds">00</span>
                </div>
            </div>

            <button
                type="button"
                onclick="openSubmitModal()"
                class="inline-flex items-center gap-1.5 bg-white hover:bg-slate-100 text-slate-900 text-xs font-bold py-2.5 px-4 rounded-xl shadow-xs transition-colors cursor-pointer border border-slate-300"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ $tes->isForm() ? 'Kirim Formulir' : 'Selesai Ujian' }}</span>
            </button>
        </div>
    </div>

    <!-- Main Exam Container: 2 Columns -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- Kolom Kiri: Area Soal & Pilihan Jawaban (8 Kolom) -->
        <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-6">
            @foreach($soals as $index => $soal)
                @php
                    $jawaban = $jawabanMap->get($soal->id_soal);
                    $sudahJawab = false;
                    if ($jawaban) {
                        if ($soal->tipe_soal === 'PG' && !is_null($jawaban->id_pilihan)) {
                            $sudahJawab = true;
                        } elseif ($soal->tipe_soal === 'Esai' && !empty(trim((string)$jawaban->jawaban_esai))) {
                            $sudahJawab = true;
                        }
                    }
                    $isRagu = (bool) ($jawaban->is_ragu_ragu ?? false);
                @endphp

                <div 
                    id="soalCard_{{ $index }}" 
                    class="soal-container space-y-6 {{ $index === 0 ? '' : 'hidden' }}"
                    data-soal-id="{{ $soal->id_soal }}"
                    data-soal-index="{{ $index }}"
                    data-tipe="{{ $soal->tipe_soal }}"
                >
                    <!-- Header Soal -->
                    <div class="flex flex-wrap items-center justify-between gap-2 pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-black bg-slate-900 text-white">
                                Soal No. {{ $index + 1 }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                {{ $soal->tipe_soal === 'PG' ? 'Pilihan Ganda' : 'Esai' }}
                            </span>
                        </div>

                        <div class="flex items-center gap-3 text-xs text-slate-500">
                            <span class="font-medium">Bobot: <strong class="text-slate-900">{{ $soal->bobot_nilai }}</strong> poin</span>
                            <span>&bull;</span>
                            <span class="text-[11px] text-slate-400">
                                Ditambahkan: {{ $soal->created_at ? $soal->created_at->format('d M Y, H:i') : '-' }}
                            </span>
                        </div>
                    </div>

                    <!-- Teks Pertanyaan -->
                    <div class="text-slate-900 text-sm md:text-base leading-relaxed font-medium">
                        {!! nl2br(e($soal->pertanyaan)) !!}
                    </div>

                    <!-- Pilihan Jawaban PG -->
                    @if($soal->tipe_soal === 'PG')
                        <div class="space-y-3 pt-2">
                            @foreach($soal->pilihanJawabans as $pIndex => $pilihan)
                                @php
                                    $huruf = chr(65 + $pIndex);
                                    $isSelected = ($jawaban && $jawaban->id_pilihan == $pilihan->id_pilihan);
                                @endphp
                                <label 
                                    class="option-card flex items-start gap-3 p-3.5 rounded-xl border transition-all cursor-pointer select-none {{ $isSelected ? 'border-slate-900 bg-slate-50 shadow-2xs' : 'border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50/50' }}"
                                    onclick="selectPgOption({{ $index }}, {{ $soal->id_soal }}, {{ $pilihan->id_pilihan }}, this)"
                                >
                                    <input 
                                        type="radio" 
                                        name="pg_{{ $soal->id_soal }}" 
                                        value="{{ $pilihan->id_pilihan }}" 
                                        class="sr-only"
                                        {{ $isSelected ? 'checked' : '' }}
                                    >
                                    <span class="option-badge flex items-center justify-center h-6 w-6 rounded-lg text-xs font-bold shrink-0 transition-colors {{ $isSelected ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700' }}">
                                        {{ $huruf }}
                                    </span>
                                    <span class="text-xs md:text-sm text-slate-800 pt-0.5 leading-normal">
                                        {{ $pilihan->teks_pilihan }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <!-- Form Jawaban Esai -->
                        <div class="space-y-2 pt-2">
                            <label class="block text-xs font-bold text-slate-700">Tuliskan Jawaban Esai Anda:</label>
                            <textarea 
                                id="esai_{{ $soal->id_soal }}" 
                                rows="6" 
                                class="w-full p-4 rounded-xl border border-slate-300 text-xs md:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all leading-relaxed"
                                placeholder="Ketikkan uraian jawaban Anda di sini..."
                                oninput="handleEsaiInput({{ $index }}, {{ $soal->id_soal }})"
                            >{{ $jawaban->jawaban_esai ?? '' }}</textarea>
                            <p class="text-[11px] text-slate-400">Jawaban esai otomatis tersimpan setiap kali Anda berhenti mengetik.</p>
                        </div>
                    @endif

                    <!-- Kontrol Navigasi Soal Bawah -->
                    <div class="pt-6 border-t border-slate-100 flex items-center justify-between gap-3">
                        <button 
                            type="button" 
                            onclick="prevQuestion({{ $index }})" 
                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 py-2.5 px-4 rounded-xl transition-colors cursor-pointer {{ $index === 0 ? 'opacity-40 cursor-not-allowed pointer-events-none' : '' }}"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            <span>Sebelumnya</span>
                        </button>

                        <!-- Tombol Ragu-ragu -->
                        <button 
                            type="button" 
                            onclick="toggleRaguRagu({{ $index }}, {{ $soal->id_soal }})"
                            id="btnRagu_{{ $index }}"
                            class="inline-flex items-center gap-1.5 text-xs font-bold py-2.5 px-4 rounded-xl border transition-colors cursor-pointer {{ $isRagu ? 'bg-amber-100 border-amber-300 text-amber-900' : 'bg-white border-slate-300 text-slate-600 hover:bg-slate-50' }}"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 {{ $isRagu ? 'text-amber-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
                            </svg>
                            <span id="textRagu_{{ $index }}">{{ $isRagu ? 'Ragu-ragu (Aktif)' : 'Ragu-ragu' }}</span>
                        </button>

                        @if($index < count($soals) - 1)
                            <button 
                                type="button" 
                                onclick="nextQuestion({{ $index }})" 
                                class="inline-flex items-center gap-1.5 text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 py-2.5 px-5 rounded-xl transition-colors cursor-pointer shadow-xs"
                            >
                                <span>Selanjutnya</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        @else
                            <button 
                                type="button" 
                                onclick="openSubmitModal()" 
                                class="inline-flex items-center gap-1.5 text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 py-2.5 px-5 rounded-xl transition-colors cursor-pointer shadow-xs"
                            >
                                <span>Selesaikan Ujian</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Kolom Kanan: Palet Navigasi Nomor Soal (4 Kolom) -->
        <div class="lg:col-span-4 bg-white rounded-2xl border border-slate-200 shadow-sm p-5 space-y-5">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Daftar Nomor Soal</h3>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-700 font-mono bg-slate-100 px-2 py-0.5 rounded-md" id="sidebarTimer">00:00:00</span>
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
                        $isTerjawab = false;
                        if ($jawaban) {
                            if ($soal->tipe_soal === 'PG' && !is_null($jawaban->id_pilihan)) {
                                $isTerjawab = true;
                            } elseif ($soal->tipe_soal === 'Esai' && !empty(trim((string)$jawaban->jawaban_esai))) {
                                $isTerjawab = true;
                            }
                        }
                        $isRagu = (bool) ($jawaban->is_ragu_ragu ?? false);
                    @endphp

                    <button
                        type="button"
                        id="palletBtn_{{ $index }}"
                        onclick="jumpToQuestion({{ $index }})"
                        data-answered="{{ $isTerjawab ? '1' : '0' }}"
                        data-ragu="{{ $isRagu ? '1' : '0' }}"
                        class="pallet-btn relative h-10 w-full rounded-xl text-xs font-bold transition-all cursor-pointer flex items-center justify-center
                            {{ $isRagu ? 'bg-amber-400 text-slate-900' : ($isTerjawab ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200') }}
                            {{ $index === 0 ? 'ring-2 ring-slate-900 ring-offset-2' : '' }}
                        "
                    >
                        {{ $index + 1 }}
                    </button>
                @endforeach
            </div>

            <!-- Tombol Submit Bawah Palet -->
            <div class="pt-3 border-t border-slate-100">
                <button
                    type="button"
                    onclick="openSubmitModal()"
                    class="w-full inline-flex items-center justify-center gap-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold py-3 px-4 rounded-xl shadow-xs transition-colors cursor-pointer"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ $tes->isForm() ? 'Kirim Tanggapan Formulir' : 'Kumpulkan Lembar Ujian' }}</span>
                </button>
            </div>
        </div>

    </div>

    <!-- Modal Dialog Konfirmasi Selesai Ujian -->
    <div id="submitModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-xl border border-slate-200">
            <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center mx-auto border border-slate-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <div class="text-center space-y-1">
                <h3 class="text-base font-bold text-slate-900">{{ $tes->isForm() ? 'Konfirmasi Kirim Formulir' : 'Konfirmasi Selesai Asesmen' }}</h3>
                <p class="text-xs text-slate-500">Periksa kembali ringkasan jawaban Anda sebelum melakukan submit akhir:</p>
            </div>

            <!-- Ringkasan Status Jawaban -->
            <div class="bg-slate-50 rounded-xl p-3 grid grid-cols-3 gap-2 text-center text-xs">
                <div class="bg-white p-2 rounded-lg border border-slate-200">
                    <span class="block text-[10px] text-slate-400 font-medium">Terjawab</span>
                    <span class="text-base font-extrabold text-slate-900 mt-0.5 block" id="modalAnswered">0</span>
                </div>
                <div class="bg-white p-2 rounded-lg border border-slate-200">
                    <span class="block text-[10px] text-slate-400 font-medium">Ragu-ragu</span>
                    <span class="text-base font-extrabold text-slate-900 mt-0.5 block" id="modalRagu">0</span>
                </div>
                <div class="bg-white p-2 rounded-lg border border-slate-200">
                    <span class="block text-[10px] text-slate-400 font-medium">Belum Diisi</span>
                    <span class="text-base font-extrabold text-slate-900 mt-0.5 block" id="modalUnanswered">0</span>
                </div>
            </div>

            <p id="unansweredWarning" class="text-xs text-slate-700 bg-slate-50 p-2.5 rounded-xl border border-slate-200 leading-relaxed hidden">
                ⚠️ <strong>Perhatian:</strong> Masih ada pertanyaan yang belum Anda jawab. Anda tetap dapat mengirimkan formulir ini sekarang.
            </p>

            <form action="{{ route('siswa.tes.selesai', $tes->id_tes) }}" method="POST" class="flex items-center gap-2 pt-2">
                @csrf
                <button
                    type="button"
                    onclick="closeSubmitModal()"
                    class="w-1/2 py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors cursor-pointer"
                >
                    Tinjau Kembali
                </button>
                <button
                    type="submit"
                    class="w-1/2 py-2.5 px-4 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl shadow-xs transition-colors cursor-pointer"
                >
                    {{ $tes->isForm() ? 'Ya, Kirim Formulir' : 'Ya, Kumpulkan' }}
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
let esaiDebounceTimer = null;
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
            document.querySelector('#submitModal form').submit();
        } else {
            updateTimerDisplay(remainingSeconds);
            // Visual alert jika sisa waktu < 5 menit (300 detik)
            if (remainingSeconds <= 300) {
                const timerEl = document.getElementById('timerDisplay');
                timerEl.classList.remove('text-white');
                timerEl.classList.add('text-amber-400');
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

    document.getElementById('hours').innerText = hStr;
    document.getElementById('minutes').innerText = mStr;
    document.getElementById('seconds').innerText = sStr;

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
            btn.classList.add('ring-2', 'ring-slate-900', 'ring-offset-2');
        } else {
            btn.classList.remove('ring-2', 'ring-slate-900', 'ring-offset-2');
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
        card.classList.remove('border-slate-900', 'bg-slate-50', 'shadow-2xs');
        card.classList.add('border-slate-200', 'bg-white');
        const badge = card.querySelector('.option-badge');
        if (badge) {
            badge.classList.remove('bg-slate-900', 'text-white');
            badge.classList.add('bg-slate-100', 'text-slate-700');
        }
    });

    labelEl.classList.remove('border-slate-200', 'bg-white');
    labelEl.classList.add('border-slate-900', 'bg-slate-50', 'shadow-2xs');
    const badge = labelEl.querySelector('.option-badge');
    if (badge) {
        badge.classList.remove('bg-slate-100', 'text-slate-700');
        badge.classList.add('bg-slate-900', 'text-white');
    }

    // Ambil status ragu-ragu saat ini
    const palletBtn = document.getElementById('palletBtn_' + soalIndex);
    const isRagu = palletBtn.getAttribute('data-ragu') === '1';

    // Kirim AJAX save
    saveToServer(soalId, pilihanId, null, isRagu, function(data) {
        updatePalletButton(soalIndex, true, isRagu);
    });
}

// 4. Input Jawaban Esai (Debounced Auto-Save)
function handleEsaiInput(soalIndex, soalId) {
    clearTimeout(esaiDebounceTimer);
    showSavingIndicator();

    esaiDebounceTimer = setTimeout(function() {
        const text = document.getElementById('esai_' + soalId).value;
        const palletBtn = document.getElementById('palletBtn_' + soalIndex);
        const isRagu = palletBtn.getAttribute('data-ragu') === '1';
        const isFilled = text.trim().length > 0;

        saveToServer(soalId, null, text, isRagu, function(data) {
            updatePalletButton(soalIndex, isFilled, isRagu);
        });
    }, 800);
}

// 5. Toggle Ragu-ragu
function toggleRaguRagu(soalIndex, soalId) {
    const palletBtn = document.getElementById('palletBtn_' + soalIndex);
    const currentlyRagu = palletBtn.getAttribute('data-ragu') === '1';
    const isNowRagu = !currentlyRagu;

    const container = document.getElementById('soalCard_' + soalIndex);
    const tipe = container.getAttribute('data-tipe');

    let pilihanId = null;
    let esaiText = null;

    if (tipe === 'PG') {
        const checked = container.querySelector('input[type="radio"]:checked');
        if (checked) pilihanId = checked.value;
    } else {
        esaiText = document.getElementById('esai_' + soalId).value;
    }

    const isAnswered = palletBtn.getAttribute('data-answered') === '1';

    // Update UI tombol ragu-ragu
    const btn = document.getElementById('btnRagu_' + soalIndex);
    const textEl = document.getElementById('textRagu_' + soalIndex);
    if (isNowRagu) {
        btn.classList.remove('bg-white', 'border-slate-300', 'text-slate-600');
        btn.classList.add('bg-amber-100', 'border-amber-300', 'text-amber-900');
        textEl.innerText = 'Ragu-ragu (Aktif)';
    } else {
        btn.classList.remove('bg-amber-100', 'border-amber-300', 'text-amber-900');
        btn.classList.add('bg-white', 'border-slate-300', 'text-slate-600');
        textEl.innerText = 'Ragu-ragu';
    }

    saveToServer(soalId, pilihanId, esaiText, isNowRagu, function() {
        updatePalletButton(soalIndex, isAnswered, isNowRagu);
    });
}

// 6. Helper: Kirim Data Jawaban ke Server (AJAX)
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
            jawaban_esai: esaiText,
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

// 7. Update Warna Palet Button
function updatePalletButton(soalIndex, isAnswered, isRagu) {
    const btn = document.getElementById('palletBtn_' + soalIndex);
    btn.setAttribute('data-answered', isAnswered ? '1' : '0');
    btn.setAttribute('data-ragu', isRagu ? '1' : '0');

    btn.className = 'pallet-btn relative h-10 w-full rounded-xl text-xs font-bold transition-all cursor-pointer flex items-center justify-center';

    if (isRagu) {
        btn.classList.add('bg-amber-400', 'text-slate-900');
    } else if (isAnswered) {
        btn.classList.add('bg-emerald-500', 'text-white');
    } else {
        btn.classList.add('bg-slate-100', 'text-slate-700', 'hover:bg-slate-200');
    }

    if (currentIndex === soalIndex) {
        btn.classList.add('ring-2', 'ring-slate-900', 'ring-offset-2');
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

// 8. Visual Indicator Auto-Save
function showSavingIndicator() {
    const el = document.getElementById('saveIndicator');
    const txt = document.getElementById('saveText');
    if (el && txt) {
        el.className = 'hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold bg-slate-800 border border-slate-700 text-slate-200';
        txt.innerText = 'Menyimpan...';
    }
}

function showSavedIndicator() {
    const el = document.getElementById('saveIndicator');
    const txt = document.getElementById('saveText');
    if (el && txt) {
        el.className = 'hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold bg-slate-800 border border-slate-700 text-slate-300';
        txt.innerText = 'Tersimpan';
    }
}

function showErrorIndicator() {
    const el = document.getElementById('saveIndicator');
    const txt = document.getElementById('saveText');
    if (el && txt) {
        el.className = 'hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold bg-slate-800 border border-slate-600 text-slate-300';
        txt.innerText = 'Gagal simpan (Cek koneksi)';
    }
}

// 9. Modal Submit Final
function openSubmitModal() {
    let answered = 0;
    let ragu = 0;
    document.querySelectorAll('.pallet-btn').forEach(btn => {
        if (btn.getAttribute('data-answered') === '1') answered++;
        if (btn.getAttribute('data-ragu') === '1') ragu++;
    });

    const unanswered = TOTAL_SOAL - answered;

    document.getElementById('modalAnswered').innerText = answered;
    document.getElementById('modalRagu').innerText = ragu;
    document.getElementById('modalUnanswered').innerText = unanswered;

    const warnEl = document.getElementById('unansweredWarning');
    if (unanswered > 0) {
        warnEl.classList.remove('hidden');
    } else {
        warnEl.classList.add('hidden');
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
