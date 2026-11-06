<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Siswa extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $table = 'siswas';
    protected $primaryKey = 'id_siswa';

    protected $fillable = [
        'id_kelas',
        'id_admin',
        'nama_siswa',
        'nisn',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Disable remember_token karena kolom tidak ada pada ERD resmi.
     */
    public function getRememberTokenName(): string
    {
        return '';
    }

    /**
     * Kelas tempat siswa ini terdaftar.
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    /**
     * Admin yang mendaftarkan siswa ini.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'id_admin', 'id_admin');
    }

    /**
     * Sesi tes yang pernah diikuti siswa ini.
     */
    public function sesiTesSiswas(): HasMany
    {
        return $this->hasMany(SesiTesSiswa::class, 'id_siswa', 'id_siswa');
    }
}
