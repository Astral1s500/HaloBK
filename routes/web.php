<?php

use App\Http\Controllers\Admin\GuruBKController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Depan / Landing Page HaloBK
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Autentikasi Guest (Login & Post)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Logout (POST)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 1. Rute Khusus Admin (Protected via admin.auth)
Route::middleware('admin.auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        $totalGuru = \App\Models\GuruBk::count();
        $totalSiswa = \App\Models\Siswa::count();
        return view('admin.dashboard', compact('totalGuru', 'totalSiswa'));
    })->name('dashboard');

    // Sumber CRUD MASTER data Admin
    Route::resource('guru', GuruBKController::class);
    
    // Fitur Import & Export Excel Siswa
    Route::get('siswa/export/excel', [SiswaController::class, 'exportExcel'])->name('siswa.exportExcel');
    Route::get('siswa/template/excel', [SiswaController::class, 'downloadTemplate'])->name('siswa.downloadTemplate');
    Route::post('siswa/import/excel', [SiswaController::class, 'importExcel'])->name('siswa.importExcel');

    Route::post('siswa/{id}/restore', [SiswaController::class, 'restore'])->name('siswa.restore');
    Route::delete('siswa/{id}/force-delete', [SiswaController::class, 'forceDelete'])->name('siswa.forceDelete');
    Route::resource('siswa', SiswaController::class);
});

// 2. Rute Khusus Guru BK (Protected via guru.auth)
Route::middleware('guru.auth')->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', function () {
        $guruId = \Illuminate\Support\Facades\Auth::guard('guru')->id();
        $totalKelas = \App\Models\Kelas::where('id_guru', $guruId)->count();
        $totalMateri = \App\Models\Materi::where('id_guru', $guruId)->count();
        $totalTes = \App\Models\Tes::where('id_guru', $guruId)->count();
        $kelasList = \App\Models\Kelas::where('id_guru', $guruId)->withCount('siswas')->latest()->take(5)->get();
        return view('guru.dashboard', compact('totalKelas', 'totalMateri', 'totalTes', 'kelasList'));
    })->name('dashboard');

    // Kelola Kelas Binaan
    Route::resource('kelas', \App\Http\Controllers\Guru\KelasController::class);
    Route::post('kelas/{kelas}/kick-student/{siswa}', [\App\Http\Controllers\Guru\KelasController::class, 'kickStudent'])->name('kelas.kickStudent');

    // Modul Materi Bimbingan & Tambah Kategori
    Route::post('kategori', [\App\Http\Controllers\Guru\MateriController::class, 'storeKategori'])->name('kategori.store');
    Route::get('materi/{materi}/buka', [\App\Http\Controllers\Guru\MateriController::class, 'buka'])->name('materi.buka');
    Route::resource('materi', \App\Http\Controllers\Guru\MateriController::class);

    // Modul Paket Asesmen Tes & Bank Soal
    Route::resource('tes', \App\Http\Controllers\Guru\TesController::class);
    Route::post('tes/{te}/toggle-status', [\App\Http\Controllers\Guru\TesController::class, 'toggleStatus'])->name('tes.toggleStatus');
    Route::post('tes/{te}/terapkan-template', [\App\Http\Controllers\Guru\TesController::class, 'terapkanTemplate'])->name('tes.terapkanTemplate');
    Route::get('tes/{te}/soal/create', [\App\Http\Controllers\Guru\SoalController::class, 'create'])->name('tes.soal.create');
    Route::post('tes/{te}/soal', [\App\Http\Controllers\Guru\SoalController::class, 'store'])->name('tes.soal.store');
    Route::post('tes/{te}/soal/batch-store', [\App\Http\Controllers\Guru\SoalController::class, 'batchStore'])->name('tes.soal.batchStore');
    Route::get('soal/{soal}/edit', [\App\Http\Controllers\Guru\SoalController::class, 'edit'])->name('soal.edit');
    Route::put('soal/{soal}', [\App\Http\Controllers\Guru\SoalController::class, 'update'])->name('soal.update');
    Route::delete('soal/{soal}', [\App\Http\Controllers\Guru\SoalController::class, 'destroy'])->name('soal.destroy');

    // Modul Koreksi & Rekapitulasi Nilai
    Route::get('koreksi', [\App\Http\Controllers\Guru\KoreksiController::class, 'index'])->name('koreksi.index');
    Route::get('koreksi/{te}', [\App\Http\Controllers\Guru\KoreksiController::class, 'show'])->name('koreksi.show');
    Route::get('koreksi/siswa/{sesi}', [\App\Http\Controllers\Guru\KoreksiController::class, 'koreksiSiswa'])->name('koreksi.siswa');
    Route::post('koreksi/nilai/{jawaban}', [\App\Http\Controllers\Guru\KoreksiController::class, 'simpanNilaiEsai'])->name('koreksi.simpanNilai');
    Route::post('koreksi/simpan-semua/{sesi}', [\App\Http\Controllers\Guru\KoreksiController::class, 'simpanSemuaNilai'])->name('koreksi.simpanSemua');
});

// 3. Rute Khusus Siswa (Protected via siswa.auth)
Route::middleware('siswa.auth')->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Siswa\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/join-kelas', [\App\Http\Controllers\Siswa\DashboardController::class, 'joinKelas'])->name('joinKelas');
    Route::post('/leave-kelas', [\App\Http\Controllers\Siswa\DashboardController::class, 'leaveKelas'])->name('leaveKelas');

    // Modul Materi Bimbingan Siswa
    Route::get('/materi', [\App\Http\Controllers\Siswa\MateriController::class, 'index'])->name('materi.index');
    Route::get('/materi/{materi}/buka', [\App\Http\Controllers\Siswa\MateriController::class, 'buka'])->name('materi.buka');
    Route::get('/materi/{materi}/download', [\App\Http\Controllers\Siswa\MateriController::class, 'download'])->name('materi.download');

    // Modul Pengerjaan Tes CBT Siswa
    Route::get('/tes/{te}', [\App\Http\Controllers\Siswa\TesController::class, 'show'])->name('tes.show');
    Route::post('/tes/{te}/start', [\App\Http\Controllers\Siswa\TesController::class, 'start'])->name('tes.start');
    Route::get('/tes/{te}/kerjakan', [\App\Http\Controllers\Siswa\TesController::class, 'kerjakan'])->name('tes.kerjakan');
    Route::post('/tes/{te}/simpan-jawaban', [\App\Http\Controllers\Siswa\TesController::class, 'simpanJawaban'])->name('tes.simpanJawaban');
    Route::post('/tes/{te}/selesai', [\App\Http\Controllers\Siswa\TesController::class, 'selesai'])->name('tes.selesai');
    Route::get('/tes/{te}/hasil', [\App\Http\Controllers\Siswa\TesController::class, 'hasil'])->name('tes.hasil');
});
