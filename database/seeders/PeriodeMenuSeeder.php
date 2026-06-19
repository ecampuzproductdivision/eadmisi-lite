<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Page;
use App\Models\Role;
use App\Models\RolePagePermission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seed the Periode menu under Settings, above Form Pendaftaran.
 * This must run AFTER other seeders that create the Settings menu.
 */
class PeriodeMenuSeeder extends Seeder
{
    public function run(): void
    {
        $settings = Menu::where('menu_code', 'SETTINGS')->first();

        if (!$settings) {
            $this->command->error('Settings menu not found!');
            return;
        }

        // Check if Periode menu already exists
        $existing = Menu::where('menu_code', 'SETTINGS_PERIODE')->first();
        if ($existing) {
            $this->command->info('Periode menu already exists.');
            return;
        }

        // Find "Form Pendaftaran" child to insert before it
        $formPendaftaran = Menu::where('parent_id', $settings->id)
            ->where('menu_code', 'SETTINGS_FORM_PENDAFTARAN')
            ->first();

        $sortOrder = $formPendaftaran ? $formPendaftaran->sort_order : 7;

        // Shift menus down by 1
        Menu::where('parent_id', $settings->id)
            ->where('sort_order', '>=', $sortOrder)
            ->increment('sort_order');

        // Create Periode menu
        $menu = Menu::create([
            'menu_name'  => 'Periode',
            'menu_code'  => 'SETTINGS_PERIODE',
            'parent_id'  => $settings->id,
            'icon'       => 'ti ti-calendar-stats',
            'url'        => '/settings/periode',
            'sort_order' => $sortOrder,
            'is_active'  => true,
        ]);

        $this->command->info("Periode menu created (sort_order: {$sortOrder})");

        // Create Page record
        $page = Page::create([
            'page_name'  => 'Periode',
            'page_code'  => 'SETTINGS_PERIODE',
            'url'        => '/settings/periode',
            'menu_id'    => $menu->id,
            'icon'       => 'ti ti-calendar-stats',
            'sort_order' => 1,
            'is_active'  => true,
        ]);

        $this->command->info("Periode page created (ID: {$page->id})");

        // Grant permissions to all roles
        $roles = Role::all();
        foreach ($roles as $role) {
            RolePagePermission::updateOrCreate(
                ['role_id' => $role->id, 'page_id' => $page->id],
                ['can_view' => true, 'can_create' => true, 'can_edit' => true, 'can_delete' => true]
            );
        }

        $this->command->info('Permissions granted to ' . $roles->count() . ' roles.');
        $this->command->info('PeriodeMenuSeeder completed!');
    }
}