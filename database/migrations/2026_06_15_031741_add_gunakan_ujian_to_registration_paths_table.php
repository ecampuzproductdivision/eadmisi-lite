<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add gunakan_ujian toggle to registration_paths.
     */
    public function up(): void
    {
        Schema::table('registration_paths', function (Blueprint $table) {
            $table->boolean('gunakan_ujian')->default(false)->after('is_active')
                ->comment('Aktifkan ujian online untuk jalur ini');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registration_paths', function (Blueprint $table) {
            $table->dropColumn('gunakan_ujian');
        });
    }
};