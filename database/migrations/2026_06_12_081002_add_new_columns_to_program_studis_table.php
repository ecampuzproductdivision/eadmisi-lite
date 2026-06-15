<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('program_studis', function (Blueprint $table) {
            // Add new columns alongside existing ones
            $table->string('kode_prodi', 20)->nullable()->after('id');
            $table->string('label_nim', 50)->nullable()->after('kode_prodi');
            $table->string('nama_prodi', 200)->nullable()->after('label_nim');
            $table->string('jurusan', 200)->nullable()->after('nama_prodi');
            $table->string('jenjang_akademik', 10)->nullable()->after('jurusan'); // S1, D3, D4, S2, S3
            $table->string('kelompok', 20)->default('Eksakta')->after('jenjang_akademik'); // Eksakta / Non Eksakta
            $table->string('program', 50)->default('Reguler')->after('kelompok'); // Reguler, Karyawan
            $table->string('label_prodi_no_pendaftaran', 50)->nullable()->after('program');
            $table->boolean('status_aktif')->default(true)->after('label_prodi_no_pendaftaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_studis', function (Blueprint $table) {
            $table->dropColumn([
                'kode_prodi',
                'label_nim',
                'nama_prodi',
                'jurusan',
                'jenjang_akademik',
                'kelompok',
                'program',
                'label_prodi_no_pendaftaran',
                'status_aktif',
            ]);
        });
    }
};