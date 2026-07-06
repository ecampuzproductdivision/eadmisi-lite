<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class RegionProxyController extends Controller
{
    /**
     * Proxy endpoint for Indonesian provinces.
     */
    public function provinces()
    {
        $response = Http::get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
        $data = collect($response->json())->map(fn($p) => [
            'id' => $p['id'],
            'text' => $p['name'],
        ]);
        return response()->json($data);
    }

    /**
     * Proxy endpoint for regencies (kabupaten/kota) with live search.
     * GET /api/regions/regencies?q=sleman
     */
    public function regencies()
    {
        $search = request('q', '');
        
        // Fetch all provinces first to find matching regencies across all
        $provinces = Http::get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')->json();
        $results = collect();
        
        foreach ($provinces as $province) {
            $regencies = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$province['id']}.json")->json();
            
            $filtered = collect($regencies)->filter(function($r) use ($search) {
                if (empty($search)) return true;
                return stripos($r['name'], $search) !== false;
            })->map(function($r) use ($province) {
                $type = str_starts_with($r['name'], 'KABUPATEN') ? 'Kab.' : 'Kota';
                $cleanName = str_replace(['KABUPATEN ', 'KOTA '], '', $r['name']);
                return [
                    'id' => $r['id'],
                    'text' => "{$type} {$cleanName}, {$province['name']}",
                ];
            });
            
            $results = $results->merge($filtered);
            
            // If searching, stop early when we have enough results
            if (!empty($search) && $results->count() > 50) break;
        }
        
        // Limit results for performance
        $results = $results->take(50)->values();
        
        return response()->json($results);
    }

    /**
     * Proxy endpoint for districts (kecamatan) by regency ID.
     * GET /api/regions/districts/{regency_id}?q=search
     */
    public function districts($regencyId)
    {
        $search = request('q', '');
        
        $response = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/districts/{$regencyId}.json");
        
        $results = collect($response->json())->filter(function($d) use ($search) {
            if (empty($search)) return true;
            return stripos($d['name'], $search) !== false;
        })->map(fn($d) => [
            'id' => $d['id'],
            'text' => $d['name'],
        ])->take(50)->values();
        
        return response()->json($results);
    }

    /**
     * Proxy endpoint for villages (kelurahan) by district ID.
     * GET /api/regions/villages/{district_id}?q=search
     */
    public function villages($districtId)
    {
        $search = request('q', '');
        
        $response = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/villages/{$districtId}.json");
        
        $results = collect($response->json())->filter(function($v) use ($search) {
            if (empty($search)) return true;
            return stripos($v['name'], $search) !== false;
        })->map(fn($v) => [
            'id' => $v['id'],
            'text' => $v['name'],
        ])->take(50)->values();
        
        return response()->json($results);
    }
}