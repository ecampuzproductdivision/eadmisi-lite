<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegistrasiUlangMenuSeeder extends Seeder
{
    public function run(): void
    {
        // Check if menu already exists
        $existing = DB::table('menus')->where('menu_code', 'REGISTRASI_ULANG')->first();
        if ($existing) {
            $this->command->info('Menu "Registrasi Ulang" sudah ada, skip.');
            return;
        }

        // Find "Pendaftaran" menu to insert RIGHT AFTER it
        $pendaftaranMenu = DB::table('menus')
            ->whereNull('parent_id')
            ->where('menu_code', 'PENDAFTARAN')
            ->first();

        $sortOrder = $pendaftaranMenu ? ($pendaftaranMenu->sort_order + 1) : 2;

        // Shift menus down by 1
        DB::table('menus')
            ->whereNull('parent_id')
            ->where('sort_order', '>=', $sortOrder)
            ->increment('sort_order');

        // Create registrasi-ulang menu
        $menuId = DB::table('menus')->insertGetId([
            'menu_name'  => 'Registrasi Ulang',
            'menu_code'  => 'REGISTRASI_ULANG',
            'parent_id'  => null,
            'icon'       => 'ti ti-user-check',
            'url'        => '/registrasi-ulang',
            'sort_order' => $sortOrder,
            'is_active'  => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('Menu "Registrasi Ulang" berhasil ditambahkan ke sidebar!');

        // Create page record for permission tracking
        $pageId = DB::table('pages')->insertGetId([
            'page_name'  => 'Registrasi Ulang',
            'page_code'  => 'PAGE_REGISTRASI_ULANG',
            'url'        => '/registrasi-ulang',
            'menu_id'    => $menuId,
            'icon'       => 'ti ti-user-check',
            'sort_order' => 1,
            'is_active'  => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('Page "Registrasi Ulang" berhasil ditambahkan!');

        // Grant permissions to SUPER_ADMIN role
        $superAdminRoleId = DB::table('roles')->where('role_code', 'SUPER_ADMIN')->value('id');
        if ($superAdminRoleId) {
            $existingPerm = DB::table('role_page_permissions')
                ->where('role_id', $superAdminRoleId)
                ->where('page_id', $pageId)
                ->first();

            if (!$existingPerm) {
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