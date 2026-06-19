<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wawancara', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')
                ->constrained('registrations')
                ->onDelete('cascade')
                ->comment('ID pendaftaran (calon mahasiswa)');
            $table->date('tanggal_wawancara')->nullable()->comment('Tanggal wawancara');
            $table->time('jam_wawancara')->nullable()->comment('Jam wawancara');
            $table->string('lokasi_wawancara', 255)->nullable()->comment('Lokasi / virtual link wawancara');
            $table->enum('status_wawancara', ['Belum Wawancara', 'Lolos', 'Tidak Lolos'])
                ->default('Belum Wawancara')
                ->comment('Status hasil wawancara');
            $table->text('catatan_pewawancara')->nullable()->comment('Catatan dari pewawancara');
            $table->timestamps();

            // One registration can only have one wawancara record
            $table->unique('pendaftaran_id', 'wawancara_pendaftaran_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wawancara');
    }
};