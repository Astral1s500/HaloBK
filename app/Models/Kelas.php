<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    protected $table = 'kelas';
    protected $primaryKey = 'id_kelas';

    protected $fillable = [
        'id_guru',
        'nama_kelas',
        'kode_unik_kelas',
    ];

    /**
     * Guru BK yang mengampu kelas ini.
     */
    public function guruBk(): BelongsTo
    {
        return $this->belongsTo(GuruBk::class, 'id_guru', 'id_guru');
    }

    /**
     * Siswa yang terdaftar di kelas ini.
     */
    public function siswas(): HasMany
    {
        return $this->hasMany(Siswa::class, 'id_kelas', 'id_kelas');
    }

    /**
     * Tes yang ditetapkan untuk kelas ini.
     */
    public function tes(): HasMany
    {
        return $this->hasMany(Tes::class, 'id_kelas', 'id_kelas');
    }

    /**
     * Materi bimbingan yang ditujukan untuk kelas ini.
     */
    public function materis(): HasMany
    {
        return $this->hasMany(Materi::class, 'id_kelas', 'id_kelas');
    }
}
