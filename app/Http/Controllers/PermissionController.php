<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $permissions = Permission::paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('settings.permissions.partials.permission_rows', compact('permissions'))->render(),
                'next_page' => $permissions->nextPageUrl(),
                'has_more' => $permissions->hasMorePages(),
            ]);
        }

        return view('settings.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('settings.permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'permission_name' => 'required|string|max:255|unique:permissions',
        ]);

        Permission::create($request->all());
        return redirect()->route('permissions.index')->with('success', 'Permission created successfully.');
    }

    public function edit(Permission $permission)
    {
        return view('settings.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'permission_name' => 'required|string|max:255|unique:permissions,permission_name,' . $permission->id,
        ]);

        $permission->update($request->all());
        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully.');
    }
}