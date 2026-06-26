<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuFormPendaftaranSubMenuSeeder extends Seeder
{
    public function run(): void
    {
        // Cari parent menu Form Pendaftaran
        $parentMenu = DB::table('menus')->where('menu_code', 'SETTINGS_FORM_PENDAFTARAN')->first();

        if (!$parentMenu) {
            $this->command->error('Menu "Form Pendaftaran" tidak ditemukan! Jalankan MenuFormPendaftaranSeeder terlebih dahulu.');
            return;
        }

        // Cek apakah sub-menu sudah ada
        $existingCount = DB::table('menus')->where('parent_id', $parentMenu->id)->count();
        if ($existingCount > 0) {
            $this->command->info('Sub-menu "Form Pendaftaran" sudah ada (' . $existingCount . ' item), skip.');
            return;
        }

        $baseSort = DB::table('menus')->where('parent_id', $parentMenu->id)->max('sort_order') ?? 0;

        $subMenus = [
            [
                'menu_name' => 'Buat Form Baru',
                'menu_code' => 'SETTINGS_FORM_PENDAFTARAN_CREATE',
                'icon'      => 'ti ti-plus',
                'url'       => '/settings/form-pendaftaran/create',
                'sort_order' => $baseSort + 1,
            ],
            [
                'menu_name' => 'Form Builder',
                'menu_code' => 'SETTINGS_FORM_PENDAFTARAN_BUILDER',
                'icon'      => 'ti ti-layout',
                'url'       => '/settings/form-pendaftaran',
                'sort_order' => $baseSort + 2,
            ],
        ];

        $roles = DB::table('roles')->get();
        $superAdminRole = $roles->firstWhere('role_code', 'SUPER_ADMIN');

        foreach ($subMenus as $data) {
            $menuId = DB::table('menus')->insertGetId([
                'menu_name'  => $data['menu_name'],
                'menu_code'  => $data['menu_code'],
                'parent_id'  => $parentMenu->id,
                'icon'       => $data['icon'],
                'url'        => $data['url'],
                'sort_order' => $data['sort_order'],
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->command->info("Sub-menu '{$data['menu_name']}' created (ID: {$menuId})");

            // Buat page untuk permission tracking
            $pageId = DB::table('pages')->insertGetId([
                'page_name'  => $data['menu_name'],
                'page_code'  => $data['menu_code'],
                'url'        => $data['url'],
                'menu_id'    => $menuId,
                'icon'       => $data['icon'],
                'sort_order' => $data['sort_order'],
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Berikan permission ke semua role (terutama SUPER_ADMIN dan ADMIN)
            foreach ($roles as $role) {
                // Default: only SUPER_ADMIN gets full access
                $canView = in_array($role->role_code, ['SUPER_ADMIN', 'ADMIN']);
                $canCreate = $role->role_code === 'SUPER_ADMIN';
                $canEdit = $role->role_code === 'SUPER_ADMIN';
                $canDelete = $role->role_code === 'SUPER_ADMIN';

                DB::table('role_page_permissions')->insert([
                    'role_id'    => $role->id,
                    'page_id'    => $pageId,
                    'can_view'   => $canView,
                    'can_create' => $canCreate,
                    'can_edit'   => $canEdit,
                    'can_delete' => $canDelete,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('MenuFormPendaftaranSubMenuSeeder completed! ' . count($subMenus) . ' sub-menus created.');
    }
}