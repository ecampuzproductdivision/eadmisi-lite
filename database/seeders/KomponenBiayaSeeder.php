<?php

namespace Database\Seeders;

use App\Models\KomponenBiaya;
use Illuminate\Database\Seeder;

class KomponenBiayaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kode_komponen' => 'BIAYA-PMB-001', 'nama_komponen' => 'Pendaftaran',            'deskripsi' => 'Biaya pendaftaran calon mahasiswa baru', 'is_active' => true],
            ['kode_komponen' => 'BIAYA-PMB-002', 'nama_komponen' => 'SPP Tetap',               'deskripsi' => 'Sumbangan Pembinaan Pendidikan (SPP) tetap per semester', 'is_active' => true],
            ['kode_komponen' => 'BIAYA-PMB-003', 'nama_komponen' => 'SPP Variabel',            'deskripsi' => 'SPP variabel berdasarkan jumlah SKS yang diambil', 'is_active' => true],
            ['kode_komponen' => 'BIAYA-PMB-004', 'nama_komponen' => 'Uang Gedung',             'deskripsi' => 'Biaya pembangunan dan pemeliharaan fasilitas gedung', 'is_active' => true],
            ['kode_komponen' => 'BIAYA-PMB-005', 'nama_komponen' => 'Praktikum',               'deskripsi' => 'Biaya praktikum laboratorium per semester', 'is_active' => true],
            ['kode_komponen' => 'BIAYA-PMB-006', 'nama_komponen' => 'Wisuda',                  'deskripsi' => 'Biaya penyelenggaraan wisuda', 'is_active' => true],
            ['kode_komponen' => 'BIAYA-PMB-007', 'nama_komponen' => 'Almamater & Atribut',     'deskripsi' => 'Paket jas almamater, jaket, dan atribut lainnya', 'is_active' => true],
            ['kode_komponen' => 'BIAYA-PMB-008', 'nama_komponen' => 'Asuransi',                'deskripsi' => 'Asuransi kesehatan dan kecelakaan mahasiswa', 'is_active' => false],
        ];

        foreach ($data as $item) {
            KomponenBiaya::firstOrCreate(
                ['kode_komponen' => $item['kode_komponen']],
                $item
            );
        }

        $this->command->info('8 data komponen biaya berhasil di-seed.');
    }
}