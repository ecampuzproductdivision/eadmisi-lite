<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageRegistrationPathSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cari menu REGISTRATION_PATHS
        $menu = DB::table('menus')->where('menu_code', 'REGISTRATION_PATHS')->first();

        // Cek apakah page sudah ada
        $existing = DB::table('pages')->where('page_code', 'PAGE_REGISTRATION_PATHS')->first();

        if (!$existing) {
            $pageId = DB::table('pages')->insertGetId([
                'page_name' => 'Jalur Pendaftaran',
                'page_code' => 'PAGE_REGISTRATION_PATHS',
                'url' => '/settings/registration-paths',
                'menu_id' => $menu ? $menu->id : null,
                'icon' => 'ti-road',
                'sort_order' => 1,
                'is_active' => true,
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

                $this->command->info('Permission untuk SUPER_ADMIN berhasil dibuat!');
            }

            $this->command->info('Page "Jalur Pendaftaran" berhasil ditambahkan ke Settings > Pages!');
        } else {
            $this->command->info('Page "Jalur Pendaftaran" sudah ada, skip.');
        }
    }
}
