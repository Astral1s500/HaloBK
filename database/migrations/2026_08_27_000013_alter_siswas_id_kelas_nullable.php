<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jadikan id_kelas nullable di tabel siswas agar siswa baru bisa
     * didaftarkan oleh Admin tanpa harus memilih kelas awal terlebih dahulu.
     */
    public function up(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_kelas')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_kelas')->nullable(false)->change();
        });
    }
};
