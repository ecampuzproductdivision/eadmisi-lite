<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\Registration;
use App\Models\RegistrationPath;
use App\Models\Wawancara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WawancaraController extends Controller
{
    public function index(Request $request)
    {
        $pathIds = RegistrationPath::where('gunakan_wawancara', true)->pluck('id');

        $query = Registration::whereIn('registration_path_id', $pathIds)
            ->with(['user', 'registrationPath', 'wawancara', 'programStudi1']);
        $registrations = \App\Helpers\SortHelper::apply($query, ['created_at'], 'created_at', 'desc')->paginate(15);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('wawancara.partials.wawancara_rows', compact('registrations'))->render(),
                'next_page' => $registrations->nextPageUrl(),
                'has_more' => $registrations->hasMorePages(),
            ]);
        }

        return view('wawancara.index', compact('registrations'));
    }

    public function storeSchedule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pendaftaran_id'         => 'required|exists:registrations,id',
            'tanggal_wawancara'      => 'nullable|date',
            'jam_wawancara'          => 'nullable|string|max:10',
            'lokasi_wawancara'       => 'nullable|string|max:255',
            'nama_pewawancara'       => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Save schedule data
        $hasScheduleData = $request->filled('tanggal_wawancara')
            || $request->filled('jam_wawancara')
            || $request->filled('lokasi_wawancara')
            || $request->filled('nama_pewawancara');

        Wawancara::updateOrCreate(
            ['pendaftaran_id' => $request->pendaftaran_id],
            [
                'tanggal_wawancara' => $request->tanggal_wawancara,
                'jam_wawancara'     => $request->jam_wawancara,
                'lokasi_wawancara'  => $request->lokasi_wawancara,
                'nama_pewawancara'  => $request->nama_pewawancara,
            ]
        );

        // Auto-transition: if admin sets schedule data, update registration status_wawancara
        if ($hasScheduleData) {
            Registration::where('id', $request->pendaftaran_id)
                ->whereNull('status_wawancara')
                ->orWhere('status_wawancara', 'menunggu_penjadwalan_wawancara')
                ->update(['status_wawancara' => 'menunggu_wawancara']);
        }

        ActivityLogger::log('update', 'wawancara', 'Interview scheduled for registration #' . $request->pendaftaran_id);

        return redirect()->route('wawancara.index')
            ->with('success', 'Jadwal wawancara berhasil disimpan.');
    }

    public function storeHasil(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pendaftaran_id'    => 'required|exists:registrations,id',
            'status_wawancara'  => 'required|in:Lolos,Tidak Lolos',
            'catatan_pewawancara' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Wawancara::updateOrCreate(
            ['pendaftaran_id' => $request->pendaftaran_id],
            [
                'status_wawancara'    => $request->status_wawancara,
                'catatan_pewawancara' => $request->catatan_pewawancara,
            ]
        );

        ActivityLogger::log('update', 'wawancara', 'Interview result for registration #' . $request->pendaftaran_id . ': ' . $request->status_wawancara);

        return redirect()->route('wawancara.index')
            ->with('success', 'Hasil wawancara berhasil disimpan.');
    }
}