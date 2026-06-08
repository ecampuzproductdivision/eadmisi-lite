<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountSettingsController extends Controller
{
    public function index()
    {
        $user = auth()->user()->load('roles');
        return view('settings.account', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
        ]);

        $request->user()->update($request->only(['name', 'email', 'phone', 'bio']));

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $request->user()->update([
            'password' => bcrypt($request->new_password),
        ]);

        return redirect()->back()->with('success', 'Password updated successfully.');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->file('avatar')->store('avatars', 'public');
        $request->user()->update([
            'avatar' => asset('storage/' . $path),
        ]);

        return redirect()->back()->with('success', 'Avatar updated successfully.');
    }

    public function deleteAvatar(Request $request)
    {
        $request->user()->update([
            'avatar' => null,
        ]);

        return redirect()->back()->with('success', 'Avatar deleted successfully.');
    }
}