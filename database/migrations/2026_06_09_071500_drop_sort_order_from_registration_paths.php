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
        $hasSortOrder = false;
        try {
            $hasSortOrder = Schema::hasColumn('registration_paths', 'sort_order');
        } catch (\Throwable $e) {
            $columns = Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM registration_paths");
            foreach ($columns as $column) {
                if ($column->Field === 'sort_order') {
                    $hasSortOrder = true;
                    break;
                }
            }
        }

        if ($hasSortOrder) {
            Schema::table('registration_paths', function (Blueprint $table) {
                $table->dropColumn('sort_order');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $hasSortOrder = false;
        try {
            $hasSortOrder = Schema::hasColumn('registration_paths', 'sort_order');
        } catch (\Throwable $e) {
            $columns = Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM registration_paths");
            foreach ($columns as $column) {
                if ($column->Field === 'sort_order') {
                    $hasSortOrder = true;
                    break;
                }
            }
        }

        if (!$hasSortOrder) {
            Schema::table('registration_paths', function (Blueprint $table) {
                $table->integer('sort_order')->default(0);
            });
        }
    }
};
