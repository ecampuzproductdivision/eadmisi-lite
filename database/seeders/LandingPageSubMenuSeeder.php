<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Page;
use App\Models\Role;
use App\Models\RolePagePermission;
use Illuminate\Database\Seeder;

class LandingPageSubMenuSeeder extends Seeder
{
    public function run(): void
    {
        $parentMenu = Menu::where('menu_code', 'LANDING_PAGE')->first();
        if (!$parentMenu) {
            $this->command->error('LANDING_PAGE menu not found. Run LandingPageMenuSeeder first.');
            return;
        }

        $existingSub = Menu::where('parent_id', $parentMenu->id)->count();
        if ($existingSub > 0) {
            $this->command->info('Landing Page sub-menus already exist. Skipping.');
            return;
        }

        $baseSort = Menu::where('parent_id', $parentMenu->id)->max('sort_order') ?? 0;

        $subMenus = [
            [
                'menu_name'  => 'Keunggulan',
                'menu_code'  => 'LANDING_PAGE_FEATURES',
                'icon'       => 'ti ti-star',
                'url'        => '/settings/landing-page#features',
                'sort_order' => $baseSort + 1,
            ],
            [
                'menu_name'  => 'Program Studi',
                'menu_code'  => 'LANDING_PAGE_PRODI',
                'icon'       => 'ti ti-book',
                'url'        => '/settings/landing-page#prodi',
                'sort_order' => $baseSort + 2,
            ],
            [
                'menu_name'  => 'Fasilitas',
                'menu_code'  => 'LANDING_PAGE_FACILITIES',
                'icon'       => 'ti ti-building-stadium',
                'url'        => '/settings/landing-page#fasilitas',
                'sort_order' => $baseSort + 3,
            ],
            [
                'menu_name'  => 'Pengaturan',
                'menu_code'  => 'LANDING_PAGE_SETTINGS',
                'icon'       => 'ti ti-settings',
                'url'        => '/settings/landing-page#pengaturan',
                'sort_order' => $baseSort + 4,
            ],
        ];

        $roles = Role::all();

        foreach ($subMenus as $data) {
            $menu = Menu::create(array_merge($data, ['parent_id' => $parentMenu->id, 'is_active' => true]));
            $this->command->info("Sub-menu '{$menu->menu_name}' created (ID: {$menu->id})");

            $page = Page::create([
                'page_name'  => $data['menu_name'],
                'page_code'  => $data['menu_code'],
                'url'        => $data['url'],
                'menu_id'    => $menu->id,
                'icon'       => $data['icon'],
                'sort_order' => 1,
                'is_active'  => true,
            ]);

            foreach ($roles as $role) {
                RolePagePermission::updateOrCreate(
                    ['role_id' => $role->id, 'page_id' => $page->id],
                    ['can_view' => true, 'can_create' => true, 'can_edit' => true, 'can_delete' => true]
                );
            }
        }

        $this->command->info('LandingPageSubMenuSeeder completed! ' . count($subMenus) . ' sub-menus created.');
    }
}