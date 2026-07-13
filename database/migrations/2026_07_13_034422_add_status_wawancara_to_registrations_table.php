<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            if (!Schema::hasColumn('registrations', 'status_wawancara')) {
                $table->string('status_wawancara', 50)->nullable()->after('status_registrasi_ulang')
                    ->comment('menunggu_penjadwalan_wawancara|menunggu_wawancara|null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn('status_wawancara');
        });
    }
};