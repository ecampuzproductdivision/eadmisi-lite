<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\TemplateBerkas;
use App\Models\SyaratDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SyaratBerkasController extends Controller
{
    /**
     * Display list of all document requirement templates.
     */
    public function index(Request $request)
    {
        $query = TemplateBerkas::withCount('syaratDokumens');
        $templates = \App\Helpers\SortHelper::apply($query, ['id', 'nama_template', 'status', 'created_at'], 'id', 'desc')->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('syarat-berkas.partials.template_rows', compact('templates'))->render(),
                'next_page' => $templates->nextPageUrl(),
                'has_more' => $templates->hasMorePages(),
            ]);
        }

        return view('syarat-berkas.index', compact('templates'));
    }

    /**
     * Store a newly created template.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_template' => 'required|string|max:200',
            'deskripsi'     => 'nullable|string',
            'status_aktif'  => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        TemplateBerkas::create($request->all());

        ActivityLogger::log('create', 'template_berkas', 'Created template: ' . $request->nama_template);

        return redirect()->route('syarat-berkas.index')
            ->with('success', 'Template syarat berkas berhasil ditambahkan.');
    }

    /**
     * Show edit form (JSON).
     */
    public function edit(TemplateBerkas $templateBerkas)
    {
        return response()->json($templateBerkas);
    }

    /**
     * Update the specified template.
     */
    public function update(Request $request, TemplateBerkas $templateBerkas)
    {
        $validator = Validator::make($request->all(), [
            'nama_template' => 'required|string|max:200',
            'deskripsi'     => 'nullable|string',
            'status_aktif'  => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $templateBerkas->update($request->all());

        ActivityLogger::log('update', 'template_berkas', 'Updated template ID: ' . $templateBerkas->id);

        return redirect()->route('syarat-berkas.index')
            ->with('success', 'Template berhasil diperbarui.');
    }

    /**
     * Remove the specified template.
     */
    public function destroy(TemplateBerkas $templateBerkas)
    {
        $templateBerkas->syaratDokumens()->delete();
        $templateBerkas->delete();

        ActivityLogger::log('delete', 'template_berkas', 'Deleted template ID: ' . $templateBerkas->id);

        return redirect()->route('syarat-berkas.index')
            ->with('success', 'Template berhasil dihapus.');
    }

    /**
     * Toggle template status.
     */
    public function toggleStatus(TemplateBerkas $templateBerkas)
    {
        $templateBerkas->update(['status_aktif' => !$templateBerkas->status_aktif]);

        return redirect()->route('syarat-berkas.index')
            ->with('success', 'Status template berhasil diubah.');
    }

    // ========================================================================
    // CHILD: DOCUMENT REQUIREMENTS MANAGEMENT (Drill-down)
    // ========================================================================

    /**
     * Display documents inside a template.
     */
    public function kelolaDokumen(TemplateBerkas $templateBerkas)
    {
        $dokumens = $templateBerkas->syaratDokumens()
            ->orderBy('urutan')
            ->orderBy('id')
            ->paginate(10);

        if (request()->ajax()) {
            return response()->json([
                'html' => view('syarat-berkas.partials.dokumen_rows', compact('dokumens'))->render(),
                'next_page' => $dokumens->nextPageUrl(),
                'has_more' => $dokumens->hasMorePages(),
            ]);
        }

        return view('syarat-berkas.template-dokumen', compact('templateBerkas', 'dokumens'));
    }

    /**
     * Store a new document requirement.
     */
    public function storeDokumen(Request $request, TemplateBerkas $templateBerkas)
    {
        $validator = Validator::make($request->all(), [
            'nama_dokumen'      => 'required|string|max:200',
            'ekstensi_diizinkan'=> 'required|string|max:255',
            'max_size'          => 'required|integer|min:1',
            'status_wajib'      => 'boolean',
            'urutan'            => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        $data['template_berkas_id'] = $templateBerkas->id;
        if (empty($data['urutan'])) {
            $data['urutan'] = $templateBerkas->syaratDokumens()->max('urutan') + 1;
        }

        SyaratDokumen::create($data);

        ActivityLogger::log('create', 'syarat_dokumen', 'Added document: ' . $request->nama_dokumen . ' to template ID: ' . $templateBerkas->id);

        return redirect()->route('syarat-berkas.kelola-dokumen', $templateBerkas->id)
            ->with('success', 'Dokumen berhasil ditambahkan.');
    }

    /**
     * Update a document requirement.
     */
    public function updateDokumen(Request $request, TemplateBerkas $templateBerkas, SyaratDokumen $syaratDokumen)
    {
        $validator = Validator::make($request->all(), [
            'nama_dokumen'      => 'required|string|max:200',
            'ekstensi_diizinkan'=> 'required|string|max:255',
            'max_size'          => 'required|integer|min:1',
            'status_wajib'      => 'boolean',
            'urutan'            => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $syaratDokumen->update($request->all());

        ActivityLogger::log('update', 'syarat_dokumen', 'Updated document ID: ' . $syaratDokumen->id);

        return redirect()->route('syarat-berkas.kelola-dokumen', $templateBerkas->id)
            ->with('success', 'Dokumen berhasil diperbarui.');
    }

    /**
     * Remove a document requirement.
     */
    public function destroyDokumen(TemplateBerkas $templateBerkas, SyaratDokumen $syaratDokumen)
    {
        $templateId = $templateBerkas ? $templateBerkas->id : $syaratDokumen->template_berkas_id;
        $syaratDokumen->delete();

        ActivityLogger::log('delete', 'syarat_dokumen', 'Deleted document ID: ' . $syaratDokumen->id);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Item dokumen berhasil dihapus.'
            ]);
        }

        return redirect()->route('syarat-berkas.kelola-dokumen', $templateId)
            ->with('success', 'Item dokumen berhasil dihapus.');
    }
}