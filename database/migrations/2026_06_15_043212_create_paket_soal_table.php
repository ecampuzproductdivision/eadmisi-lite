<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Create parent table for exam question packages.
     */
    public function up(): void
    {
        Schema::create('paket_soal', function (Blueprint $table) {
            $table->id();
            $table->string('nama_paket', 200)->comment('Nama paket soal');
            $table->text('deskripsi')->nullable()->comment('Deskripsi paket soal');
            $table->boolean('status_aktif')->default(true)->comment('Status aktif paket');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_soal');
    }
};