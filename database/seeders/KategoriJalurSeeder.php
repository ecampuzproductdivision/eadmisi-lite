<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriJalurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        DB::table('kategori_jalurs')->truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $data = [
            ['nama' => 'Seleksi Nasional'],
            ['nama' => 'Seleksi Mandiri'],
            ['nama' => 'Seleksi Prestasi'],
            ['nama' => 'Seleksi Internasional'],
            ['nama' => 'Seleksi Kerjasama'],
        ];

        DB::table('kategori_jalurs')->insert($data);

        $this->command->info('Kategori jalur seeded successfully!');
    }
}