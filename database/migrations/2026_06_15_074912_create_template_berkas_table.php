<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_berkas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_template', 200)->comment('Nama template syarat berkas');
            $table->text('deskripsi')->nullable()->comment('Deskripsi template');
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_berkas');
    }
};