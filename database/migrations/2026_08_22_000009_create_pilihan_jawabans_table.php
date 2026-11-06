<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel: pilihan_jawabans
     * ERD: pilihan_jawaban (id_pilihan PK, id_soal FK, teks_pilihan, is_benar Boolean)
     * Hanya relevan untuk soal tipe PG.
     */
    public function up(): void
    {
        Schema::create('pilihan_jawabans', function (Blueprint $table) {
            $table->id('id_pilihan');
            $table->unsignedBigInteger('id_soal');
            $table->text('teks_pilihan');
            $table->boolean('is_benar')->default(false);
            $table->timestamps();

            $table->foreign('id_soal')
                  ->references('id_soal')
                  ->on('soals')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pilihan_jawabans');
    }
};
