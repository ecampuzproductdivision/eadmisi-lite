<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RegionalApiController extends Controller
{
    /**
     * GET /api/regions/regencies?q=search
     * Returns all ~500 regencies instantly using the cahya.github.io static JSON endpoint.
     */
    public function getRegencies(Request $request)
    {
        $search = strtolower($request->get('q', ''));
        
        // Use cahya.github.io static JSON (fast, no province iteration needed)
        $response = Http::timeout(5)->get('https://cahya.github.io/api-wilayah-indonesia/api/regencies.json');
        
        if ($response->successful()) {
            $data = $response->json() ?? [];
        } else {
            // Fallback: use local wilayah_indonesia.json file
            $jsonPath = public_path('assets/data/wilayah_indonesia.json');
            $data = json_decode(file_get_contents($jsonPath), true) ?? [];
            // Format: [{id: "Sleman", text: "Kab. Sleman, D.I. Yogyakarta"}]
            $results = collect($data)->map(fn($item) => [
                'id' => $item['id'],
                'text' => $item['text']
            ]);
            if (!empty($search)) {
                $results = $results->filter(fn($i) => str_contains(strtolower($i['text']), $search));
            }
            return response()->json($results->take(50)->values());
        }
        
        // Map the cahya data format: [{id: "1101", name: "KAB. SIMEULUE", province_id: "11"}]
        // to Select2 format: [{id: "1101", text: "Kab. Simeulue, Aceh"}]
        // We need province names. Fetch provinces once.
        $provinceResponse = Http::timeout(3)->get('https://cahya.github.io/api-wilayah-indonesia/api/provinces.json');
        $provinces = $provinceResponse->successful() ? collect($provinceResponse->json())->keyBy('id') : collect();
        
        $results = collect($data)->map(function ($item) use ($provinces) {
            $provinceName = $provinces->get($item['province_id'])['name'] ?? '';
            $name = $item['name'] ?? '';
            $type = str_starts_with($name, 'KAB.') ? 'Kab.' : 'Kota';
            $cleanName = str_replace(['KAB. ', 'KOTA '], '', $name);
            return [
                'id' => $item['id'],
                'text' => "{$type} {$cleanName}, {$provinceName}"
            ];
        });
        
        if (!empty($search)) {
            $results = $results->filter(fn($i) => str_contains(strtolower($i['text']), $search));
        }
        
        return response()->json($results->take(50)->values());
    }

    /**
     * GET /api/regions/districts/{regencyId}?q=search
     */
    public function getDistricts($regencyId)
    {
        $search = strtolower(request('q', ''));
        $response = Http::timeout(5)->get("https://cahya.github.io/api-wilayah-indonesia/api/districts/{$regencyId}.json");
        
        if (!$response->successful()) {
            return response()->json([]);
        }
        
        $data = $response->json() ?? [];
        $results = collect($data)->map(fn($i) => ['id' => $i['id'], 'text' => $i['name']]);
        
        if (!empty($search)) {
            $results = $results->filter(fn($i) => str_contains(strtolower($i['text']), $search));
        }
        
        return response()->json($results->take(50)->values());
    }

    /**
     * GET /api/regions/villages/{districtId}?q=search
     */
    public function getVillages($districtId)
    {
        $search = strtolower(request('q', ''));
        $response = Http::timeout(5)->get("https://cahya.github.io/api-wilayah-indonesia/api/villages/{$districtId}.json");
        
        if (!$response->successful()) {
            return response()->json([]);
        }
        
        $data = $response->json() ?? [];
        $results = collect($data)->map(fn($i) => ['id' => $i['id'], 'text' => $i['name']]);
        
        if (!empty($search)) {
            $results = $results->filter(fn($i) => str_contains(strtolower($i['text']), $search));
        }
        
        return response()->json($results->take(50)->values());
    }
}