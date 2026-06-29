<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\FormField;
use App\Models\Registration;
use App\Models\RegistrationPath;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function showRegistrationForm($jalurId = null)
    {
        // If no jalur_id provided, try from query string
        if (!$jalurId) {
            $jalurId = request()->query('jalur_id');
        }

        $path = null;
        $formFields = collect();

        if ($jalurId) {
            $path = RegistrationPath::with(['programStudi', 'formPendaftaran.fields' => function ($q) {
                $q->active()->ordered();
            }])->find($jalurId);

            if ($path && $path->formPendaftaran) {
                $formFields = $path->formPendaftaran->fields;
            }
        }

        return view('auth.register', compact('path', 'formFields'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'jalur_id' => 'required|exists:registration_paths,id',
            'password' => 'required|string|min:8|confirmed',
            'pilihan_prodi' => 'required|array',
            'pilihan_prodi.1' => 'required|exists:program_studis,id',
            'pilihan_prodi.2' => 'nullable|exists:program_studis,id',
        ]);

        $path = RegistrationPath::with(['programStudi', 'templateBerkas.syaratDokumens', 'formPendaftaran.fields' => function ($q) {
            $q->active()->ordered();
        }])->findOrFail($request->jalur_id);

        // NOTE: Document (berkas) upload validation is REMOVED from this single-step registration.
        // File uploads (ijazah, KK, etc.) are handled in a separate dedicated document-upload step
        // after initial registration is complete. See RegistrationController@uploadDocuments or
        // the document-upload blade view for file upload handling.
        // The dynamic form fields below contain the only validated inputs at this stage.

        // Build dynamic validation rules from form fields
        $dynamicRules = [];
        $fieldLabels = [];
        if ($path->formPendaftaran) {
            foreach ($path->formPendaftaran->fields as $field) {
                $rules = [];

                if ($field->is_required) {
                    $rules[] = 'required';
                } else {
                    $rules[] = 'nullable';
                }

                switch ($field->field_type) {
                    case 'email':
                        $rules[] = 'email';
                        if ($field->field_name === 'email') {
                            $rules[] = 'unique:users,email';
                        }
                        break;
                    case 'number':
                        $rules[] = 'numeric';
                        break;
                    case 'tel':
                        $rules[] = 'string';
                        $rules[] = 'max:20';
                        break;
                    case 'date':
                        $rules[] = 'date';
                        break;
                    default:
                        $rules[] = 'string';
                        $rules[] = 'max:255';
                        break;
                }

                $dynamicRules['field_' . $field->id] = $rules;
                $fieldLabels['field_' . $field->id] = $field->field_label;
            }
        }

        $validated = $request->validate($dynamicRules, [], $fieldLabels);

        DB::beginTransaction();
        try {
            // STEP A: Extract name and email from dynamic fields for user creation
            $userName = '';
            $userEmail = '';
            $userPhone = '';

            if ($path->formPendaftaran) {
                foreach ($path->formPendaftaran->fields as $field) {
                    $value = $validated['field_' . $field->id] ?? '';
                    if ($field->field_name === 'nama_lengkap') {
                        $userName = $value;
                    } elseif ($field->field_name === 'email') {
                        $userEmail = $value;
                    } elseif ($field->field_name === 'no_hp') {
                        $userPhone = $value;
                    }
                }
            }

            // Fallback: use first field value as name if no nama_lengkap field
            if (empty($userName) && $path->formPendaftaran && $path->formPendaftaran->fields->isNotEmpty()) {
                $firstField = $path->formPendaftaran->fields->first();
                $userName = $validated['field_' . $firstField->id] ?? '';
            }

            // If still no email, generate a placeholder
            if (empty($userEmail)) {
                $userEmail = 'user_' . Str::random(8) . '@pendaftaran.local';
            }

            // Generate unique username
            $baseUsername = Str::slug($userName, '_') ?: 'user';
            $username = $baseUsername;
            $counter = 1;
            while (User::where('username', $username)->exists()) {
                $username = $baseUsername . '_' . $counter++;
            }

            // STEP B: Create User account
            $user = User::create([
                'name' => $userName ?: 'Calon Mahasiswa',
                'username' => $username,
                'email' => $userEmail,
                'phone' => $userPhone,
                'password' => $request->password,
                'status' => 'active',
            ]);

            // Assign CALON_MAHASISWA role
            $calonMahasiswa = Role::where('role_code', 'CALON_MAHASISWA')->first();
            if ($calonMahasiswa) {
                $user->roles()->attach($calonMahasiswa->id);
            }

            // STEP C: Create Registration record
            $registrationData = [
                'user_id' => $user->id,
                'registration_path_id' => $path->id,
                'program_studi_1_id' => $request->pilihan_prodi[1] ?? null,
                'program_studi_2_id' => $request->pilihan_prodi[2] ?? null,
                'memerlukan_ujian' => $path->is_ujian_online ? true : false,
                'memerlukan_wawancara' => $path->is_wawancara ? true : false,
                'status' => $path->is_upload_berkas ? 'documents_uploaded' : 'submitted',
            ];

            // Map dynamic fields to registration columns if they match known fields
            if ($path->formPendaftaran) {
                foreach ($path->formPendaftaran->fields as $field) {
                    $value = $validated['field_' . $field->id] ?? '';
                    if (!empty($value)) {
                        switch ($field->field_name) {
                            case 'nama_lengkap':
                                $registrationData['nama_lengkap'] = $value;
                                break;
                            case 'tempat_lahir':
                                $registrationData['tempat_lahir'] = $value;
                                break;
                            case 'tanggal_lahir':
                                $registrationData['tanggal_lahir'] = $value;
                                break;
                            case 'jenis_kelamin':
                                // Map full label to single-char enum (L/P)
                                $registrationData['jenis_kelamin'] = match (strtolower($value)) {
                                    'laki-laki', 'laki laki', 'l' => 'L',
                                    'perempuan', 'p' => 'P',
                                    default => null,
                                };
                                break;
                            case 'agama':
                                $registrationData['agama'] = $value;
                                break;
                            case 'nik':
                                $registrationData['nik'] = $value;
                                break;
                            case 'alamat':
                                $registrationData['alamat'] = $value;
                                break;
                            case 'kode_pos':
                                $registrationData['kode_pos'] = $value;
                                break;
                            case 'no_hp':
                                $registrationData['no_hp'] = $value;
                                break;
                            case 'email':
                                $registrationData['email'] = $value;
                                break;
                            case 'nama_sekolah':
                                $registrationData['nama_sekolah'] = $value;
                                break;
                            case 'jurusan':
                                $registrationData['jurusan'] = $value;
                                break;
                            case 'tahun_lulus':
                                $registrationData['tahun_lulus'] = $value;
                                break;
                        }
                    }
                }
            }

            $registration = Registration::create($registrationData);

            // Store files if uploaded
            if ($path->is_upload_berkas && $request->hasFile('dokumen_berkas')) {
                foreach ($request->file('dokumen_berkas') as $berkasId => $file) {
                    if ($file->isValid()) {
                        $berkas = \App\Models\SyaratDokumen::find($berkasId);
                        if ($berkas) {
                            $filePath = $file->store('registrations/' . $registration->id, 'public');
                            $registration->documents()->create([
                                'type' => \Illuminate\Support\Str::slug($berkas->nama_syarat, '_'),
                                'original_name' => $file->getClientOriginalName(),
                                'file_path' => $filePath,
                                'mime_type' => $file->getMimeType(),
                                'file_size' => $file->getSize(),
                            ]);
                        }
                    }
                }
            }

            // STEP D: Auto-login the user
            Auth::login($user);

            ActivityLogger::log('register_complete', 'auth', 'User registered via single-step wizard: ' . $userEmail . ', path: ' . $path->name);

            DB::commit();

            return redirect()->route('home')->with('success', 'Selamat! Akun Anda berhasil dibuat. Selamat datang di dashboard PMB.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Single-step registration failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withInput()->with('error', 'Terjadi kesalahan saat mendaftar. Silakan coba lagi. Error: ' . $e->getMessage());
        }
    }
}