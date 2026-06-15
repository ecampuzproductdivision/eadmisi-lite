<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add jumlah_pilihan_prodi column to registration_paths table.
     */
    public function up(): void
    {
        Schema::table('registration_paths', function (Blueprint $table) {
            $table->integer('jumlah_pilihan_prodi')->default(1)->after('quota')
                ->comment('Jumlah pilihan program studi yang dapat dipilih pendaftar (1, 2, atau 3)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registration_paths', function (Blueprint $table) {
            $table->dropColumn('jumlah_pilihan_prodi');
        });
    }
};