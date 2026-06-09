<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MenuRegistrationPathSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Cek apakah menu registration_paths sudah ada
        $existing = DB::table('menus')->where('menu_code', 'REGISTRATION_PATHS')->first();

        if (!$existing) {
            // Cari sort_order terakhir di root menu (parent_id is null)
            $lastRoot = DB::table('menus')
                ->whereNull('parent_id')
                ->orderBy('sort_order', 'desc')
                ->first();

            $sortOrder = $lastRoot ? ($lastRoot->sort_order + 1) : 1;

            DB::table('menus')->insert([
                'menu_name' => 'Jalur Pendaftaran',
                'menu_code' => 'REGISTRATION_PATHS',
                'parent_id' => null,
                'icon' => 'ti-road',
                'url' => '/settings/registration-paths',
                'sort_order' => $sortOrder,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $this->command->info('Menu "Jalur Pendaftaran" berhasil ditambahkan di root menu!');
        } else {
            // Update existing menu to be at root (parent_id = null)
            DB::table('menus')->where('menu_code', 'REGISTRATION_PATHS')->update([
                'parent_id' => null,
                'updated_at' => $now,
            ]);
            $this->command->info('Menu "Jalur Pendaftaran" berhasil diubah menjadi root menu!');
        }
    }
}