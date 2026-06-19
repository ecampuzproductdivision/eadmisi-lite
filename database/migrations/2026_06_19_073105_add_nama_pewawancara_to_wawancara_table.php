<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wawancara', function (Blueprint $table) {
            $table->string('nama_pewawancara', 255)->nullable()->after('lokasi_wawancara')
                  ->comment('Nama dosen/tim pewawancara (string teks bebas)');
        });
    }

    public function down(): void
    {
        Schema::table('wawancara', function (Blueprint $table) {
            $table->dropColumn('nama_pewawancara');
        });
    }
};