<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Pivot table for many-to-many between registration_paths and soal_ujian.
     */
    public function up(): void
    {
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jalur_soal');
    }
};