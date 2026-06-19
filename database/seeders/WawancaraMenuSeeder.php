<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Page;
use App\Models\Role;
use App\Models\RolePagePermission;
use Illuminate\Database\Seeder;

class WawancaraMenuSeeder extends Seeder
{
    public function run(): void
    {
        // Check if menu already exists
        $existing = Menu::where('menu_code', 'KELOLA_WAWANCARA')->first();
        if ($existing) {
            $this->command->info('Wawancara menu already exists.');
            return;
        }

        // Create as root menu item (top level)
        $menu = Menu::create([
            'menu_name'  => 'Kelola Wawancara',
            'menu_code'  => 'KELOLA_WAWANCARA',
            'parent_id'  => null,
            'icon'       => 'ti ti-message-dots',
            'url'        => '/settings/wawancara',
            'sort_order' => 17,
            'is_active'  => true,
        ]);

        $this->command->info("Wawancara menu created (ID: {$menu->id})");

        // Create Page record
        $page = Page::create([
            'page_name'  => 'Kelola Wawancara',
            'page_code'  => 'KELOLA_WAWANCARA',
            'url'        => '/settings/wawancara',
            'menu_id'    => $menu->id,
            'icon'       => 'ti ti-message-dots',
            'sort_order' => 1,
            'is_active'  => true,
        ]);

        $this->command->info("Wawancara page created (ID: {$page->id})");

        // Grant permissions to all roles
        $roles = Role::all();
        foreach ($roles as $role) {
            RolePagePermission::updateOrCreate(
                ['role_id' => $role->id, 'page_id' => $page->id],
                ['can_view' => true, 'can_create' => true, 'can_edit' => true, 'can_delete' => true]
            );
        }

        $this->command->info('Permissions granted to ' . $roles->count() . ' roles.');
        $this->command->info('WawancaraMenuSeeder completed!');
    }
}