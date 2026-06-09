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
        DB::table('menus')->where('menu_code', 'PMB')->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $existing = DB::table('menus')->where('menu_code', 'PMB')->first();
        if (!$existing) {
            DB::table('menus')->insert([
                'menu_name' => 'PMB',
                'menu_code' => 'PMB',
                'parent_id' => null,
                'icon' => 'ti-user-plus',
                'url' => '#',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
};
