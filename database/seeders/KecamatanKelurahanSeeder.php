<?php

namespace Database\Seeders;

use App\Models\Regency;
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
        // 1. Seed for Sleman (Code: 3404)
        $sleman = Regency::where('code', '3404')->first();
        if ($sleman) {
            $kecamatansSleman = [
                'Depok' => ['Caturtunggal', 'Condongcatur', 'Maguwoharjo'],
                'Mlati' => ['Sinduadi', 'Sendangadi', 'Tlogoadi'],
                'Gamping' => ['Nogotirto', 'Trihanggo', 'Ambarketawang'],
                'Kalasan' => ['Tirtomartani', 'Selomartani', 'Purwomartani'],
                'Ngaglik' => ['Sariharjo', 'Minomartani', 'Sinduharjo'],
            ];

            foreach ($kecamatansSleman as $kecName => $kelurahas) {
                $kec = Kecamatan::firstOrCreate([
                    'regency_id' => $sleman->id,
                    'name' => $kecName,
                ]);

                foreach ($kelurahas as $kelName) {
                    Kelurahan::firstOrCreate([
                        'kecamatan_id' => $kec->id,
                        'name' => $kelName,
                    ]);
                }
            }
        }

        // 2. Seed for Yogyakarta (Code: 3471)
        $yogyakarta = Regency::where('code', '3471')->first();
        if ($yogyakarta) {
            $kecamatansYogya = [
                'Gondokusuman' => ['Terban', 'Kotabaru', 'Klitren'],
                'Umbulharjo' => ['Muja Muju', 'Semaki', 'Pandeyan'],
                'Danurejan' => ['Bausasran', 'Tegalpanggung', 'Suryatmajan'],
            ];

            foreach ($kecamatansYogya as $kecName => $kelurahas) {
                $kec = Kecamatan::firstOrCreate([
                    'regency_id' => $yogyakarta->id,
                    'name' => $kecName,
                ]);

                foreach ($kelurahas as $kelName) {
                    Kelurahan::firstOrCreate([
                        'kecamatan_id' => $kec->id,
                        'name' => $kelName,
                    ]);
                }
            }
        }
    }
}
