<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;

class RiwayatPendaftaranController extends Controller
{
    /**
     * Display list of registrations for the current user (calon mahasiswa).
     */
    public function index()
    {
        $registrations = Registration::where('user_id', auth()->id())
            ->with(['registrationPath.templateBerkas.syaratDokumens', 'programStudi1', 'programStudi2', 'payments', 'documents', 'examResults'])
            ->whereIn('status', ['submitted', 'documents_uploaded', 'payment_pending', 'payment_verified', 'exam_completed', 'reviewed', 'accepted', 'rejected', 'Lulus', 'Gagal', 'Menunggu Verifikasi Registrasi Ulang', 'registered'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('daftar-pmb.riwayat.index', compact('registrations'));
    }
}