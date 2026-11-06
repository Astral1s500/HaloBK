<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel: guru_bks
     * ERD: Guru BK (id_guru PK, nama_guru, nip, password)
     */
    public function up(): void
    {
        Schema::create('guru_bks', function (Blueprint $table) {
            $table->id('id_guru');
            $table->string('nama_guru');
            $table->string('nip')->unique();
            $table->string('password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guru_bks');
    }
};
