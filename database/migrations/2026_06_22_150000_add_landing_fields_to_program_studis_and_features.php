<?php

use App\Helpers\SchemaHelper;
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
            if (!SchemaHelper::hasColumn('program_studis', 'deskripsi_singkat')) {
                $table->text('deskripsi_singkat')->nullable()->after('jurusan');
            }
            if (!SchemaHelper::hasColumn('program_studis', 'akreditasi')) {
                $table->string('akreditasi', 50)->default('A')->after('deskripsi_singkat');
            }
            if (!SchemaHelper::hasColumn('program_studis', 'kode_icon')) {
                $table->string('kode_icon', 100)->default('ti-device-analytics')->after('akreditasi');
            }
        });

        // ─── Rename columns in landing_features using raw SQL/Schema ───
        if (DB::getDriverName() === 'sqlite') {
            Schema::table('landing_features', function (Blueprint $table) {
                if (SchemaHelper::hasColumn('landing_features', 'title')) {
                    $table->renameColumn('title', 'judul_poin');
                }
                if (SchemaHelper::hasColumn('landing_features', 'description')) {
                    $table->renameColumn('description', 'deskripsi_poin');
                }
                if (SchemaHelper::hasColumn('landing_features', 'icon')) {
                    $table->renameColumn('icon', 'nama_icon');
                }
            });
        } else {
            if (SchemaHelper::hasColumn('landing_features', 'title')) {
                DB::statement('ALTER TABLE landing_features CHANGE COLUMN title judul_poin VARCHAR(255) DEFAULT NULL');
            }
            if (SchemaHelper::hasColumn('landing_features', 'description')) {
                DB::statement('ALTER TABLE landing_features CHANGE COLUMN description deskripsi_poin TEXT DEFAULT NULL');
            }
            if (SchemaHelper::hasColumn('landing_features', 'icon')) {
                DB::statement('ALTER TABLE landing_features CHANGE COLUMN icon nama_icon VARCHAR(100) DEFAULT NULL');
            }
        }

        Schema::table('landing_features', function (Blueprint $table) {
            if (!SchemaHelper::hasColumn('landing_features', 'warna_skema')) {
                $table->string('warna_skema', 50)->default('danger');
            }
        });
    }

    public function down(): void
    {
        // Revert program_studis
        Schema::table('program_studis', function (Blueprint $table) {
            $columns = ['deskripsi_singkat', 'akreditasi', 'kode_icon'];
            foreach ($columns as $col) {
                if (SchemaHelper::hasColumn('program_studis', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        // Revert landing_features
        if (DB::getDriverName() === 'sqlite') {
            Schema::table('landing_features', function (Blueprint $table) {
                if (SchemaHelper::hasColumn('landing_features', 'judul_poin')) {
                    $table->renameColumn('judul_poin', 'title');
                }
                if (SchemaHelper::hasColumn('landing_features', 'deskripsi_poin')) {
                    $table->renameColumn('deskripsi_poin', 'description');
                }
                if (SchemaHelper::hasColumn('landing_features', 'nama_icon')) {
                    $table->renameColumn('nama_icon', 'icon');
                }
            });
        } else {
            if (SchemaHelper::hasColumn('landing_features', 'judul_poin')) {
                DB::statement('ALTER TABLE landing_features CHANGE COLUMN judul_poin title VARCHAR(255) DEFAULT NULL');
            }
            if (SchemaHelper::hasColumn('landing_features', 'deskripsi_poin')) {
                DB::statement('ALTER TABLE landing_features CHANGE COLUMN deskripsi_poin description TEXT DEFAULT NULL');
            }
            if (SchemaHelper::hasColumn('landing_features', 'nama_icon')) {
                DB::statement('ALTER TABLE landing_features CHANGE COLUMN nama_icon icon VARCHAR(100) DEFAULT NULL');
            }
        }

        Schema::table('landing_features', function (Blueprint $table) {
            if (SchemaHelper::hasColumn('landing_features', 'warna_skema')) {
                $table->dropColumn('warna_skema');
            }
        });
    }
};