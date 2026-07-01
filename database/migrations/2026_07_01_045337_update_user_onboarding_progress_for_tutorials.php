<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Rename completed_steps to tutorials_progress for multi-tutorial support.
     */
    public function up(): void
    {
        Schema::table('user_onboarding_progress', function (Blueprint $table) {
            $table->renameColumn('completed_steps', 'tutorials_progress');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_onboarding_progress', function (Blueprint $table) {
            $table->renameColumn('tutorials_progress', 'completed_steps');
        });
    }
};