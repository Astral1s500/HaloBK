<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ubah constraint foreign key id_kelas pada tabel siswas dari CASCADE menjadi SET NULL.
     * Hal ini krusial agar jika kelas atau akun Guru BK dihapus, akun siswa TIDAK ikut terhapus
     * secara tidak sengaja, melainkan hanya status kelasnya yang menjadi null (belum terdaftar).
     */
    public function up(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropForeign('siswas_id_kelas_foreign');
            $table->foreign('id_kelas')
                  ->references('id_kelas')
                  ->on('kelas')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropForeign('siswas_id_kelas_foreign');
            $table->foreign('id_kelas')
                  ->references('id_kelas')
                  ->on('kelas')
                  ->onDelete('cascade');
        });
    }
};
