<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\RegistrationPath;
use App\Models\Form;
use App\Models\FormField;
use App\Models\Regency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Shuchkin\SimpleXLSXGen;

class ExcelImportExportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create CALON_MAHASISWA role
        Role::create(['role_code' => 'CALON_MAHASISWA', 'role_name' => 'Calon Mahasiswa']);

        // Create Super Admin user and sign in
        $admin = User::create([
            'name' => 'Admin Test',
            'username' => 'admin_test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'status' => 'active',
        ]);
        $adminRole = Role::create(['role_code' => 'SUPER_ADMIN', 'role_name' => 'Super Admin']);
        $admin->roles()->attach($adminRole->id);

        $this->actingAs($admin);
    }

    public function test_export_template()
    {
        $form = Form::create(['nama' => 'Form Test', 'is_active' => true]);
        FormField::ensureCoreFields($form->id);
        
        // Add a dynamic custom field
        FormField::create([
            'form_id' => $form->id,
            'field_type' => 'text',
            'field_name' => 'nisn',
            'field_label' => 'NISN',
            'is_required' => true,
            'is_active' => true,
            'is_system' => false,
            'sort_order' => 6,
        ]);

        $path = RegistrationPath::create([
            'name' => 'Jalur Test',
            'code' => 'TEST-01',
            'form_pendaftaran_id' => $form->id,
            'is_active' => true,
        ]);

        $response = $this->get(route('pendaftaran.export-template', $path->id));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->assertHeader('Content-Disposition', 'attachment; filename="Template_Import_jalur_test.xlsx"');
    }

    public function test_import_excel()
    {
        $form = Form::create(['nama' => 'Form Test', 'is_active' => true]);
        FormField::ensureCoreFields($form->id);
        
        // Add custom fields
        FormField::create([
            'form_id' => $form->id,
            'field_type' => 'text',
            'field_name' => 'nisn',
            'field_label' => 'NISN',
            'is_required' => true,
            'is_active' => true,
            'is_system' => false,
            'sort_order' => 6,
        ]);

        $path = RegistrationPath::create([
            'name' => 'Jalur Test',
            'code' => 'TEST-01',
            'form_pendaftaran_id' => $form->id,
            'is_active' => true,
        ]);

        // Create dummy regency
        $regency = Regency::create(['code' => '3404', 'name' => 'KAB. SLEMAN']);

        // Build Excel content with header, sample row (will be skipped), and actual data row
        $headers = ['Nama Lengkap', 'Jenis Kelamin (L/P)', 'Email', 'Nomor Handphone', 'Domisili Kabupaten', 'NISN'];
        $sample = ['Budi Santoso', 'L', 'budi.santoso@example.com', '081234567890', 'KAB. SLEMAN', '1234567890'];
        $data = ['Andi Wijaya', 'L', 'andi@test.com', '087711223344', 'KAB. SLEMAN', '9876543210'];

        $xlsxContent = (string) SimpleXLSXGen::fromArray([$headers, $sample, $data]);

        // Write content to a temp file
        $tempFile = tempnam(sys_get_temp_dir(), 'import_') . '.xlsx';
        file_put_contents($tempFile, $xlsxContent);

        $uploadedFile = new UploadedFile(
            $tempFile,
            'import_test.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );

        $response = $this->post(route('pendaftaran.import'), [
            'registration_path_id' => $path->id,
            'file' => $uploadedFile,
        ]);

        $response->assertRedirect(route('pendaftaran.index'));
        $response->assertSessionHas('success');

        // Assert database records
        $this->assertDatabaseHas('users', [
            'name' => 'Andi Wijaya',
            'email' => 'andi@test.com',
            'phone' => '087711223344',
        ]);

        $this->assertDatabaseHas('registrations', [
            'nama_lengkap' => 'Andi Wijaya',
            'jenis_kelamin' => 'L',
            'email' => 'andi@test.com',
            'no_hp' => '087711223344',
            'regency_id' => $regency->id,
            'nisn' => '9876543210',
            'status' => 'accepted',
            'status_kelulusan' => 'Lulus',
            'status_pendaftaran' => 'Lulus',
        ]);

        @unlink($tempFile);
    }
}
