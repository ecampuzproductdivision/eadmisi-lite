<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\FormField;
use Illuminate\Http\Request;

class FormPendaftaranController extends Controller
{
    /**
     * 1. LIST DATA - Tampilkan semua form.
     */
    public function index()
    {
        $forms = Form::withCount('fields')->orderBy('created_at', 'desc')->paginate(20);
        return view('settings.form-pendaftaran.index', compact('forms'));
    }

    /**
     * 2. TAMBAH DATA - Form untuk membuat form baru (isi nama).
     */
    public function create()
    {
        return view('settings.form-pendaftaran.create');
    }

    /**
     * Simpan form baru beserta field dari builder.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $form = Form::create($validated);

        // Auto-create core system fields (Nama Lengkap, No. WA, Email)
        FormField::ensureCoreFields($form->id);

        // Simpan fields dari builder jika ada (start after core fields)
        if ($request->has('fields') && is_array($request->fields)) {
            $order = 1;
            foreach ($request->fields as $fieldData) {
                $form->fields()->create([
                    'field_type'    => $fieldData['field_type'] ?? 'text',
                    'field_name'    => $fieldData['field_name'] ?? 'field_' . $order,
                    'field_label'   => $fieldData['field_label'] ?? 'Field ' . $order,
                    'placeholder'   => $fieldData['placeholder'] ?? null,
                    'help_text'     => $fieldData['help_text'] ?? null,
                    'options'       => isset($fieldData['options']) ? $fieldData['options'] : null,
                    'section'       => $fieldData['section'] ?? null,
                    'width'         => $fieldData['width'] ?? 'col-12',
                    'is_required'   => $fieldData['is_required'] ?? false,
                    'is_active'     => true,
                    'default_value' => $fieldData['default_value'] ?? null,
                    'sort_order'    => $order++,
                ]);
            }
        }

        return redirect()->route('settings.form-pendaftaran.builder', $form->id)
            ->with('success', 'Form "' . $form->nama . '" berhasil dibuat. Silakan atur field-formnya.');
    }

    /**
     * 3. BUILDER - Halaman builder untuk form tertentu (drag & drop).
     */
    public function builder($id)
    {
        $form = Form::with('fields')->findOrFail($id);

        // Ensure core fields exist for this form
        FormField::ensureCoreFields($form->id);

        // Reload after ensuring core fields
        $form->load('fields');
        $fields = $form->fields->groupBy('section');
        $fieldTypes = FormField::fieldTypes();
        $widthOptions = FormField::widthOptions();
        $sections = ['Data Pribadi', 'Kontak & Alamat', 'Pendidikan Terakhir', 'Dokumen', 'Lainnya'];

        return view('settings.form-pendaftaran.builder', compact(
            'form', 'fields', 'fieldTypes', 'widthOptions', 'sections'
        ));
    }

    /**
     * Hapus form.
     */
    public function destroy($id)
    {
        $form = Form::findOrFail($id);
        $form->fields()->delete();
        $form->delete();

        return redirect()->route('settings.form-pendaftaran.index')
            ->with('success', 'Form berhasil dihapus.');
    }

    /**
     * Toggle status form aktif/nonaktif.
     */
    public function toggleStatus($id)
    {
        $form = Form::findOrFail($id);
        $form->update(['is_active' => !$form->is_active]);

        return redirect()->route('settings.form-pendaftaran.index')
            ->with('success', 'Status form berhasil diubah.');
    }

    // ================ AJAX untuk Form Builder ================

    /**
     * Store field via AJAX (untuk form builder).
     */
    public function storeField(Request $request)
    {
        $form = Form::findOrFail($request->form_id);
        
        $maxSort = $form->fields()->max('sort_order') ?? 0;
        
        // handle is_required from JSON or form-data
        $isRequired = $request->boolean('is_required');
        if ($request->is_required === '1' || $request->is_required === 'true' || $request->is_required === true) {
            $isRequired = true;
        }
        
        // generate unique field_name if missing
        $fieldName = $request->field_name;
        if (!$fieldName) {
            $fieldName = 'field_' . ($maxSort + 1);
        }
        // ensure unique per form
        $baseName = $fieldName;
        $counter = 1;
        while (FormField::where('form_id', $form->id)->where('field_name', $fieldName)->exists()) {
            $fieldName = $baseName . '_' . $counter++;
        }

        $data = [
            'form_id'       => $form->id,
            'field_type'    => $request->field_type ?: 'text',
            'field_label'   => $request->field_label ?: 'Field ' . ($maxSort + 1),
            'field_name'    => $fieldName,
            'placeholder'   => $request->placeholder,
            'help_text'     => $request->help_text,
            'section'       => $request->section,
            'width'         => $request->width ?: 'col-12',
            'is_required'   => $isRequired,
            'is_active'     => true,
            'default_value' => $request->default_value,
            'sort_order'    => $maxSort + 1,
        ];

        // Handle options for select/radio/checkbox
        if ($request->has('options') && is_array($request->options)) {
            $data['options'] = array_values(array_filter($request->options, function($v) { return !empty($v); }));
        } else {
            $data['options'] = null;
        }

        $field = $form->fields()->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Field berhasil ditambahkan',
            'field' => $field,
        ]);
    }

    /**
     * Update field via AJAX.
     */
    public function updateField(Request $request, $id)
    {
        $field = FormField::findOrFail($id);

        // Prevent editing core/system fields
        if ($field->isCoreField()) {
            return response()->json([
                'success' => false,
                'message' => 'Field sistem tidak dapat diedit.',
            ], 403);
        }

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

        return response()->json([
            'success' => true,
            'message' => 'Field berhasil diperbarui',
            'field' => $field,
        ]);
    }

    /**
     * Reorder fields via drag & drop.
     */
    public function reorderFields(Request $request)
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
     * Toggle field status via AJAX.
     */
    public function toggleFieldStatus($id)
    {
        $field = FormField::findOrFail($id);

        // Prevent toggling core/system fields
        if ($field->isCoreField()) {
            return response()->json([
                'success' => false,
                'message' => 'Status field sistem tidak dapat diubah.',
            ], 403);
        }

        $field->update(['is_active' => !$field->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Status field berhasil diubah',
            'is_active' => $field->is_active,
        ]);
    }

    /**
     * Duplicate field via AJAX.
     */
    public function duplicateField($id)
    {
        $original = FormField::findOrFail($id);

        // Prevent duplicating core/system fields
        if ($original->isCoreField()) {
            return response()->json([
                'success' => false,
                'message' => 'Field sistem tidak dapat diduplikasi.',
            ], 403);
        }

        $copy = $original->replicate();
        $copy->field_name = $original->field_name . '_copy';
        $copy->sort_order = $original->sort_order + 1;
        $copy->is_active = true;
        $copy->save();

        $this->resortFields($original->form_id);

        return response()->json([
            'success' => true,
            'message' => 'Field berhasil diduplikasi',
        ]);
    }

    /**
     * Delete field via AJAX.
     */
    public function destroyField($id)
    {
        $field = FormField::findOrFail($id);

        // Prevent deleting core/system fields
        if ($field->isCoreField()) {
            return response()->json([
                'success' => false,
                'message' => 'Field sistem tidak dapat dihapus.',
            ], 403);
        }

        $formId = $field->form_id;
        $field->delete();

        $this->resortFields($formId);

        return response()->json([
            'success' => true,
            'message' => 'Field berhasil dihapus',
        ]);
    }

    /**
     * Get fields for a form via AJAX (untuk preview).
     */
    public function getFields($formId)
    {
        // Ensure core fields exist
        FormField::ensureCoreFields($formId);

        $form = Form::with('fields')->findOrFail($formId);
        $fields = $form->fields->groupBy('section');

        return response()->json([
            'success' => true,
            'fields' => $fields,
        ]);
    }

    private function resortFields($formId)
    {
        // Keep core fields first, then sort the rest
        $coreFields = FormField::where('form_id', $formId)->system()->orderBy('sort_order')->get();
        $customFields = FormField::where('form_id', $formId)->notSystem()->orderBy('sort_order')->get();

        $order = 1;
        foreach ($coreFields as $field) {
            $field->update(['sort_order' => $order++]);
        }
        foreach ($customFields as $field) {
            $field->update(['sort_order' => $order++]);
        }
    }
}