<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tesList = App\Models\Tes::with(['soals.pilihanJawabans', 'sesiTesSiswas.jawabanSiswas'])->get();

foreach ($tesList as $tes) {
    echo "ID: {$tes->id_tes} | Judul: {$tes->judul} | Tipe: {$tes->tipe}\n";
    echo "  Total Soal: " . $tes->soals->count() . "\n";
    echo "  Total Sesi: " . $tes->sesiTesSiswas->count() . "\n";
    $selesai = $tes->sesiTesSiswas->where('status_pengerjaan', 'selesai')->count();
    echo "  Sesi Selesai: " . $selesai . "\n";
    
    foreach ($tes->soals as $idx => $soal) {
        $jawaban = App\Models\JawabanSiswa::where('id_soal', $soal->id_soal)
            ->whereHas('sesiTesSiswa', fn($q) => $q->where('id_tes', $tes->id_tes)->where('status_pengerjaan', 'selesai'))
            ->get();
        $benar = $jawaban->filter(function($j) use ($soal) {
            if ($soal->isPilihanGanda()) {
                return $j->pilihanJawaban && $j->pilihanJawaban->is_benar;
            } else {
                return ($j->nilai_soal ?? 0) >= ($soal->bobot_nilai / 2);
            }
        })->count();
        $salah = $selesai - $benar;
        $pctSalah = $selesai > 0 ? round(($salah / $selesai) * 100, 1) : 0;
        echo "    Soal #" . ($idx + 1) . " (ID: {$soal->id_soal}, Tipe: {$soal->tipe_soal}): Benar={$benar}, Salah={$salah}, ErrorRate={$pctSalah}%\n";
    }
    echo "\n";
}
