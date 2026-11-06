<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel: jawaban_siswas
     * ERD: jawaban_siswa (id_jawaban_siswa PK, id_sesi FK, id_soal FK,
     *      id_pilihan FK (Nullable jika Esai), jawaban_esai (Nullable),
     *      is_ragu_ragu Boolean, nilai_soal)
     */
    public function up(): void
    {
        Schema::create('jawaban_siswas', function (Blueprint $table) {
            $table->id('id_jawaban_siswa');
            $table->unsignedBigInteger('id_sesi');
            $table->unsignedBigInteger('id_soal');
            // Nullable: hanya diisi jika soal tipe PG
            $table->unsignedBigInteger('id_pilihan')->nullable();
            // Nullable: hanya diisi jika soal tipe Esai
            $table->text('jawaban_esai')->nullable();
            $table->boolean('is_ragu_ragu')->default(false);
            $table->decimal('nilai_soal', 5, 2)->default(0);
            $table->timestamps();

            $table->foreign('id_sesi')
                  ->references('id_sesi')
                  ->on('sesi_tes_siswas')
                  ->onDelete('cascade');

            $table->foreign('id_soal')
                  ->references('id_soal')
                  ->on('soals')
                  ->onDelete('cascade');

            $table->foreign('id_pilihan')
                  ->references('id_pilihan')
                  ->on('pilihan_jawabans')
                  ->onDelete('set null');

            // Satu sesi hanya bisa punya 1 jawaban per soal
            $table->unique(['id_sesi', 'id_soal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawaban_siswas');
    }
};
