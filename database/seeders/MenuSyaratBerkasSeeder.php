<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSyaratBerkasSeeder extends Seeder
{
    public function run(): void
    {
        $existing = DB::table('menus')->where('menu_code', 'SYARAT_BERKAS')->first();

        if (!$existing) {
            $lastRoot = DB::table('menus')
                ->whereNull('parent_id')
                ->orderBy('sort_order', 'desc')
                ->first();

            $sortOrder = $lastRoot ? ($lastRoot->sort_order + 1) : 1;

            $menuId = DB::table('menus')->insertGetId([
                'menu_name'  => 'Syarat Berkas',
                'menu_code'  => 'SYARAT_BERKAS',
                'parent_id'  => null,
                'icon'       => 'ti ti-file-check',
                'url'        => '/settings/syarat-berkas',
                'sort_order' => $sortOrder,
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $pageId = DB::table('pages')->insertGetId([
                'page_name'  => 'Syarat Berkas',
                'page_code'  => 'PAGE_SYARAT_BERKAS',
                'url'        => '/settings/syarat-berkas',
                'menu_id'    => $menuId,
                'icon'       => 'ti ti-file-check',
                'sort_order' => 1,
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $superAdminRoleId = DB::table('roles')->where('role_code', 'SUPER_ADMIN')->value('id');
            if ($superAdminRoleId) {
                DB::table('role_page_permissions')->insert([
                    'role_id'    => $superAdminRoleId,
                    'page_id'    => $pageId,
                    'can_view'   => true,
                    'can_create' => true,
                    'can_edit'   => true,
                    'can_delete' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $this->command->info('Menu "Syarat Berkas" berhasil ditambahkan sebagai root menu!');
        } else {
            $this->command->info('Menu "Syarat Berkas" sudah ada, skip.');
        }
    }
}