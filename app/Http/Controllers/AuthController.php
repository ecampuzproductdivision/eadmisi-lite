<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            ActivityLogger::log('login', 'auth', 'User logged in: ' . Auth::user()->email);

            return redirect()->intended('/home');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            ActivityLogger::log('logout', 'auth', 'User logged out: ' . $user->email);
        }

        // Flush session completely
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Force logout from all guards
        Auth::guard('web')->logout();

        // Clear remember me cookie
        $cookie = \Cookie::forget('remember_web_'.sha1(config('app.key')));

        return redirect()->route('login')
            ->withCookie($cookie)
            ->with('success', 'You have been logged out successfully.');
    }
}