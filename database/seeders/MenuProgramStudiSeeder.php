<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuProgramStudiSeeder extends Seeder
{
    public function run(): void
    {
        $existing = DB::table('menus')->where('menu_code', 'MASTER_PROGRAM_STUDI')->first();

        if (!$existing) {
            // Find "Jalur Pendaftaran" menu to insert Master Program Studi BEFORE it
            $jalurPendaftaran = DB::table('menus')
                ->whereNull('parent_id')
                ->where('menu_code', 'REGISTRATION_PATHS')
                ->first();

            $sortOrder = $jalurPendaftaran ? $jalurPendaftaran->sort_order : 11;

            // Shift existing menus starting from this position down by 1
            DB::table('menus')
                ->whereNull('parent_id')
                ->where('sort_order', '>=', $sortOrder)
                ->increment('sort_order');

            $menuId = DB::table('menus')->insertGetId([
                'menu_name'  => 'Master Program Studi',
                'menu_code'  => 'MASTER_PROGRAM_STUDI',
                'parent_id'  => null,
                'icon'       => 'ti ti-school',
                'url'        => '/settings/program-studi',
                'sort_order' => $sortOrder,
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $pageId = DB::table('pages')->insertGetId([
                'page_name'  => 'Master Program Studi',
                'page_code'  => 'PAGE_MASTER_PROGRAM_STUDI',
                'url'        => '/settings/program-studi',
                'menu_id'    => $menuId,
                'icon'       => 'ti ti-school',
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

            $this->command->info('Menu "Master Program Studi" berhasil ditambahkan sebagai root menu!');
        } else {
            $this->command->info('Menu "Master Program Studi" sudah ada, skip.');
        }
    }
}