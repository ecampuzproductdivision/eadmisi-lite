<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuCategorySeeder extends Seeder
{
    /**
     * Mapping of menu_code => category
     * - null means no category (like Home)
     * - Child menus inherit parent's category (handled separately)
     */
    private array $categoryMap = [
        // Home - no category (always on top)
        'HOME' => null,

        // MASTER DATA
        'MASTER_PROGRAM_STUDI' => 'MASTER_DATA',
        'REGISTRATION_PATHS' => 'MASTER_DATA',
        'SYARAT_BERKAS' => 'MASTER_DATA',
        'PAKET_SOAL' => 'MASTER_DATA',
        'KOMPONEN_BIAYA' => 'MASTER_DATA',
        'SETTINGS_PERIODE' => 'MASTER_DATA',

        // TRANSACTION
        'PENDAFTARAN' => 'TRANSACTION',
        'REGISTRASI_ULANG' => 'TRANSACTION',
        'TAGIHAN_ADMIN' => 'TRANSACTION',
        'KELOLA_WAWANCARA' => 'TRANSACTION',
        'CRM_LEADS' => 'TRANSACTION',
        'DAFTAR_PMB' => 'TRANSACTION',

        // Calon Mahasiswa menus (TRANSACTION)
        'RIWAYAT_PENDAFTARAN' => 'TRANSACTION',
        'TAGIHAN_CALON' => 'TRANSACTION',
        'TES_ONLINE_CALON' => 'TRANSACTION',

        // SETTINGS (parent and children)
        'SETTINGS' => 'SETTINGS',
        'SETTINGS_USERS' => 'SETTINGS',
        'SETTINGS_ROLES' => 'SETTINGS',
        'SETTINGS_MENUS' => 'SETTINGS',
        'SETTINGS_PAGES' => 'SETTINGS',
        'SETTINGS_PERMISSIONS' => 'SETTINGS',
        'SETTINGS_LOGS' => 'SETTINGS',
        'SETTINGS_FORM_PENDAFTARAN' => 'SETTINGS',
        'SETTINGS_BEASISWA_POTONGAN' => 'SETTINGS',
        'LANDING_PAGE' => 'SETTINGS',
    ];

    public function run(): void
    {
        $updated = 0;
        $skipped = 0;

        foreach ($this->categoryMap as $menuCode => $category) {
            $affected = DB::table('menus')
                ->where('menu_code', $menuCode)
                ->update(['category' => $category]);

            if ($affected > 0) {
                $updated += $affected;
                $this->command->info("Set category '{$category}' for menu_code '{$menuCode}' ({$affected} row(s)).");
            } else {
                $skipped++;
                $this->command->warn("Menu with menu_code '{$menuCode}' not found, skipped.");
            }
        }

        // Also update child menus that inherit from their parent
        $parentMenus = DB::table('menus')
            ->whereIn('menu_code', array_keys($this->categoryMap))
            ->whereNotNull('category')
            ->get();

        foreach ($parentMenus as $parent) {
            $childUpdated = DB::table('menus')
                ->where('parent_id', $parent->id)
                ->whereNull('category')
                ->update(['category' => $parent->category]);

            if ($childUpdated > 0) {
                $this->command->info("Inherited category '{$parent->category}' to {$childUpdated} child menu(s) of '{$parent->menu_code}'.");
                $updated += $childUpdated;
            }
        }

        $this->command->info("MenuCategorySeeder completed! Total updated: {$updated}, skipped: {$skipped}.");
    }
}