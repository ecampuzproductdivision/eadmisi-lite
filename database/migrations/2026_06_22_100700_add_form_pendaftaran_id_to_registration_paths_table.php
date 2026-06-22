<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registration_paths', function (Blueprint $table) {
            $table->foreignId('form_pendaftaran_id')
                ->nullable()
                ->after('kategori_jalur_id')
                ->constrained('forms')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('registration_paths', function (Blueprint $table) {
            $table->dropForeign(['form_pendaftaran_id']);
            $table->dropColumn('form_pendaftaran_id');
        });
    }
};