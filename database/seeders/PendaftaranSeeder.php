<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PendaftaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Insert menu "Pendaftaran" (parent_id null = menu utama di sidebar)
        // Cari sort_order terakhir di root menu
        $lastRoot = DB::table('menus')
            ->whereNull('parent_id')
            ->orderBy('sort_order', 'desc')
            ->first();

        $sortOrder = $lastRoot ? ($lastRoot->sort_order + 1) : 1;

        $menuId = DB::table('menus')->insertGetId([
            'menu_name'  => 'Pendaftaran',
            'menu_code'  => 'PENDAFTARAN',
            'parent_id'  => null,
            'icon'       => 'ti ti-clipboard-list',
            'url'        => '/pendaftaran',
            'sort_order' => $sortOrder,
            'is_active'  => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('Menu "Pendaftaran" berhasil ditambahkan ke sidebar!');

        // 2. Insert page "Pendaftaran" untuk permission tracking
        $pageId = DB::table('pages')->insertGetId([
            'page_name'  => 'Pendaftaran',
            'page_code'  => 'PAGE_PENDAFTARAN',
            'url'        => '/pendaftaran',
            'menu_id'    => $menuId,
            'icon'       => 'ti ti-clipboard-list',
            'sort_order' => 1,
            'is_active'  => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('Page "Pendaftaran" berhasil ditambahkan!');

        // 3. Berikan permissions ke role SUPER_ADMIN (id: 1)
        $superAdminRoleId = DB::table('roles')->where('role_code', 'SUPER_ADMIN')->value('id');

        if ($superAdminRoleId) {
            $existing = DB::table('role_page_permissions')
                ->where('role_id', $superAdminRoleId)
                ->where('page_id', $pageId)
                ->first();

            if (!$existing) {
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
        }

        $this->command->info('Permission untuk SUPER_ADMIN berhasil dibuat!');
    }
}