<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Helpers\SortHelper;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProgramStudiController extends Controller
{
    /**
     * Display a listing of study programs.
     */
    public function index(Request $request)
    {
        $query = ProgramStudi::query();

        $programStudis = SortHelper::apply($query, [
            'id', 'kode_prodi', 'nama_prodi', 'jurusan', 'jenjang_akademik',
            'kelompok', 'status_aktif', 'created_at'
        ], 'created_at', 'desc')->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('program-studi.partials.prodi_rows', compact('programStudis'))->render(),
                'next_page' => $programStudis->nextPageUrl(),
                'has_more' => $programStudis->hasMorePages(),
            ]);
        }

        return view('program-studi.index', compact('programStudis'));
    }

    /**
     * Show the form for creating a new study program.
     */
    public function create()
    {
        return view('program-studi.create');
    }

    /**
     * Store a newly created study program in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_prodi'      => 'required|string|max:20|unique:program_studis,kode_prodi',
            'label_nim'       => 'required|string|max:50',
            'nama_prodi'      => 'required|string|max:200',
            'jurusan'         => 'required|string|max:200',
            'jenjang_akademik' => 'required|string|in:S1,D3,D4,S2,S3',
            'kelompok'         => 'required|string|in:Eksakta,Non Eksakta',
            'program'          => 'required|string|max:50',
            'label_prodi_no_pendaftaran' => 'nullable|string|max:50',
            'status_aktif'     => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        ProgramStudi::create($request->all());

        ActivityLogger::log('create', 'program_studi', 'Created study program: ' . $request->kode_prodi);

        return redirect()->route('program-studi.index')
            ->with('success', 'Program Studi berhasil dibuat.');
    }

    /**
     * Display the specified study program.
     */
    public function show(ProgramStudi $programStudi)
    {
        return view('program-studi.show', compact('programStudi'));
    }

    /**
     * Show the form for editing the specified study program.
     */
    public function edit(ProgramStudi $programStudi)
    {
        return view('program-studi.edit', compact('programStudi'));
    }

    /**
     * Update the specified study program in storage.
     */
    public function update(Request $request, ProgramStudi $programStudi)
    {
        $validator = Validator::make($request->all(), [
            'kode_prodi'      => 'required|string|max:20|unique:program_studis,kode_prodi,' . $programStudi->id,
            'label_nim'       => 'required|string|max:50',
            'nama_prodi'      => 'required|string|max:200',
            'jurusan'         => 'required|string|max:200',
            'jenjang_akademik' => 'required|string|in:S1,D3,D4,S2,S3',
            'kelompok'         => 'required|string|in:Eksakta,Non Eksakta',
            'program'          => 'required|string|max:50',
            'label_prodi_no_pendaftaran' => 'nullable|string|max:50',
            'status_aktif'     => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $programStudi->update($request->all());

        ActivityLogger::log('update', 'program_studi', 'Updated study program: ' . $programStudi->kode_prodi);

        return redirect()->route('program-studi.index')
            ->with('success', 'Program Studi berhasil diperbarui.');
    }

    /**
     * Remove the specified study program from storage.
     */
    public function destroy(ProgramStudi $programStudi)
    {
        $kode = $programStudi->kode_prodi;
        $programStudi->delete();

        ActivityLogger::log('delete', 'program_studi', 'Deleted study program: ' . $kode);

        return redirect()->route('program-studi.index')
            ->with('success', 'Program Studi berhasil dihapus.');
    }
}