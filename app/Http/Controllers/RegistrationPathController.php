<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Helpers\PeriodeHelper;
use App\Models\ExamQuestion;
use App\Models\ExamResult;
use App\Models\KategoriJalur;
use App\Models\ProgramStudi;
use App\Models\Registration;
use App\Models\RegistrationDocument;
use App\Models\RegistrationPath;
use App\Models\SoalUjian;
use App\Models\PaketSoal;
use App\Models\TemplateBerkas;
use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegistrationPathController extends Controller
{
    /**
     * Display a listing of the resource with infinite scroll.
     */
    public function index(Request $request)
    {
        // Filter paths by the currently active period
        $paths = RegistrationPath::with('kategori', 'formPendaftaran')
            ->byActivePeriode()
            ->orderBy('code')
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('registration-paths.partials.path_rows', compact('paths'))->render(),
                'next_page' => $paths->nextPageUrl(),
                'has_more' => $paths->hasMorePages(),
            ]);
        }

        return view('registration-paths.index', compact('paths'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = KategoriJalur::orderBy('nama')->get();
        $programStudis = ProgramStudi::active()->orderBy('nama')->get();
        $paketSoals = PaketSoal::active()->orderBy('nama_paket')->get();
        $templateBerkas = TemplateBerkas::active()->orderBy('nama_template')->get();
        $forms = Form::active()->orderBy('nama')->get();
        return view('registration-paths.create', compact('kategoris', 'programStudis', 'paketSoals', 'templateBerkas', 'forms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50|unique:registration_paths,code',
            'name' => 'required|string|max:200',
            'description' => 'nullable|string',
            'form_pendaftaran_id' => 'nullable|exists:forms,id',
            'registration_start' => 'nullable|date',
            'registration_end' => 'nullable|date|after_or_equal:registration_start',
            'fee' => 'nullable|numeric|min:0',
            'color' => 'nullable|string|max:20',
            'quota' => 'nullable|integer|min:0',
            'jumlah_pilihan_prodi' => 'required|integer|in:1,2,3',
            'program_studi_ids' => 'required|array|min:1',
            'program_studi_ids.*' => 'exists:program_studis,id',
            'is_active' => 'boolean',
            'gunakan_ujian' => 'boolean',
            'paket_soal_id' => 'nullable|exists:paket_soal,id',
            'gunakan_berkas' => 'boolean',
            'template_berkas_id' => 'nullable|exists:template_berkas,id',
            'metode_pengumuman' => 'required|in:langsung,ditahan',
            'gunakan_wawancara' => 'boolean',
        ]);

        // Auto-set metode_pengumuman to 'ditahan' if wawancara is enabled
        if ($request->boolean('gunakan_wawancara')) {
            $request->merge(['metode_pengumuman' => 'ditahan']);
        }

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validate paket_soal total skor == 100 if using exam
        if ($request->boolean('gunakan_ujian') && $request->filled('paket_soal_id')) {
            $paket = PaketSoal::find($request->paket_soal_id);
            if ($paket && (int) $paket->total_skor !== 100) {
                return redirect()->back()
                    ->withErrors(['paket_soal_id' => 'Total skor paket soal (' . $paket->total_skor . ') harus tepat 100.'])
                    ->withInput();
            }
        }

        // Validate template_berkas required if menggunakan_berkas is true
        if ($request->boolean('gunakan_berkas') && !$request->filled('template_berkas_id')) {
            return redirect()->back()
                ->withErrors(['template_berkas_id' => 'Template syarat berkas wajib dipilih jika menggunakan unggah berkas.'])
                ->withInput();
        }

        // Auto-bind the currently active period
        $activePeriodeId = PeriodeHelper::getActiveId();

        $data = $request->except(['program_studi_ids']);
        $data['periode_id'] = $activePeriodeId;

        $path = RegistrationPath::create($data);

        // Sync pivot tabel jalur_prodi
        if ($request->has('program_studi_ids')) {
            $path->programStudis()->sync($request->program_studi_ids);
        }

        ActivityLogger::log('create', 'registration_path', 'Created registration path: ' . $request->code);

        return redirect()->route('registration-paths.index')
            ->with('success', 'Jalur Pendaftaran berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(RegistrationPath $registrationPath)
    {
        $registrationPath->load('kategori', 'programStudis', 'formPendaftaran');
        return view('registration-paths.show', compact('registrationPath'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RegistrationPath $registrationPath)
    {
        $kategoris = KategoriJalur::orderBy('nama')->get();
        $programStudis = ProgramStudi::active()->orderBy('nama')->get();
        $paketSoals = PaketSoal::active()->orderBy('nama_paket')->get();
        $templateBerkas = TemplateBerkas::active()->orderBy('nama_template')->get();
        $forms = Form::active()->orderBy('nama')->get();
        // Load pivot relationships for pre-selection
        $registrationPath->load('programStudis', 'formPendaftaran');
        return view('registration-paths.edit', compact('registrationPath', 'kategoris', 'programStudis', 'paketSoals', 'templateBerkas', 'forms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RegistrationPath $registrationPath)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50|unique:registration_paths,code,' . $registrationPath->id,
            'name' => 'required|string|max:200',
            'description' => 'nullable|string',
            'form_pendaftaran_id' => 'nullable|exists:forms,id',
            'registration_start' => 'nullable|date',
            'registration_end' => 'nullable|date|after_or_equal:registration_start',
            'fee' => 'nullable|numeric|min:0',
            'color' => 'nullable|string|max:20',
            'quota' => 'nullable|integer|min:0',
            'jumlah_pilihan_prodi' => 'required|integer|in:1,2,3',
            'program_studi_ids' => 'required|array|min:1',
            'program_studi_ids.*' => 'exists:program_studis,id',
            'is_active' => 'boolean',
            'gunakan_ujian' => 'boolean',
            'paket_soal_id' => 'nullable|exists:paket_soal,id',
            'gunakan_berkas' => 'boolean',
            'template_berkas_id' => 'nullable|exists:template_berkas,id',
            'metode_pengumuman' => 'required|in:langsung,ditahan',
            'gunakan_wawancara' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validate paket_soal total skor == 100 if using exam
        if ($request->boolean('gunakan_ujian') && $request->filled('paket_soal_id')) {
            $paket = PaketSoal::find($request->paket_soal_id);
            if ($paket && (int) $paket->total_skor !== 100) {
                return redirect()->back()
                    ->withErrors(['paket_soal_id' => 'Total skor paket soal (' . $paket->total_skor . ') harus tepat 100.'])
                    ->withInput();
            }
        }

        // Validate template_berkas required if menggunakan_berkas is true
        if ($request->boolean('gunakan_berkas') && !$request->filled('template_berkas_id')) {
            return redirect()->back()
                ->withErrors(['template_berkas_id' => 'Template syarat berkas wajib dipilih jika menggunakan unggah berkas.'])
                ->withInput();
        }

        // Auto-set metode_pengumuman to 'ditahan' if wawancara is enabled
        if ($request->boolean('gunakan_wawancara')) {
            $request->merge(['metode_pengumuman' => 'ditahan']);
        }

        $registrationPath->update($request->except(['program_studi_ids']));

        // Sync pivot tabel jalur_prodi
        if ($request->has('program_studi_ids')) {
            $registrationPath->programStudis()->sync($request->program_studi_ids);
        }

        ActivityLogger::log('update', 'registration_path', 'Updated registration path: ' . $registrationPath->code);

        return redirect()->route('registration-paths.index')
            ->with('success', 'Jalur Pendaftaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RegistrationPath $registrationPath)
    {
        $code = $registrationPath->code;
        $registrationPath->delete();

        ActivityLogger::log('delete', 'registration_path', 'Deleted registration path: ' . $code);

        return redirect()->route('registration-paths.index')
            ->with('success', 'Jalur Pendaftaran berhasil dihapus.');
    }

    /**
     * Halaman publik daftar jalur pendaftaran dengan infinite scroll.
     */
    public function publicIndex()
    {
        $kategoris = KategoriJalur::orderBy('nama')->get();
        return view('pmb.registration-paths', compact('kategoris'));
    }

    /**
     * Halaman "Daftar PMB" untuk calon mahasiswa.
     */
    public function daftarPmb()
    {
        $kategoris = KategoriJalur::orderBy('nama')->get();
        return view('daftar-pmb.index', compact('kategoris'));
    }

    /**
     * Halaman "Alur Pendaftaran PMB" - Step by step registration.
     */
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
            ->first();

        // Determine current step (only for Daftar PMB registration flow)
        // Step 1: Biodata (draft without program_studi)
        // Step 2: Program Studi (draft with program_studi_1_id)
        // Step 3: Upload Dokumen (submitted)
        // Step 4: Ujian Online (CBT) - only if path has is_ujian_online
        // Step Final: Selesai
        $hasExam = $path && $path->is_ujian_online;
        $totalSteps = $hasExam ? 5 : 4;

        $currentStep = 1;
        if ($registration) {
            if ($registration->status === 'draft' && $registration->program_studi_1_id) {
                $currentStep = 2; // Step 1 done, step 2 active
            }
            if ($registration->status === 'submitted') {
                $currentStep = 3; // Step 1 & 2 complete, step 3 (Upload) active
            }
            if (in_array($registration->status, ['documents_uploaded', 'payment_pending', 'payment_verified', 'exam_completed', 'reviewed', 'accepted'])) {
                if ($hasExam && $registration->status !== 'exam_completed') {
                    $currentStep = 4; // Docs uploaded, but exam not done yet
                } else {
                    $currentStep = $totalSteps; // All steps complete
                }
            }
        }

        return view('daftar-pmb.registration-steps', compact('path', 'registration', 'currentStep'));
    }

    /**
     * Halaman formulir pendaftaran (biodata pribadi).
     */
    public function registrationForm($pathCode = null)
    {
        $path = null;
        if ($pathCode) {
            $path = RegistrationPath::where('code', $pathCode)->first();
        }
        $programStudis = ProgramStudi::where('status', true)->orderBy('kode')->get();
        return view('daftar-pmb.registration-form', compact('path', 'programStudis'));
    }

    /**
     * Simpan data pendaftaran (biodata pribadi).
     * Saving always sets status to 'draft' so user must select program studi next.
     */
    public function registrationStore(Request $request, $pathCode = null)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap'   => 'required|string|max:200',
            'tempat_lahir'   => 'nullable|string|max:100',
            'tanggal_lahir'  => 'nullable|date',
            'jenis_kelamin'  => 'nullable|in:L,P',
            'agama'          => 'nullable|string|max:20',
            'nik'            => 'nullable|string|size:16',
            'alamat'         => 'nullable|string',
            'kode_pos'       => 'nullable|string|size:5',
            'no_hp'          => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:200',
            'nama_sekolah'   => 'nullable|string|max:200',
            'jurusan'        => 'nullable|string|max:200',
            'tahun_lulus'    => 'nullable|string|max:4',
            'registration_path_id' => 'nullable|exists:registration_paths,id',
            'is_draft'       => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $pathId = null;
        if ($pathCode) {
            $pathObj = RegistrationPath::where('code', $pathCode)->first();
            $pathId = $pathObj?->id;
        }

        $isDraft = $request->input('is_draft') === '1';
        // Always save as draft after biodata; user must select program studi next.
        $status = 'draft';

        // Check if user already has a registration for this path
        $registration = Registration::where('user_id', auth()->id())
            ->where('registration_path_id', $pathId)
            ->first();

        $data = [
            'user_id'             => auth()->id(),
            'registration_path_id' => $pathId,
            'nama_lengkap'        => $request->input('nama_lengkap'),
            'tempat_lahir'        => $request->input('tempat_lahir'),
            'tanggal_lahir'       => $request->input('tanggal_lahir'),
            'jenis_kelamin'       => $request->input('jenis_kelamin'),
            'agama'               => $request->input('agama'),
            'nik'                 => $request->input('nik'),
            'alamat'              => $request->input('alamat'),
            'kode_pos'            => $request->input('kode_pos'),
            'no_hp'               => $request->input('no_hp'),
            'email'               => $request->input('email'),
            'nama_sekolah'        => $request->input('nama_sekolah'),
            'jurusan'             => $request->input('jurusan'),
            'tahun_lulus'         => $request->input('tahun_lulus'),
            'status'              => $status,
        ];

        if ($registration) {
            $registration->update($data);
        } else {
            $registration = Registration::create($data);
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => $isDraft ? 'Draft berhasil disimpan!' : 'Data pendaftaran berhasil disimpan!']);
        }

        return redirect()->route('daftar-pmb.steps', $pathCode)
            ->with('success', $isDraft ? 'Draft berhasil disimpan!' : 'Data pendaftaran berhasil disimpan. Silakan lanjut memilih program studi.');
    }

    /**
     * Halaman form pilihan program studi.
     */
    public function programStudiForm($pathCode = null)
    {
        $path = null;
        $pathId = null;
        if ($pathCode) {
            $path = RegistrationPath::where('code', $pathCode)->first();
            $pathId = $path?->id;
        }

        $registration = Registration::where('user_id', auth()->id())
            ->where('registration_path_id', $pathId)
            ->first();

        if (!$registration) {
            return redirect()->route('daftar-pmb.steps', $pathCode)
                ->with('error', 'Silakan lengkapi data pendaftaran terlebih dahulu.');
        }

        $programStudis = ProgramStudi::where('status', true)->orderBy('kode')->get();
        return view('daftar-pmb.program-studi', compact('path', 'registration', 'programStudis'));
    }

    /**
     * Simpan pilihan program studi.
     */
    public function programStudiStore(Request $request, $pathCode = null)
    {
        // Convert empty string to null for program_studi_2_id
        $input = $request->all();
        if (empty($input['program_studi_2_id'])) {
            $input['program_studi_2_id'] = null;
        }
        $request->merge($input);

        $validator = Validator::make($request->all(), [
            'program_studi_1_id' => 'required|exists:program_studis,id',
            'program_studi_2_id' => 'nullable|exists:program_studis,id|different:program_studi_1_id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $pathId = null;
        if ($pathCode) {
            $pathObj = RegistrationPath::where('code', $pathCode)->first();
            $pathId = $pathObj?->id;
        }

        $registration = Registration::where('user_id', auth()->id())
            ->where('registration_path_id', $pathId)
            ->first();

        if (!$registration) {
            return redirect()->route('daftar-pmb.steps', $pathCode)
                ->with('error', 'Data pendaftaran tidak ditemukan.');
        }

        $registration->update([
            'program_studi_1_id' => $request->input('program_studi_1_id'),
            'program_studi_2_id' => $request->input('program_studi_2_id') ?: null,
            'status' => 'submitted',
        ]);

        return redirect()->route('daftar-pmb.steps', $pathCode)
            ->with('success', 'Pilihan program studi berhasil disimpan. Silakan lanjut ke tahap upload dokumen.');
    }

    /**
     * Halaman form upload dokumen.
     */
    public function documentUpload($pathCode = null)
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
            ->first();

        if (!$registration) {
            return redirect()->route('daftar-pmb.steps', $pathCode)
                ->with('error', 'Silakan lengkapi data pendaftaran terlebih dahulu.');
        }

        if ($registration->status === 'draft') {
            return redirect()->route('daftar-pmb.program-studi.form', $pathCode)
                ->with('error', 'Silakan pilih program studi terlebih dahulu.');
        }

        $existingDocuments = RegistrationDocument::where('registration_id', $registration->id)
            ->get()
            ->keyBy('type');

        return view('daftar-pmb.document-upload', compact('path', 'registration', 'existingDocuments'));
    }

    /**
     * Simpan dokumen upload (dynamic from BO syaratDokumens).
     */
    public function documentStore(Request $request, $pathCode = null)
    {
        $pathObj = null;
        $pathId = null;
        if ($pathCode) {
            $pathObj = RegistrationPath::with('templateBerkas.syaratDokumens')
                ->where('code', $pathCode)
                ->first();
            $pathId = $pathObj?->id;
        }

        $registration = Registration::where('user_id', auth()->id())
            ->where('registration_path_id', $pathId)
            ->first();

        if (!$registration) {
            return redirect()->route('daftar-pmb.steps', $pathCode)
                ->with('error', 'Data pendaftaran tidak ditemukan.');
        }

        // Build dynamic validation from SyaratDokumen records
        $syaratBerkas = $pathObj && $pathObj->templateBerkas
            ? $pathObj->templateBerkas->syaratDokumens
            : collect();

        $validationRules = [];
        foreach ($syaratBerkas as $berkas) {
            $rules = [];
            if ($berkas->status_wajib) {
                $rules[] = 'required';
            } else {
                $rules[] = 'nullable';
            }
            $rules[] = 'file';
            $rules[] = 'max:' . ($berkas->max_size ?? 2048);

            if ($berkas->ekstensi_diizinkan) {
                $mimes = str_replace(',', ',', $berkas->ekstensi_diizinkan);
                $rules[] = 'mimes:' . $mimes;
            }

            $validationRules['berkas.' . $berkas->id] = implode('|', $rules);
        }

        if (!empty($validationRules)) {
            $request->validate($validationRules);
        }

        // Process uploaded files
        if ($request->hasFile('berkas')) {
            foreach ($request->file('berkas') as $berkasId => $file) {
                if (!$file || !$file->isValid()) continue;

                $berkas = \App\Models\SyaratDokumen::find($berkasId);
                if (!$berkas) continue;

                $typeSlug = \Illuminate\Support\Str::slug($berkas->nama_dokumen, '_');

                // Delete old file for this syarat document
                $oldDoc = RegistrationDocument::where('registration_id', $registration->id)
                    ->where('type', $typeSlug)
                    ->first();

                if ($oldDoc) {
                    $oldPath = storage_path('app/public/' . $oldDoc->file_path);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                    $oldDoc->delete();
                }

                // Store new file
                $filePath = $file->store('registrations/' . $registration->id, 'public');

                RegistrationDocument::create([
                    'registration_id' => $registration->id,
                    'type'            => $typeSlug,
                    'original_name'   => $file->getClientOriginalName(),
                    'file_path'       => $filePath,
                    'mime_type'       => $file->getMimeType(),
                    'file_size'       => $file->getSize(),
                ]);
            }
        }

        // Update registration status to documents_uploaded
        if ($registration->status === 'submitted') {
            \Illuminate\Support\Facades\DB::table('registrations')
                ->where('id', $registration->id)
                ->update(['status' => 'documents_uploaded', 'updated_at' => now()]);
        }

        return redirect()->route('daftar-pmb.steps', $pathCode)
            ->with('success', 'Dokumen berhasil diunggah. Silakan lanjut ke tahap berikutnya.');
    }

    /**
     * Halaman ujian online - CTA & Info.
     */
    public function examPage($pathCode = null)
    {
        $path = null;
        $pathId = null;
        if ($pathCode) {
            $path = RegistrationPath::where('code', $pathCode)->first();
            $pathId = $path?->id;
        }

        $registration = Registration::where('user_id', auth()->id())
            ->where('registration_path_id', $pathId)
            ->first();

        if (!$registration) {
            return redirect()->route('daftar-pmb.steps', $pathCode)
                ->with('error', 'Silakan lengkapi data pendaftaran terlebih dahulu.');
        }

        $examResult = ExamResult::where('registration_id', $registration->id)
            ->where('status', 'completed')
            ->first();

        return view('daftar-pmb.exam-online', compact('path', 'registration', 'examResult'));
    }

    /**
     * Mulai ujian - buat exam result baru.
     */
    public function examStart($pathCode = null)
    {
        $pathId = null;
        if ($pathCode) {
            $pathObj = RegistrationPath::where('code', $pathCode)->first();
            $pathId = $pathObj?->id;
        }

        $registration = Registration::where('user_id', auth()->id())
            ->where('registration_path_id', $pathId)
            ->first();

        if (!$registration) {
            return redirect()->route('daftar-pmb.steps', $pathCode);
        }

        // Check if already completed
        $existingExam = ExamResult::where('registration_id', $registration->id)
            ->where('status', 'completed')
            ->first();

        if ($existingExam) {
            return redirect()->route('daftar-pmb.exam.page', $pathCode);
        }

        // Check if there's an in_progress exam
        $examResult = ExamResult::where('registration_id', $registration->id)
            ->where('status', 'in_progress')
            ->first();

        if (!$examResult) {
            $questions = ExamQuestion::orderBy('order')->get();
            $examResult = ExamResult::create([
                'registration_id' => $registration->id,
                'total_questions' => $questions->count(),
                'answers' => [],
                'status' => 'in_progress',
            ]);
        }

        return redirect()->route('daftar-pmb.exam.question', [$pathCode, 0]);
    }

    /**
     * Tampilkan soal ujian.
     */
    public function examQuestion($pathCode = null, $index = 0)
    {
        $path = null;
        $pathId = null;
        if ($pathCode) {
            $path = RegistrationPath::where('code', $pathCode)->first();
            $pathId = $path?->id;
        }

        $registration = Registration::where('user_id', auth()->id())
            ->where('registration_path_id', $pathId)
            ->first();

        $examResult = ExamResult::where('registration_id', $registration->id)
            ->where('status', 'in_progress')
            ->first();

        if (!$examResult) {
            return redirect()->route('daftar-pmb.exam.page', $pathCode);
        }

        $questions = ExamQuestion::orderBy('order')->get();
        $currentQuestion = $questions[$index] ?? null;
        $answers = $examResult->answers ?? [];
        $currentQuestionIndex = (int) $index;

        // Calculate elapsed time
        $elapsedSeconds = 0;
        if ($examResult->created_at) {
            $elapsedSeconds = max(0, now()->diffInSeconds($examResult->created_at, false) * -1);
        }

        return view('daftar-pmb.exam-online', compact(
            'path', 'registration', 'examResult', 'questions',
            'currentQuestion', 'currentQuestionIndex', 'answers', 'elapsedSeconds'
        ));
    }

    /**
     * Simpan jawaban dan pindah ke soal berikutnya.
     */
    public function examAnswer(Request $request, $pathCode = null)
    {
        $pathId = null;
        if ($pathCode) {
            $pathObj = RegistrationPath::where('code', $pathCode)->first();
            $pathId = $pathObj?->id;
        }

        $registration = Registration::where('user_id', auth()->id())
            ->where('registration_path_id', $pathId)
            ->first();

        $examResult = ExamResult::where('registration_id', $registration->id)
            ->where('status', 'in_progress')
            ->first();

        if (!$examResult) {
            return redirect()->route('daftar-pmb.exam.page', $pathCode);
        }

        $questionId = $request->input('question_id');
        $answer = $request->input('answer');
        $elapsedSeconds = $request->input('elapsed_seconds', 0);

        // Save answer
        $answers = $examResult->answers ?? [];
        if ($questionId && $answer) {
            $answers[$questionId] = $answer;
            $examResult->update([
                'answers' => $answers,
                'duration_seconds' => $elapsedSeconds,
            ]);
        }

        // Find current question index
        $questions = ExamQuestion::orderBy('order')->get();
        $currentIndex = $questions->search(function ($q) use ($questionId) {
            return $q->id == $questionId;
        });

        $nextIndex = $currentIndex + 1;
        if ($nextIndex >= $questions->count()) {
            $nextIndex = $questions->count() - 1;
        }

        return redirect()->route('daftar-pmb.exam.question', [$pathCode, $nextIndex]);
    }

    /**
     * Selesai ujian - koreksi jawaban.
     */
    public function examSubmit(Request $request, $pathCode = null)
    {
        $pathId = null;
        if ($pathCode) {
            $pathObj = RegistrationPath::where('code', $pathCode)->first();
            $pathId = $pathObj?->id;
        }

        $registration = Registration::where('user_id', auth()->id())
            ->where('registration_path_id', $pathId)
            ->first();

        $examResult = ExamResult::where('registration_id', $registration->id)
            ->where('status', 'in_progress')
            ->first();

        if (!$examResult) {
            return redirect()->route('daftar-pmb.exam.page', $pathCode);
        }

        // Save last answer if any
        $questionId = $request->input('question_id');
        $answer = $request->input('answer');
        $elapsedSeconds = $request->input('elapsed_seconds', 0);

        $answers = $examResult->answers ?? [];
        if ($questionId && $answer) {
            $answers[$questionId] = $answer;
        }

        // Grade the exam
        $questions = ExamQuestion::orderBy('order')->get();
        $correctAnswers = 0;

        foreach ($questions as $question) {
            if (isset($answers[$question->id]) && $answers[$question->id] === $question->correct_answer) {
                $correctAnswers++;
            }
        }

        $totalQuestions = $questions->count();
        $wrongAnswers = $totalQuestions - $correctAnswers;
        $score = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;

        $examResult->update([
            'answers' => $answers,
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctAnswers,
            'wrong_answers' => $wrongAnswers,
            'score' => $score,
            'duration_seconds' => $elapsedSeconds,
            'status' => 'completed',
        ]);

        // Update registration status
        \Illuminate\Support\Facades\DB::table('registrations')
            ->where('id', $registration->id)
            ->update(['status' => 'exam_completed', 'updated_at' => now()]);

        return redirect()->route('daftar-pmb.exam.page', $pathCode)
            ->with('success', 'Ujian berhasil diselesaikan!');
    }

    /**
     * Halaman review / ringkasan pendaftaran.
     */
    public function review($pathCode = null)
    {
        $path = null;
        $pathId = null;
        if ($pathCode) {
            $path = RegistrationPath::where('code', $pathCode)->first();
            $pathId = $path?->id;
        }

        $registration = Registration::where('user_id', auth()->id())
            ->where('registration_path_id', $pathId)
            ->with(['programStudi1', 'programStudi2', 'registrationPath'])
            ->first();

        if (!$registration) {
            return redirect()->route('daftar-pmb.steps', $pathCode);
        }

        $examResult = ExamResult::where('registration_id', $registration->id)
            ->where('status', 'completed')
            ->first();

        $documents = RegistrationDocument::where('registration_id', $registration->id)
            ->get()
            ->keyBy('type');

        $documentLabels = [
            'foto_formal' => 'Foto Formal',
            'ijazah' => 'Ijazah / SKHUN',
            'kartu_keluarga' => 'Kartu Keluarga',
            'akta_kelahiran' => 'Akta Kelahiran',
        ];

        return view('daftar-pmb.review', compact('path', 'registration', 'examResult', 'documents', 'documentLabels'));
    }

    /**
     * API: data jalur pendaftaran untuk infinite scroll.
     */
    public function apiList(Request $request)
    {
        $perPage = 6;
        $page = $request->get('page', 1);
        $kategoriId = $request->get('kategori_id');

        $query = RegistrationPath::with('kategori')
            ->where('is_active', true)
            ->orderBy('code');

        if ($kategoriId) {
            $query->where('kategori_jalur_id', $kategoriId);
        }

        $paths = $query->paginate($perPage, ['*'], 'page', $page);

        $data = $paths->map(function ($path) {
            return [
                'id' => $path->id,
                'code' => $path->code,
                'name' => $path->name,
                'description' => $path->description,
                'fee_formatted' => 'Rp ' . number_format($path->fee, 0, ',', '.'),
                'fee' => (int) $path->fee,
                'color' => $path->color ?? 'secondary',
                'quota' => $path->quota,
                'kategori' => $path->kategori ? $path->kategori->nama : null,
                'registration_start' => $path->registration_start?->format('d M Y'),
                'registration_end' => $path->registration_end?->format('d M Y'),
                'is_open' => $path->registration_start === null || $path->registration_end === null
                    ? true
                    : (now()->between($path->registration_start, $path->registration_end)),
            ];
        });

        return response()->json([
            'data' => $data,
            'current_page' => $paths->currentPage(),
            'last_page' => $paths->lastPage(),
            'per_page' => $paths->perPage(),
            'total' => $paths->total(),
            'has_more' => $paths->hasMorePages(),
        ]);
    }
}