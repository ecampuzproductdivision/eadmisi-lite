<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Helpers\PeriodeHelper;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * PeriodeController - Manages Academic Periods (Periode) Master.
 *
 * CRUD for academic periods + toggle activation.
 * Only ONE period can be active at a time.
 */
class PeriodeController extends Controller
{
    /**
     * Display a listing of academic periods.
     */
    public function index()
    {
        $periodes = Periode::orderBy('tahun_akademik', 'desc')
            ->orderByRaw("CASE WHEN semester = 'Ganjil' THEN 1 WHEN semester = 'Genap' THEN 2 WHEN semester = 'Pendek' THEN 3 ELSE 4 END")
            ->paginate(10);

        return view('periode.index', compact('periodes'));
    }

    /**
     * Store a newly created academic period.
     * Business Logic: Setting a period to Active auto-deactivates all others.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun_akademik' => 'required|string|max:20',
            'semester'       => 'required|in:Ganjil,Genap,Pendek',
            'status_aktif'   => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check for duplicate (tahun_akademik + semester)
        $exists = Periode::where('tahun_akademik', $request->tahun_akademik)
            ->where('semester', $request->semester)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withErrors(['tahun_akademik' => 'Kombinasi tahun akademik dan semester sudah ada.'])
                ->withInput();
        }

        // If user wants this period to be active, deactivate all others first
        $statusAktif = $request->boolean('status_aktif');
        if ($statusAktif) {
            Periode::where('status_aktif', true)->update(['status_aktif' => false]);
        }

        $periode = Periode::create([
            'tahun_akademik' => $request->tahun_akademik,
            'semester'       => $request->semester,
            'status_aktif'   => $statusAktif,
        ]);

        // Clear global cache for active period
        PeriodeHelper::clearCache();

        ActivityLogger::log('create', 'periode', 'Created academic period: ' . $periode->label);

        return redirect()->route('periode.index')
            ->with('success', 'Periode akademik berhasil ditambahkan.');
    }

    /**
     * Update the specified academic period.
     * Business Logic: Setting a period to Active auto-deactivates all others.
     */
    public function update(Request $request, Periode $periode)
    {
        $validator = Validator::make($request->all(), [
            'tahun_akademik' => 'required|string|max:20',
            'semester'       => 'required|in:Ganjil,Genap,Pendek',
            'status_aktif'   => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check for duplicate (exclude current record)
        $exists = Periode::where('tahun_akademik', $request->tahun_akademik)
            ->where('semester', $request->semester)
            ->where('id', '!=', $periode->id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withErrors(['tahun_akademik' => 'Kombinasi tahun akademik dan semester sudah ada.'])
                ->withInput();
        }

        // If user wants this period to be active, deactivate all others first
        $statusAktif = $request->boolean('status_aktif');
        if ($statusAktif) {
            Periode::where('status_aktif', true)
                ->where('id', '!=', $periode->id)
                ->update(['status_aktif' => false]);
        }

        $periode->update([
            'tahun_akademik' => $request->tahun_akademik,
            'semester'       => $request->semester,
            'status_aktif'   => $statusAktif,
        ]);

        // Clear global cache for active period
        PeriodeHelper::clearCache();

        ActivityLogger::log('update', 'periode', 'Updated academic period: ' . $periode->label);

        return redirect()->route('periode.index')
            ->with('success', 'Periode akademik berhasil diperbarui.');
    }

    /**
     * Toggle active status for a period.
     * Setting to active auto-deactivates all others.
     */
    public function toggleActive(Periode $periode)
    {
        // If deactivating, just turn it off
        if ($periode->status_aktif) {
            $periode->update(['status_aktif' => false]);
            $message = 'Periode akademik dinonaktifkan.';
        } else {
            // Activating: deactivate all others first
            Periode::where('status_aktif', true)
                ->where('id', '!=', $periode->id)
                ->update(['status_aktif' => false]);
            $periode->update(['status_aktif' => true]);
            $message = 'Periode akademik diaktifkan.';
        }

        // Clear global cache for active period
        PeriodeHelper::clearCache();

        ActivityLogger::log('update', 'periode', $message . ' (' . $periode->label . ')');

        return redirect()->route('periode.index')
            ->with('success', $message);
    }

    /**
     * Remove the specified academic period.
     */
    public function destroy(Periode $periode)
    {
        // If destroying the active period, clear cache
        $wasActive = $periode->status_aktif;
        $label = $periode->label;
        $periode->delete();

        if ($wasActive) {
            PeriodeHelper::clearCache();
        }

        ActivityLogger::log('delete', 'periode', 'Deleted academic period: ' . $label);

        return redirect()->route('periode.index')
            ->with('success', 'Periode akademik berhasil dihapus.');
    }

    /**
     * Get active period data (AJAX endpoint for global filter).
     */
    public function getActive()
    {
        $active = Periode::active()->first();

        if (!$active) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada periode aktif.',
                'data' => null,
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $active,
        ]);
    }
}