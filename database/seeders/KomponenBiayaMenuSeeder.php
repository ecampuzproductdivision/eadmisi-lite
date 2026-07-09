<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Page;
use App\Models\Role;
use App\Models\RolePagePermission;
use Illuminate\Database\Seeder;

class KomponenBiayaMenuSeeder extends Seeder
{
    public function run(): void
    {
        $existing = Menu::where('menu_code', 'KOMPONEN_BIAYA')->first();
        if ($existing) {
            $this->command->info('Komponen Biaya menu already exists.');
            return;
        }

        $menu = Menu::create([
            'menu_name'  => 'Komponen Biaya',
            'menu_code'  => 'KOMPONEN_BIAYA',
            'parent_id'  => 2, // Settings menu
            'icon'       => 'ti ti-currency-dollar',
            'url'        => '/settings/komponen-biaya',
            'sort_order' => 10,
            'is_active'  => true,
        ]);

        $this->command->info("Komponen Biaya menu created (ID: {$menu->id})");

        $page = Page::create([
            'page_name'  => 'Komponen Biaya',
            'page_code'  => 'KOMPONEN_BIAYA',
            'url'        => '/settings/komponen-biaya',
            'menu_id'    => $menu->id,
            'icon'       => 'ti ti-currency-dollar',
            'sort_order' => 1,
            'is_active'  => true,
        ]);

        $this->command->info("Komponen Biaya page created (ID: {$page->id})");

        $roles = Role::all();
        foreach ($roles as $role) {
            RolePagePermission::updateOrCreate(
                ['role_id' => $role->id, 'page_id' => $page->id],
                ['can_view' => true, 'can_create' => true, 'can_edit' => true, 'can_delete' => true]
            );
        }

        $this->command->info('Permissions granted to ' . $roles->count() . ' roles.');
        $this->command->info('KomponenBiayaMenuSeeder completed!');
    }
}