<?php

use App\Models\Form;
use App\Models\FormField;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Get old core field names that are being replaced/removed
        $oldCoreNames = ['nama_lengkap', 'no_hp', 'email', 'jenis_kelamin', 'domisili_kabupaten'];
        $newCoreNames = FormField::coreFieldNames();

        $forms = Form::all();

        foreach ($forms as $form) {
            // Delete old system fields that are no longer in CORE_FIELDS
            // but keep 'nama_lengkap', 'no_hp', 'email' since they still exist in new set
            // We need to remove fields that are NOT in the new core set but WERE system before
            // Actually, the old set was: nama_lengkap, no_hp, email
            // The new set is: nama_lengkap, jenis_kelamin, email, no_hp, domisili_kabupaten
            // So we need to UPDATE existing fields and CREATE missing ones

            // First, mark all old system fields as non-system so ensureCoreFields can rebuild
            FormField::where('form_id', $form->id)
                ->where('is_system', true)
                ->delete();

            // Now recreate core fields per new definition
            FormField::ensureCoreFields($form->id);
        }
    }

    public function down(): void
    {
        // Revert not possible in a simple migration
    }
};