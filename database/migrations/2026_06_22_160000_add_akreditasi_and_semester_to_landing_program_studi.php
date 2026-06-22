<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('landing_program_studi', function (Blueprint $table) {
            $table->string('akreditasi', 50)->nullable()->after('kode_icon');
            $table->integer('jumlah_semester')->nullable()->after('akreditasi');
        });
    }

    public function down(): void
    {
        Schema::table('landing_program_studi', function (Blueprint $table) {
            $table->dropColumn(['akreditasi', 'jumlah_semester']);
        });
    }
};