<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registration_paths', function (Blueprint $table) {
            $table->foreignId('template_berkas_id')
                ->nullable()
                ->after('paket_soal_id')
                ->constrained('template_berkas')
                ->onDelete('set null')
                ->comment('ID template syarat berkas');
        });
    }

    public function down(): void
    {
        Schema::table('registration_paths', function (Blueprint $table) {
            $table->dropForeign(['template_berkas_id']);
            $table->dropColumn('template_berkas_id');
        });
    }
};