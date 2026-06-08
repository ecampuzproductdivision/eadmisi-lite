<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    // --- LOGIN BY GOOGLE (khusus user existing) ---
    public function redirectToGoogleLogin()
    {
        session()->put('google_intent', 'login');
        return Socialite::driver('google')->redirect();
    }

    // --- DAFTAR BY GOOGLE (user baru + lengkapi profil) ---
    public function redirectToGoogleRegister()
    {
        session()->put('google_intent', 'register');
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $socialiteUser = Socialite::driver('google')->stateless()->user();
            $intent = session()->pull('google_intent', 'login');

            Log::info('Google callback', [
                'email' => $socialiteUser->getEmail(),
                'intent' => $intent,
            ]);

            $user = User::where('google_id', $socialiteUser->getId())
                ->orWhere('email', $socialiteUser->getEmail())
                ->first();

            if ($intent === 'login') {
                // LOGIN: hanya untuk user yang sudah terdaftar
                if (!$user) {
                    return redirect()->route('register')
                        ->with('error', 'Akun Google ini belum terdaftar. Silakan daftar terlebih dahulu.');
                }

                // Link google_id jika belum
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $socialiteUser->getId(),
                        'avatar' => $socialiteUser->getAvatar(),
                    ]);
                }

                Auth::login($user, true);
                ActivityLogger::log('login', 'auth', 'User logged in via Google: ' . $user->email);

                return redirect()->intended('/home');
            }

            // --- REGISTER ---
            if ($user) {
                // User sudah ada → langsung login
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $socialiteUser->getId(),
                        'avatar' => $socialiteUser->getAvatar(),
                    ]);
                }

                Auth::login($user, true);
                return redirect()->intended('/home')
                    ->with('success', 'Akun sudah terdaftar. Selamat datang kembali!');
            }

            // User baru → create + redirect lengkapi data
            $newUser = User::create([
                'name' => $socialiteUser->getName(),
                'email' => $socialiteUser->getEmail(),
                'google_id' => $socialiteUser->getId(),
                'avatar' => $socialiteUser->getAvatar(),
                'password' => bcrypt(uniqid()),
                'status' => 'active',
            ]);

            session()->put('google_registration_user_id', $newUser->id);

            ActivityLogger::log('register_google', 'auth', 'Google user created: ' . $newUser->email);

            return redirect()->route('google.complete.registration')
                ->with('success', 'Silakan lengkapi No. WhatsApp dan Asal Wilayah Anda.');

        } catch (\Exception $e) {
            Log::error('Google auth failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('login')
                ->withErrors(['email' => 'Google authentication failed: ' . $e->getMessage()]);
        }
    }

    public function showCompleteRegistrationForm()
    {
        $userId = session('google_registration_user_id');
        if (!$userId) {
            return redirect()->route('register')
                ->with('error', 'Silakan daftar melalui Google terlebih dahulu.');
        }

        $user = User::findOrFail($userId);
        return view('auth.complete-google-registration', compact('user'));
    }

    public function completeRegistration(Request $request)
    {
        $userId = session('google_registration_user_id');
        if (!$userId) {
            return redirect()->route('register')
                ->with('error', 'Sesi tidak valid. Silakan daftar ulang.');
        }

        $request->validate([
            'phone' => 'required|string|max:20',
            'regency_id' => 'nullable|exists:regencies,id',
        ]);

        $user = User::findOrFail($userId);

        $user->update([
            'phone' => $request->phone,
            'regency_id' => $request->regency_id,
        ]);

        // Assign CALON_MAHASISWA role
        $calonMahasiswaRole = Role::where('role_code', 'CALON_MAHASISWA')->first();
        if ($calonMahasiswaRole && $user->roles()->count() === 0) {
            $user->roles()->attach($calonMahasiswaRole->id);
        }

        session()->forget('google_registration_user_id');

        Auth::login($user, true);
        ActivityLogger::log('register_google_complete', 'auth', 'Google registration completed: ' . $user->email);

        return redirect()->route('home')->with('success', 'Pendaftaran selesai! Selamat datang di eAkademik.');
    }

    public function showSimulationForm()
    {
        return view('auth.google_simulation');
    }
}