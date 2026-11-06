<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jadikan nilai_soal nullable agar Guru BK bisa skip penilaian Esai
     * tanpa mewajibkan pengisian nilai. Nilai default tetap 0.
     */
    public function up(): void
    {
        Schema::table('jawaban_siswas', function (Blueprint $table) {
            $table->decimal('nilai_soal', 5, 2)->nullable()->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jawaban_siswas', function (Blueprint $table) {
            $table->decimal('nilai_soal', 5, 2)->default(0)->change();
        });
    }
};
