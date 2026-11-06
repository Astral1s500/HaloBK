<?php

namespace App\Models;

use App\Services\PsikometrikService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Soal extends Model
{
    protected $table = 'soals';
    protected $primaryKey = 'id_soal';

    protected $fillable = [
        'id_tes',
        'pertanyaan',
        'tipe_soal',
        'bobot_nilai',
        'timer_per_soal',
        'gambar_lampiran',
    ];

    protected $casts = [
        'bobot_nilai' => 'float',
    ];

    /**
     * Tes yang memiliki soal ini.
     */
    public function tes(): BelongsTo
    {
        return $this->belongsTo(Tes::class, 'id_tes', 'id_tes');
    }

    /**
     * Pilihan jawaban untuk soal PG.
     */
    public function pilihanJawabans(): HasMany
    {
        return $this->hasMany(PilihanJawaban::class, 'id_soal', 'id_soal');
    }

    /**
     * Jawaban siswa untuk soal ini.
     */
    public function jawabanSiswas(): HasMany
    {
        return $this->hasMany(JawabanSiswa::class, 'id_soal', 'id_soal');
    }

    /**
     * Helper: cek apakah soal ini bertipe Pilihan Ganda.
     */
    public function isPilihanGanda(): bool
    {
        return $this->tipe_soal === 'PG';
    }

    /**
     * Helper: cek apakah soal ini bertipe Esai.
     */
    public function isEsai(): bool
    {
        return $this->tipe_soal === 'Esai';
    }

    /**
     * Mengambil nama dimensi dari teks pertanyaan.
     */
    public function getDimensiAttribute(): string
    {
        $tipe = $this->tes ? PsikometrikService::deteksiJenis($this->tes) : PsikometrikService::TIPE_VARK;
        return PsikometrikService::ekstrakDimensi($this->pertanyaan ?? '', $tipe);
    }

    /**
     * Mengambil teks pertanyaan murni yang sudah dibersihkan dari tag dimensi.
     */
    public function getCleanPertanyaanAttribute(): string
    {
        return PsikometrikService::bersihkanPertanyaan($this->pertanyaan ?? '');
    }
}
