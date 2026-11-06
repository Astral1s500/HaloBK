<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    protected $table = 'kategoris';
    protected $primaryKey = 'id_kategori';

    protected $fillable = [
        'nama_kategori',
    ];

    /**
     * Materi yang masuk dalam kategori ini.
     */
    public function materis(): HasMany
    {
        return $this->hasMany(Materi::class, 'id_kategori', 'id_kategori');
    }

    /**
     * Tes yang masuk dalam kategori ini.
     */
    public function tes(): HasMany
    {
        return $this->hasMany(Tes::class, 'id_kategori', 'id_kategori');
    }
}
