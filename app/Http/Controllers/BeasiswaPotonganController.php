<?php

namespace App\Http\Controllers;

use App\Models\MasterPotongan;
use App\Models\PlottingPotongan;
use App\Models\Registration;
use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BeasiswaPotonganController extends Controller
{
    /**
     * Display list of master scholarships and plottings.
     */
    public function index()
    {
        $masters = MasterPotongan::orderBy('created_at', 'desc')->get();
        
        $plottings = PlottingPotongan::with(['registration.user', 'registration.registrationPath', 'masterPotongan'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get all eligible registrations whose PMB registration payment is LUNAS
        $eligibleRegistrations = Registration::whereNotNull('paid_at')
            ->with(['user', 'registrationPath'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Attach total re-registration fee to each eligible registration
        foreach ($eligibleRegistrations as $reg) {
            $reg->total_biaya = \DB::table('jalur_pendaftaran_biayas')
                ->where('registration_path_id', $reg->registration_path_id)
                ->sum('nominal');
        }

        return view('settings.beasiswa-potongan.index', compact('masters', 'plottings', 'eligibleRegistrations'));
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
                ->withInput()
                ->with('active_tab', 'master');
        }

        $master = MasterPotongan::create($request->all());

        ActivityLogger::log('create', 'master_potongan', 'Created master scholarship/discount: ' . $master->nama_potongan);

        return redirect()->route('beasiswa-potongan.index')
            ->with('success', 'Master potongan berhasil ditambahkan.')
            ->with('active_tab', 'master');
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
                ->withInput()
                ->with('active_tab', 'master');
        }

        $master->update($request->all());

        ActivityLogger::log('update', 'master_potongan', 'Updated master scholarship/discount: ' . $master->nama_potongan);

        return redirect()->route('beasiswa-potongan.index')
            ->with('success', 'Master potongan berhasil diperbarui.')
            ->with('active_tab', 'master');
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
            ->with('success', 'Master potongan berhasil dihapus.')
            ->with('active_tab', 'master');
    }

    /**
     * Store a new Plotting Potongan.
     */
    public function storePlotting(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'registration_id' => 'required|exists:registrations,id|unique:plotting_potongans,registration_id',
            'master_potongan_id' => 'required|exists:master_potongans,id',
            'nominal_potongan' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
        ], [
            'registration_id.unique' => 'Calon mahasiswa ini sudah terdaftar mendapatkan potongan/beasiswa.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('active_tab', 'plotting');
        }

        $plotting = PlottingPotongan::create($request->all());

        ActivityLogger::log('create', 'plotting_potongan', 'Plotted scholarship for registration #' . $plotting->registration_id . ' with nominal: ' . $plotting->nominal_potongan);

        return redirect()->route('beasiswa-potongan.index')
            ->with('success', 'Plotting potongan mahasiswa berhasil ditambahkan.')
            ->with('active_tab', 'plotting');
    }

    /**
     * Update a Plotting Potongan.
     */
    public function updatePlotting(Request $request, $id)
    {
        $plotting = PlottingPotongan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'master_potongan_id' => 'required|exists:master_potongans,id',
            'nominal_potongan' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('active_tab', 'plotting');
        }

        $plotting->update($request->all());

        ActivityLogger::log('update', 'plotting_potongan', 'Updated plotting scholarship for registration #' . $plotting->registration_id . ' with nominal: ' . $plotting->nominal_potongan);

        return redirect()->route('beasiswa-potongan.index')
            ->with('success', 'Plotting potongan mahasiswa berhasil diperbarui.')
            ->with('active_tab', 'plotting');
    }

    /**
     * Delete a Plotting Potongan.
     */
    public function destroyPlotting($id)
    {
        $plotting = PlottingPotongan::findOrFail($id);
        $regId = $plotting->registration_id;
        $plotting->delete();

        ActivityLogger::log('delete', 'plotting_potongan', 'Deleted plotting scholarship for registration #' . $regId);

        return redirect()->route('beasiswa-potongan.index')
            ->with('success', 'Plotting potongan mahasiswa berhasil dihapus.')
            ->with('active_tab', 'plotting');
    }
}
