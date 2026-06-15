<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\SoalUjian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SoalUjianController extends Controller
{
    public function index(Request $request)
    {
        $soals = SoalUjian::orderBy('urutan')->orderBy('id')->paginate(10);
        if ($request->ajax()) {
            return response()->json([
                'html' => view('soal-ujian.partials.soal_rows', compact('soals'))->render(),
                'next_page' => $soals->nextPageUrl(),
                'has_more' => $soals->hasMorePages(),
            ]);
        }
        return view('soal-ujian.index', compact('soals'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pertanyaan' => 'required|string',
            'opsi_a' => 'required|string|max:255',
            'opsi_b' => 'required|string|max:255',
            'opsi_c' => 'required|string|max:255',
            'opsi_d' => 'required|string|max:255',
            'kunci_jawaban' => 'required|in:A,B,C,D',
            'skor' => 'required|integer|min:0|max:100',
            'urutan' => 'nullable|integer|min:0',
            'status_aktif' => 'boolean',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $data = $request->all();
        if (empty($data['urutan'])) {
            $data['urutan'] = SoalUjian::max('urutan') + 1;
        }
        SoalUjian::create($data);
        ActivityLogger::log('create', 'soal_ujian', 'Created exam question');
        return redirect()->route('soal-ujian.index')->with('success', 'Soal ujian berhasil ditambahkan.');
    }

    public function edit(SoalUjian $soalUjian)
    {
        return response()->json($soalUjian);
    }

    public function update(Request $request, SoalUjian $soalUjian)
    {
        $validator = Validator::make($request->all(), [
            'pertanyaan' => 'required|string',
            'opsi_a' => 'required|string|max:255',
            'opsi_b' => 'required|string|max:255',
            'opsi_c' => 'required|string|max:255',
            'opsi_d' => 'required|string|max:255',
            'kunci_jawaban' => 'required|in:A,B,C,D',
            'skor' => 'required|integer|min:0|max:100',
            'urutan' => 'nullable|integer|min:0',
            'status_aktif' => 'boolean',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $soalUjian->update($request->all());
        ActivityLogger::log('update', 'soal_ujian', 'Updated exam question ID: ' . $soalUjian->id);
        return redirect()->route('soal-ujian.index')->with('success', 'Soal ujian berhasil diperbarui.');
    }

    public function destroy(SoalUjian $soalUjian)
    {
        $soalUjian->delete();
        ActivityLogger::log('delete', 'soal_ujian', 'Deleted exam question ID: ' . $soalUjian->id);
        return redirect()->route('soal-ujian.index')->with('success', 'Soal ujian berhasil dihapus.');
    }

    public function toggleStatus(SoalUjian $soalUjian)
    {
        $soalUjian->update(['status_aktif' => !$soalUjian->status_aktif]);
        return redirect()->route('soal-ujian.index')->with('success', 'Status soal ujian berhasil diubah.');
    }
}