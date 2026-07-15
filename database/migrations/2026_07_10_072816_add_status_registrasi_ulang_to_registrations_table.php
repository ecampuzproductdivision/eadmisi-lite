<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->string('status_registrasi_ulang', 50)->nullable()->after('status_pendaftaran')
                ->comment('belum_registrasi|menunggu_pembayaran|sudah_registrasi_no_tagihan|sudah_registrasi_lunas');
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn('status_registrasi_ulang');
        });
    }
};
