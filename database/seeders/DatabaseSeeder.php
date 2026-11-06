<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\GuruBk;
use App\Models\Kategori;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Siswa;
use App\Models\Tes;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed data awal untuk pengujian autentikasi dan sistem HaloBK.
     */
    public function run(): void
    {
        // 1. Seed Akun Admin
        $admin = Admin::updateOrCreate(
            ['username' => 'admin'],
            [
                'nama_admin' => 'Administrator Utama',
                'password'   => Hash::make('password'),
            ]
        );

        // 2. Seed Akun Guru BK
        $guru = GuruBk::updateOrCreate(
            ['nip' => '198501012010011001'],
            [
                'nama_guru' => 'Dra. Hj. Siti Rahmawati, M.Pd',
                'password'  => Hash::make('password'),
            ]
        );

        // 3. Seed Master Kategori
        $kategoriKarir = Kategori::updateOrCreate(
            ['nama_kategori' => 'Bimbingan Karir & Studi Lanjut']
        );
        $kategoriMinat = Kategori::updateOrCreate(
            ['nama_kategori' => 'Tes Minat & Bakat']
        );

        // 4. Seed Kelas
        $kelas = Kelas::updateOrCreate(
            ['kode_unik_kelas' => 'BK-MIPA1'],
            [
                'id_guru'    => $guru->id_guru,
                'nama_kelas' => 'XII-MIPA-1',
            ]
        );

        // 5. Seed Akun Siswa
        $siswa = Siswa::updateOrCreate(
            ['nisn' => '0051234567'],
            [
                'id_kelas'   => $kelas->id_kelas,
                'id_admin'   => $admin->id_admin,
                'nama_siswa' => 'Ahmad Fauzi',
                'password'   => Hash::make('password'),
            ]
        );

        // 6. Seed Materi Sampel (dengan Pilihan Warna Card Materi)
        Materi::updateOrCreate(
            ['judul_materi' => 'Panduan Pemilihan Jurusan Kuliah 2026'],
            [
                'id_guru'     => $guru->id_guru,
                'id_kelas'    => $kelas->id_kelas,
                'id_kategori' => $kategoriKarir->id_kategori,
                'file_path'   => 'materi/panduan-jurusan.pdf',
                'deskripsi'   => 'Modul persiapan eksplorasi program studi dan jalur masuk perguruan tinggi.',
                'warna'       => 'biru',
            ]
        );

        Materi::updateOrCreate(
            ['judul_materi' => 'Strategi Regulasi Diri & Manajemen Waktu Belajar'],
            [
                'id_guru'     => $guru->id_guru,
                'id_kelas'    => $kelas->id_kelas,
                'id_kategori' => $kategoriKarir->id_kategori,
                'file_path'   => 'materi/strategi-waktu.pdf',
                'deskripsi'   => 'Tips praktis mengelola jadwal belajar, mengatasi prokrastinasi, dan menjaga fokus.',
                'warna'       => 'hijau',
            ]
        );

        // 7. Seed Tes Sampel
        Tes::updateOrCreate(
            ['judul' => 'Kuesioner Minat & Bakat Karir Siswa'],
            [
                'id_kelas'      => $kelas->id_kelas,
                'id_kategori'   => $kategoriMinat->id_kategori,
                'id_guru'       => $guru->id_guru,
                'waktu_mulai'   => now(),
                'waktu_selesai' => now()->addDays(14),
                'durasi_menit'  => 60,
                'acak_soal'     => true,
                'status'        => 'aktif',
            ]
        );
    }
}
