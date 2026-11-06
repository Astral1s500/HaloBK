<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class PsikometrikKategoriSeeder extends Seeder
{
    /**
     * Seed 4 kategori asesmen psikometrik resmi HaloBK.
     */
    public function run(): void
    {
        $kategoris = [
            'Belajar (VARK)',
            'Pribadi (OCEAN)',
            'Karier (RIASEC)',
            'Sosial',
        ];

        foreach ($kategoris as $nama) {
            Kategori::firstOrCreate(['nama_kategori' => $nama]);
        }
    }
}

