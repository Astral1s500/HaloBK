<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PilihanJawaban extends Model
{
    protected $table = 'pilihan_jawabans';
    protected $primaryKey = 'id_pilihan';

    protected $fillable = [
        'id_soal',
        'teks_pilihan',
        'is_benar',
    ];

    protected $casts = [
        'is_benar' => 'boolean',
    ];

    /**
     * Soal yang memiliki pilihan ini.
     */
    public function soal(): BelongsTo
    {
        return $this->belongsTo(Soal::class, 'id_soal', 'id_soal');
    }

    /**
     * Jawaban siswa yang memilih opsi ini.
     */
    public function jawabanSiswas(): HasMany
    {
        return $this->hasMany(JawabanSiswa::class, 'id_pilihan', 'id_pilihan');
    }
}
