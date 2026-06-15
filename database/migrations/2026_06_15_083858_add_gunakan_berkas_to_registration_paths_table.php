<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registration_paths', function (Blueprint $table) {
            $table->boolean('gunakan_berkas')->default(false)->after('paket_soal_id')
                ->comment('Toggle untuk mengaktifkan upload berkas');
        });
    }

    public function down(): void
    {
        Schema::table('registration_paths', function (Blueprint $table) {
            $table->dropColumn('gunakan_berkas');
        });
    }
};