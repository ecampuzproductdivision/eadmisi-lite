<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Rename completed_steps to tutorials_progress for multi-tutorial support.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            Schema::table('user_onboarding_progress', function (Blueprint $table) {
                $table->renameColumn('completed_steps', 'tutorials_progress');
            });
        } else {
            DB::statement('ALTER TABLE user_onboarding_progress CHANGE COLUMN completed_steps tutorials_progress LONGTEXT DEFAULT NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            Schema::table('user_onboarding_progress', function (Blueprint $table) {
                $table->renameColumn('tutorials_progress', 'completed_steps');
            });
        } else {
            DB::statement('ALTER TABLE user_onboarding_progress CHANGE COLUMN tutorials_progress completed_steps LONGTEXT DEFAULT NULL');
        }
    }
};
