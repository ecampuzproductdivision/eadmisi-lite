<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;

class WilayahController extends Controller
{
    public function getKabupatens()
    {
        $kabupatens = Kabupaten::orderBy('nama_kabupaten')
            ->get(['id', 'nama_kabupaten']);

        return response()->json($kabupatens);
    }

    public function getKecamatans($kabupaten_id)
    {
        $kecamatans = Kecamatan::where('kabupaten_id', $kabupaten_id)
            ->where('is_active', true)
            ->orderBy('nama_kecamatan')
            ->get(['id', 'nama_kecamatan']);

        return response()->json($kecamatans);
    }

    public function getKelurahans($kecamatan_id)
    {
        $kelurahans = Kelurahan::where('kecamatan_id', $kecamatan_id)
            ->where('is_active', true)
            ->orderBy('nama_kelurahan')
            ->get(['id', 'nama_kelurahan']);

        return response()->json($kelurahans);
    }
}