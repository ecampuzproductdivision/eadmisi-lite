<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Permission;
use App\Models\RolePagePermission;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create SUPER_ADMIN role
        $superAdmin = Role::create([
            'role_name' => 'Super Admin',
            'role_code' => 'SUPER_ADMIN',
            'description' => 'Full access to all system features',
            'status' => 'active',
        ]);

        // Create CALON_MAHASISWA role
        $calonMahasiswa = Role::create([
            'role_name' => 'Calon Mahasiswa',
            'role_code' => 'CALON_MAHASISWA',
            'description' => 'Prospective student who registered via Google OAuth or PMB',
            'status' => 'active',
        ]);

        // ===== CREATE MENUS =====

        // Home Menu (Single, no parent)
        $homeMenu = Menu::create([
            'menu_name' => 'Home',
            'menu_code' => 'HOME',
            'category' => null,
            'parent_id' => null,
            'icon' => 'ti ti-home',
            'url' => '/home',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // Page record for Home (so it appears in Pages & Permissions management)
        $homePage = Page::create([
            'page_name' => 'Home',
            'page_code' => 'HOME',
            'url' => '/home',
            'menu_id' => $homeMenu->id,
            'icon' => 'ti ti-home',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // Give full permissions to SUPER_ADMIN for Home
        RolePagePermission::create([
            'role_id' => $superAdmin->id,
            'page_id' => $homePage->id,
            'can_view' => true,
            'can_create' => true,
            'can_edit' => true,
            'can_delete' => true,
        ]);

        // Give CALON_MAHASISWA role permission to view Home only
        RolePagePermission::create([
            'role_id' => $calonMahasiswa->id,
            'page_id' => $homePage->id,
            'can_view' => true,
            'can_create' => false,
            'can_edit' => false,
            'can_delete' => false,
        ]);

        // Create admin user
        $admin = User::create([
            'name' => 'Admin Akademik',
            'email' => 'admin@akademik.com',
            'password' => 'password',
            'status' => 'active',
        ]);

        $admin->roles()->attach($superAdmin->id);

        // Create John Doe user with Calon Mahasiswa role
        $johnDoe = User::create([
            'name' => 'John Doe',
            'email' => 'juniarhendra24@gmail.com',
            'password' => 'password',
            'status' => 'active',
        ]);

        $johnDoe->roles()->attach($calonMahasiswa->id);

        // Parent Menu: Settings
        $settingsMenu = Menu::create([
            'menu_name' => 'Settings',
            'menu_code' => 'SETTINGS',
            'category' => 'SETTINGS',
            'parent_id' => null,
            'icon' => 'ti-settings',
            'url' => '#',
            'sort_order' => 10,
            'is_active' => true,
        ]);

        // Children Menu items (sub-menu of Settings)
        $menuItems = [
            ['menu_name' => 'Users', 'menu_code' => 'SETTINGS_USERS', 'icon' => 'ti ti-users', 'url' => '/settings/users', 'sort_order' => 1],
            ['menu_name' => 'Roles', 'menu_code' => 'SETTINGS_ROLES', 'icon' => 'ti ti-shield', 'url' => '/settings/roles', 'sort_order' => 2],
            ['menu_name' => 'Menus', 'menu_code' => 'SETTINGS_MENUS', 'icon' => 'ti ti-menu-2', 'url' => '/settings/menus', 'sort_order' => 3],
            ['menu_name' => 'Pages', 'menu_code' => 'SETTINGS_PAGES', 'icon' => 'ti ti-file', 'url' => '/settings/pages', 'sort_order' => 4],
            ['menu_name' => 'Permissions', 'menu_code' => 'SETTINGS_PERMISSIONS', 'icon' => 'ti ti-lock', 'url' => '/settings/permissions', 'sort_order' => 5],
            ['menu_name' => 'Activity Logs', 'menu_code' => 'SETTINGS_LOGS', 'icon' => 'ti ti-activity', 'url' => '/settings/logs', 'sort_order' => 6],
        ];

        foreach ($menuItems as $item) {
            $childMenu = Menu::create([
                'menu_name' => $item['menu_name'],
                'menu_code' => $item['menu_code'],
                'category' => 'SETTINGS',
                'parent_id' => $settingsMenu->id,
                'icon' => $item['icon'],
                'url' => $item['url'],
                'sort_order' => $item['sort_order'],
                'is_active' => true,
            ]);

            // Also create a Page record for permission management
            $page = Page::create([
                'page_name' => $item['menu_name'],
                'page_code' => $item['menu_code'],
                'url' => $item['url'],
                'menu_id' => $childMenu->id,
                'icon' => $item['icon'],
                'sort_order' => $item['sort_order'],
                'is_active' => true,
            ]);

            // Give full permissions to SUPER_ADMIN
            RolePagePermission::create([
                'role_id' => $superAdmin->id,
                'page_id' => $page->id,
                'can_view' => true,
                'can_create' => true,
                'can_edit' => true,
                'can_delete' => true,
            ]);
        }

        // Create basic permissions
        $permNames = ['view', 'create', 'edit', 'delete'];
        foreach ($permNames as $name) {
            Permission::create(['permission_name' => $name]);
        }
    }
}