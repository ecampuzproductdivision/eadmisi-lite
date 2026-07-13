<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\RegistrationPath;
use App\Models\KategoriJalur;
use App\Models\FormField;
use App\Models\User;
use App\Models\Role;
use App\Models\Regency;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Shuchkin\SimpleXLSX;
use Shuchkin\SimpleXLSXGen;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    /**
     * Display a list of submitted registrations for Super Admin.
     */
    public function index(Request $request)
    {
        $query = Registration::with([
            'user',
            'registrationPath' => fn($q) => $q->withTrashed()->with('templateBerkas.syaratDokumens'),
            'programStudi1',
            'programStudi2',
            'documents',
            'payments',
            'examResults',
        ])->whereIn('status', ['submitted', 'documents_uploaded', 'payment_pending', 'payment_verified', 'exam_completed', 'reviewed', 'accepted', 'rejected', 'Menunggu Verifikasi Registrasi Ulang', 'registered', 'Lulus', 'Gagal']);

        // Filter by registration path
        if ($request->filled('path_id')) {
            $query->where('registration_path_id', $request->path_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by name, NIK, or registration number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%")
                  ->orWhere('no_pendaftaran', 'like', "%{$search}%");
            });
        }

        $registrations = $query->orderBy('created_at', 'desc')->paginate(15);

        $paths = RegistrationPath::where('is_active', true)->orderBy('name')->get();

        return view('pendaftaran.index', compact('registrations', 'paths'));
    }

    /**
     * Show detail of a registration.
     */
    public function show($id)
    {
        $registration = Registration::with([
            'user',
            'registrationPath' => fn($q) => $q->withTrashed()->with('templateBerkas.syaratDokumens'),
            'programStudi1',
            'programStudi2',
            'documents',
        ])->findOrFail($id);

        // Get required documents from the specific path's template
        $requiredDocuments = collect();
        if ($registration->registrationPath && $registration->registrationPath->templateBerkas) {
            $requiredDocuments = $registration->registrationPath->templateBerkas->syaratDokumens;
        }

        // Key uploaded documents by their syarat_dokumen id
        $uploadedDocuments = $registration->documents->keyBy('type');

        return view('pendaftaran.show', compact('registration', 'requiredDocuments', 'uploadedDocuments'));
    }

    /**
     * Verify re-registration and generate NIM for the student.
     */
    public function verifyReRegistration(Request $request, $id)
    {
        $registration = Registration::findOrFail($id);

        $request->validate([
            'nim' => 'required|string|max:20|unique:registrations,nim,' . $id,
        ], [
            'nim.unique' => 'NIM sudah terdaftar untuk mahasiswa lain.',
        ]);

        $registration->update([
            'nim' => $request->nim,
            'status' => 'registered',
        ]);

        \App\Helpers\ActivityLogger::log('update', 'registration', 'Admin approved re-registration and generated NIM: ' . $request->nim . ' for registration #' . $id);

        return redirect()->route('pendaftaran.show', $id)
            ->with('success', 'Registrasi ulang berhasil disetujui dan NIM ' . $request->nim . ' berhasil digenerate.');
    }

    /**
     * Bulk update kelulusan (Lulus / Gagal) for selected registrations.
     * Only students with payment verified can be processed.
     */
    public function bulkKelulusan(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|array',
            'selected_ids.*' => 'exists:registrations,id',
            'action' => 'required|in:Lulus,Gagal',
        ], [
            'selected_ids.required' => 'Pilih minimal satu pendaftar.',
            'action.in' => 'Aksi harus Lulus atau Gagal.',
        ]);

        $ids = $request->selected_ids;
        $action = $request->action;
        $count = 0;

        foreach ($ids as $id) {
            $registration = Registration::find($id);
            if (!$registration) continue;

            // Safety: only allow if payment is verified
            $paidInvoice = $registration->payments->firstWhere('transaction_status', 'success');
            if (!$paidInvoice) continue;

            // Skip if already in terminal state
            if (in_array($registration->status, ['Lulus', 'Gagal', 'rejected'])) continue;

            $updateData = [
                'status' => $action,
                'status_kelulusan' => $action === 'Lulus' ? 'Lulus' : 'Tidak Lulus',
                'status_pendaftaran' => $action === 'Lulus' ? 'Lulus' : 'Gagal',
            ];

            // TRIGGER 1: When candidate passes selection, set re-registration status
            if ($action === 'Lulus') {
                $updateData['status_registrasi_ulang'] = 'belum_registrasi';
            }

            $registration->update($updateData);

            \App\Helpers\ActivityLogger::log('update', 'registration', 'Admin set status ' . $action . ' for registration #' . $id);
            $count++;
        }

        $msg = $count > 0
            ? $count . ' pendaftar berhasil diupdate menjadi "' . $action . '".'
            : 'Tidak ada pendaftar yang dapat diproses.';

        return redirect()->route('pendaftaran.index')->with('success', $msg);
    }

    /**
     * Download dynamically generated Excel template based on the chosen path.
     */
    public function exportTemplate($pathId)
    {
        $path = RegistrationPath::findOrFail($pathId);
        
        $fields = FormField::where('form_id', $path->form_pendaftaran_id)
            ->where('is_active', true)
            ->notSystem()
            ->orderBy('sort_order')
            ->get();

        $staticHeaders = ['Nama Lengkap', 'Jenis Kelamin (L/P)', 'Email', 'Nomor Handphone', 'Domisili Kabupaten'];
        $dynamicHeaders = [];
        
        foreach ($fields as $field) {
            $dynamicHeaders[] = $field->field_label;
        }
        
        $headers = array_merge($staticHeaders, $dynamicHeaders);

        // Build sample dummy data row
        $sampleRow = ['Budi Santoso', 'L', 'budi.santoso@example.com', '081234567890', 'KAB. SLEMAN'];
        foreach ($fields as $field) {
            if ($field->field_type === 'select' || $field->field_type === 'radio') {
                $opts = $field->options;
                if (!empty($opts) && is_array($opts)) {
                    $sampleRow[] = $opts[0];
                } else {
                    $sampleRow[] = 'Pilihan';
                }
            } elseif ($field->field_type === 'date') {
                $sampleRow[] = '2005-08-17';
            } elseif ($field->field_type === 'number') {
                $sampleRow[] = '123456';
            } else {
                $sampleRow[] = 'Contoh ' . $field->field_label;
            }
        }

        $data = [
            $headers,
            $sampleRow
        ];

        $xlsx = SimpleXLSXGen::fromArray($data);
        $filename = 'Template_Import_' . Str::slug($path->name, '_') . '.xlsx';

        return response((string) $xlsx)
            ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Import candidate list from Excel upload.
     */
    public function importExcel(Request $request)
    {
        $request->validate([
            'registration_path_id' => 'required|exists:registration_paths,id',
            'file' => 'required|file',
        ], [
            'registration_path_id.required' => 'Jalur Pendaftaran wajib dipilih.',
            'file.required' => 'File Excel wajib diunggah.',
        ]);

        $file = $request->file('file');
        $filePath = $file->getRealPath();

        $xlsx = SimpleXLSX::parse($filePath);
        if (!$xlsx) {
            return redirect()->back()->with('error', 'Gagal memproses file Excel. Pastikan format file adalah .xlsx valid. Error: ' . SimpleXLSX::parseError());
        }

        $rows = $xlsx->rows();
        if (count($rows) <= 1) {
            return redirect()->back()->with('error', 'File Excel tidak memiliki baris data.');
        }

        $pathId = $request->registration_path_id;
        $path = RegistrationPath::findOrFail($pathId);
        $fields = FormField::where('form_id', $path->form_pendaftaran_id)
            ->where('is_active', true)
            ->notSystem()
            ->orderBy('sort_order')
            ->get();

        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        DB::beginTransaction();
        try {
            foreach ($rows as $index => $row) {
                if ($index === 0) continue; // Skip header
                
                // Trim all columns in the row
                $row = array_map(function($val) {
                    return is_string($val) ? trim($val) : $val;
                }, $row);

                // If the entire row is empty, skip
                if (empty(array_filter($row))) {
                    continue;
                }

                // Skip the sample row if present
                if (strtolower($row[0] ?? '') === 'budi santoso' || strtolower($row[2] ?? '') === 'budi.santoso@example.com') {
                    continue;
                }

                $nama_lengkap = $row[0] ?? null;
                $jenis_kelamin = $row[1] ?? null;
                $email = $row[2] ?? null;
                $no_hp = $row[3] ?? null;
                $domisili_kabupaten = $row[4] ?? null;

                if (empty($nama_lengkap)) {
                    $errors[] = "Baris " . ($index + 1) . ": Nama Lengkap wajib diisi.";
                    $errorCount++;
                    continue;
                }

                // Standardize gender
                $genderChar = null;
                if (!empty($jenis_kelamin)) {
                    $genderChar = match (strtolower($jenis_kelamin)) {
                        'laki-laki', 'laki laki', 'l' => 'L',
                        'perempuan', 'p' => 'P',
                        default => null,
                    };
                }

                // Find Regency ID
                $regencyId = null;
                if (!empty($domisili_kabupaten)) {
                    $regency = Regency::where('name', 'like', "%{$domisili_kabupaten}%")->first();
                    if ($regency) {
                        $regencyId = $regency->id;
                    }
                }

                // Fallback email if empty
                if (empty($email)) {
                    $email = 'camaba_' . Str::random(8) . '@eadmisi.test';
                }

                // Generate unique no_pendaftaran
                $noPendaftaran = Registration::generateNoPendaftaran($path->id);

                // Ensure unique username
                $username = $noPendaftaran;
                while (User::where('username', $username)->exists()) {
                    $username = $noPendaftaran . '_' . Str::random(4);
                }

                // Ensure unique email
                $finalEmail = $email;
                $emailCounter = 1;
                while (User::where('email', $finalEmail)->exists()) {
                    $parts = explode('@', $email);
                    $finalEmail = $parts[0] . '+' . $emailCounter++ . '@' . ($parts[1] ?? 'eadmisi.test');
                }

                // Create User account
                $user = User::create([
                    'name' => $nama_lengkap,
                    'username' => $username,
                    'email' => $finalEmail,
                    'phone' => $no_hp ?: '',
                    'password' => Hash::make('pendaftaran123'),
                    'status' => 'active',
                ]);

                // Attach role CALON_MAHASISWA
                $calonMahasiswaRole = Role::where('role_code', 'CALON_MAHASISWA')->first();
                if ($calonMahasiswaRole) {
                    $user->roles()->attach($calonMahasiswaRole->id);
                }

                // Create Registration Data
                $registrationData = [
                    'user_id' => $user->id,
                    'registration_path_id' => $path->id,
                    'no_pendaftaran' => $noPendaftaran,
                    'nama_lengkap' => $nama_lengkap,
                    'jenis_kelamin' => $genderChar,
                    'email' => $finalEmail,
                    'no_hp' => $no_hp,
                    'regency_id' => $regencyId,
                    'memerlukan_ujian' => $path->is_ujian_online ? true : false,
                    'memerlukan_wawancara' => $path->is_wawancara ? true : false,
                    'status' => 'accepted',
                    'status_kelulusan' => 'Lulus',
                    'status_pendaftaran' => 'Lulus',
                    'status_registrasi_ulang' => 'belum_registrasi',
                ];

                // Map Dynamic columns
                $colIdx = 5;
                foreach ($fields as $field) {
                    $val = $row[$colIdx] ?? '';
                    if (!empty($val)) {
                        switch ($field->field_name) {
                            case 'tempat_lahir':
                                $registrationData['tempat_lahir'] = $val;
                                break;
                            case 'tanggal_lahir':
                                try {
                                    $registrationData['tanggal_lahir'] = date('Y-m-d', strtotime($val));
                                } catch (\Exception $ex) {
                                    $registrationData['tanggal_lahir'] = null;
                                }
                                break;
                            case 'agama':
                                $registrationData['agama'] = $val;
                                break;
                            case 'nik':
                                $registrationData['nik'] = $val;
                                break;
                            case 'nisn':
                                $registrationData['nisn'] = $val;
                                break;
                            case 'nama_ibu_kandung':
                                $registrationData['nama_ibu_kandung'] = $val;
                                break;
                            case 'penerima_kps':
                                $registrationData['penerima_kps'] = $val;
                                break;
                            case 'kebutuhan_khusus':
                                $registrationData['kebutuhan_khusus'] = $val;
                                break;
                            case 'kewarganegaraan':
                                $registrationData['kewarganegaraan'] = $val;
                                break;
                            case 'alamat':
                                $registrationData['alamat'] = $val;
                                break;
                            case 'kode_pos':
                                $registrationData['kode_pos'] = $val;
                                break;
                            case 'nama_sekolah':
                                $registrationData['nama_sekolah'] = $val;
                                break;
                            case 'jurusan':
                                $registrationData['jurusan'] = $val;
                                break;
                            case 'tahun_lulus':
                                $registrationData['tahun_lulus'] = $val;
                                break;
                            case 'kabupaten':
                                $reg = Regency::where('name', 'like', "%{$val}%")->first();
                                if ($reg) {
                                    $registrationData['regency_id'] = $reg->id;
                                }
                                break;
                            case 'kecamatan':
                                $kec = Kecamatan::where('name', 'like', "%{$val}%")->first();
                                if ($kec) {
                                    $registrationData['kecamatan_id'] = $kec->id;
                                }
                                break;
                            case 'desa_kelurahan':
                                $kel = Kelurahan::where('name', 'like', "%{$val}%")->first();
                                if ($kel) {
                                    $registrationData['kelurahan_id'] = $kel->id;
                                }
                                break;
                        }
                    }
                    $colIdx++;
                }

                Registration::create($registrationData);
                $successCount++;
            }

            DB::commit();

            $message = "Berhasil mengimpor {$successCount} data calon mahasiswa.";
            if ($errorCount > 0) {
                $message .= " Gagal pada {$errorCount} baris.";
                return redirect()->route('pendaftaran.index')->with('success', $message)->withErrors($errors);
            }

            return redirect()->route('pendaftaran.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import Excel failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memproses impor data. Silakan periksa kembali file Anda. Detail: ' . $e->getMessage());
        }
    }
}
