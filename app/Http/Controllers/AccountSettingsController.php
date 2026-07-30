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

    /**
     * Handle Mandatory & Blocking Modal profile completion submission.
     */
    public function updateBiodataModal(Request $request)
    {
        $request->validate([
            'nama_lengkap'  => 'required|string|max:200',
            'jenis_kelamin' => 'required|in:L,P',
            'phone'         => 'required|string|max:20',
            'domisili'      => 'required|string|max:255',
        ]);

        $user = auth()->user();
        $regencyId = is_numeric($request->domisili) ? (int)$request->domisili : null;

        $user->update([
            'name'                 => $request->nama_lengkap,
            'jenis_kelamin'        => $request->jenis_kelamin,
            'phone'                => $request->phone,
            'domisili'             => $request->domisili,
            'regency_id'           => $regencyId ?? $user->regency_id,
            'is_profile_completed' => true,
        ]);

        // Sync to active Registration record if one exists
        $registration = \App\Models\Registration::where('user_id', $user->id)->latest()->first();
        if ($registration) {
            $registration->update([
                'nama_lengkap'  => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_hp'         => $request->phone,
                'regency_id'    => $regencyId ?? $registration->regency_id,
            ]);
        }

        \App\Helpers\ActivityLogger::log('update_biodata_modal', 'profile', 'Completed biodata for user: ' . $user->email);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Biodata Anda berhasil dilengkapi! Akses penuh ke portal pendaftaran telah dibuka.',
                'user'    => $user,
            ]);
        }

        return redirect()->back()->with('success', 'Biodata Anda berhasil dilengkapi!');
    }
}