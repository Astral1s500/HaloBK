<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admins';
    protected $primaryKey = 'id_admin';

    protected $fillable = [
        'nama_admin',
        'username',
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
     * Siswa yang didaftarkan oleh admin ini.
     */
    public function siswas(): HasMany
    {
        return $this->hasMany(Siswa::class, 'id_admin', 'id_admin');
    }
}
