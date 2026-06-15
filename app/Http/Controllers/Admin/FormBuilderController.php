<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormField;
use Illuminate\Http\Request;

class FormBuilderController extends Controller
{
    /**
     * Display the global form builder page.
     */
    public function index()
    {
        $fields = FormField::orderBy('sort_order')->orderBy('id')->get()->groupBy('section');
        $fieldTypes = FormField::fieldTypes();
        $widthOptions = FormField::widthOptions();
        $sections = ['Data Pribadi', 'Kontak & Alamat', 'Pendidikan Terakhir', 'Dokumen', 'Lainnya'];

        return view('settings.form-pendaftaran.form-builder', compact(
            'fields',
            'fieldTypes',
            'widthOptions',
            'sections'
        ));
    }

    /**
     * Store a new field via AJAX.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'field_type'   => 'required|string|max:50',
            'field_label'  => 'required|string|max:255',
            'field_name'   => 'required|string|max:100|unique:form_fields,field_name',
            'placeholder'  => 'nullable|string|max:255',
            'help_text'    => 'nullable|string',
            'section'      => 'nullable|string|max:100',
            'width'        => 'nullable|string|max:20',
            'is_required'  => 'boolean',
            'default_value'=> 'nullable|string',
            'options'      => 'nullable|array',
            'options.*'    => 'string|max:255',
        ]);

        $maxSort = FormField::max('sort_order');
        $validated['sort_order'] = ($maxSort ?? 0) + 1;
        $validated['is_required'] = $request->boolean('is_required');
        $validated['is_active'] = true;

        if ($request->has('options') && is_array($request->options)) {
            $validated['options'] = array_values(array_filter($request->options));
        } else {
            $validated['options'] = null;
        }

        $field = FormField::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Field berhasil ditambahkan',
                'field' => $field,
            ]);
        }

        return redirect()->route('settings.form-builder.index')
            ->with('success', 'Field berhasil ditambahkan.');
    }

    /**
     * Update field via AJAX.
     */
    public function update(Request $request, $id)
    {
        $field = FormField::findOrFail($id);

        $validated = $request->validate([
            'field_label'   => 'required|string|max:255',
            'placeholder'   => 'nullable|string|max:255',
            'help_text'     => 'nullable|string',
            'section'       => 'nullable|string|max:100',
            'width'         => 'nullable|string|max:20',
            'is_required'   => 'boolean',
            'default_value' => 'nullable|string',
            'options'       => 'nullable|array',
            'options.*'     => 'string|max:255',
        ]);

        $validated['is_required'] = $request->boolean('is_required');

        if ($request->has('options') && is_array($request->options)) {
            $validated['options'] = array_values(array_filter($request->options));
        } else {
            $validated['options'] = null;
        }

        $field->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Field berhasil diperbarui',
                'field' => $field,
            ]);
        }

        return redirect()->route('settings.form-builder.index')
            ->with('success', 'Field berhasil diperbarui.');
    }

    /**
     * Reorder fields via drag & drop.
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'fields' => 'required|array',
            'fields.*.id' => 'required|exists:form_fields,id',
            'fields.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($request->fields as $item) {
            FormField::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Urutan field berhasil diperbarui',
        ]);
    }

    /**
     * Toggle field active status.
     */
    public function toggleStatus($id)
    {
        $field = FormField::findOrFail($id);
        $field->update(['is_active' => !$field->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Status field berhasil diubah',
            'is_active' => $field->is_active,
        ]);
    }

    /**
     * Delete field.
     */
    public function destroy($id)
    {
        $field = FormField::findOrFail($id);
        $field->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Field berhasil dihapus',
            ]);
        }

        return redirect()->route('settings.form-builder.index')
            ->with('success', 'Field berhasil dihapus.');
    }

    /**
     * Get all fields (AJAX).
     */
    public function getFields()
    {
        $fields = FormField::orderBy('sort_order')->orderBy('id')->get()->groupBy('section');

        return response()->json([
            'success' => true,
            'fields' => $fields,
        ]);
    }

    /**
     * Duplicate a field.
     */
    public function duplicate($id)
    {
        $original = FormField::findOrFail($id);
        $copy = $original->replicate();
        $copy->field_name = $original->field_name . '_copy';
        $copy->sort_order = $original->sort_order + 1;
        $copy->is_active = true;
        $copy->save();

        $this->resortFields();

        return response()->json([
            'success' => true,
            'message' => 'Field berhasil diduplikasi',
        ]);
    }

    /**
     * Resort all fields.
     */
    private function resortFields()
    {
        $fields = FormField::orderBy('sort_order')->get();
        $order = 1;
        foreach ($fields as $field) {
            $field->update(['sort_order' => $order++]);
        }
    }
}