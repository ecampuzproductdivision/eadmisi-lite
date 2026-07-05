<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\RegistrationPath;
use App\Models\KategoriJalur;
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
        ])->whereIn('status', ['submitted', 'documents_uploaded', 'payment_pending', 'payment_verified', 'exam_completed', 'reviewed', 'accepted', 'rejected', 'Menunggu Verifikasi Registrasi Ulang', 'registered']);

        // Filter by registration path
        if ($request->filled('path_id')) {
            $query->where('registration_path_id', $request->path_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by name or NIK
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
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
}