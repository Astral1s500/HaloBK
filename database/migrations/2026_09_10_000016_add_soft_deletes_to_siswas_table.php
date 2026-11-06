<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 1. Tambah soft deletes (deleted_at) pada tabel siswas agar siswa yang dihapus
     *    tidak hilang secara permanen dari basis data dan riwayat nilai tetap utuh.
     * 2. Ubah foreign key id_admin menjadi nullable dengan ON DELETE SET NULL agar jika
     *    akun admin dihapus, seluruh data siswa tidak terhapus secara otomatis.
     */
    public function up(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->softDeletes();

            // Ubah foreign key id_admin menjadi SET NULL
            $table->dropForeign('siswas_id_admin_foreign');
            $table->unsignedBigInteger('id_admin')->nullable()->change();
            $table->foreign('id_admin')
                  ->references('id_admin')
                  ->on('admins')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropSoftDeletes();

            $table->dropForeign('siswas_id_admin_foreign');
            $table->unsignedBigInteger('id_admin')->nullable(false)->change();
            $table->foreign('id_admin')
                  ->references('id_admin')
                  ->on('admins')
                  ->onDelete('cascade');
        });
    }
};
