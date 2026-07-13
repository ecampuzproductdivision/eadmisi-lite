<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\RegistrationPath;
use Illuminate\Http\Request;

class RegistrasiUlangController extends Controller
{
    /**
     * Display datatable of approved candidates for re-registration tracking.
     * Only shows registrations with status = 'Lulus' (passed selection).
     */
    public function index(Request $request)
    {
        $query = Registration::with([
            'user',
            'registrationPath' => fn($q) => $q->withTrashed(),
            'programStudi1',
            'programStudi2',
            'payments' => function ($q) {
                $q->where('payment_type', 'registrasi_ulang');
            },
        ])->where(function ($q) {
            // Only show candidates who have passed selection OR already in re-registration flow
            $q->where('status', 'Lulus')
              ->orWhere('status', 'Menunggu Verifikasi Registrasi Ulang')
              ->orWhere('status', 'registered')
              ->orWhere(function ($sub) {
                  $sub->whereNotNull('status_registrasi_ulang')
                      ->whereIn('status_registrasi_ulang', [
                          'belum_registrasi',
                          'menunggu_pembayaran',
                          'sudah_registrasi_no_tagihan',
                          'sudah_registrasi_lunas',
                      ]);
              });
        });

        // Filter by registration path
        if ($request->filled('path_id')) {
            $query->where('registration_path_id', $request->path_id);
        }

        // Filter by status_registrasi_ulang
        if ($request->filled('status_ulang')) {
            $query->where('status_registrasi_ulang', $request->status_ulang);
        }

        // Filter by status kelulusan
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('no_pendaftaran', 'like', "%{$search}%");
            });
        }

        $registrations = $query->orderBy('created_at', 'desc')->paginate(15);
        $paths = RegistrationPath::where('is_active', true)->orderBy('name')->get();

        return view('registrasi-ulang.index', compact('registrations', 'paths'));
    }

    /**
     * Show detail of a registration in re-registration context.
     */
    public function show($id)
    {
        $registration = Registration::with([
            'user',
            'registrationPath' => fn($q) => $q->withTrashed(),
            'programStudi1',
            'programStudi2',
            'payments' => function ($q) {
                $q->where('payment_type', 'registrasi_ulang');
            },
            'kabupaten',
            'kecamatan',
            'kelurahan',
        ])->findOrFail($id);

        // Resolve full wilayah names
        $regencyName = '-';
        if ($registration->kabupaten) {
            $type = $registration->kabupaten->type ?? 'Kab.';
            $regencyName = $type . ' ' . $registration->kabupaten->nama_kabupaten;
        }
        $kecamatanName = $registration->kecamatan?->nama_kecamatan ?? '-';
        $kelurahanName = $registration->kelurahan?->nama_kelurahan ?? '-';

        return view('registrasi-ulang.show', compact(
            'registration', 'regencyName', 'kecamatanName', 'kelurahanName'
        ));
    }

    /**
     * Approve re-registration and generate NIM.
     */
    public function approve(Request $request, $id)
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
            'status_registrasi_ulang' => 'sudah_registrasi_lunas',
        ]);

        \App\Helpers\ActivityLogger::log('update', 'registration',
            'Admin approved re-registration and generated NIM: ' . $request->nim . ' for registration #' . $id);

        return redirect()->route('registrasi-ulang.show', $id)
            ->with('success', 'Registrasi ulang berhasil disetujui dan NIM ' . $request->nim . ' berhasil digenerate.');
    }
}