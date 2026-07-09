<?php

namespace Database\Seeders;

use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Database\Seeder;

class KecamatanKelurahanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Kabupaten for Sleman and Yogyakarta
        $sleman = Kabupaten::firstOrCreate(['nama_kabupaten' => 'Sleman']);
        $yogyakarta = Kabupaten::firstOrCreate(['nama_kabupaten' => 'Yogyakarta']);

        // 2. Seed for Sleman
        $kecamatansSleman = [
            'Depok' => ['Caturtunggal', 'Condongcatur', 'Maguwoharjo'],
            'Mlati' => ['Sinduadi', 'Sendangadi', 'Tlogoadi'],
            'Gamping' => ['Nogotirto', 'Trihanggo', 'Ambarketawang'],
            'Kalasan' => ['Tirtomartani', 'Selomartani', 'Purwomartani'],
            'Ngaglik' => ['Sariharjo', 'Minomartani', 'Sinduharjo'],
        ];

        foreach ($kecamatansSleman as $kecName => $kelurahas) {
            $kec = Kecamatan::firstOrCreate([
                'kabupaten_id' => $sleman->id,
                'nama_kecamatan' => $kecName,
            ]);

            foreach ($kelurahas as $kelName) {
                Kelurahan::firstOrCreate([
                    'kecamatan_id' => $kec->id,
                    'nama_kelurahan' => $kelName,
                ]);
            }
        }

        // 3. Seed for Yogyakarta
        $kecamatansYogya = [
            'Gondokusuman' => ['Terban', 'Kotabaru', 'Klitren'],
            'Umbulharjo' => ['Muja Muju', 'Semaki', 'Pandeyan'],
            'Danurejan' => ['Bausasran', 'Tegalpanggung', 'Suryatmajan'],
        ];

        foreach ($kecamatansYogya as $kecName => $kelurahas) {
            $kec = Kecamatan::firstOrCreate([
                'kabupaten_id' => $yogyakarta->id,
                'nama_kecamatan' => $kecName,
            ]);

            foreach ($kelurahas as $kelName) {
                Kelurahan::firstOrCreate([
                    'kecamatan_id' => $kec->id,
                    'nama_kelurahan' => $kelName,
                ]);
            }
        }
    }
}