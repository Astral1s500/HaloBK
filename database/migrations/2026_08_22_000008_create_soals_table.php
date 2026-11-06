<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel: soals
     * ERD: soal (id_soal PK, id_tes FK, pertanyaan, tipe_soal, bobot_nilai,
     *            timer_per_soal, gambar_lampiran)
     */
    public function up(): void
    {
        Schema::create('soals', function (Blueprint $table) {
            $table->id('id_soal');
            $table->unsignedBigInteger('id_tes');
            $table->text('pertanyaan');
            // tipe_soal: PG (Pilihan Ganda) | Esai
            $table->enum('tipe_soal', ['PG', 'Esai']);
            $table->decimal('bobot_nilai', 5, 2)->default(0);
            $table->unsignedInteger('timer_per_soal')->nullable()->comment('Detik per soal, null = tidak ada timer');
            $table->string('gambar_lampiran')->nullable();
            $table->timestamps();

            $table->foreign('id_tes')
                  ->references('id_tes')
                  ->on('tes')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soals');
    }
};
