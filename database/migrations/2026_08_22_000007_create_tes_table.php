<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel: tes
     * ERD: tes (id_tes PK, id_kelas FK, id_kategori FK, id_guru FK,
     *           judul, waktu_mulai, waktu_selesai, durasi_menit, acak_soal, status)
     */
    public function up(): void
    {
        Schema::create('tes', function (Blueprint $table) {
            $table->id('id_tes');
            $table->unsignedBigInteger('id_kelas');
            $table->unsignedBigInteger('id_kategori');
            $table->unsignedBigInteger('id_guru');
            $table->string('judul');
            $table->dateTime('waktu_mulai')->nullable();
            $table->dateTime('waktu_selesai')->nullable();
            $table->unsignedInteger('durasi_menit')->default(60);
            $table->boolean('acak_soal')->default(false);
            // Status: draft | aktif | selesai
            $table->enum('status', ['draft', 'aktif', 'selesai'])->default('draft');
            $table->timestamps();

            $table->foreign('id_kelas')
                  ->references('id_kelas')
                  ->on('kelas')
                  ->onDelete('cascade');

            $table->foreign('id_kategori')
                  ->references('id_kategori')
                  ->on('kategoris')
                  ->onDelete('cascade');

            $table->foreign('id_guru')
                  ->references('id_guru')
                  ->on('guru_bks')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tes');
    }
};
