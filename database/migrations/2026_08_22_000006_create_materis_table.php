<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel: materis
     * ERD: materi (id_materi PK, id_guru FK, id_kategori FK, judul_materi, file_path, deskripsi)
     */
    public function up(): void
    {
        Schema::create('materis', function (Blueprint $table) {
            $table->id('id_materi');
            $table->unsignedBigInteger('id_guru');
            $table->unsignedBigInteger('id_kategori');
            $table->string('judul_materi');
            $table->string('file_path');
            $table->text('deskripsi')->nullable();
            $table->timestamps();

            $table->foreign('id_guru')
                  ->references('id_guru')
                  ->on('guru_bks')
                  ->onDelete('cascade');

            $table->foreign('id_kategori')
                  ->references('id_kategori')
                  ->on('kategoris')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materis');
    }
};
