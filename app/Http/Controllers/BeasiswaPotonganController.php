<?php

namespace App\Http\Controllers;

use App\Models\MasterPotongan;
use App\Models\PlottingPotongan;
use App\Models\Registration;
use App\Models\RegistrationPath;
use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BeasiswaPotonganController extends Controller
{
    /**
     * Display list of master scholarships.
     */
    public function index()
    {
        $masters = MasterPotongan::withCount('plottings')->orderBy('created_at', 'desc')->get();
        return view('settings.beasiswa-potongan.index', compact('masters'));
    }

    /**
     * Store a new Master Potongan.
     */
    public function storeMaster(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_potongan' => 'required|string|max:200',
            'tipe_potongan' => 'required|in:rupiah,persen',
            'nilai_potongan' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $master = MasterPotongan::create($request->all());

        ActivityLogger::log('create', 'master_potongan', 'Created master scholarship/discount: ' . $master->nama_potongan);

        return redirect()->route('beasiswa-potongan.index')
            ->with('success', 'Master potongan berhasil ditambahkan.');
    }

    /**
     * Update a Master Potongan.
     */
    public function updateMaster(Request $request, $id)
    {
        $master = MasterPotongan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_potongan' => 'required|string|max:200',
            'tipe_potongan' => 'required|in:rupiah,persen',
            'nilai_potongan' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $master->update($request->all());

        ActivityLogger::log('update', 'master_potongan', 'Updated master scholarship/discount: ' . $master->nama_potongan);

        return redirect()->route('beasiswa-potongan.index')
            ->with('success', 'Master potongan berhasil diperbarui.');
    }

    /**
     * Delete a Master Potongan.
     */
    public function destroyMaster($id)
    {
        $master = MasterPotongan::findOrFail($id);
        $name = $master->nama_potongan;
        $master->delete();

        ActivityLogger::log('delete', 'master_potongan', 'Deleted master scholarship/discount: ' . $name);

        return redirect()->route('beasiswa-potongan.index')
            ->with('success', 'Master potongan berhasil dihapus.');
    }

    /**
     * Show dedicated student plotting management page for a scholarship.
     */
    public function showPlotting($id, Request $request)
    {
        $master = MasterPotongan::findOrFail($id);

        // Load active Period and all Period options for filter
        $periodes = \App\Models\Periode::orderBy('nama_periode', 'desc')->get();
        $activePeriode = \App\Models\Periode::where('status_aktif', true)->first();
        $selectedPeriodeId = $request->input('periode_id', $activePeriode?->id);

        // Load all Registration Paths for filter
        $paths = RegistrationPath::orderBy('name')->get();
        $selectedPathId = $request->input('registration_path_id');

        // Text Search
        $search = $request->input('search');

        // Query eligible students (PMB paid)
        $query = Registration::whereNotNull('paid_at')
            ->with(['user', 'registrationPath', 'plottingPotongan.masterPotongan']);

        // Apply Periode filter
        if ($selectedPeriodeId) {
            $query->whereHas('registrationPath', function ($q) use ($selectedPeriodeId) {
                $q->where('periode_id', $selectedPeriodeId);
            });
        }

        // Apply Registration Path filter
        if ($selectedPathId) {
            $query->where('registration_path_id', $selectedPathId);
        }

        // Apply search by Name/NIK/No Pendaftaran
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('no_pendaftaran', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($qu) use ($search) {
                      $qu->where('name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%");
                  });
            });
        }

        $registrations = $query->orderBy('created_at', 'desc')->get();

        // Calculate and attach total fee + default scholarship discount for each student
        foreach ($registrations as $reg) {
            $reg->total_biaya = \DB::table('jalur_pendaftaran_biayas')
                ->where('registration_path_id', $reg->registration_path_id)
                ->sum('nominal');

            // Default calculated discount value based on master settings
            if ($reg->plottingPotongan && $reg->plottingPotongan->master_potongan_id == $master->id) {
                // If already plotted to THIS scholarship, keep current stored value
                $reg->calculated_potongan = (int) $reg->plottingPotongan->nominal_potongan;
            } else {
                if ($master->tipe_potongan === 'rupiah') {
                    $reg->calculated_potongan = (int) $master->nilai_potongan;
                } else {
                    $reg->calculated_potongan = (int) round(($reg->total_biaya * $master->nilai_potongan) / 100);
                }
            }
        }

        return view('settings.beasiswa-potongan.plotting', compact(
            'master', 'registrations', 'periodes', 'paths', 
            'selectedPeriodeId', 'selectedPathId', 'search'
        ));
    }

    /**
     * Save student plottings in bulk.
     */
    public function savePlotting(Request $request, $id)
    {
        $master = MasterPotongan::findOrFail($id);

        $checkedIds = $request->input('registration_ids', []);
        $nominals = $request->input('nominals', []);
        $keterangans = $request->input('keterangans', []);

        \Illuminate\Support\Facades\DB::transaction(function () use ($id, $checkedIds, $nominals, $keterangans) {
            // 1. Delete previous plottings for this master that are no longer checked
            PlottingPotongan::where('master_potongan_id', $id)
                ->whereNotIn('registration_id', $checkedIds)
                ->delete();

            // 2. Insert or update the checked plottings
            foreach ($checkedIds as $regId) {
                $nominal = (int) ($nominals[$regId] ?? 0);
                $keterangan = $keterangans[$regId] ?? null;

                PlottingPotongan::updateOrCreate(
                    ['registration_id' => $regId],
                    [
                        'master_potongan_id' => $id,
                        'nominal_potongan' => $nominal,
                        'keterangan' => $keterangan,
                    ]
                );
            }
        });

        ActivityLogger::log('update', 'plotting_potongan', 'Bulk updated student plottings for scholarship ID: ' . $id);

        return redirect()->route('beasiswa-potongan.plotting.show', $id)
            ->with('success', 'Plotting penerima beasiswa berhasil disimpan.');
    }
}
