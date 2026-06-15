<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('syarat_dokumen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_berkas_id')
                ->constrained('template_berkas')
                ->onDelete('cascade')
                ->comment('ID template induk');
            $table->string('nama_dokumen', 200)->comment('Nama dokumen, contoh: KTP, Ijazah');
            $table->string('ekstensi_diizinkan', 255)->default('PDF,PNG,JPG,JPEG')
                ->comment('Format file yang diizinkan, dipisahkan koma');
            $table->integer('max_size')->default(2048)->comment('Ukuran maksimal dalam KB');
            $table->boolean('status_wajib')->default(true)->comment('true=wajib, false=opsional');
            $table->integer('urutan')->default(0)->comment('Urutan tampil');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('syarat_dokumen');
    }
};