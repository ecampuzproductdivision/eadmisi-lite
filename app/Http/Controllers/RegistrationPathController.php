<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\KategoriJalur;
use App\Models\RegistrationPath;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegistrationPathController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $paths = RegistrationPath::with('kategori')->orderBy('created_at', 'desc')->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('registration-paths.partials.path_rows', compact('paths'))->render(),
                'next_page' => $paths->nextPageUrl(),
                'has_more' => $paths->hasMorePages(),
            ]);
        }

        return view('registration-paths.index', compact('paths'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = KategoriJalur::orderBy('nama')->get();
        return view('registration-paths.create', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50|unique:registration_paths,code',
            'name' => 'required|string|max:200',
            'description' => 'nullable|string',
            'registration_start' => 'nullable|date',
            'registration_end' => 'nullable|date|after_or_equal:registration_start',
            'fee' => 'nullable|numeric|min:0',
            'color' => 'nullable|string|max:20',
            'quota' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        RegistrationPath::create($request->all());

        ActivityLogger::log('create', 'registration_path', 'Created registration path: ' . $request->code);

        return redirect()->route('registration-paths.index')
            ->with('success', 'Jalur Pendaftaran berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(RegistrationPath $registrationPath)
    {
        $registrationPath->load('kategori');
        return view('registration-paths.show', compact('registrationPath'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RegistrationPath $registrationPath)
    {
        $kategoris = KategoriJalur::orderBy('nama')->get();
        return view('registration-paths.edit', compact('registrationPath', 'kategoris'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RegistrationPath $registrationPath)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50|unique:registration_paths,code,' . $registrationPath->id,
            'name' => 'required|string|max:200',
            'description' => 'nullable|string',
            'registration_start' => 'nullable|date',
            'registration_end' => 'nullable|date|after_or_equal:registration_start',
            'fee' => 'nullable|numeric|min:0',
            'color' => 'nullable|string|max:20',
            'quota' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $registrationPath->update($request->all());

        ActivityLogger::log('update', 'registration_path', 'Updated registration path: ' . $registrationPath->code);

        return redirect()->route('registration-paths.index')
            ->with('success', 'Jalur Pendaftaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RegistrationPath $registrationPath)
    {
        $code = $registrationPath->code;
        $registrationPath->delete();

        ActivityLogger::log('delete', 'registration_path', 'Deleted registration path: ' . $code);

        return redirect()->route('registration-paths.index')
            ->with('success', 'Jalur Pendaftaran berhasil dihapus.');
    }

    /**
     * Halaman publik daftar jalur pendaftaran dengan infinite scroll.
     */
    public function publicIndex()
    {
        $kategoris = KategoriJalur::orderBy('nama')->get();
        return view('pmb.registration-paths', compact('kategoris'));
    }

    /**
     * API: data jalur pendaftaran untuk infinite scroll.
     */
    public function apiList(Request $request)
    {
        $perPage = 6;
        $page = $request->get('page', 1);
        $kategoriId = $request->get('kategori_id');

        $query = RegistrationPath::with('kategori')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc');

        if ($kategoriId) {
            $query->where('kategori_jalur_id', $kategoriId);
        }

        $paths = $query->paginate($perPage, ['*'], 'page', $page);

        $data = $paths->map(function ($path) {
            return [
                'id' => $path->id,
                'code' => $path->code,
                'name' => $path->name,
                'description' => $path->description,
                'fee_formatted' => 'Rp ' . number_format($path->fee, 0, ',', '.'),
                'fee' => (int) $path->fee,
                'color' => $path->color ?? 'secondary',
                'quota' => $path->quota,
                'kategori' => $path->kategori ? $path->kategori->nama : null,
                'registration_start' => $path->registration_start?->format('d M Y'),
                'registration_end' => $path->registration_end?->format('d M Y'),
                'is_open' => $path->registration_start === null || $path->registration_end === null
                    ? true
                    : (now()->between($path->registration_start, $path->registration_end)),
            ];
        });

        return response()->json([
            'data' => $data,
            'current_page' => $paths->currentPage(),
            'last_page' => $paths->lastPage(),
            'per_page' => $paths->perPage(),
            'total' => $paths->total(),
            'has_more' => $paths->hasMorePages(),
        ]);
    }
}