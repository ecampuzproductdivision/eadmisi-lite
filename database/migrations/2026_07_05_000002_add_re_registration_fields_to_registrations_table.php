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
        Schema::table('registrations', function (Blueprint $table) {
            // Alter status column to string to support long status strings
            $table->string('status', 50)->default('draft')->change();

            // Add new re-registration fields
            $table->string('nisn', 10)->nullable()->after('nik');
            $table->string('nama_ibu_kandung', 255)->nullable()->after('nisn');
            $table->string('penerima_kps', 10)->nullable()->after('nama_ibu_kandung');
            $table->string('kebutuhan_khusus', 10)->nullable()->after('penerima_kps');
            $table->string('kewarganegaraan', 100)->nullable()->after('kebutuhan_khusus');
            
            $table->foreignId('regency_id')->nullable()->after('kewarganegaraan')->constrained('regencies')->onDelete('set null');
            $table->foreignId('kecamatan_id')->nullable()->after('regency_id')->constrained('kecamatans')->onDelete('set null');
            $table->foreignId('kelurahan_id')->nullable()->after('kecamatan_id')->constrained('kelurahans')->onDelete('set null');

            $table->string('nim', 20)->nullable()->after('status');
            $table->timestamp('re_registration_submitted_at')->nullable()->after('nim');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropForeign(['kelurahan_id']);
            $table->dropForeign(['kecamatan_id']);
            $table->dropForeign(['regency_id']);
            
            $table->dropColumn([
                'nisn',
                'nama_ibu_kandung',
                'penerima_kps',
                'kebutuhan_khusus',
                'kewarganegaraan',
                'regency_id',
                'kecamatan_id',
                'kelurahan_id',
                'nim',
                're_registration_submitted_at'
            ]);

            // Revert status to enum
            $table->enum('status', ['draft', 'submitted', 'reviewed', 'accepted', 'rejected'])->default('draft')->change();
        });
    }
};
