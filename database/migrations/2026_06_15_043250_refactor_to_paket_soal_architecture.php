<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Refactor: Add paket_soal_id to soal_ujian and registration_paths, drop jalur_soal pivot.
     */
    public function up(): void
    {
        // 1. Add paket_soal_id to soal_ujian
        Schema::table('soal_ujian', function (Blueprint $table) {
            $table->foreignId('paket_soal_id')
                ->nullable()
                ->after('id')
                ->constrained('paket_soal')
                ->onDelete('cascade')
                ->comment('ID paket soal induk');
        });

        // 2. Add paket_soal_id to registration_paths (replace many-to-many pivot)
        Schema::table('registration_paths', function (Blueprint $table) {
            $table->foreignId('paket_soal_id')
                ->nullable()
                ->after('gunakan_ujian')
                ->constrained('paket_soal')
                ->onDelete('set null')
                ->comment('ID paket soal yang digunakan (jika menggunakan ujian)');
        });

        // 3. Drop jalur_soal pivot table (old many-to-many relationship)
        Schema::dropIfExists('jalur_soal');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate jalur_soal pivot table
        Schema::create('jalur_soal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_path_id')
                ->constrained('registration_paths')
                ->onDelete('cascade')
                ->comment('ID jalur pendaftaran');
            $table->foreignId('soal_ujian_id')
                ->constrained('soal_ujian')
                ->onDelete('cascade')
                ->comment('ID soal ujian');
            $table->timestamps();
            $table->unique(['registration_path_id', 'soal_ujian_id'], 'jalur_soal_unique');
        });

        // Remove paket_soal_id from registration_paths
        Schema::table('registration_paths', function (Blueprint $table) {
            $table->dropForeign(['paket_soal_id']);
            $table->dropColumn('paket_soal_id');
        });

        // Remove paket_soal_id from soal_ujian
        Schema::table('soal_ujian', function (Blueprint $table) {
            $table->dropForeign(['paket_soal_id']);
            $table->dropColumn('paket_soal_id');
        });
    }
};