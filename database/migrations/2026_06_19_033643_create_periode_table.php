<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Create the 'periode' (Academic Period) master table.
     * Only ONE period can be active at any time (status_aktif = true).
     */
    public function up(): void
    {
        Schema::create('periode', function (Blueprint $table) {
            $table->id();
            $table->string('tahun_akademik', 20)->comment('Contoh: "2026/2027"');
            $table->enum('semester', ['Ganjil', 'Genap', 'Pendek'])->comment('Periode semester');
            $table->boolean('status_aktif')->default(false)->comment('Hanya satu periode yang boleh aktif');
            $table->timestamps();

            // Ensure unique combo of tahun_akademik + semester
            $table->unique(['tahun_akademik', 'semester'], 'periode_unique_tahun_semester');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periode');
    }
};