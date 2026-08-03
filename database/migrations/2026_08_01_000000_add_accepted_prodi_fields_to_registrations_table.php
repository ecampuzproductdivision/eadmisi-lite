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
        Schema::table('registrations', function (Blueprint $table) {
            if (!SchemaHelper::hasColumn('registrations', 'program_studi_3_id')) {
                $table->foreignId('program_studi_3_id')->nullable()->after('program_studi_2_id')->constrained('program_studis')->onDelete('set null');
            }
            if (!SchemaHelper::hasColumn('registrations', 'accepted_program_studi_id')) {
                $table->foreignId('accepted_program_studi_id')->nullable()->after('program_studi_3_id')->constrained('program_studis')->onDelete('set null');
            }
            if (!SchemaHelper::hasColumn('registrations', 'accepted_pilihan_ke')) {
                $table->unsignedTinyInteger('accepted_pilihan_ke')->nullable()->after('accepted_program_studi_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            if (SchemaHelper::hasColumn('registrations', 'accepted_pilihan_ke')) {
                $table->dropColumn('accepted_pilihan_ke');
            }
            if (SchemaHelper::hasColumn('registrations', 'accepted_program_studi_id')) {
                $table->dropForeign(['accepted_program_studi_id']);
                $table->dropColumn('accepted_program_studi_id');
            }
            if (SchemaHelper::hasColumn('registrations', 'program_studi_3_id')) {
                $table->dropForeign(['program_studi_3_id']);
                $table->dropColumn('program_studi_3_id');
            }
        });
    }
};
