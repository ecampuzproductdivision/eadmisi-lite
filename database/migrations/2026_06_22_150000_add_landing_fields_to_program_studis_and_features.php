<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ─── Add landing-related columns to program_studis ───
        Schema::table('program_studis', function (Blueprint $table) {
            $table->text('deskripsi_singkat')->nullable()->after('jurusan');
            $table->string('akreditasi', 50)->default('A')->after('deskripsi_singkat');
            $table->string('kode_icon', 100)->default('ti-device-analytics')->after('akreditasi');
        });

        // ─── Rename columns in landing_features using raw SQL ───
        // Use raw SQL to avoid MySQL/MariaDB version compatibility issues
        // with information_schema.columns generation_expression
        DB::statement('ALTER TABLE landing_features CHANGE COLUMN title judul_poin VARCHAR(255) DEFAULT NULL');
        DB::statement('ALTER TABLE landing_features CHANGE COLUMN description deskripsi_poin TEXT DEFAULT NULL');
        DB::statement('ALTER TABLE landing_features CHANGE COLUMN icon nama_icon VARCHAR(100) DEFAULT NULL');
        Schema::table('landing_features', function (Blueprint $table) {
            $table->string('warna_skema', 50)->default('danger');
        });
    }

    public function down(): void
    {
        // Revert program_studis
        Schema::table('program_studis', function (Blueprint $table) {
            $columns = ['deskripsi_singkat', 'akreditasi', 'kode_icon'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('program_studis', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        // Revert landing_features
        // Use raw SQL for rename to avoid MySQL/MariaDB version compatibility issues
        DB::statement('ALTER TABLE landing_features CHANGE COLUMN judul_poin title VARCHAR(255) DEFAULT NULL');
        DB::statement('ALTER TABLE landing_features CHANGE COLUMN deskripsi_poin description TEXT DEFAULT NULL');
        DB::statement('ALTER TABLE landing_features CHANGE COLUMN nama_icon icon VARCHAR(100) DEFAULT NULL');
        Schema::table('landing_features', function (Blueprint $table) {
            if (Schema::hasColumn('landing_features', 'warna_skema')) {
                $table->dropColumn('warna_skema');
            }
        });
    }
};