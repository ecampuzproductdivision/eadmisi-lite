<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\PaketSoal;
use App\Models\SoalUjian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaketSoalController extends Controller
{
    /**
     * Display a listing of all exam question packages.
     */
    public function index(Request $request)
    {
        $query = PaketSoal::withCount('soalUjians');
        $pakets = \App\Helpers\SortHelper::apply($query, ['id', 'nama_paket', 'status', 'created_at'], 'id', 'desc')->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('soal-ujian.partials.paket_rows', compact('pakets'))->render(),
                'next_page' => $pakets->nextPageUrl(),
                'has_more' => $pakets->hasMorePages(),
            ]);
        }

        return view('soal-ujian.index', compact('pakets'));
    }

    /**
     * Store a newly created package.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_paket' => 'required|string|max:200',
            'deskripsi'  => 'nullable|string',
            'status_aktif' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        PaketSoal::create($request->all());

        ActivityLogger::log('create', 'paket_soal', 'Created exam package: ' . $request->nama_paket);

        return redirect()->route('paket-soal.index')
            ->with('success', 'Paket soal berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified package.
     */
    public function edit(PaketSoal $paketSoal)
    {
        return response()->json($paketSoal);
    }

    /**
     * Update the specified package.
     */
    public function update(Request $request, PaketSoal $paketSoal)
    {
        $validator = Validator::make($request->all(), [
            'nama_paket' => 'required|string|max:200',
            'deskripsi'  => 'nullable|string',
            'status_aktif' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // If trying to activate, validate total skor == 100
        if ($request->boolean('status_aktif')) {
            $totalSkor = $paketSoal->soalUjians()->sum('skor');
            if ($totalSkor !== 100) {
                return redirect()->back()
                    ->withErrors(['status_aktif' => 'Total skor soal dalam paket harus tepat 100 untuk dapat diaktifkan. Saat ini: ' . $totalSkor])
                    ->withInput();
            }
        }

        $paketSoal->update($request->all());

        ActivityLogger::log('update', 'paket_soal', 'Updated exam package ID: ' . $paketSoal->id);

        return redirect()->route('paket-soal.index')
            ->with('success', 'Paket soal berhasil diperbarui.');
    }

    /**
     * Remove the specified package.
     */
    public function destroy(PaketSoal $paketSoal)
    {
        // Delete all questions in this package first
        $paketSoal->soalUjians()->delete();
        $paketSoal->delete();

        ActivityLogger::log('delete', 'paket_soal', 'Deleted exam package ID: ' . $paketSoal->id);

        return redirect()->route('paket-soal.index')
            ->with('success', 'Paket soal berhasil dihapus.');
    }

    /**
     * Toggle status aktif/nonaktif.
     */
    public function toggleStatus(PaketSoal $paketSoal)
    {
        $newStatus = !$paketSoal->status_aktif;

        // If activating, validate total skor == 100
        if ($newStatus) {
            $totalSkor = $paketSoal->soalUjians()->sum('skor');
            if ($totalSkor !== 100) {
                return redirect()->route('paket-soal.index')
                    ->withErrors(['status_aktif' => 'Total skor soal dalam paket harus tepat 100 untuk dapat diaktifkan. Saat ini: ' . $totalSkor]);
            }
        }

        $paketSoal->update(['status_aktif' => $newStatus]);

        return redirect()->route('paket-soal.index')
            ->with('success', 'Status paket soal berhasil diubah.');
    }

    // ========================================================================
    // QUESTION MANAGEMENT INSIDE A PACKAGE (Drill-down view)
    // ========================================================================

    /**
     * Display questions inside a specific package.
     */
    public function kelolaSoal(PaketSoal $paketSoal)
    {
        $paketSoal->load('soalUjians');
        $soals = $paketSoal->soalUjians()->orderBy('urutan')->orderBy('id')->paginate(10);

        if (request()->ajax()) {
            return response()->json([
                'html' => view('soal-ujian.partials.soal_rows', compact('soals'))->render(),
                'next_page' => $soals->nextPageUrl(),
                'has_more' => $soals->hasMorePages(),
            ]);
        }

        return view('soal-ujian.package-questions', compact('paketSoal', 'soals'));
    }

    /**
     * Store a new question inside a package.
     */
    public function storeQuestion(Request $request, PaketSoal $paketSoal)
    {
        $validator = Validator::make($request->all(), [
            'pertanyaan'    => 'required|string',
            'opsi_a'        => 'required|string|max:255',
            'opsi_b'        => 'required|string|max:255',
            'opsi_c'        => 'required|string|max:255',
            'opsi_d'        => 'required|string|max:255',
            'kunci_jawaban' => 'required|in:A,B,C,D',
            'skor'          => 'required|integer|min:0|max:100',
            'urutan'        => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['paket_soal_id'] = $paketSoal->id;
        if (empty($data['urutan'])) {
            $data['urutan'] = $paketSoal->soalUjians()->max('urutan') + 1;
        }

        SoalUjian::create($data);

        ActivityLogger::log('create', 'soal_ujian', 'Created question in package ID: ' . $paketSoal->id);

        return redirect()->route('paket-soal.kelola-soal', $paketSoal->id)
            ->with('success', 'Soal berhasil ditambahkan ke paket.');
    }

    /**
     * Update a question inside a package.
     */
    public function updateQuestion(Request $request, PaketSoal $paketSoal, SoalUjian $soalUjian)
    {
        $validator = Validator::make($request->all(), [
            'pertanyaan'    => 'required|string',
            'opsi_a'        => 'required|string|max:255',
            'opsi_b'        => 'required|string|max:255',
            'opsi_c'        => 'required|string|max:255',
            'opsi_d'        => 'required|string|max:255',
            'kunci_jawaban' => 'required|in:A,B,C,D',
            'skor'          => 'required|integer|min:0|max:100',
            'urutan'        => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $soalUjian->update($request->all());

        ActivityLogger::log('update', 'soal_ujian', 'Updated question ID: ' . $soalUjian->id . ' in package ID: ' . $paketSoal->id);

        return redirect()->route('paket-soal.kelola-soal', $paketSoal->id)
            ->with('success', 'Soal berhasil diperbarui.');
    }

    /**
     * Remove a question from a package.
     */
    public function destroyQuestion(PaketSoal $paketSoal, SoalUjian $soalUjian)
    {
        $soalUjian->delete();

        ActivityLogger::log('delete', 'soal_ujian', 'Deleted question ID: ' . $soalUjian->id . ' from package ID: ' . $paketSoal->id);

        return redirect()->route('paket-soal.kelola-soal', $paketSoal->id)
            ->with('success', 'Soal berhasil dihapus dari paket.');
    }

    /**
     * Toggle status of a question inside a package.
     */
    public function toggleQuestionStatus(PaketSoal $paketSoal, SoalUjian $soalUjian)
    {
        $soalUjian->update(['status_aktif' => !$soalUjian->status_aktif]);

        return redirect()->route('paket-soal.kelola-soal', $paketSoal->id)
            ->with('success', 'Status soal berhasil diubah.');
    }
}