<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class GuruBk extends Authenticatable
{
    use Notifiable;

    protected $table = 'guru_bks';
    protected $primaryKey = 'id_guru';

    protected $fillable = [
        'nama_guru',
        'nip',
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
     * Kelas yang diampu oleh guru BK ini.
     */
    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class, 'id_guru', 'id_guru');
    }

    /**
     * Materi yang diunggah oleh guru BK ini.
     */
    public function materis(): HasMany
    {
        return $this->hasMany(Materi::class, 'id_guru', 'id_guru');
    }

    /**
     * Tes yang dibuat oleh guru BK ini.
     */
    public function tes(): HasMany
    {
        return $this->hasMany(Tes::class, 'id_guru', 'id_guru');
    }
}
