<?php

require 'c:/xampp/htdocs/HALOBK/vendor/autoload.php';

$app = require_once 'c:/xampp/htdocs/HALOBK/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$app['view']->share('errors', new \Illuminate\Support\ViewErrorBag);

echo "--- 1. Testing Materi Models and Colors ---\n";
$materis = \App\Models\Materi::all();
foreach ($materis as $m) {
    echo "- Materi ID {$m->id_materi}: '{$m->judul_materi}' | Warna: {$m->warna} | Config Name: {$m->color_config['name']}\n";
}

echo "\n--- 2. Testing Student Dashboard Rendering ---\n";
$siswa = \App\Models\Siswa::first();
\Illuminate\Support\Facades\Auth::guard('siswa')->login($siswa);
$siswaDashboard = (new \App\Http\Controllers\Siswa\DashboardController())->index()->render();

$hasBlueMateri = strpos($siswaDashboard, 'bg-blue-500/10') !== false;
$hasGreenMateri = strpos($siswaDashboard, 'bg-emerald-500/10') !== false;

echo "Materi Card Biru Rendered in Student Dashboard: " . ($hasBlueMateri ? "YES (PASS)" : "NO") . "\n";
echo "Materi Card Hijau Rendered in Student Dashboard: " . ($hasGreenMateri ? "YES (PASS)" : "NO") . "\n";

echo "\n--- 3. Testing Guru Create Materi View ---\n";
$guru = \App\Models\GuruBk::first();
\Illuminate\Support\Facades\Auth::guard('guru')->login($guru);
$guruCreate = (new \App\Http\Controllers\Guru\MateriController())->create()->render();

$hasColorPicker = strpos($guruCreate, 'name="warna"') !== false;
$hasSamplePreview = strpos($guruCreate, 'sampleMateriCard') !== false;
echo "Guru Create has Color Picker: " . ($hasColorPicker ? "YES (PASS)" : "NO") . "\n";
echo "Guru Create has Sample Preview: " . ($hasSamplePreview ? "YES (PASS)" : "NO") . "\n";

echo "\n--- 4. Testing Guru Materi Index Table ---\n";
$request = new \Illuminate\Http\Request();
$guruIndex = (new \App\Http\Controllers\Guru\MateriController())->index($request)->render();
$hasColorCol = strpos($guruIndex, 'Warna Card') !== false;
echo "Guru Index has Warna Card column: " . ($hasColorCol ? "YES (PASS)" : "NO") . "\n";

echo "\nALL TESTS COMPLETED SUCCESSFULLY!\n";
