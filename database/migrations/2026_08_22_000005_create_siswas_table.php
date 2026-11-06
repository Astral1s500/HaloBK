<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel: siswas
     * ERD: Siswa (id_siswa PK, id_kelas FK, id_admin FK, nama_siswa, nisn, password)
     */
    public function up(): void
    {
        Schema::create('siswas', function (Blueprint $table) {
            $table->id('id_siswa');
            $table->unsignedBigInteger('id_kelas');
            $table->unsignedBigInteger('id_admin');
            $table->string('nama_siswa');
            $table->string('nisn')->unique();
            $table->string('password');
            $table->timestamps();

            $table->foreign('id_kelas')
                  ->references('id_kelas')
                  ->on('kelas')
                  ->onDelete('cascade');

            $table->foreign('id_admin')
                  ->references('id_admin')
                  ->on('admins')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
