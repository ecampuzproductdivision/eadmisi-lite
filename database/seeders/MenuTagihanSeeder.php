<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuTagihanSeeder extends Seeder
{
    public function run(): void
    {
        // Cek apakah menu tagihan admin sudah ada
        $existing = DB::table('menus')->where('menu_code', 'TAGIHAN_ADMIN')->first();

        if (!$existing) {
            // Cari sort_order terakhir di root menu
            $lastRoot = DB::table('menus')
                ->whereNull('parent_id')
                ->orderBy('sort_order', 'desc')
                ->first();

            $sortOrder = $lastRoot ? ($lastRoot->sort_order + 1) : 1;

            $menuId = DB::table('menus')->insertGetId([
                'menu_name' => 'Tagihan',
                'menu_code' => 'TAGIHAN_ADMIN',
                'parent_id' => null,
                'icon' => 'ti-receipt',
                'url' => '/settings/tagihan',
                'sort_order' => $sortOrder,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Buat page untuk permission tracking
            $pageId = DB::table('pages')->insertGetId([
                'page_name'  => 'Tagihan',
                'page_code'  => 'PAGE_TAGIHAN_ADMIN',
                'url'        => '/settings/tagihan',
                'menu_id'    => $menuId,
                'icon'       => 'ti-receipt',
                'sort_order' => 1,
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Berikan permission ke SUPER_ADMIN
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

            $this->command->info('Menu "Tagihan" untuk admin berhasil ditambahkan!');
        } else {
            $this->command->info('Menu "Tagihan" admin sudah ada, skip.');
        }
    }
}