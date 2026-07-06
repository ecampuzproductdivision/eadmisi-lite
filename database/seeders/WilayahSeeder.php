<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class WilayahSeeder extends Seeder
{
    /**
     * Seed the kecamatans and kelurahans tables using the public API.
     * Source: https://github.com/emsifa/api-wilayah-indonesia
     * 
     * API endpoints:
     *   https://emsifa.github.io/api-wilayah-indonesia/api/provinces.json
     *   https://emsifa.github.io/api-wilayah-indonesia/api/regencies/{province_id}.json
     *   https://emsifa.github.io/api-wilayah-indonesia/api/districts/{regency_id}.json
     *   https://emsifa.github.io/api-wilayah-indonesia/api/villages/{district_id}.json
     * 
     * This seeder uses local mapping since our regencies use numeric BPS codes as IDs.
     * Run: php artisan db:seed --class=WilayahSeeder
     */
    public function run(): void
    {
        $baseUrl = 'https://emsifa.github.io/api-wilayah-indonesia/api';
        
        $this->command->info('Starting Indonesian administrative regions seeding...');
        
        // ── Step 1: Fetch provinces ──
        $this->command->info('Fetching provinces...');
        $provinces = Http::get("$baseUrl/provinces.json")->json();
        $this->command->info('Found ' . count($provinces) . ' provinces.');
        
        $totalKecamatan = 0;
        $totalKelurahan = 0;
        
        foreach ($provinces as $province) {
            $provinceId = $province['id'];
            $provinceName = $province['name'];
            
            // ── Step 2: Fetch regencies for this province ──
            $regencies = Http::get("$baseUrl/regencies/$provinceId.json")->json();
            
            foreach ($regencies as $regency) {
                $regencyApiId = $regency['id']; // e.g., "3404"
                $regencyName = $regency['name']; // e.g., "KABUPATEN SLEMAN"
                
                // Check if this regency exists in our local DB
                $localRegency = DB::table('regencies')
                    ->where('name', str_replace('KABUPATEN ', '', str_replace('KOTA ', '', $regencyName)))
                    ->orWhereRaw("CONCAT(type, ' ', name) = ?", [$regencyName])
                    ->orWhereRaw("CONCAT(type, ' ', name, ', ', REPLACE(province, 'D.I. ', 'DI ')) LIKE ?", ['%' . $provinceName . '%'])
                    ->first();
                
                if (!$localRegency) {
                    // Try matching by the numeric ID directly
                    $localRegency = DB::table('regencies')
                        ->where('id', $regencyApiId)
                        ->first();
                }
                
                if (!$localRegency) {
                    $type = str_starts_with($regencyName, 'KABUPATEN') ? 'Kab.' : 'Kota';
                    $cleanName = str_replace(['KABUPATEN ', 'KOTA '], '', $regencyName);
                    
                    // Create if not exists
                    $localRegencyId = DB::table('regencies')->insertGetId([
                        'id' => $regencyApiId,
                        'code' => $regencyApiId,
                        'name' => $cleanName,
                        'type' => $type,
                        'province' => $provinceName,
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    $localRegencyId = $localRegency->id;
                }
                
                // ── Step 3: Fetch districts (kecamatan) for this regency ──
                $districts = Http::get("$baseUrl/districts/$regencyApiId.json")->json();
                
                foreach ($districts as $district) {
                    $districtApiId = $district['id'];
                    $districtName = $district['name'];
                    
                    // Upsert kecamatan
                    $existingKec = DB::table('kecamatans')
                        ->where('regency_id', $localRegencyId)
                        ->where('name', $districtName)
                        ->first();
                    
                    if (!$existingKec) {
                        $kecId = DB::table('kecamatans')->insertGetId([
                            'regency_id' => $localRegencyId,
                            'name' => $districtName,
                            'is_active' => true,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    } else {
                        $kecId = $existingKec->id;
                    }
                    $totalKecamatan++;
                    
                    // ── Step 4: Fetch villages (kelurahan) for this district ──
                    $villages = Http::get("$baseUrl/villages/$districtApiId.json")->json();
                    
                    foreach ($villages as $village) {
                        $existingKel = DB::table('kelurahans')
                            ->where('kecamatan_id', $kecId)
                            ->where('name', $village['name'])
                            ->first();
                        
                        if (!$existingKel) {
                            DB::table('kelurahans')->insert([
                                'kecamatan_id' => $kecId,
                                'name' => $village['name'],
                                'is_active' => true,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                        $totalKelurahan++;
                    }
                    
                    $this->command->info("  Seeded {$districtName} ({$districtApiId}) - " . count($villages) . " villages");
                }
                
                $this->command->info(" Seeded {$regencyName} - " . count($districts) . " districts");
            }
            
            $this->command->info("Seeded {$provinceName} ({$provinceId})");
        }
        
        $this->command->info("✅ COMPLETE: {$totalKecamatan} kecamatan, {$totalKelurahan} kelurahan seeded!");
    }
}