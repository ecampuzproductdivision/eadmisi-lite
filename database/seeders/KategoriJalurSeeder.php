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
            ['nama' => 'Penelusuran Minat dan Kemampuan (PMDK)'],
            ['nama' => 'Prestasi'],
            ['nama' => 'Program Kerjasama'],
            ['nama' => 'Seleksi Mandiri'],
            ['nama' => 'Seleksi Nasional Berdasarkan Tes (SNBT)'],
            ['nama' => 'Seleksi Nasional Berdasarkan Prestasi (SNBP)'],
            ['nama' => 'Program International'],
            ['nama' => 'Ujian Masuk Bersama Lainnya'],
        ];

        DB::table('kategori_jalurs')->insert($data);

        $this->command->info('Kategori jalur seeded successfully!');
    }
}