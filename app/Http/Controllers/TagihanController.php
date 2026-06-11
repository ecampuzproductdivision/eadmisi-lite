<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\RegistrationPath;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    /**
     * Display tagihan (bill) information for the logged-in calon mahasiswa.
     */
    public function index()
    {
        $registrations = Registration::where('user_id', auth()->id())
            ->with(['registrationPath', 'programStudi1', 'programStudi2'])
            ->whereIn('status', ['submitted', 'documents_uploaded', 'payment_pending', 'payment_verified', 'exam_completed', 'reviewed', 'accepted'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('daftar-pmb.tagihan', compact('registrations'));
    }
}