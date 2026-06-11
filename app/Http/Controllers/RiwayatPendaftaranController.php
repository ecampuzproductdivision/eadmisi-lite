<?php

namespace App\Http\Controllers;

use App\Models\ExamResult;
use App\Models\Registration;
use App\Models\RegistrationDocument;
use Illuminate\Http\Request;

class RiwayatPendaftaranController extends Controller
{
    /**
     * Display list of registrations for the current user (calon mahasiswa).
     */
    public function index()
    {
        $registrations = Registration::where('user_id', auth()->id())
            ->with(['registrationPath', 'programStudi1', 'programStudi2', 'payments'])
            ->whereIn('status', ['submitted', 'documents_uploaded', 'payment_pending', 'payment_verified', 'exam_completed', 'reviewed', 'accepted', 'rejected'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('daftar-pmb.riwayat.index', compact('registrations'));
    }

    /**
     * Show detail of a specific registration for the current user.
     */
    public function show($id)
    {
        $registration = Registration::where('user_id', auth()->id())
            ->with([
                'registrationPath',
                'programStudi1',
                'programStudi2',
                'documents',
                'payments',
                'user',
            ])
            ->findOrFail($id);

        $examResult = ExamResult::where('registration_id', $registration->id)
            ->where('status', 'completed')
            ->first();

        $documentLabels = [
            'foto_formal' => 'Foto Formal',
            'ijazah' => 'Ijazah / SKHUN',
            'kartu_keluarga' => 'Kartu Keluarga',
            'akta_kelahiran' => 'Akta Kelahiran',
        ];

        return view('daftar-pmb.riwayat.show', compact('registration', 'examResult', 'documentLabels'));
    }
}