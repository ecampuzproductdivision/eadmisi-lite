<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Registration;
use App\Models\RegistrationPath;
use App\Models\JalurPendaftaranBiaya;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    /**
     * Display tagihan (bill) information for the logged-in calon mahasiswa.
     * Separated into categorical sections:
     * 1. Biaya Pendaftaran / Formulir (initial registration fee)
     * 2. Biaya Registrasi Ulang (re-registration invoice)
     */
    public function index()
    {
        // ── Section 1: Pendaftaran (initial registration fee payments) ──
        $pendaftaranRegistrations = Registration::where('user_id', auth()->id())
            ->with(['registrationPath', 'programStudi1', 'programStudi2'])
            ->whereIn('status', ['submitted', 'documents_uploaded', 'payment_pending', 'payment_verified', 'exam_completed', 'reviewed', 'accepted', 'Lulus', 'Menunggu Verifikasi Registrasi Ulang'])
            ->orderBy('created_at', 'desc')
            ->get();

        $pendaftaranPayments = \App\Models\Payment::whereIn('registration_id', $pendaftaranRegistrations->pluck('id'))
            ->where('payment_type', 'pendaftaran')
            ->whereIn('transaction_status', ['pending', 'success'])
            ->get()
            ->keyBy('registration_id');

        // ── Section 2: Registrasi Ulang (re-registration invoices) ──
        // STRICT GATE: Only show registrations where the re-registration form has been submitted
        // (status_registrasi_ulang is not null and is in one of the valid post-submission states).
        // This prevents premature billing leakage before the student completes Step 5.
        $ulangRegistrations = Registration::where('user_id', auth()->id())
            ->with(['registrationPath', 'programStudi1', 'programStudi2'])
            ->whereIn('status', ['Menunggu Verifikasi Registrasi Ulang', 'registered', 'payment_pending'])
            ->whereNotNull('status_registrasi_ulang')
            ->whereIn('status_registrasi_ulang', ['menunggu_pembayaran', 'sudah_registrasi_no_tagihan', 'sudah_registrasi_lunas'])
            ->orderBy('created_at', 'desc')
            ->get();

        $ulangPayments = \App\Models\Payment::whereIn('registration_id', $ulangRegistrations->pluck('id'))
            ->where('payment_type', 'registrasi_ulang')
            ->whereIn('transaction_status', ['pending', 'success'])
            ->get()
            ->keyBy('registration_id');

        // ── Load itemized biaya komponen for re-registration ──
        $ulangBiayaKomponens = [];
        foreach ($ulangRegistrations as $reg) {
            if ($reg->registrationPath) {
                $ulangBiayaKomponens[$reg->id] = JalurPendaftaranBiaya::with('komponenBiaya')
                    ->where('registration_path_id', $reg->registrationPath->id)
                    ->get();
            }
        }

        return view('daftar-pmb.tagihan', compact(
            'pendaftaranRegistrations', 'pendaftaranPayments',
            'ulangRegistrations', 'ulangPayments', 'ulangBiayaKomponens'
        ));
    }
}
