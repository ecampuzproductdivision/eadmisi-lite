<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Create global master bank soal (decoupled from jalur).
     */
    public function up(): void
    {
        Schema::create('soal_ujian', function (Blueprint $table) {
            $table->id();
            $table->text('pertanyaan')->comment('Teks pertanyaan');
            $table->string('opsi_a', 255);
            $table->string('opsi_b', 255);
            $table->string('opsi_c', 255);
            $table->string('opsi_d', 255);
            $table->string('kunci_jawaban', 1)->comment('A, B, C, atau D');
            $table->integer('skor')->default(0)->comment('Skor untuk soal ini');
            $table->integer('urutan')->default(0)->comment('Urutan tampil soal');
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soal_ujian');
    }
};