<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Move "Master Program Studi" (sort_order: 16) above "Jalur Pendaftaran" (sort_order: 11)
     * by setting Master Program Studi to sort_order 11, and incrementing Jalur Pendaftaran
     * and all following menus by 1.
     */
    public function up(): void
    {
        // Get current menus
        $masterProdi = DB::table('menus')->where('menu_code', 'MASTER_PROGRAM_STUDI')->first();
        $jalurPendaftaran = DB::table('menus')->where('menu_code', 'REGISTRATION_PATHS')->first();

        if (!$masterProdi || !$jalurPendaftaran) {
            return;
        }

        $targetSortOrder = $jalurPendaftaran->sort_order; // 11

        // Shift Jalur Pendaftaran and all menus after it down by 1
        DB::table('menus')
            ->where('sort_order', '>=', $targetSortOrder)
            ->where('id', '!=', $masterProdi->id)
            ->increment('sort_order');

        // Move Master Program Studi to position 11
        DB::table('menus')
            ->where('id', $masterProdi->id)
            ->update(['sort_order' => $targetSortOrder]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Get current menus (reversed logic)
        $masterProdi = DB::table('menus')->where('menu_code', 'MASTER_PROGRAM_STUDI')->first();
        $jalurPendaftaran = DB::table('menus')->where('menu_code', 'REGISTRATION_PATHS')->first();

        if (!$masterProdi || !$jalurPendaftaran) {
            return;
        }

        // Move Master Program Studi back to its original position after Jalur Pendaftaran
        $originalSortOrder = $jalurPendaftaran->sort_order;

        // Remove Master Program Studi from its current position
        DB::table('menus')
            ->where('id', $masterProdi->id)
            ->update(['sort_order' => 999]);

        // Shift Jalur Pendaftaran and everything before it back up
        DB::table('menus')
            ->where('sort_order', '>=', $originalSortOrder)
            ->where('id', '!=', $masterProdi->id)
            ->decrement('sort_order');

        // Put Master Program Studi at the end (original position)
        DB::table('menus')
            ->where('id', $masterProdi->id)
            ->update(['sort_order' => 16]);
    }
};