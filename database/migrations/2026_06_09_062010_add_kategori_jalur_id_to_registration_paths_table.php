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
        Schema::table('registration_paths', function (Blueprint $table) {
            $table->foreignId('kategori_jalur_id')
                ->nullable()
                ->constrained('kategori_jalurs')
                ->nullOnDelete()
                ->after('id')
                ->comment('Relasi ke tabel kategori_jalurs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registration_paths', function (Blueprint $table) {
            $table->dropForeign(['kategori_jalur_id']);
            $table->dropColumn('kategori_jalur_id');
        });
    }
};