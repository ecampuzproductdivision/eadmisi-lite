<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuFormPendaftaranSeeder extends Seeder
{
    public function run(): void
    {
        // Cek apakah menu/form pendaftaran sudah ada
        $existing = DB::table('menus')->where('menu_code', 'SETTINGS_FORM_PENDAFTARAN')->first();

        if (!$existing) {
            // Cari parent menu Settings
            $settingsMenu = DB::table('menus')->where('menu_code', 'SETTINGS')->first();

            if (!$settingsMenu) {
                $this->command->error('Menu Settings tidak ditemukan! Jalankan UserSeeder terlebih dahulu.');
                return;
            }

            // Cari sort_order terakhir di dalam Settings
            $lastChild = DB::table('menus')
                ->where('parent_id', $settingsMenu->id)
                ->orderBy('sort_order', 'desc')
                ->first();

            $sortOrder = $lastChild ? ($lastChild->sort_order + 1) : 7;

            $menuId = DB::table('menus')->insertGetId([
                'menu_name'  => 'Form Pendaftaran',
                'menu_code'  => 'SETTINGS_FORM_PENDAFTARAN',
                'parent_id'  => $settingsMenu->id,
                'icon'       => 'ti ti-file-text',
                'url'        => '/settings/form-pendaftaran',
                'sort_order' => $sortOrder,
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Buat page untuk permission tracking
            $pageId = DB::table('pages')->insertGetId([
                'page_name'  => 'Form Pendaftaran',
                'page_code'  => 'SETTINGS_FORM_PENDAFTARAN',
                'url'        => '/settings/form-pendaftaran',
                'menu_id'    => $menuId,
                'icon'       => 'ti ti-file-text',
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

            $this->command->info('Menu "Form Pendaftaran" berhasil ditambahkan ke Settings!');
        } else {
            $this->command->info('Menu "Form Pendaftaran" sudah ada, skip.');
        }
    }
}