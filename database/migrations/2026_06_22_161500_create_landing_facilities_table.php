<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('landing_facilities', function (Blueprint $table) {
            $table->id();
            $table->string('nama_fasilitas', 255);
            $table->text('deskripsi_fasilitas')->nullable();
            $table->string('kode_icon', 100)->default('ti-wifi');
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_facilities');
    }
};