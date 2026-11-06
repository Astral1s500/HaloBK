<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel: sesi_tes_siswas
     * ERD: sesi_tes_siswa (id_sesi PK, id_tes FK, id_siswa FK,
     *      waktu_mulai_pengerjaan, waktu_submit, total_skor, status_pengerjaan)
     */
    public function up(): void
    {
        Schema::create('sesi_tes_siswas', function (Blueprint $table) {
            $table->id('id_sesi');
            $table->unsignedBigInteger('id_tes');
            $table->unsignedBigInteger('id_siswa');
            $table->dateTime('waktu_mulai_pengerjaan')->nullable();
            $table->dateTime('waktu_submit')->nullable();
            $table->decimal('total_skor', 8, 2)->default(0);
            // Status: belum_mulai | sedang_mengerjakan | selesai
            $table->enum('status_pengerjaan', ['belum_mulai', 'sedang_mengerjakan', 'selesai'])->default('belum_mulai');
            $table->timestamps();

            $table->foreign('id_tes')
                  ->references('id_tes')
                  ->on('tes')
                  ->onDelete('cascade');

            $table->foreign('id_siswa')
                  ->references('id_siswa')
                  ->on('siswas')
                  ->onDelete('cascade');

            // Satu siswa hanya bisa punya 1 sesi per tes
            $table->unique(['id_tes', 'id_siswa']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesi_tes_siswas');
    }
};
