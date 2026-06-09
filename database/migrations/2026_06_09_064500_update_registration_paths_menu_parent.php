<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('menus')
            ->where('menu_code', 'REGISTRATION_PATHS')
            ->update(['parent_id' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $parentMenu = DB::table('menus')->where('menu_code', 'PMB')->first();
        if ($parentMenu) {
            DB::table('menus')
                ->where('menu_code', 'REGISTRATION_PATHS')
                ->update(['parent_id' => $parentMenu->id]);
        }
    }
};
