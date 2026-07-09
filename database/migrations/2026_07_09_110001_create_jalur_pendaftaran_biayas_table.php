<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jalur_pendaftaran_biayas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_path_id')->constrained()->cascadeOnDelete();
            $table->foreignId('komponen_biaya_id')->constrained('komponen_biayas')->cascadeOnDelete();
            $table->bigInteger('nominal')->default(0);
            $table->timestamps();

            $table->unique(['registration_path_id', 'komponen_biaya_id'], 'jalur_komponen_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jalur_pendaftaran_biayas');
    }
};