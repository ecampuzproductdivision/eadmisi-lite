<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\KomponenBiaya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KomponenBiayaController extends Controller
{
    public function index(Request $request)
    {
        $query = KomponenBiaya::orderBy('kode_komponen');
        $komponens = $query->paginate(20);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('komponen-biaya.partials.rows', compact('komponens'))->render(),
                'next_page' => $komponens->nextPageUrl(),
                'has_more' => $komponens->hasMorePages(),
            ]);
        }

        return view('komponen-biaya.index', compact('komponens'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_komponen' => 'required|string|max:50|unique:komponen_biayas,kode_komponen',
            'nama_komponen' => 'required|string|max:200',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['is_active'] = $request->boolean('is_active');

        KomponenBiaya::create($data);

        ActivityLogger::log('create', 'komponen_biaya', 'Created komponen biaya: ' . $request->kode_komponen);

        return redirect()->route('komponen-biaya.index')
            ->with('success', 'Komponen Biaya berhasil dibuat.');
    }

    public function update(Request $request, KomponenBiaya $komponenBiaya)
    {
        $validator = Validator::make($request->all(), [
            'kode_komponen' => 'required|string|max:50|unique:komponen_biayas,kode_komponen,' . $komponenBiaya->id,
            'nama_komponen' => 'required|string|max:200',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['is_active'] = $request->boolean('is_active');

        $komponenBiaya->update($data);

        ActivityLogger::log('update', 'komponen_biaya', 'Updated komponen biaya: ' . $komponenBiaya->kode_komponen);

        return redirect()->route('komponen-biaya.index')
            ->with('success', 'Komponen Biaya berhasil diperbarui.');
    }

    public function destroy(KomponenBiaya $komponenBiaya)
    {
        $kode = $komponenBiaya->kode_komponen;
        $komponenBiaya->delete();

        ActivityLogger::log('delete', 'komponen_biaya', 'Deleted komponen biaya: ' . $kode);

        return redirect()->route('komponen-biaya.index')
            ->with('success', 'Komponen Biaya berhasil dihapus.');
    }

    public function toggleStatus(KomponenBiaya $komponenBiaya)
    {
        $komponenBiaya->update([
            'is_active' => !$komponenBiaya->is_active,
        ]);

        return redirect()->route('komponen-biaya.index')
            ->with('success', 'Status Komponen Biaya berhasil diubah.');
    }
}