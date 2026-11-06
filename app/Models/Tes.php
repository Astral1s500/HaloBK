<?php

namespace App\Models;

use App\Services\PsikometrikService;
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
        return 'Asessment Psikometrik';
    }

    /**
     * Jenis asesmen psikometrik (VARK, OCEAN, RIASEC, Sosial).
     */
    public function getJenisPsikometrikAttribute(): string
    {
        return PsikometrikService::deteksiJenis($this);
    }

    /**
     * Meta informasi lengkap tipe psikometrik.
     */
    public function getInfoPsikometrikAttribute(): array
    {
        $all = PsikometrikService::getJenisAsesmen();
        $jenis = $this->jenis_psikometrik;
        return $all[$jenis] ?? $all[PsikometrikService::TIPE_VARK];
    }

    /**
     * Analisis Keseimbangan Dimensi Butir Soal.
     */
    public function getAnalisisKeseimbanganAttribute(): array
    {
        return PsikometrikService::analisisKeseimbangan($this);
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
