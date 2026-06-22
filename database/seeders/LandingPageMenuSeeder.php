<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Page;
use App\Models\Role;
use App\Models\RolePagePermission;
use Illuminate\Database\Seeder;

class LandingPageMenuSeeder extends Seeder
{
    public function run(): void
    {
        $existing = Menu::where('menu_code', 'LANDING_PAGE')->first();
        if ($existing) {
            $this->command->info('Landing Page menu already exists.');
            return;
        }

        $settings = Menu::where('menu_code', 'SETTINGS')->first();
        $parentId = $settings ? $settings->id : 2;

        $maxSort = Menu::where('parent_id', $parentId)->max('sort_order') ?? 0;

        $menu = Menu::create([
            'menu_name'  => 'Landing Page',
            'menu_code'  => 'LANDING_PAGE',
            'parent_id'  => $parentId,
            'icon'       => 'ti ti-settings',
            'url'        => '/settings/landing-page',
            'sort_order' => $maxSort + 1,
            'is_active'  => true,
        ]);

        $this->command->info("Landing Page menu created (ID: {$menu->id})");

        $page = Page::create([
            'page_name'  => 'Landing Page',
            'page_code'  => 'LANDING_PAGE',
            'url'        => '/settings/landing-page',
            'menu_id'    => $menu->id,
            'icon'       => 'ti ti-settings',
            'sort_order' => 1,
            'is_active'  => true,
        ]);

        $this->command->info("Landing Page page created (ID: {$page->id})");

        $roles = Role::all();
        foreach ($roles as $role) {
            RolePagePermission::updateOrCreate(
                ['role_id' => $role->id, 'page_id' => $page->id],
                ['can_view' => true, 'can_create' => true, 'can_edit' => true, 'can_delete' => true]
            );
        }

        $this->command->info('Permissions granted to ' . $roles->count() . ' roles.');
        $this->command->info('LandingPageMenuSeeder completed!');
    }
}