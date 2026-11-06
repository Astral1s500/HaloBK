<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JawabanSiswa extends Model
{
    protected $table = 'jawaban_siswas';
    protected $primaryKey = 'id_jawaban_siswa';

    protected $fillable = [
        'id_sesi',
        'id_soal',
        'id_pilihan',
        'jawaban_esai',
        'is_ragu_ragu',
        'nilai_soal',
    ];

    protected $casts = [
        'is_ragu_ragu' => 'boolean',
        'nilai_soal'   => 'float', // nullable — null berarti belum dinilai (khusus Esai)
    ];

    /**
     * Sesi tes tempat jawaban ini dicatat.
     */
    public function sesiTesSiswa(): BelongsTo
    {
        return $this->belongsTo(SesiTesSiswa::class, 'id_sesi', 'id_sesi');
    }

    /**
     * Soal yang dijawab.
     */
    public function soal(): BelongsTo
    {
        return $this->belongsTo(Soal::class, 'id_soal', 'id_soal');
    }

    /**
     * Pilihan jawaban yang dipilih (null jika soal Esai).
     */
    public function pilihanJawaban(): BelongsTo
    {
        return $this->belongsTo(PilihanJawaban::class, 'id_pilihan', 'id_pilihan');
    }

    /**
     * Helper: cek apakah jawaban ini ditandai ragu-ragu.
     */
    public function isRaguRagu(): bool
    {
        return $this->is_ragu_ragu === true;
    }

    /**
     * Helper: cek apakah ini jawaban untuk soal Esai.
     */
    public function isJawabanEsai(): bool
    {
        return ! is_null($this->jawaban_esai);
    }
}
