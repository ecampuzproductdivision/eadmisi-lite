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

        // ─── Rename columns in landing_features ───
        // Drop old columns and add new ones (compatible with all databases)
        Schema::table('landing_features', function (Blueprint $table) {
            if (Schema::hasColumn('landing_features', 'title')) {
                $table->renameColumn('title', 'judul_poin');
            }
            if (Schema::hasColumn('landing_features', 'description')) {
                $table->renameColumn('description', 'deskripsi_poin');
            }
            if (Schema::hasColumn('landing_features', 'icon')) {
                $table->renameColumn('icon', 'nama_icon');
            }
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
        Schema::table('landing_features', function (Blueprint $table) {
            if (Schema::hasColumn('landing_features', 'warna_skema')) {
                $table->dropColumn('warna_skema');
            }
            if (Schema::hasColumn('landing_features', 'judul_poin')) {
                $table->renameColumn('judul_poin', 'title');
            }
            if (Schema::hasColumn('landing_features', 'deskripsi_poin')) {
                $table->renameColumn('deskripsi_poin', 'description');
            }
            if (Schema::hasColumn('landing_features', 'nama_icon')) {
                $table->renameColumn('nama_icon', 'icon');
            }
        });
    }
};