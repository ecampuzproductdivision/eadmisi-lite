<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop existing tables cleanly (they only have dummy/empty data)
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('kelurahans');
        Schema::dropIfExists('kecamatans');
        Schema::dropIfExists('kabupatens');
        Schema::enableForeignKeyConstraints();

        // 1. Create kabupatens table
        Schema::create('kabupatens', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kabupaten', 255);
            $table->timestamps();
        });

        // 2. Create kecamatans table (fresh)
        Schema::create('kecamatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kabupaten_id')->constrained('kabupatens')->onDelete('cascade');
            $table->string('nama_kecamatan', 255);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Create kelurahans table (fresh)
        Schema::create('kelurahans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kecamatan_id')->constrained('kecamatans')->onDelete('cascade');
            $table->string('nama_kelurahan', 255);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('kelurahans');
        Schema::dropIfExists('kecamatans');
        Schema::dropIfExists('kabupatens');
        Schema::enableForeignKeyConstraints();
    }
};