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
        Schema::table('registration_paths', function (Blueprint $table) {
            $table->string('jenis_pendaftaran', 100)->nullable()->after('kategori_jalur_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registration_paths', function (Blueprint $table) {
            $table->dropColumn('jenis_pendaftaran');
        });
    }
};