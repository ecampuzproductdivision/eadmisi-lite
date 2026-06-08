<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->paginate(10);
        $roles = Role::where('status', 'active')->get();
        return view('settings.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::where('status', 'active')->get();
        return view('settings.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        }

        ActivityLogger::log('create', 'users', 'Created user: ' . $user->email);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = Role::where('status', 'active')->get();
        $userRoleIds = $user->roles()->pluck('roles.id')->toArray();
        return view('settings.users.edit', compact('user', 'roles', 'userRoleIds'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $data = $request->only(['name', 'email']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        } else {
            $user->roles()->detach();
        }

        ActivityLogger::log('update', 'users', 'Updated user: ' . $user->email);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $email = $user->email;
        $user->delete();

        ActivityLogger::log('delete', 'users', 'Deleted user: ' . $email);

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function toggleStatus(User $user)
    {
        $user->update(['status' => $user->status === 'active' ? 'inactive' : 'active']);

        ActivityLogger::log('update', 'users', 'Toggled status for user: ' . $user->email . ' to ' . $user->status);

        return redirect()->route('users.index')->with('success', 'User status updated.');
    }
}