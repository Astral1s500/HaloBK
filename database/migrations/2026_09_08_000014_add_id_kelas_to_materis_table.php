<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('materis', function (Blueprint $table) {
            $table->unsignedBigInteger('id_kelas')->nullable()->after('id_guru');

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
        Schema::table('materis', function (Blueprint $table) {
            $table->dropForeign(['id_kelas']);
            $table->dropColumn('id_kelas');
        });
    }
};
