<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run raw SQL directly to avoid Schema::hasColumn() and renameColumn()
     * which use information_schema.columns.generation_expression (not supported in MariaDB 10.1)
     */
    public function up(): void
    {
        // ─── Add landing-related columns to program_studis ───
        $colsProdi = DB::select("SHOW COLUMNS FROM program_studis");
        $prodiFields = array_column($colsProdi, 'Field');

        if (!in_array('deskripsi_singkat', $prodiFields)) {
            DB::statement("ALTER TABLE program_studis ADD COLUMN deskripsi_singkat TEXT NULL AFTER jurusan");
        }
        if (!in_array('akreditasi', $prodiFields)) {
            DB::statement("ALTER TABLE program_studis ADD COLUMN akreditasi VARCHAR(50) NOT NULL DEFAULT 'A' AFTER deskripsi_singkat");
        }
        if (!in_array('kode_icon', $prodiFields)) {
            DB::statement("ALTER TABLE program_studis ADD COLUMN kode_icon VARCHAR(100) NOT NULL DEFAULT 'ti-device-analytics' AFTER akreditasi");
        }

        // ─── Rename columns in landing_features ───
        $colsFeat = DB::select("SHOW COLUMNS FROM landing_features");
        $featFields = array_column($colsFeat, 'Field');

        if (in_array('title', $featFields)) {
            DB::statement("ALTER TABLE landing_features CHANGE COLUMN title judul_poin VARCHAR(255)");
        }
        if (in_array('description', $featFields)) {
            DB::statement("ALTER TABLE landing_features CHANGE COLUMN description deskripsi_poin TEXT");
        }
        if (in_array('icon', $featFields)) {
            DB::statement("ALTER TABLE landing_features CHANGE COLUMN icon nama_icon VARCHAR(255)");
        }
        if (!in_array('warna_skema', $featFields)) {
            DB::statement("ALTER TABLE landing_features ADD COLUMN warna_skema VARCHAR(50) NOT NULL DEFAULT 'danger' AFTER nama_icon");
        }
    }

    public function down(): void
    {
        // Revert program_studis
        $colsProdi = DB::select("SHOW COLUMNS FROM program_studis");
        $prodiFields = array_column($colsProdi, 'Field');
        $dropProdi = array_intersect(['deskripsi_singkat', 'akreditasi', 'kode_icon'], $prodiFields);
        if (!empty($dropProdi)) {
            DB::statement("ALTER TABLE program_studis DROP COLUMN " . implode(', DROP COLUMN ', $dropProdi));
        }

        // Revert landing_features
        $colsFeat = DB::select("SHOW COLUMNS FROM landing_features");
        $featFields = array_column($colsFeat, 'Field');

        if (in_array('judul_poin', $featFields)) {
            DB::statement("ALTER TABLE landing_features CHANGE COLUMN judul_poin title VARCHAR(255)");
        }
        if (in_array('deskripsi_poin', $featFields)) {
            DB::statement("ALTER TABLE landing_features CHANGE COLUMN deskripsi_poin description TEXT");
        }
        if (in_array('nama_icon', $featFields)) {
            DB::statement("ALTER TABLE landing_features CHANGE COLUMN nama_icon icon VARCHAR(255)");
        }
        if (in_array('warna_skema', $featFields)) {
            DB::statement("ALTER TABLE landing_features DROP COLUMN warna_skema");
        }
    }
};
