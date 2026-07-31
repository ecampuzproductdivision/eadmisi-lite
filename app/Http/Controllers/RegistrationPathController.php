<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Helpers\PeriodeHelper;
use App\Models\ExamQuestion;
use App\Models\ExamResult;
use App\Models\KategoriJalur;
use App\Models\Periode;
use App\Models\ProgramStudi;
use App\Models\Registration;
use App\Models\RegistrationDocument;
use App\Models\RegistrationPath;
use App\Models\SoalUjian;
use App\Models\PaketSoal;
use App\Models\TemplateBerkas;
use App\Models\Form;
use App\Models\JalurPendaftaranBiaya;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegistrationPathController extends Controller
{
    public const KATEGORI_JALUR_LIST = [
        'Penelusuran Minat dan Kemampuan (PMDK)',
        'Prestasi',
        'Program Kerjasama',
        'Seleksi Mandiri',
        'Seleksi Nasional Berdasarkan Tes (SNBT)',
        'Seleksi Nasional Berdasarkan Prestasi (SNBP)',
        'Program International',
        'Ujian Masuk Bersama Lainnya',
    ];

    public const JENIS_PENDAFTARAN_LIST = [
        'Peserta Didik Baru',
        'Pindahan',
        'Pendidikan Non Gelar (Course)',
        'Fast Track',
        'RPL Perolehan SKS',
        'RPL Transfer SKS',
        'PPG PGP / PLPG',
        'PPG Non PGP / PLPG',
    ];

    public function index(Request $request)
    {
        $query = RegistrationPath::with(['kategori', 'formPendaftaran', 'periode']);

        if ($request->filled('periode_id')) {
            $query->where('periode_id', $request->periode_id);
        }

        if ($request->filled('kategori')) {
            $query->where('kategori_jalur_id', $request->kategori);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $paths = \App\Helpers\SortHelper::apply($query, ['code', 'name', 'biaya', 'kuota', 'is_active'], 'code', 'asc')->paginate(10);
        $kategoris = KategoriJalur::orderBy('nama')->get();
        $periodes = Periode::orderByDesc('tahun_akademik')->orderBy('semester')->get();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('registration-paths.partials.path_rows', compact('paths'))->render(),
                'next_page' => $paths->nextPageUrl(),
                'has_more' => $paths->hasMorePages(),
            ]);
        }

        return view('registration-paths.index', compact('paths', 'kategoris', 'periodes'));
    }

    public function create()
    {
        $kategoris = KategoriJalur::orderBy('nama')->get();
        $periodes = Periode::orderByDesc('tahun_akademik')->get();
        $activePeriodeId = PeriodeHelper::getActiveId();
        $programStudis = ProgramStudi::active()->orderBy('nama')->get();
        $paketSoals = PaketSoal::active()->orderBy('nama_paket')->get();
        $templateBerkas = TemplateBerkas::active()->orderBy('nama_template')->get();
        $forms = Form::active()->orderBy('nama')->get();
        $listMasterKomponen = \App\Models\KomponenBiaya::active()->orderBy('kode_komponen')->get();
        $jenisPendaftaranList = self::JENIS_PENDAFTARAN_LIST;
        $kategoriJalurList = self::KATEGORI_JALUR_LIST;
        return view('registration-paths.create', compact('kategoris', 'periodes', 'activePeriodeId', 'programStudis', 'paketSoals', 'templateBerkas', 'forms', 'listMasterKomponen', 'jenisPendaftaranList', 'kategoriJalurList'));
    }

    public function store(Request $request)
    {
        if (!$request->boolean('gunakan_ujian')) {
            $request->merge([
                'paket_soal_id' => null,
                'nilai_ambang_batas' => null,
            ]);
        }

        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50|unique:registration_paths,code',
            'name' => 'required|string|max:200',
            'description' => 'nullable|string',
            'kategori_jalur' => 'nullable|string|max:100',
            'jenis_pendaftaran' => 'nullable|string|max:100',
            'form_pendaftaran_id' => 'nullable|exists:forms,id',
            'registration_start' => 'nullable|date',
            'registration_end' => 'nullable|date|after_or_equal:registration_start',
            'fee' => 'nullable|numeric|min:0',
            'color' => 'nullable|string|max:20',
            'quota' => 'nullable|integer|min:0',
            'jumlah_pilihan_prodi' => 'required|integer|in:1,2,3',
            'periode_id' => 'required|exists:periode,id',
            'program_studi_ids' => 'required|array|min:1',
            'program_studi_ids.*' => 'exists:program_studis,id',
            'is_active' => 'boolean',
            'gunakan_ujian' => 'boolean',
            'paket_soal_id' => 'nullable|exists:paket_soal,id',
            'gunakan_berkas' => 'boolean',
            'template_berkas_id' => 'nullable|exists:template_berkas,id',
            'metode_pengumuman' => 'required|in:langsung,ditahan,penilaian_manual',
            'gunakan_wawancara' => 'boolean',
            'nilai_ambang_batas' => 'nullable|integer|min:0|max:100',
        ]);

        if ($request->boolean('gunakan_wawancara')) {
            $request->merge(['metode_pengumuman' => 'ditahan']);
        } elseif (!$request->boolean('gunakan_ujian') && !$request->boolean('gunakan_wawancara')) {
            $request->merge(['metode_pengumuman' => 'penilaian_manual']);
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->boolean('gunakan_ujian') && $request->filled('paket_soal_id')) {
            $paket = PaketSoal::find($request->paket_soal_id);
            if ($paket && (int) $paket->total_skor !== 100) {
                return redirect()->back()
                    ->withErrors(['paket_soal_id' => 'Total skor paket soal (' . $paket->total_skor . ') harus tepat 100.'])
                    ->withInput();
            }
        }

        if ($request->boolean('gunakan_berkas') && !$request->filled('template_berkas_id')) {
            return redirect()->back()
                ->withErrors(['template_berkas_id' => 'Template syarat berkas wajib dipilih jika menggunakan unggah berkas.'])
                ->withInput();
        }

        $data = $request->except(['program_studi_ids', 'kategori_jalur']);

        // Handle kategori_jalur: store the selected category name directly
        if ($request->filled('kategori_jalur')) {
            $kategori = KategoriJalur::where('nama', $request->kategori_jalur)->first();
            $data['kategori_jalur_id'] = $kategori?->id;
        }

        $path = RegistrationPath::create($data);

        if ($request->has('program_studi_ids')) {
            $path->programStudis()->sync($request->program_studi_ids);
        }

        if ($request->has('komponen_id') && is_array($request->komponen_id)) {
            $biayaData = [];
            foreach ($request->komponen_id as $i => $komponenId) {
                if (empty($komponenId)) continue;
                $biayaData[$komponenId] = ['nominal' => $request->komponen_nominal[$i] ?? 0];
            }
            $path->komponenBiayas()->sync($biayaData);
        } else {
            $path->komponenBiayas()->sync([]);
        }

        ActivityLogger::log('create', 'registration_path', 'Created registration path: ' . $request->code);
        return redirect()->route('registration-paths.index')->with('success', 'Jalur Pendaftaran berhasil dibuat.');
    }

    public function show(RegistrationPath $registrationPath)
    {
        $registrationPath->load('kategori', 'programStudis', 'formPendaftaran');
        return view('registration-paths.show', compact('registrationPath'));
    }

    public function edit(RegistrationPath $registrationPath)
    {
        $kategoris = KategoriJalur::orderBy('nama')->get();
        $periodes = Periode::orderByDesc('tahun_akademik')->get();
        $activePeriodeId = PeriodeHelper::getActiveId();
        $programStudis = ProgramStudi::active()->orderBy('nama')->get();
        $paketSoals = PaketSoal::active()->orderBy('nama_paket')->get();
        $templateBerkas = TemplateBerkas::active()->orderBy('nama_template')->get();
        $forms = Form::active()->orderBy('nama')->get();
        $listMasterKomponen = \App\Models\KomponenBiaya::active()->orderBy('kode_komponen')->get();
        $registrationPath->load('programStudis', 'formPendaftaran', 'komponenBiayas');
        $jenisPendaftaranList = self::JENIS_PENDAFTARAN_LIST;
        $kategoriJalurList = self::KATEGORI_JALUR_LIST;
        return view('registration-paths.edit', compact('registrationPath', 'kategoris', 'periodes', 'activePeriodeId', 'programStudis', 'paketSoals', 'templateBerkas', 'forms', 'listMasterKomponen', 'jenisPendaftaranList', 'kategoriJalurList'));
    }

    public function update(Request $request, RegistrationPath $registrationPath)
    {
        if (!$request->boolean('gunakan_ujian')) {
            $request->merge(['paket_soal_id' => null, 'nilai_ambang_batas' => null]);
        }

        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50|unique:registration_paths,code,' . $registrationPath->id,
            'name' => 'required|string|max:200',
            'description' => 'nullable|string',
            'kategori_jalur' => 'nullable|string|max:100',
            'jenis_pendaftaran' => 'nullable|string|max:100',
            'form_pendaftaran_id' => 'nullable|exists:forms,id',
            'registration_start' => 'nullable|date',
            'registration_end' => 'nullable|date|after_or_equal:registration_start',
            'fee' => 'nullable|numeric|min:0',
            'color' => 'nullable|string|max:20',
            'quota' => 'nullable|integer|min:0',
            'jumlah_pilihan_prodi' => 'required|integer|in:1,2,3',
            'periode_id' => 'required|exists:periode,id',
            'program_studi_ids' => 'required|array|min:1',
            'program_studi_ids.*' => 'exists:program_studis,id',
            'is_active' => 'boolean',
            'gunakan_ujian' => 'boolean',
            'paket_soal_id' => 'nullable|exists:paket_soal,id',
            'gunakan_berkas' => 'boolean',
            'template_berkas_id' => 'nullable|exists:template_berkas,id',
            'metode_pengumuman' => 'required|in:langsung,ditahan,penilaian_manual',
            'gunakan_wawancara' => 'boolean',
            'nilai_ambang_batas' => 'nullable|integer|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->boolean('gunakan_ujian') && $request->filled('paket_soal_id')) {
            $paket = PaketSoal::find($request->paket_soal_id);
            if ($paket && (int) $paket->total_skor !== 100) {
                return redirect()->back()->withErrors(['paket_soal_id' => 'Total skor paket soal (' . $paket->total_skor . ') harus tepat 100.'])->withInput();
            }
        }

        if ($request->boolean('gunakan_berkas') && !$request->filled('template_berkas_id')) {
            return redirect()->back()->withErrors(['template_berkas_id' => 'Template syarat berkas wajib dipilih jika menggunakan unggah berkas.'])->withInput();
        }

        if ($request->boolean('gunakan_wawancara')) {
            $request->merge(['metode_pengumuman' => 'ditahan']);
        } elseif (!$request->boolean('gunakan_ujian') && !$request->boolean('gunakan_wawancara')) {
            $request->merge(['metode_pengumuman' => 'penilaian_manual']);
        }

        $data = $request->except(['program_studi_ids', 'kategori_jalur']);

        // Handle kategori_jalur: store the selected category name directly
        if ($request->filled('kategori_jalur')) {
            $kategori = KategoriJalur::where('nama', $request->kategori_jalur)->first();
            $data['kategori_jalur_id'] = $kategori?->id;
        }

        $registrationPath->update($data);

        if ($request->has('program_studi_ids')) {
            $registrationPath->programStudis()->sync($request->program_studi_ids);
        }

        if ($request->has('komponen_id') && is_array($request->komponen_id)) {
            $biayaData = [];
            foreach ($request->komponen_id as $i => $komponenId) {
                if (empty($komponenId)) continue;
                $biayaData[$komponenId] = ['nominal' => $request->komponen_nominal[$i] ?? 0];
            }
            $registrationPath->komponenBiayas()->sync($biayaData);
        } else {
            $registrationPath->komponenBiayas()->sync([]);
        }

        ActivityLogger::log('update', 'registration_path', 'Updated registration path: ' . $registrationPath->code);
        return redirect()->route('registration-paths.index')->with('success', 'Jalur Pendaftaran berhasil diperbarui.');
    }

    public function destroy(RegistrationPath $registrationPath)
    {
        $code = $registrationPath->code;
        $registrationPath->delete();
        ActivityLogger::log('delete', 'registration_path', 'Deleted registration path: ' . $code);
        return redirect()->route('registration-paths.index')->with('success', 'Jalur Pendaftaran berhasil dihapus.');
    }

    public function publicIndex()
    {
        $kategoris = KategoriJalur::orderBy('nama')->get();
        return view('pmb.registration-paths', compact('kategoris'));
    }

    public function daftarPmb()
    {
        $kategoris = KategoriJalur::orderBy('nama')->get();
        return view('daftar-pmb.index', compact('kategoris'));
    }

    public function registrationSteps($pathCode = null)
    {
        $path = null;
        $pathId = null;
        if ($pathCode) {
            $path = RegistrationPath::with('templateBerkas.syaratDokumens')
                ->where('code', $pathCode)
                ->first();
            $pathId = $path?->id;
        }

        $registration = Registration::where('user_id', auth()->id())
            ->where('registration_path_id', $pathId)
            ->with(['payments', 'examResults'])
            ->first();

        $isPaymentLocked = false;
        $hasPaidInvoice = false;
        if ($registration) {
            $payments = $registration->payments;
            $paidInvoice = $payments->firstWhere('transaction_status', 'success');
            $hasPaidInvoice = $paidInvoice !== null;
            $isPaymentLocked = !$hasPaidInvoice;
        }

        $hasExam = $path && $path->is_ujian_online;
        $hasManualVerification = $path && $path->metode_pengumuman === 'penilaian_manual';
        $hasWawancara = $path && $path->gunakan_wawancara;
        // Step count: if wawancara is enabled, inject interview step between exam and re-registration
        $totalSteps = $hasWawancara ? 6 : ($hasExam ? 5 : ($hasManualVerification ? 5 : 4));
        $hasStep4Content = $hasExam || $hasManualVerification || $hasWawancara;

        $isStep3Completed = false;
        $documentCount = 0;
        if ($path && $path->is_upload_berkas && $path->templateBerkas) {
            $totalRequiredDocs = $path->templateBerkas->syaratDokumens()->where('status_wajib', true)->count();
            if ($registration) {
                $documentCount = RegistrationDocument::where('registration_id', $registration->id)->count();
                $isStep3Completed = $totalRequiredDocs > 0 && $documentCount >= $totalRequiredDocs;
            }
        } else {
            $isStep3Completed = true;
        }

        $currentStep = 1;
        if ($registration) {
            // Special handling: Menunggu Verifikasi Registrasi Ulang -> always final step
            if (in_array($registration->status, ['Menunggu Verifikasi Registrasi Ulang', 'registered'])) {
                $currentStep = $totalSteps;
            } elseif ($registration->status === 'draft' && $registration->program_studi_1_id) {
                $currentStep = 2;
            } elseif ($registration->status === 'submitted') {
                $currentStep = 3;
            }
            // Only lock steps 3+ for non-re-registration statuses
            if ($currentStep !== $totalSteps && $isPaymentLocked && !in_array($registration->status, ['Menunggu Verifikasi Registrasi Ulang', 'registered']) && in_array($registration->status, ['submitted', 'documents_uploaded', 'payment_pending', 'payment_verified', 'exam_completed', 'reviewed', 'accepted', 'Lulus'])) {
                $currentStep = 2;
            }
            // Skip the cascade step calculation for re-registration statuses - already handled above
            if (!in_array($registration->status, ['Menunggu Verifikasi Registrasi Ulang', 'registered']) && in_array($registration->status, ['submitted', 'documents_uploaded', 'payment_pending', 'payment_verified', 'exam_completed', 'reviewed', 'accepted', 'rejected', 'Lulus', 'Gagal'])) {
                if (!$isPaymentLocked && $isStep3Completed) {
                    if ($hasExam && !in_array($registration->status, ['exam_completed', 'reviewed', 'accepted', 'rejected', 'Menunggu Verifikasi Registrasi Ulang', 'registered', 'Lulus', 'Gagal'])) {
                        $currentStep = 4;
                    } elseif ($hasExam && in_array($registration->status, ['exam_completed', 'reviewed', 'accepted', 'rejected', 'Menunggu Verifikasi Registrasi Ulang', 'registered', 'Lulus', 'Gagal'])) {
                        if ($hasWawancara) {
                            $isWawancaraCompleted = $registration->wawancara && in_array($registration->wawancara->status_wawancara, ['Lolos', 'Tidak Lolos']);
                            $isWawancaraCompleted = $isWawancaraCompleted || in_array($registration->status_wawancara, ['Lolos', 'Tidak Lolos']);
                            $currentStep = $isWawancaraCompleted ? $totalSteps : 5;
                        } else {
                            $currentStep = $totalSteps;
                        }
                    } elseif ($hasManualVerification && in_array($registration->status, ['Lulus', 'accepted', 'Menunggu Verifikasi Registrasi Ulang', 'registered', 'payment_pending'])) {
                        // payment_pending after re-registration submit -> advance to step 5
                        $currentStep = $totalSteps;
                    } elseif ($hasManualVerification && $registration->status === 'Gagal') {
                        $currentStep = 4;
                    } elseif ($hasManualVerification && !in_array($registration->status, ['Lulus', 'Gagal', 'accepted', 'rejected', 'Menunggu Verifikasi Registrasi Ulang', 'registered', 'payment_pending'])) {
                        $currentStep = 4;
                    } elseif (!$hasStep4Content) {
                        $currentStep = $totalSteps;
                    } else {
                        $currentStep = $totalSteps;
                    }
                } elseif ($isStep3Completed && $isPaymentLocked) {
                    $currentStep = 2;
                } else {
                    $currentStep = 3;
                }
            }
        }

        $examResult = null;
        if ($registration) {
            $examResult = $registration->examResults->where('status', 'completed')->first();
        }

        if ($registration && $examResult && $registration->status === 'exam_completed') {
            $metode = $path ? $path->metode_pengumuman : 'ditahan';
            if ($metode === 'langsung' || $metode === 'Langsung (One Day Service)') {
                $score = $examResult->score ?? 0;
                $globalThreshold = ($path && $path->nilai_ambang_batas !== null) ? $path->nilai_ambang_batas : 75;

                $accId = null;
                $accPil = null;

                $registration->loadMissing(['programStudi1', 'programStudi2', 'programStudi3']);

                if ($registration->program_studi_1_id) {
                    $p1 = $registration->programStudi1;
                    $t1 = ($p1 && $p1->passing_grade !== null && $p1->passing_grade > 0) ? $p1->passing_grade : $globalThreshold;
                    if ($score >= $t1) {
                        $accId = $registration->program_studi_1_id;
                        $accPil = 1;
                    }
                }

                if (!$accId && $registration->program_studi_2_id) {
                    $p2 = $registration->programStudi2;
                    $t2 = ($p2 && $p2->passing_grade !== null && $p2->passing_grade > 0) ? $p2->passing_grade : $globalThreshold;
                    if ($score >= $t2) {
                        $accId = $registration->program_studi_2_id;
                        $accPil = 2;
                    }
                }

                if (!$accId && $registration->program_studi_3_id) {
                    $p3 = $registration->programStudi3;
                    $t3 = ($p3 && $p3->passing_grade !== null && $p3->passing_grade > 0) ? $p3->passing_grade : $globalThreshold;
                    if ($score >= $t3) {
                        $accId = $registration->program_studi_3_id;
                        $accPil = 3;
                    }
                }

                if ($accId !== null) {
                    $registration->update([
                        'status' => 'accepted',
                        'status_kelulusan' => 'Lulus',
                        'status_pendaftaran' => 'Lulus',
                        'accepted_program_studi_id' => $accId,
                        'accepted_pilihan_ke' => $accPil,
                        'status_registrasi_ulang' => 'belum_registrasi',
                        'updated_at' => now()
                    ]);
                } else {
                    $registration->update([
                        'status' => 'rejected',
                        'status_kelulusan' => 'Tidak Lulus',
                        'status_pendaftaran' => 'Gagal',
                        'accepted_program_studi_id' => null,
                        'accepted_pilihan_ke' => null,
                        'updated_at' => now()
                    ]);
                }
                $registration->refresh();
            }
        }

        // --- Re-registration invoice data (any status that indicates re-registration has been submitted) ---
        $ulangPayment = null;
        $ulangBiayaList = collect();
        $ulangTotalBiaya = 0;
        if ($registration && in_array($registration->status, ['Menunggu Verifikasi Registrasi Ulang', 'registered', 'payment_pending'])) {
            // Only get registrasi_ulang payment type
            $ulangPayment = Payment::where('registration_id', $registration->id)
                ->where('payment_type', 'registrasi_ulang')
                ->orderBy('created_at', 'desc')
                ->first();

            if ($path) {
                $ulangBiayaList = JalurPendaftaranBiaya::with('komponenBiaya')
                    ->where('registration_path_id', $path->id)
                    ->get();
                $ulangTotalBiaya = $ulangBiayaList->sum('nominal');
            }
        }

        $masterRegencies = collect();
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(3)->get('https://cahya.github.io/api-wilayah-indonesia/api/regencies.json');
            if ($response->successful()) {
                $provResponse = \Illuminate\Support\Facades\Http::timeout(2)->get('https://cahya.github.io/api-wilayah-indonesia/api/provinces.json');
                $provinces = $provResponse->successful() ? collect($provResponse->json())->keyBy('id') : collect();
                $masterRegencies = collect($response->json())->map(function ($item) use ($provinces) {
                    $prov = $provinces->get($item['province_id']);
                    $provName = $prov['name'] ?? '';
                    $name = $item['name'] ?? '';
                    $type = str_starts_with($name, 'KAB.') ? 'Kab.' : 'Kota';
                    $cleanName = str_replace(['KAB. ', 'KOTA '], '', $name);
                    return (object)['id' => $item['id'], 'display' => "{$type} {$cleanName}, {$provName}"];
                })->sortBy('display')->values();
            }
        } catch (\Exception $e) {
            $jsonPath = public_path('assets/data/wilayah_indonesia.json');
            if (file_exists($jsonPath)) {
                $localData = json_decode(file_get_contents($jsonPath), true);
                $masterRegencies = collect($localData)->map(fn($item) => (object)['id' => $item['id'], 'display' => $item['text']]);
            }
        }

        return view('daftar-pmb.registration-steps', compact(
            'path', 'registration', 'currentStep', 'hasExam', 'totalSteps',
            'isStep3Completed', 'documentCount', 'isPaymentLocked',
            'hasPaidInvoice', 'examResult', 'masterRegencies',
            'ulangPayment', 'ulangBiayaList', 'ulangTotalBiaya',
            'hasWawancara'
        ));
    }

    public function registrationForm($pathCode = null)
    {
        $path = null;
        if ($pathCode) {
            $path = RegistrationPath::where('code', $pathCode)->first();
        }
        $programStudis = ProgramStudi::where('status', true)->orderBy('kode')->get();
        return view('daftar-pmb.registration-form', compact('path', 'programStudis'));
    }

    public function registrationStore(Request $request, $pathCode = null)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:200',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'agama' => 'nullable|string|max:20',
            'nik' => 'nullable|string|size:16',
            'alamat' => 'nullable|string',
            'kode_pos' => 'nullable|string|size:5',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:200',
            'nama_sekolah' => 'nullable|string|max:200',
            'jurusan' => 'nullable|string|max:200',
            'tahun_lulus' => 'nullable|string|max:4',
            'registration_path_id' => 'nullable|exists:registration_paths,id',
            'is_draft' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $pathId = null;
        if ($pathCode) {
            $pathObj = RegistrationPath::where('code', $pathCode)->first();
            $pathId = $pathObj?->id;
        }

        $isDraft = $request->input('is_draft') === '1';
        $status = 'draft';
        $registration = Registration::where('user_id', auth()->id())->where('registration_path_id', $pathId)->first();

        $data = [
            'user_id' => auth()->id(),
            'registration_path_id' => $pathId,
            'nama_lengkap' => $request->input('nama_lengkap'),
            'tempat_lahir' => $request->input('tempat_lahir'),
            'tanggal_lahir' => $request->input('tanggal_lahir'),
            'jenis_kelamin' => $request->input('jenis_kelamin'),
            'agama' => $request->input('agama'),
            'nik' => $request->input('nik'),
            'alamat' => $request->input('alamat'),
            'kode_pos' => $request->input('kode_pos'),
            'no_hp' => $request->input('no_hp'),
            'email' => $request->input('email'),
            'nama_sekolah' => $request->input('nama_sekolah'),
            'jurusan' => $request->input('jurusan'),
            'tahun_lulus' => $request->input('tahun_lulus'),
            'status' => $status,
        ];

        if ($registration) {
            $registration->update($data);
        } else {
            $registration = Registration::create($data);
        }

        if ($request->ajax()) return response()->json(['success' => true, 'message' => $isDraft ? 'Draft berhasil disimpan!' : 'Data pendaftaran berhasil disimpan!']);
        return redirect()->route('daftar-pmb.steps', $pathCode)->with('success', $isDraft ? 'Draft berhasil disimpan!' : 'Data pendaftaran berhasil disimpan. Silakan lanjut memilih program studi.');
    }

    public function programStudiForm($pathCode = null)
    {
        $path = null;
        $pathId = null;
        if ($pathCode) {
            $path = RegistrationPath::where('code', $pathCode)->first();
            $pathId = $path?->id;
        }
        $registration = Registration::where('user_id', auth()->id())->where('registration_path_id', $pathId)->first();
        if (!$registration) return redirect()->route('daftar-pmb.steps', $pathCode)->with('error', 'Silakan lengkapi data pendaftaran terlebih dahulu.');
        $programStudis = ProgramStudi::where('status', true)->orderBy('kode')->get();
        return view('daftar-pmb.program-studi', compact('path', 'registration', 'programStudis'));
    }

    public function programStudiStore(Request $request, $pathCode = null)
    {
        $input = $request->all();
        if (empty($input['program_studi_2_id'])) $input['program_studi_2_id'] = null;
        $request->merge($input);

        $validator = Validator::make($request->all(), [
            'program_studi_1_id' => 'required|exists:program_studis,id',
            'program_studi_2_id' => 'nullable|exists:program_studis,id|different:program_studi_1_id',
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator)->withInput();

        $pathId = null;
        if ($pathCode) {
            $pathObj = RegistrationPath::where('code', $pathCode)->first();
            $pathId = $pathObj?->id;
        }

        $registration = Registration::where('user_id', auth()->id())->where('registration_path_id', $pathId)->first();
        if (!$registration) return redirect()->route('daftar-pmb.steps', $pathCode)->with('error', 'Data pendaftaran tidak ditemukan.');

        $registration->update([
            'program_studi_1_id' => $request->input('program_studi_1_id'),
            'program_studi_2_id' => $request->input('program_studi_2_id') ?: null,
            'status' => 'submitted',
        ]);

        return redirect()->route('daftar-pmb.steps', $pathCode)->with('success', 'Pilihan program studi berhasil disimpan.');
    }

    public function documentUpload($pathCode = null)
    {
        $path = null;
        $pathId = null;
        if ($pathCode) {
            $path = RegistrationPath::with('templateBerkas.syaratDokumens')->where('code', $pathCode)->first();
            $pathId = $path?->id;
        }
        $registration = Registration::where('user_id', auth()->id())->where('registration_path_id', $pathId)->first();
        if (!$registration) return redirect()->route('daftar-pmb.steps', $pathCode)->with('error', 'Silakan lengkapi data pendaftaran terlebih dahulu.');
        if ($registration->status === 'draft') return redirect()->route('daftar-pmb.program-studi.form', $pathCode)->with('error', 'Silakan pilih program studi terlebih dahulu.');
        $existingDocuments = RegistrationDocument::where('registration_id', $registration->id)->get()->keyBy('type');
        return view('daftar-pmb.document-upload', compact('path', 'registration', 'existingDocuments'));
    }

    public function documentStore(Request $request, $pathCode = null)
    {
        $pathObj = null;
        $pathId = null;
        if ($pathCode) {
            $pathObj = RegistrationPath::with('templateBerkas.syaratDokumens')->where('code', $pathCode)->first();
            $pathId = $pathObj?->id;
        }
        $registration = Registration::where('user_id', auth()->id())->where('registration_path_id', $pathId)->first();
        if (!$registration) return redirect()->route('daftar-pmb.steps', $pathCode)->with('error', 'Data pendaftaran tidak ditemukan.');

        $syaratBerkas = $pathObj && $pathObj->templateBerkas ? $pathObj->templateBerkas->syaratDokumens : collect();
        $validationRules = [];
        foreach ($syaratBerkas as $berkas) {
            $rules = [];
            $rules[] = $berkas->status_wajib ? 'required' : 'nullable';
            $rules[] = 'file';
            $rules[] = 'max:' . ($berkas->max_size ?? 2048);
            if ($berkas->ekstensi_diizinkan) {
                $rules[] = 'mimes:' . str_replace(',', ',', $berkas->ekstensi_diizinkan);
            }
            $validationRules['berkas.' . $berkas->id] = implode('|', $rules);
        }
        if (!empty($validationRules)) $request->validate($validationRules);

        if ($request->hasFile('berkas')) {
            foreach ($request->file('berkas') as $berkasId => $file) {
                if (!$file || !$file->isValid()) continue;
                $berkas = \App\Models\SyaratDokumen::find($berkasId);
                if (!$berkas) continue;
                $typeSlug = \Illuminate\Support\Str::slug($berkas->nama_dokumen, '_');
                $oldDoc = RegistrationDocument::where('registration_id', $registration->id)->where('type', $typeSlug)->first();
                if ($oldDoc) {
                    $oldPath = storage_path('app/public/' . $oldDoc->file_path);
                    if (file_exists($oldPath)) unlink($oldPath);
                    $oldDoc->delete();
                }
                $filePath = $file->store('registrations/' . $registration->id, 'public');
                RegistrationDocument::create([
                    'registration_id' => $registration->id,
                    'type' => $typeSlug,
                    'original_name' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }
        if ($registration->status === 'submitted') {
            \Illuminate\Support\Facades\DB::table('registrations')->where('id', $registration->id)->update(['status' => 'documents_uploaded', 'updated_at' => now()]);
        }
        return redirect()->route('daftar-pmb.steps', $pathCode)->with('success', 'Dokumen berhasil diunggah.');
    }

    public function examPage($pathCode = null)
    {
        $path = null;
        $pathId = null;
        if ($pathCode) { $path = RegistrationPath::where('code', $pathCode)->first(); $pathId = $path?->id; }
        $registration = Registration::where('user_id', auth()->id())->where('registration_path_id', $pathId)->first();
        if (!$registration) return redirect()->route('daftar-pmb.steps', $pathCode)->with('error', 'Silakan lengkapi data pendaftaran terlebih dahulu.');
        $examResult = ExamResult::where('registration_id', $registration->id)->where('status', 'completed')->first();
        return view('daftar-pmb.exam-online', compact('path', 'registration', 'examResult'));
    }

    public function examStart($pathCode = null)
    {
        $pathId = null;
        if ($pathCode) { $pathObj = RegistrationPath::where('code', $pathCode)->first(); $pathId = $pathObj?->id; }
        $registration = Registration::where('user_id', auth()->id())->where('registration_path_id', $pathId)->first();
        if (!$registration) return redirect()->route('daftar-pmb.steps', $pathCode);
        if (ExamResult::where('registration_id', $registration->id)->where('status', 'completed')->first()) return redirect()->route('daftar-pmb.exam.page', $pathCode);
        $examResult = ExamResult::where('registration_id', $registration->id)->where('status', 'in_progress')->first();
        if (!$examResult) {
            $questions = ExamQuestion::orderBy('order')->get();
            $examResult = ExamResult::create(['registration_id' => $registration->id, 'total_questions' => $questions->count(), 'answers' => [], 'status' => 'in_progress']);
        }
        return redirect()->route('daftar-pmb.exam.question', [$pathCode, 0]);
    }

    public function examQuestion($pathCode = null, $index = 0)
    {
        $path = null; $pathId = null;
        if ($pathCode) { $path = RegistrationPath::where('code', $pathCode)->first(); $pathId = $path?->id; }
        $registration = Registration::where('user_id', auth()->id())->where('registration_path_id', $pathId)->first();
        $examResult = ExamResult::where('registration_id', $registration->id)->where('status', 'in_progress')->first();
        if (!$examResult) return redirect()->route('daftar-pmb.exam.page', $pathCode);
        $questions = ExamQuestion::orderBy('order')->get();
        $currentQuestion = $questions[$index] ?? null;
        $answers = $examResult->answers ?? [];
        $currentQuestionIndex = (int) $index;
        $elapsedSeconds = 0;
        if ($examResult->created_at) $elapsedSeconds = max(0, now()->diffInSeconds($examResult->created_at, false) * -1);
        return view('daftar-pmb.exam-online', compact('path', 'registration', 'examResult', 'questions', 'currentQuestion', 'currentQuestionIndex', 'answers', 'elapsedSeconds'));
    }

    public function examAnswer(Request $request, $pathCode = null)
    {
        $pathId = null;
        if ($pathCode) { $pathObj = RegistrationPath::where('code', $pathCode)->first(); $pathId = $pathObj?->id; }
        $registration = Registration::where('user_id', auth()->id())->where('registration_path_id', $pathId)->first();
        $examResult = ExamResult::where('registration_id', $registration->id)->where('status', 'in_progress')->first();
        if (!$examResult) return redirect()->route('daftar-pmb.exam.page', $pathCode);
        $questionId = $request->input('question_id');
        $answer = $request->input('answer');
        $elapsedSeconds = $request->input('elapsed_seconds', 0);
        $answers = $examResult->answers ?? [];
        if ($questionId && $answer) { $answers[$questionId] = $answer; $examResult->update(['answers' => $answers, 'duration_seconds' => $elapsedSeconds]); }
        $questions = ExamQuestion::orderBy('order')->get();
        $currentIndex = $questions->search(function ($q) use ($questionId) { return $q->id == $questionId; });
        $nextIndex = $currentIndex + 1;
        if ($nextIndex >= $questions->count()) $nextIndex = $questions->count() - 1;
        return redirect()->route('daftar-pmb.exam.question', [$pathCode, $nextIndex]);
    }

    public function examSubmit(Request $request, $pathCode = null)
    {
        $pathId = null;
        if ($pathCode) { $pathObj = RegistrationPath::where('code', $pathCode)->first(); $pathId = $pathObj?->id; }
        $registration = Registration::where('user_id', auth()->id())->where('registration_path_id', $pathId)->first();
        $examResult = ExamResult::where('registration_id', $registration->id)->where('status', 'in_progress')->first();
        if (!$examResult) return redirect()->route('daftar-pmb.exam.page', $pathCode);
        $questionId = $request->input('question_id');
        $answer = $request->input('answer');
        $elapsedSeconds = $request->input('elapsed_seconds', 0);
        $answers = $examResult->answers ?? [];
        if ($questionId && $answer) $answers[$questionId] = $answer;
        $questions = ExamQuestion::orderBy('order')->get();
        $correctAnswers = 0;
        foreach ($questions as $question) { if (isset($answers[$question->id]) && $answers[$question->id] === $question->correct_answer) $correctAnswers++; }
        $totalQuestions = $questions->count();
        $wrongAnswers = $totalQuestions - $correctAnswers;
        $score = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;
        $examResult->update(['answers' => $answers, 'total_questions' => $totalQuestions, 'correct_answers' => $correctAnswers, 'wrong_answers' => $wrongAnswers, 'score' => $score, 'duration_seconds' => $elapsedSeconds, 'status' => 'completed']);
        $jalur = $registrationPath ?? ($registration->registrationPath ?? null);
        $threshold = ($jalur && $jalur->nilai_ambang_batas !== null) ? $jalur->nilai_ambang_batas : 75;
        $metodePengumuman = $jalur ? $jalur->metode_pengumuman : 'ditahan';
        if ($metodePengumuman === 'langsung' || $metodePengumuman === 'Langsung (One Day Service)') {
            if ($score >= $threshold) {
                \Illuminate\Support\Facades\DB::table('registrations')->where('id', $registration->id)->update(['status' => 'accepted', 'status_kelulusan' => 'Lulus', 'status_pendaftaran' => 'Lulus', 'updated_at' => now()]);
            } else {
                \Illuminate\Support\Facades\DB::table('registrations')->where('id', $registration->id)->update(['status' => 'rejected', 'status_kelulusan' => 'Tidak Lulus', 'status_pendaftaran' => 'Gagal', 'updated_at' => now()]);
            }
        } else {
            \Illuminate\Support\Facades\DB::table('registrations')->where('id', $registration->id)->update(['status' => 'exam_completed', 'updated_at' => now()]);
        }
        return redirect()->route('daftar-pmb.exam.page', $pathCode)->with('success', 'Ujian berhasil diselesaikan!');
    }

    public function review($pathCode = null)
    {
        $path = null; $pathId = null;
        if ($pathCode) { $path = RegistrationPath::where('code', $pathCode)->first(); $pathId = $path?->id; }
        $registration = Registration::where('user_id', auth()->id())->where('registration_path_id', $pathId)->with(['programStudi1', 'programStudi2', 'registrationPath'])->first();
        if (!$registration) return redirect()->route('daftar-pmb.steps', $pathCode);
        $examResult = ExamResult::where('registration_id', $registration->id)->where('status', 'completed')->first();
        $documents = RegistrationDocument::where('registration_id', $registration->id)->get()->keyBy('type');
        $documentLabels = ['foto_formal' => 'Foto Formal', 'ijazah' => 'Ijazah / SKHUN', 'kartu_keluarga' => 'Kartu Keluarga', 'akta_kelahiran' => 'Akta Kelahiran'];
        return view('daftar-pmb.review', compact('path', 'registration', 'examResult', 'documents', 'documentLabels'));
    }

    public function reRegistrationStore(Request $request, $pathCode = null)
    {
        $path = null; $pathId = null;
        if ($pathCode) { $path = RegistrationPath::where('code', $pathCode)->first(); $pathId = $path?->id; }
        $registration = Registration::where('user_id', auth()->id())->where('registration_path_id', $pathId)->firstOrFail();
        if ($registration->status !== 'accepted' && $registration->status_kelulusan !== 'Lulus') {
            return redirect()->back()->with('error', 'Status pendaftaran Anda tidak valid untuk Registrasi Ulang.');
        }
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required|string|in:Islam,Kristen,Katolik,Hindu,Budha,Khonghucu,Penghayat Kepercayaan,Lainnya',
            'nik' => 'required|digits:16',
            'nisn' => 'required|digits:10',
            'nama_ibu_kandung' => 'required|string|max:255',
            'penerima_kps' => 'required|string|in:Ya,Tidak',
            'kebutuhan_khusus' => 'required|string|in:Ya,Tidak',
            'kewarganegaraan' => 'required|string|max:100',
            'regency_id' => 'required|exists:regencies,id',
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'kelurahan_id' => 'required|exists:kelurahans,id',
        ], ['nik.digits' => 'NIK harus bernilai tepat 16 digit angka.', 'nisn.digits' => 'NISN harus bernilai tepat 10 digit angka.']);
        if ($validator->fails()) return redirect()->back()->withErrors($validator)->withInput();

        $registration->update([
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'agama' => $request->agama,
            'nik' => $request->nik,
            'nisn' => $request->nisn,
            'nama_ibu_kandung' => $request->nama_ibu_kandung,
            'penerima_kps' => $request->penerima_kps,
            'kebutuhan_khusus' => $request->kebutuhan_khusus,
            'kewarganegaraan' => $request->kewarganegaraan,
            'regency_id' => $request->regency_id,
            'kecamatan_id' => $request->kecamatan_id,
            'kelurahan_id' => $request->kelurahan_id,
            'status' => 'Menunggu Verifikasi Registrasi Ulang',
            're_registration_submitted_at' => now(),
            'status_registrasi_ulang' => 'menunggu_pembayaran',
        ]);

        if ($path) {
            $biayaKomponens = JalurPendaftaranBiaya::with('komponenBiaya')->where('registration_path_id', $path->id)->get();
            $totalBiaya = $biayaKomponens->sum('nominal');

            // Check if there is an active discount plotting for this student
            $plotting = \DB::table('plotting_potongans')
                ->join('master_potongans', 'plotting_potongans.master_potongan_id', '=', 'master_potongans.id')
                ->where('plotting_potongans.registration_id', $registration->id)
                ->select('plotting_potongans.nominal_potongan', 'master_potongans.nama_potongan')
                ->first();

            $discountAmount = 0;
            $discountName = '';
            if ($plotting) {
                $discountAmount = (int) $plotting->nominal_potongan;
                $discountName = $plotting->nama_potongan;
            }

            $finalBiaya = max(0, $totalBiaya - $discountAmount);

            if ($finalBiaya > 0) {
                // TRIGGER 2A: Has billing fees -> generate invoice, set 'menunggu_pembayaran'
                $itemizedDetails = [];
                foreach ($biayaKomponens as $bk) {
                    $itemizedDetails[] = ['kode_komponen' => $bk->komponenBiaya?->kode_komponen, 'nama_komponen' => $bk->komponenBiaya?->nama_komponen, 'nominal' => (int) $bk->nominal];
                }

                // If there's a discount, append it as a negative itemized entry for billing details clarity
                if ($discountAmount > 0) {
                    $itemizedDetails[] = [
                        'kode_komponen' => 'SCHOLARSHIP',
                        'nama_komponen' => 'Potongan: ' . $discountName,
                        'nominal' => -((int) $discountAmount)
                    ];
                }

                $metadata = [
                    'payment_type' => 'registrasi_ulang', 
                    'itemized_breakdown' => $itemizedDetails, 
                    'total_biaya' => $totalBiaya, 
                    'potongan_beasiswa' => $discountAmount,
                    'nama_beasiswa' => $discountName,
                    'final_biaya' => $finalBiaya,
                    'jalur' => $path->name, 
                    'kode_jalur' => $path->code, 
                    'generated_at' => now()->toDateTimeString()
                ];
                $paymentService = app(\App\Services\PaymentService::class);
                $payment = $paymentService->createInvoice($registration, 'registrasi_ulang', $finalBiaya);
                $payment->update(['metadata' => $metadata]);

                $registration->update(['status_registrasi_ulang' => 'menunggu_pembayaran']);

                ActivityLogger::log('create', 'payment', 'Re-registration invoice generated: ' . $payment->invoice_number . ' for ' . ($path?->name ?? '') . ' total: ' . $finalBiaya . ' (discounted by ' . $discountAmount . ' via ' . $discountName . ')');
            } else {
                // TRIGGER 2B: No billing fees or fully covered by scholarship -> bypass billing, set langsung
                $registration->update(['status_registrasi_ulang' => 'sudah_registrasi_no_tagihan']);

                ActivityLogger::log('update', 'registration', 'Re-registration completed without billing (Rp ' . $finalBiaya . ', original: ' . $totalBiaya . ', discount: ' . $discountAmount . ') for #' . $registration->id);
            }
        } else {
            // No path available -> assume no billing
            $registration->update(['status_registrasi_ulang' => 'sudah_registrasi_no_tagihan']);
        }

        ActivityLogger::log('update', 'registration', 'Applicant completed re-registration for path: ' . ($path?->name ?? ''));
        session()->flash('active_tab', 'ulang');
        // Redirect to wizard Step 5 which shows the re-registration invoice payment info
        return redirect()->route('daftar-pmb.steps', ['pathCode' => $pathCode, 're_registration' => 1])
            ->with('success', 'Registrasi Ulang berhasil dikirim. Silakan selesaikan pembayaran biaya registrasi.');
    }

    public function pembayaranRegistrasi($registrationId)
    {
        $registration = Registration::with([
            'registrationPath', 'programStudi1',
            'payments' => function ($q) { $q->where('payment_type', 'registrasi_ulang')->orderBy('created_at', 'desc'); }
        ])->where('user_id', auth()->id())->findOrFail($registrationId);

        $path = $registration->registrationPath;
        $biayaKomponens = collect();
        $totalBiaya = 0;
        if ($path) {
            $biayaKomponens = JalurPendaftaranBiaya::with('komponenBiaya')->where('registration_path_id', $path->id)->get();
            $totalBiaya = $biayaKomponens->sum('nominal');
        }
        $payment = $registration->payments->first();
        $paymentStatus = 'Belum Dibayar';
        if ($payment) {
            if ($payment->transaction_status === 'success') $paymentStatus = 'Lunas';
            elseif ($payment->transaction_status === 'pending') $paymentStatus = 'Menunggu Pembayaran';
        }

        return view('daftar-pmb.pembayaran-registrasi', compact('registration', 'path', 'biayaKomponens', 'totalBiaya', 'payment', 'paymentStatus'));
    }

    public function apiList(Request $request)
    {
        $perPage = 6;
        $page = $request->get('page', 1);
        $kategoriId = $request->get('kategori_id');
        $query = RegistrationPath::with(['kategori', 'periode'])->where('is_active', true)->orderBy('code');
        if ($kategoriId) $query->where('kategori_jalur_id', $kategoriId);
        $paths = $query->paginate($perPage, ['*'], 'page', $page);
        $data = $paths->map(function ($path) {
            $periodeText = $path->periode ? $path->periode->tahun_akademik . ' - ' . $path->periode->semester : null;
            return [
                'id' => $path->id,
                'code' => $path->code,
                'name' => $path->name,
                'description' => $path->description,
                'fee_formatted' => 'Rp ' . number_format($path->fee, 0, ',', '.'),
                'fee' => (int) $path->fee,
                'color' => $path->color ?? 'secondary',
                'quota' => $path->quota,
                'kategori' => $path->kategori?->nama,
                'periode' => $periodeText,
                'periode_label' => $periodeText ? 'Pendaftaran TA ' . $periodeText : null,
                'registration_start' => $path->registration_start?->format('d M Y'),
                'registration_end' => $path->registration_end?->format('d M Y'),
                'is_open' => $path->registration_start === null || $path->registration_end === null ? true : now()->between($path->registration_start, $path->registration_end)
            ];
        });
        return response()->json(['data' => $data, 'current_page' => $paths->currentPage(), 'last_page' => $paths->lastPage(), 'per_page' => $paths->perPage(), 'total' => $paths->total(), 'has_more' => $paths->hasMorePages()]);
    }
}