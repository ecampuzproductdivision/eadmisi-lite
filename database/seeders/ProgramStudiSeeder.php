<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramStudiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        DB::table('program_studis')->truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $data = [
            ['kode' => 'TI', 'nama' => 'Teknik Informatika', 'jenjang' => 'S1', 'fakultas' => 'Fakultas Teknologi Informasi', 'status' => true],
            ['kode' => 'SI', 'nama' => 'Sistem Informasi', 'jenjang' => 'S1', 'fakultas' => 'Fakultas Teknologi Informasi', 'status' => true],
            ['kode' => 'TK', 'nama' => 'Teknik Komputer', 'jenjang' => 'D4', 'fakultas' => 'Fakultas Teknologi Informasi', 'status' => true],
            ['kode' => 'AK', 'nama' => 'Akuntansi', 'jenjang' => 'S1', 'fakultas' => 'Fakultas Ekonomi dan Bisnis', 'status' => true],
            ['kode' => 'MB', 'nama' => 'Manajemen Bisnis', 'jenjang' => 'S1', 'fakultas' => 'Fakultas Ekonomi dan Bisnis', 'status' => true],
            ['kode' => 'HK', 'nama' => 'Hukum', 'jenjang' => 'S1', 'fakultas' => 'Fakultas Hukum', 'status' => true],
            ['kode' => 'TI2', 'nama' => 'Teknik Informatika', 'jenjang' => 'S2', 'fakultas' => 'Fakultas Teknologi Informasi', 'status' => true],
            ['kode' => 'AK2', 'nama' => 'Akuntansi', 'jenjang' => 'S2', 'fakultas' => 'Fakultas Ekonomi dan Bisnis', 'status' => true],
        ];

        DB::table('program_studis')->insert($data);

        $this->command->info('Program studi seeded successfully!');
    }
}