<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('landing_program_studi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_studi_id')->constrained('program_studis')->cascadeOnDelete();
            $table->text('deskripsi_singkat')->nullable();
            $table->string('kode_icon', 100)->default('ti-device-analytics');
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_program_studi');
    }
};