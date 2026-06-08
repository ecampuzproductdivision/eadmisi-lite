<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OtpVerificationController extends Controller
{
    public function showVerificationForm(Request $request)
    {
        $email = $request->session()->get('otp_email');
        if (!$email) {
            return redirect()->route('register')->with('error', 'Silakan daftar terlebih dahulu.');
        }

        return view('auth.verify-otp', compact('email'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp_code' => 'required|string|size:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        if (!$user->otp_code || !$user->otp_expires_at) {
            return back()->withErrors(['otp_code' => 'Kode OTP tidak valid atau sudah kadaluarsa. Silakan daftar ulang.']);
        }

        if (Carbon::now()->gt($user->otp_expires_at)) {
            return back()->withErrors(['otp_code' => 'Kode OTP sudah kadaluarsa. Silakan minta kode baru.']);
        }

        if ($user->otp_code !== $request->otp_code) {
            return back()->withErrors(['otp_code' => 'Kode OTP yang Anda masukkan salah.']);
        }

        // OTP valid → clear OTP & login
        $user->update([
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        ActivityLogger::log('verify_otp', 'auth', 'User verified email: ' . $user->email);

        // Assign CALON_MAHASISWA role (if not already assigned from register)
        if ($user->roles()->count() === 0) {
            $calonMahasiswa = \App\Models\Role::where('role_code', 'CALON_MAHASISWA')->first();
            if ($calonMahasiswa) {
                $user->roles()->attach($calonMahasiswa->id);
            }
        }

        Auth::login($user);

        $request->session()->forget('otp_email');

        return redirect()->route('home')->with('success', 'Selamat! Akun Anda berhasil diverifikasi. Selamat datang di eAkademik!');
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        // Generate new OTP
        $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->update([
            'otp_code' => $otpCode,
            'otp_expires_at' => Carbon::now()->addMinutes(5),
        ]);

        // Send email
        Mail::to($user->email)->send(new \App\Mail\OtpMail($otpCode, $user->name));

        return back()->with('success', 'Kode OTP baru telah dikirim ke email Anda.');
    }
}