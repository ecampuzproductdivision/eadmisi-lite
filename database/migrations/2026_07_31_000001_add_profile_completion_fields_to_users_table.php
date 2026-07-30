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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_profile_completed')) {
                $table->boolean('is_profile_completed')->default(false)->after('status');
            }
            if (!Schema::hasColumn('users', 'jenis_kelamin')) {
                $table->string('jenis_kelamin', 10)->nullable()->after('is_profile_completed');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'is_profile_completed')) {
                $table->dropColumn('is_profile_completed');
            }
            if (Schema::hasColumn('users', 'jenis_kelamin')) {
                $table->dropColumn('jenis_kelamin');
            }
        });
    }
};
