<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DaftarPmbSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Insert menu "Daftar PMB" (parent_id null = menu utama di sidebar)
        $menuId = DB::table('menus')->insertGetId([
            'menu_name'  => 'Daftar PMB',
            'menu_code'  => 'DAFTAR_PMB',
            'parent_id'  => null,
            'icon'       => 'ti ti-notebook',
            'url'        => '/daftar-pmb',
            'sort_order' => 2, // After Riwayat Pendaftaran
            'is_active'  => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Insert page "Daftar PMB" untuk permission tracking
        $pageId = DB::table('pages')->insertGetId([
            'page_name'  => 'Daftar PMB',
            'page_code'  => 'PAGE_DAFTAR_PMB',
            'url'        => '/daftar-pmb',
            'menu_id'    => $menuId,
            'icon'       => 'ti ti-notebook',
            'sort_order' => 1,
            'is_active'  => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Berikan permissions ke role CALON_MAHASISWA (id: 2)
        $calonMhsRoleId = DB::table('roles')->where('role_code', 'CALON_MAHASISWA')->value('id');

        if ($calonMhsRoleId) {
            $existing = DB::table('role_page_permissions')
                ->where('role_id', $calonMhsRoleId)
                ->where('page_id', $pageId)
                ->first();

            if (!$existing) {
                DB::table('role_page_permissions')->insert([
                    'role_id'    => $calonMhsRoleId,
                    'page_id'    => $pageId,
                    'can_view'   => true,
                    'can_create' => false,
                    'can_edit'   => false,
                    'can_delete' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('Menu "Daftar PMB" berhasil ditambahkan ke sidebar!');
        $this->command->info('Page & permissions untuk CALON_MAHASISWA berhasil dibuat!');
    }
}