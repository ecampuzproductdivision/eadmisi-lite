<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Pivot table for many-to-many relationship between registration_paths and program_studis.
     */
    public function up(): void
    {
        Schema::create('jalur_prodi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_path_id')
                ->constrained('registration_paths')
                ->onDelete('cascade')
                ->comment('ID jalur pendaftaran');
            $table->foreignId('program_studi_id')
                ->constrained('program_studis')
                ->onDelete('cascade')
                ->comment('ID program studi');
            $table->timestamps();

            // Prevent duplicate pairs
            $table->unique(['registration_path_id', 'program_studi_id'], 'jalur_prodi_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jalur_prodi');
    }
};