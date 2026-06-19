<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\Registration;
use App\Models\RegistrationPath;
use App\Models\Wawancara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * WawancaraController - Manages interview scheduling and results for applicants.
 *
 * Only shows registrations where the Registration Path has gunakan_wawancara = true.
 * Business rules:
 * - Setting status_wawancara to 'Tidak Lolos' overrides any exam score.
 * - Scheduling updates sync to the applicant portal immediately.
 */
class WawancaraController extends Controller
{
    /**
     * Display a list of applicants needing/managing interview scheduling.
     */
    public function index()
    {
        // Get all registration paths that use interviews
        $pathIds = RegistrationPath::where('gunakan_wawancara', true)->pluck('id');

        // Get registrations for those paths, with wawancara data
        $registrations = Registration::whereIn('registration_path_id', $pathIds)
            ->with([
                'user',
                'registrationPath',
                'wawancara',
                'programStudi1',
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('wawancara.index', compact('registrations'));
    }

    /**
     * Store or update interview schedule (Date, Time, Location).
     */
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
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update or create wawancara record
        $wawancara = Wawancara::updateOrCreate(
            ['pendaftaran_id' => $request->pendaftaran_id],
            [
                'tanggal_wawancara' => $request->tanggal_wawancara,
                'jam_wawancara'     => $request->jam_wawancara,
                'lokasi_wawancara'  => $request->lokasi_wawancara,
                'nama_pewawancara'  => $request->nama_pewawancara,
            ]
        );

        ActivityLogger::log('update', 'wawancara', 'Interview scheduled for registration #' . $request->pendaftaran_id);

        return redirect()->route('wawancara.index')
            ->with('success', 'Jadwal wawancara berhasil disimpan.');
    }

    /**
     * Store interview result (status + notes).
     * If status is 'Tidak Lolos', the applicant is overridden as failed.
     */
    public function storeHasil(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pendaftaran_id'    => 'required|exists:registrations,id',
            'status_wawancara'  => 'required|in:Lolos,Tidak Lolos',
            'catatan_pewawancara' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $wawancara = Wawancara::updateOrCreate(
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