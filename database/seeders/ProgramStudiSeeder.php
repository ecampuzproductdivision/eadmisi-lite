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
            ['kode' => 'TI', 'nama' => 'Teknik Informatika', 'jenjang' => 'S1', 'fakultas' => 'Fakultas Teknologi Informasi', 'status' => true, 'nama_prodi' => 'Teknik Informatika', 'jenjang_akademik' => 'S1', 'kode_prodi' => '55201', 'status_aktif' => true],
            ['kode' => 'SI', 'nama' => 'Sistem Informasi', 'jenjang' => 'S1', 'fakultas' => 'Fakultas Teknologi Informasi', 'status' => true, 'nama_prodi' => 'Sistem Informasi', 'jenjang_akademik' => 'S1', 'kode_prodi' => '57201', 'status_aktif' => true],
            ['kode' => 'TK', 'nama' => 'Teknik Komputer', 'jenjang' => 'D4', 'fakultas' => 'Fakultas Teknologi Informasi', 'status' => true, 'nama_prodi' => 'Teknik Komputer', 'jenjang_akademik' => 'D4', 'kode_prodi' => '55401', 'status_aktif' => true],
            ['kode' => 'AK', 'nama' => 'Akuntansi', 'jenjang' => 'S1', 'fakultas' => 'Fakultas Ekonomi dan Bisnis', 'status' => true, 'nama_prodi' => 'Akuntansi', 'jenjang_akademik' => 'S1', 'kode_prodi' => '62201', 'status_aktif' => true],
            ['kode' => 'MB', 'nama' => 'Manajemen Bisnis', 'jenjang' => 'S1', 'fakultas' => 'Fakultas Ekonomi dan Bisnis', 'status' => true, 'nama_prodi' => 'Manajemen Bisnis', 'jenjang_akademik' => 'S1', 'kode_prodi' => '61201', 'status_aktif' => true],
            ['kode' => 'HK', 'nama' => 'Hukum', 'jenjang' => 'S1', 'fakultas' => 'Fakultas Hukum', 'status' => true, 'nama_prodi' => 'Ilmu Hukum', 'jenjang_akademik' => 'S1', 'kode_prodi' => '74201', 'status_aktif' => true],
            ['kode' => 'TI2', 'nama' => 'Teknik Informatika', 'jenjang' => 'S2', 'fakultas' => 'Fakultas Teknologi Informasi', 'status' => true, 'nama_prodi' => 'Teknik Informatika', 'jenjang_akademik' => 'S2', 'kode_prodi' => '55101', 'status_aktif' => true],
            ['kode' => 'AK2', 'nama' => 'Akuntansi', 'jenjang' => 'S2', 'fakultas' => 'Fakultas Ekonomi dan Bisnis', 'status' => true, 'nama_prodi' => 'Akuntansi', 'jenjang_akademik' => 'S2', 'kode_prodi' => '62101', 'status_aktif' => true],
        ];

        DB::table('program_studis')->insert($data);

        $this->command->info('Program studi seeded successfully!');
    }
}