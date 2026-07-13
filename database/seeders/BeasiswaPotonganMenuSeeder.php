<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Page;
use App\Models\Role;
use App\Models\RolePagePermission;
use Illuminate\Database\Seeder;

class BeasiswaPotonganMenuSeeder extends Seeder
{
    public function run(): void
    {
        $settings = Menu::where('menu_code', 'SETTINGS')->first();

        if (!$settings) {
            $this->command->error('Settings parent menu not found!');
            return;
        }

        // Check if menu already exists
        $existing = Menu::where('menu_code', 'SETTINGS_BEASISWA_POTONGAN')->first();
        if ($existing) {
            $this->command->info('Beasiswa & Potongan menu already exists.');
            return;
        }

        // Get max sort order to place it at the end of the Settings sub-menu
        $sortOrder = Menu::where('parent_id', $settings->id)->max('sort_order') + 1;

        // Create Menu
        $menu = Menu::create([
            'menu_name'  => 'Beasiswa & Potongan',
            'menu_code'  => 'SETTINGS_BEASISWA_POTONGAN',
            'parent_id'  => $settings->id,
            'icon'       => 'ti ti-gift',
            'url'        => '/settings/beasiswa-potongan',
            'sort_order' => $sortOrder,
            'is_active'  => true,
        ]);

        $this->command->info("Beasiswa & Potongan menu created (sort_order: {$sortOrder})");

        // Create Page record
        $page = Page::create([
            'page_name'  => 'Beasiswa & Potongan',
            'page_code'  => 'SETTINGS_BEASISWA_POTONGAN',
            'url'        => '/settings/beasiswa-potongan',
            'menu_id'    => $menu->id,
            'icon'       => 'ti ti-gift',
            'sort_order' => 1,
            'is_active'  => true,
        ]);

        $this->command->info("Beasiswa & Potongan page created (ID: {$page->id})");

        // Grant permissions to all roles (Super Admin, Admin, BAA, Keuangan, etc.)
        $roles = Role::all();
        foreach ($roles as $role) {
            // Calon Mahasiswa role shouldn't access admin settings
            if ($role->role_code === 'CALON_MAHASISWA') {
                continue;
            }
            RolePagePermission::updateOrCreate(
                ['role_id' => $role->id, 'page_id' => $page->id],
                ['can_view' => true, 'can_create' => true, 'can_edit' => true, 'can_delete' => true]
            );
        }

        $this->command->info('Permissions granted to admin roles.');
        $this->command->info('BeasiswaPotonganMenuSeeder completed!');
    }
}
