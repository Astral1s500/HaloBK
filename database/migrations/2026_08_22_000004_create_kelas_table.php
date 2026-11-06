<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel: kelas
     * ERD: Kelas (id_kelas PK, id_guru FK, nama_kelas, kode_unik_kelas)
     */
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id('id_kelas');
            $table->unsignedBigInteger('id_guru');
            $table->string('nama_kelas');
            $table->string('kode_unik_kelas')->unique();
            $table->timestamps();

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
        Schema::dropIfExists('kelas');
    }
};
