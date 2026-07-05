<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use App\Models\Kelurahan;

class WilayahController extends Controller
{
    public function getKecamatans($kabupaten_id)
    {
        $kecamatans = Kecamatan::where('regency_id', $kabupaten_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($kecamatans);
    }

    public function getKelurahans($kecamatan_id)
    {
        $kelurahans = Kelurahan::where('kecamatan_id', $kecamatan_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($kelurahans);
    }
}
