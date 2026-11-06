<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SesiTesSiswa extends Model
{
    protected $table = 'sesi_tes_siswas';
    protected $primaryKey = 'id_sesi';

    protected $fillable = [
        'id_tes',
        'id_siswa',
        'waktu_mulai_pengerjaan',
        'waktu_submit',
        'total_skor',
        'status_pengerjaan',
    ];

    protected $casts = [
        'waktu_mulai_pengerjaan' => 'datetime',
        'waktu_submit'           => 'datetime',
        'total_skor'             => 'float',
    ];

    /**
     * Tes yang sedang/sudah dikerjakan.
     */
    public function tes(): BelongsTo
    {
        return $this->belongsTo(Tes::class, 'id_tes', 'id_tes');
    }

    /**
     * Siswa yang mengerjakan tes ini.
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa')->withTrashed();
    }

    /**
     * Semua jawaban siswa dalam sesi ini.
     */
    public function jawabanSiswas(): HasMany
    {
        return $this->hasMany(JawabanSiswa::class, 'id_sesi', 'id_sesi');
    }

    /**
     * Helper: cek apakah sesi sudah selesai disubmit.
     */
    public function isSelesai(): bool
    {
        return $this->status_pengerjaan === 'selesai';
    }

    /**
     * Helper: cek apakah sesi sedang berlangsung.
     */
    public function isSedangMengerjakan(): bool
    {
        return $this->status_pengerjaan === 'sedang_mengerjakan';
    }
}
