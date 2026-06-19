<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registration_paths', function (Blueprint $table) {
            $table->string('metode_pengumuman', 20)->default('langsung')->after('template_berkas_id')
                  ->comment('Enum: langsung, ditahan');
            $table->boolean('gunakan_wawancara')->default(false)->after('metode_pengumuman')
                  ->comment('Apakah jalur ini menggunakan tahapan wawancara');
        });
    }

    public function down(): void
    {
        Schema::table('registration_paths', function (Blueprint $table) {
            $table->dropColumn(['metode_pengumuman', 'gunakan_wawancara']);
        });
    }
};