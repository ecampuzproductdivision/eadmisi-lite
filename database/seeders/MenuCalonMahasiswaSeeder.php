<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuCalonMahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $calonMhsRoleId = DB::table('roles')->where('role_code', 'CALON_MAHASISWA')->value('id');

        // ============================================================
        // 1. MENU: Riwayat Pendaftaran (untuk Calon Mahasiswa)
        // ============================================================
        $menuRiwayatId = DB::table('menus')->insertGetId([
            'menu_name'  => 'Riwayat Pendaftaran',
            'menu_code'  => 'RIWAYAT_PENDAFTARAN',
            'parent_id'  => null,
            'icon'       => 'ti ti-history',
            'url'        => '/riwayat-pendaftaran',
            'sort_order' => 1, // After Home
            'is_active'  => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $pageRiwayatId = DB::table('pages')->insertGetId([
            'page_name'  => 'Riwayat Pendaftaran',
            'page_code'  => 'PAGE_RIWAYAT_PENDAFTARAN',
            'url'        => '/riwayat-pendaftaran',
            'menu_id'    => $menuRiwayatId,
            'icon'       => 'ti ti-history',
            'sort_order' => 1,
            'is_active'  => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Permission ONLY for CALON_MAHASISWA
        if ($calonMhsRoleId) {
            $existing = DB::table('role_page_permissions')
                ->where('role_id', $calonMhsRoleId)
                ->where('page_id', $pageRiwayatId)
                ->first();
            if (!$existing) {
                DB::table('role_page_permissions')->insert([
                    'role_id'    => $calonMhsRoleId,
                    'page_id'    => $pageRiwayatId,
                    'can_view'   => true,
                    'can_create' => false,
                    'can_edit'   => false,
                    'can_delete' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // ============================================================
        // 2. MENU: Tagihan (untuk Calon Mahasiswa)
        // ============================================================
        $menuTagihanId = DB::table('menus')->insertGetId([
            'menu_name'  => 'Tagihan',
            'menu_code'  => 'TAGIHAN_CALON',
            'parent_id'  => null,
            'icon'       => 'ti ti-receipt',
            'url'        => '/tagihan',
            'sort_order' => 3, // After Riwayat Pendaftaran & Daftar PMB
            'is_active'  => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $pageTagihanId = DB::table('pages')->insertGetId([
            'page_name'  => 'Tagihan',
            'page_code'  => 'PAGE_TAGIHAN_CALON',
            'url'        => '/tagihan',
            'menu_id'    => $menuTagihanId,
            'icon'       => 'ti ti-receipt',
            'sort_order' => 1,
            'is_active'  => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Permission ONLY for CALON_MAHASISWA
        if ($calonMhsRoleId) {
            $existing = DB::table('role_page_permissions')
                ->where('role_id', $calonMhsRoleId)
                ->where('page_id', $pageTagihanId)
                ->first();
            if (!$existing) {
                DB::table('role_page_permissions')->insert([
                    'role_id'    => $calonMhsRoleId,
                    'page_id'    => $pageTagihanId,
                    'can_view'   => true,
                    'can_create' => false,
                    'can_edit'   => false,
                    'can_delete' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // ============================================================
        // 3. MENU: Tes Online (untuk Calon Mahasiswa)
        // ============================================================
        $menuTesId = DB::table('menus')->insertGetId([
            'menu_name'  => 'Tes Online',
            'menu_code'  => 'TES_ONLINE_CALON',
            'parent_id'  => null,
            'icon'       => 'ti ti-pencil',
            'url'        => '/tes-online',
            'sort_order' => 4, // After Tagihan
            'is_active'  => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $pageTesId = DB::table('pages')->insertGetId([
            'page_name'  => 'Tes Online',
            'page_code'  => 'PAGE_TES_ONLINE_CALON',
            'url'        => '/tes-online',
            'menu_id'    => $menuTesId,
            'icon'       => 'ti ti-pencil',
            'sort_order' => 1,
            'is_active'  => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Permission ONLY for CALON_MAHASISWA
        if ($calonMhsRoleId) {
            $existing = DB::table('role_page_permissions')
                ->where('role_id', $calonMhsRoleId)
                ->where('page_id', $pageTesId)
                ->first();
            if (!$existing) {
                DB::table('role_page_permissions')->insert([
                    'role_id'    => $calonMhsRoleId,
                    'page_id'    => $pageTesId,
                    'can_view'   => true,
                    'can_create' => false,
                    'can_edit'   => false,
                    'can_delete' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('Menu "Riwayat Pendaftaran", "Tagihan", dan "Tes Online" untuk CALON_MAHASISWA berhasil ditambahkan ke sidebar!');
        $this->command->info('Pages & permissions untuk CALON_MAHASISWA berhasil dibuat!');
    }
}
