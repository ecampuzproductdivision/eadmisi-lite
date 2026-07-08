<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WilayahDummySeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing tables to avoid duplicate key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('kelurahans')->truncate();
        DB::table('kecamatans')->truncate();
        DB::table('kabupatens')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Seed Kabupatens
        DB::table('kabupatens')->insert([
            ['id' => 1, 'nama_kabupaten' => 'Kab. Sleman, D.I. Yogyakarta'],
            ['id' => 2, 'nama_kabupaten' => 'Kota Palu, Sulawesi Tengah'],
            ['id' => 3, 'nama_kabupaten' => 'Kab. Bantul, D.I. Yogyakarta'],
            ['id' => 4, 'nama_kabupaten' => 'Kota Jakarta Selatan, D.K.I. Jakarta'],
            ['id' => 5, 'nama_kabupaten' => 'Kota Bandung, Jawa Barat'],
            ['id' => 6, 'nama_kabupaten' => 'Kota Surabaya, Jawa Timur'],
            ['id' => 7, 'nama_kabupaten' => 'Kota Medan, Sumatera Utara'],
        ]);

        // 2. Seed Kecamatans
        DB::table('kecamatans')->insert([
            // Sleman Districts
            ['id' => 10, 'kabupaten_id' => 1, 'nama_kecamatan' => 'Depok'],
            ['id' => 11, 'kabupaten_id' => 1, 'nama_kecamatan' => 'Mlati'],
            // Palu Districts
            ['id' => 20, 'kabupaten_id' => 2, 'nama_kecamatan' => 'Palu Timur'],
            ['id' => 21, 'kabupaten_id' => 2, 'nama_kecamatan' => 'Palu Barat'],
            // Bantul Districts
            ['id' => 30, 'kabupaten_id' => 3, 'nama_kecamatan' => 'Kasihan'],
            ['id' => 31, 'kabupaten_id' => 3, 'nama_kecamatan' => 'Sewon'],
            // Jakarta Selatan Districts
            ['id' => 40, 'kabupaten_id' => 4, 'nama_kecamatan' => 'Kebayoran Baru'],
            ['id' => 41, 'kabupaten_id' => 4, 'nama_kecamatan' => 'Cilandak'],
            // Bandung Districts
            ['id' => 50, 'kabupaten_id' => 5, 'nama_kecamatan' => 'Coblong'],
            ['id' => 51, 'kabupaten_id' => 5, 'nama_kecamatan' => 'Cicendo'],
            // Surabaya Districts
            ['id' => 60, 'kabupaten_id' => 6, 'nama_kecamatan' => 'Tegalsari'],
            ['id' => 61, 'kabupaten_id' => 6, 'nama_kecamatan' => 'Gubeng'],
            // Medan Districts
            ['id' => 70, 'kabupaten_id' => 7, 'nama_kecamatan' => 'Medan Baru'],
            ['id' => 71, 'kabupaten_id' => 7, 'nama_kecamatan' => 'Medan Area'],
        ]);

        // 3. Seed Kelurahans / Desas
        DB::table('kelurahans')->insert([
            // Depok (Sleman) Villages
            ['id' => 100, 'kecamatan_id' => 10, 'nama_kelurahan' => 'Condongcatur'],
            ['id' => 101, 'kecamatan_id' => 10, 'nama_kelurahan' => 'Caturtunggal'],
            // Mlati (Sleman) Villages
            ['id' => 110, 'kecamatan_id' => 11, 'nama_kelurahan' => 'Sindadi'],
            // Palu Timur Villages
            ['id' => 200, 'kecamatan_id' => 20, 'nama_kelurahan' => 'Besusu'],
            // Palu Barat Villages
            ['id' => 210, 'kecamatan_id' => 21, 'nama_kelurahan' => 'Lere'],
            // Bantul (Kasihan/Sewon) Villages
            ['id' => 300, 'kecamatan_id' => 30, 'nama_kelurahan' => 'Tamantirto'],
            ['id' => 310, 'kecamatan_id' => 31, 'nama_kelurahan' => 'Panggungharjo'],
            // Jakarta Selatan (Kebayoran Baru/Cilandak) Villages
            ['id' => 400, 'kecamatan_id' => 40, 'nama_kelurahan' => 'Selong'],
            ['id' => 410, 'kecamatan_id' => 41, 'nama_kelurahan' => 'Cilandak Barat'],
            // Bandung (Coblong/Cicendo) Villages
            ['id' => 500, 'kecamatan_id' => 50, 'nama_kelurahan' => 'Dago'],
            ['id' => 501, 'kecamatan_id' => 50, 'nama_kelurahan' => 'Sadang Serang'],
            ['id' => 510, 'kecamatan_id' => 51, 'nama_kelurahan' => 'Pasir Kaliki'],
            // Surabaya (Tegalsari/Gubeng) Villages
            ['id' => 600, 'kecamatan_id' => 60, 'nama_kelurahan' => 'Kedungdoro'],
            ['id' => 610, 'kecamatan_id' => 61, 'nama_kelurahan' => 'Mojo'],
            // Medan (Medan Baru/Medan Area) Villages
            ['id' => 700, 'kecamatan_id' => 70, 'nama_kelurahan' => 'Babura'],
            ['id' => 710, 'kecamatan_id' => 71, 'nama_kelurahan' => 'Matsum'],
        ]);
    }
}