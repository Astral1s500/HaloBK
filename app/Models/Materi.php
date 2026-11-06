<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Materi extends Model
{
    protected $table = 'materis';
    protected $primaryKey = 'id_materi';

    protected $fillable = [
        'id_guru',
        'id_kelas',
        'id_kategori',
        'judul_materi',
        'file_path',
        'deskripsi',
        'warna',
    ];

    /**
     * Daftar pilihan warna card materi dengan skema medium opacity yang lembut.
     */
    public static function getAvailableColors(): array
    {
        return [
            'netral' => [
                'name'    => 'Netral',
                'label'   => 'Abu-abu / Slate',
                'card_bg' => 'bg-white border border-slate-200 hover:border-slate-300 shadow-2xs',
                'badge'   => 'bg-slate-50 text-slate-700 border border-slate-200',
                'btn'     => 'bg-slate-50 text-slate-700 hover:bg-slate-100 hover:text-slate-900 border border-slate-200',
                'dot'     => 'bg-slate-400',
                'preview' => 'bg-white border-slate-200 text-slate-700',
            ],
            'biru' => [
                'name'    => 'Biru',
                'label'   => 'Biru Lembut',
                'card_bg' => 'bg-white border border-blue-200 hover:border-blue-300 shadow-2xs',
                'badge'   => 'bg-blue-50 text-blue-700 border border-blue-200',
                'btn'     => 'bg-slate-50 text-slate-700 hover:bg-slate-100 hover:text-slate-900 border border-slate-200',
                'dot'     => 'bg-blue-400',
                'preview' => 'bg-white border-blue-200 text-blue-700',
            ],
            'hijau' => [
                'name'    => 'Hijau',
                'label'   => 'Hijau Lembut',
                'card_bg' => 'bg-white border border-emerald-200 hover:border-emerald-300 shadow-2xs',
                'badge'   => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                'btn'     => 'bg-slate-50 text-slate-700 hover:bg-slate-100 hover:text-slate-900 border border-slate-200',
                'dot'     => 'bg-emerald-400',
                'preview' => 'bg-white border-emerald-200 text-emerald-700',
            ],
            'kuning' => [
                'name'    => 'Kuning',
                'label'   => 'Kuning / Amber',
                'card_bg' => 'bg-white border border-amber-200 hover:border-amber-300 shadow-2xs',
                'badge'   => 'bg-amber-50 text-amber-700 border border-amber-200',
                'btn'     => 'bg-slate-50 text-slate-700 hover:bg-slate-100 hover:text-slate-900 border border-slate-200',
                'dot'     => 'bg-amber-400',
                'preview' => 'bg-white border-amber-200 text-amber-700',
            ],
            'ungu' => [
                'name'    => 'Ungu',
                'label'   => 'Ungu Lembut',
                'card_bg' => 'bg-white border border-purple-200 hover:border-purple-300 shadow-2xs',
                'badge'   => 'bg-purple-50 text-purple-700 border border-purple-200',
                'btn'     => 'bg-slate-50 text-slate-700 hover:bg-slate-100 hover:text-slate-900 border border-slate-200',
                'dot'     => 'bg-purple-400',
                'preview' => 'bg-white border-purple-200 text-purple-700',
            ],
            'merah_muda' => [
                'name'    => 'Merah Muda',
                'label'   => 'Merah Muda / Rose',
                'card_bg' => 'bg-white border border-rose-200 hover:border-rose-300 shadow-2xs',
                'badge'   => 'bg-rose-50 text-rose-700 border border-rose-200',
                'btn'     => 'bg-slate-50 text-slate-700 hover:bg-slate-100 hover:text-slate-900 border border-slate-200',
                'dot'     => 'bg-rose-400',
                'preview' => 'bg-white border-rose-200 text-rose-700',
            ],
        ];
    }

    /**
     * Konfigurasi styling warna card materi ini.
     */
    public function getColorConfigAttribute(): array
    {
        $colors = self::getAvailableColors();
        return $colors[$this->warna] ?? $colors['netral'];
    }

    /**
     * Guru BK yang mengunggah materi ini.
     */
    public function guruBk(): BelongsTo
    {
        return $this->belongsTo(GuruBk::class, 'id_guru', 'id_guru');
    }

    /**
     * Target kelas binaan materi ini.
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    /**
     * Kategori materi ini.
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }
}
