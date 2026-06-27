<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\FormField;
use Illuminate\Http\Request;

class FormPendaftaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Form::withCount('fields');
        $forms = \App\Helpers\SortHelper::apply($query, ['nama', 'created_at'], 'created_at', 'desc')->paginate(20);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('settings.form-pendaftaran.partials.form_rows', compact('forms'))->render(),
                'next_page' => $forms->nextPageUrl(),
                'has_more' => $forms->hasMorePages(),
            ]);
        }

        return view('settings.form-pendaftaran.index', compact('forms'));
    }

    public function create()
    {
        return view('settings.form-pendaftaran.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $form = Form::create($validated);
        FormField::ensureCoreFields($form->id);

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
            ->with('success', 'Form "' . $form->nama . '" berhasil dibuat.');
    }

    public function builder($id)
    {
        $form = Form::with('fields')->findOrFail($id);
        FormField::ensureCoreFields($form->id);
        $form->load('fields');
        $fields = $form->fields->groupBy('section');
        $fieldTypes = FormField::fieldTypes();
        $widthOptions = FormField::widthOptions();
        $sections = ['Data Pribadi', 'Kontak & Alamat', 'Pendidikan Terakhir', 'Dokumen', 'Lainnya'];

        return view('settings.form-pendaftaran.builder', compact(
            'form', 'fields', 'fieldTypes', 'widthOptions', 'sections'
        ));
    }

    public function destroy($id)
    {
        $form = Form::findOrFail($id);
        $form->fields()->delete();
        $form->delete();
        return redirect()->route('settings.form-pendaftaran.index')
            ->with('success', 'Form berhasil dihapus.');
    }

    public function toggleStatus($id)
    {
        $form = Form::findOrFail($id);
        $form->update(['is_active' => !$form->is_active]);
        return redirect()->route('settings.form-pendaftaran.index')
            ->with('success', 'Status form berhasil diubah.');
    }

    public function storeField(Request $request)
    {
        $form = Form::findOrFail($request->form_id);
        $maxSort = $form->fields()->max('sort_order') ?? 0;
        $isRequired = $request->boolean('is_required');
        if ($request->is_required === '1' || $request->is_required === 'true' || $request->is_required === true) {
            $isRequired = true;
        }
        $fieldName = $request->field_name;
        if (!$fieldName) {
            $fieldName = 'field_' . ($maxSort + 1);
        }
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

    public function updateField(Request $request, $id)
    {
        $field = FormField::findOrFail($id);
        if ($field->isCoreField()) {
            return response()->json(['success' => false, 'message' => 'Field sistem tidak dapat diedit.'], 403);
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
        return response()->json(['success' => true, 'message' => 'Field berhasil diperbarui', 'field' => $field]);
    }

    public function reorderFields(Request $request)
    {
        $request->validate(['fields' => 'required|array', 'fields.*.id' => 'required|exists:form_fields,id', 'fields.*.sort_order' => 'required|integer|min:0']);
        foreach ($request->fields as $item) {
            FormField::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }
        return response()->json(['success' => true, 'message' => 'Urutan field berhasil diperbarui']);
    }

    public function toggleFieldStatus($id)
    {
        $field = FormField::findOrFail($id);
        if ($field->isCoreField()) {
            return response()->json(['success' => false, 'message' => 'Status field sistem tidak dapat diubah.'], 403);
        }
        $field->update(['is_active' => !$field->is_active]);
        return response()->json(['success' => true, 'message' => 'Status field berhasil diubah', 'is_active' => $field->is_active]);
    }

    public function duplicateField($id)
    {
        $original = FormField::findOrFail($id);
        if ($original->isCoreField()) {
            return response()->json(['success' => false, 'message' => 'Field sistem tidak dapat diduplikasi.'], 403);
        }
        $copy = $original->replicate();
        $copy->field_name = $original->field_name . '_copy';
        $copy->sort_order = $original->sort_order + 1;
        $copy->is_active = true;
        $copy->save();
        $this->resortFields($original->form_id);
        return response()->json(['success' => true, 'message' => 'Field berhasil diduplikasi']);
    }

    public function destroyField($id)
    {
        $field = FormField::findOrFail($id);
        if ($field->isCoreField()) {
            return response()->json(['success' => false, 'message' => 'Field sistem tidak dapat dihapus.'], 403);
        }
        $formId = $field->form_id;
        $field->delete();
        $this->resortFields($formId);
        return response()->json(['success' => true, 'message' => 'Field berhasil dihapus']);
    }

    public function getFields($formId)
    {
        FormField::ensureCoreFields($formId);
        $form = Form::with('fields')->findOrFail($formId);
        $fields = $form->fields->groupBy('section');
        return response()->json(['success' => true, 'fields' => $fields]);
    }

    private function resortFields($formId)
    {
        $coreFields = FormField::where('form_id', $formId)->system()->orderBy('sort_order')->get();
        $customFields = FormField::where('form_id', $formId)->notSystem()->orderBy('sort_order')->get();
        $order = 1;
        foreach ($coreFields as $field) { $field->update(['sort_order' => $order++]); }
        foreach ($customFields as $field) { $field->update(['sort_order' => $order++]); }
    }
}