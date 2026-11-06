<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tes extends Model
{
    protected $table = 'tes';
    protected $primaryKey = 'id_tes';

    protected $fillable = [
        'id_kelas',
        'id_kategori',
        'id_guru',
        'judul',
        'tipe',
        'waktu_mulai',
        'waktu_selesai',
        'durasi_menit',
        'acak_soal',
        'status',
    ];

    protected $casts = [
        'waktu_mulai'   => 'datetime',
        'waktu_selesai' => 'datetime',
        'acak_soal'     => 'boolean',
    ];

    /**
     * Memeriksa apakah asesmen ini bertipe formulir pendataan.
     */
    public function isForm(): bool
    {
        return $this->tipe === 'form';
    }

    /**
     * Memeriksa apakah asesmen ini bertipe tes reguler / CBT.
     */
    public function isTes(): bool
    {
        return $this->tipe !== 'form';
    }

    /**
     * Label tipe asesmen yang ramah dibaca.
     */
    public function getTipeLabelAttribute(): string
    {
        return $this->isForm() ? 'Formulir Pendataan' : 'Tes CBT';
    }

    /**
     * Kelas yang ditugaskan tes ini.
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    /**
     * Kategori tes ini.
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    /**
     * Guru BK pembuat tes ini.
     */
    public function guruBk(): BelongsTo
    {
        return $this->belongsTo(GuruBk::class, 'id_guru', 'id_guru');
    }

    /**
     * Daftar soal dalam tes ini.
     */
    public function soals(): HasMany
    {
        return $this->hasMany(Soal::class, 'id_tes', 'id_tes');
    }

    /**
     * Sesi pengerjaan tes oleh para siswa.
     */
    public function sesiTesSiswas(): HasMany
    {
        return $this->hasMany(SesiTesSiswa::class, 'id_tes', 'id_tes');
    }
}
