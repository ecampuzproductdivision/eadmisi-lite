<?php

use App\Helpers\SchemaHelper;
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
        Schema::table('program_studis', function (Blueprint $table) {
            if (!SchemaHelper::hasColumn('program_studis', 'passing_grade')) {
                $table->integer('passing_grade')->nullable()->after('status_aktif')
                      ->comment('Nilai passing grade / ambang batas spesifik prodi (0-100)');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_studis', function (Blueprint $table) {
            if (SchemaHelper::hasColumn('program_studis', 'passing_grade')) {
                $table->dropColumn('passing_grade');
            }
        });
    }
};
