<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Mail\OtpMail;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'regency_id' => 'nullable|exists:regencies,id',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Generate OTP 6 digit
        $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'regency_id' => $request->regency_id,
            'password' => $request->password,
            'status' => 'active',
            'otp_code' => $otpCode,
            'otp_expires_at' => Carbon::now()->addMinutes(5),
        ]);

        // Assign CALON_MAHASISWA role
        $calonMahasiswa = Role::where('role_code', 'CALON_MAHASISWA')->first();
        if ($calonMahasiswa) {
            $user->roles()->attach($calonMahasiswa->id);
        }

        // Send OTP via email
        try {
            Mail::to($user->email)->send(new OtpMail($otpCode, $user->name));
        } catch (\Exception $e) {
            // Log error but continue
            \Illuminate\Support\Facades\Log::error('OTP email failed: ' . $e->getMessage());
        }

        ActivityLogger::log('register_otp', 'auth', 'User registered, OTP sent to: ' . $user->email);

        // Store info in session for OTP verification
        $request->session()->put('otp_email', $user->email);
        $request->session()->put('otp_code', $otpCode);

        return redirect()->route('otp.verify.form')->with('success', 'Akun berhasil dibuat! Silakan cek email Anda untuk kode OTP.');
    }
}