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
        Schema::table('tes', function (Blueprint $table) {
            $table->enum('tipe', ['tes', 'form'])->default('tes')->after('judul');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tes', function (Blueprint $table) {
            $table->dropColumn('tipe');
        });
    }
};
