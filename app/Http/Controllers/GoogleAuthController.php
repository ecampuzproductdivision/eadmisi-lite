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
        if (empty(config('services.google.client_id')) || empty(config('services.google.client_secret'))) {
            return redirect()->route('auth.google.simulation');
        }
        try {
            return Socialite::driver('google')->redirect();
        } catch (\Exception $e) {
            return redirect()->route('auth.google.simulation');
        }
    }

    // --- DAFTAR BY GOOGLE (user baru + lengkapi profil) ---
    public function redirectToGoogleRegister()
    {
        session()->put('google_intent', 'register');
        if (empty(config('services.google.client_id')) || empty(config('services.google.client_secret'))) {
            return redirect()->route('auth.google.simulation');
        }
        try {
            return Socialite::driver('google')->redirect();
        } catch (\Exception $e) {
            return redirect()->route('auth.google.simulation');
        }
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $intent = session()->pull('google_intent', 'register');

            if ($request->has('mock_login') || $request->has('google_id')) {
                $googleId = $request->get('google_id', 'mock_google_' . time());
                $name     = $request->get('name', 'Budi Google');
                $email    = $request->get('email', 'budi.google@gmail.com');
                $avatar   = $request->get('avatar_url', 'https://lh3.googleusercontent.com/a/default-user');
            } else {
                $socialiteUser = Socialite::driver('google')->stateless()->user();
                $googleId = $socialiteUser->getId();
                $name     = $socialiteUser->getName();
                $email    = $socialiteUser->getEmail();
                $avatar   = $socialiteUser->getAvatar();
            }

            Log::info('Google callback', [
                'email' => $email,
                'intent' => $intent,
            ]);

            $user = User::where('google_id', $googleId)
                ->orWhere('email', $email)
                ->first();

            $calonMahasiswaRole = Role::where('role_code', 'CALON_MAHASISWA')->first();

            if ($user) {
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleId,
                        'avatar' => $avatar,
                    ]);
                }

                // Ensure user has CALON_MAHASISWA role for student portal
                if ($calonMahasiswaRole && !$user->roles->contains($calonMahasiswaRole->id)) {
                    $user->roles()->attach($calonMahasiswaRole->id);
                }

                Auth::login($user, true);
                ActivityLogger::log('login_google_camaba', 'auth', 'Calon Mahasiswa logged in via Google: ' . $user->email);
                return redirect()->route('daftar-pmb')
                    ->with('success', 'Selamat datang kembali di Portal Calon Mahasiswa!');
            }

            // User baru → create + assign CALON_MAHASISWA role + auto-login + redirect to Student Dashboard
            $newUser = User::create([
                'name' => $name,
                'email' => $email,
                'google_id' => $googleId,
                'avatar' => $avatar,
                'password' => bcrypt(uniqid()),
                'status' => 'active',
                'is_profile_completed' => false,
            ]);

            if ($calonMahasiswaRole) {
                $newUser->roles()->attach($calonMahasiswaRole->id);
            }

            Auth::login($newUser, true);
            ActivityLogger::log('register_google_camaba', 'auth', 'Calon Mahasiswa Google user created: ' . $newUser->email);

            return redirect()->route('daftar-pmb')
                ->with('success', 'Selamat datang! Silakan lengkapi biodata singkat Anda untuk memulai pendaftaran.');

        } catch (\Exception $e) {
            Log::error('Google auth failed for Calon Mahasiswa', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->isMethod('get') && !$request->has('mock_login')) {
                return redirect()->route('auth.google.simulation');
            }

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
        $regencies = \App\Models\Regency::all();

        return view('auth.complete_registration', compact('user', 'regencies'));
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
            'is_profile_completed' => true,
        ]);

        // Assign CALON_MAHASISWA role
        $calonMahasiswaRole = Role::where('role_code', 'CALON_MAHASISWA')->first();
        if ($calonMahasiswaRole && $user->roles()->count() === 0) {
            $user->roles()->attach($calonMahasiswaRole->id);
        }

        session()->forget('google_registration_user_id');

        Auth::login($user, true);
        ActivityLogger::log('register_google_complete', 'auth', 'Google registration completed: ' . $user->email);

        return redirect()->route('daftar-pmb')->with('success', 'Pendaftaran selesai! Selamat datang di Portal Calon Mahasiswa.');
    }

    public function showSimulationForm()
    {
        return view('auth.google_simulation');
    }
}