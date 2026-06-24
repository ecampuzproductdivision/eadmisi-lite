<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Page;
use App\Models\Permission;
use App\Models\RolePagePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::withCount('users')->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('settings.roles.partials.role_rows', compact('roles'))->render(),
                'next_page' => $roles->nextPageUrl(),
                'has_more' => $roles->hasMorePages(),
            ]);
        }

        return view('settings.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('settings.roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_name' => 'required|string|max:255|unique:roles',
            'role_code' => 'required|string|max:50|unique:roles',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        Role::create($request->all());

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        return view('settings.roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'role_name' => 'required|string|max:255|unique:roles,role_name,' . $role->id,
            'role_code' => 'required|string|max:50|unique:roles,role_code,' . $role->id,
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $role->update($request->all());

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        if ($role->role_code === 'SUPER_ADMIN') {
            return redirect()->route('roles.index')->with('error', 'Cannot delete SUPER_ADMIN role.');
        }

        $role->users()->detach();
        $role->pagePermissions()->delete();
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }

    public function permissions(Role $role)
    {
        $pages = Page::with('menu')->orderBy('sort_order')->get();
        $permissions = Permission::all();
        $rolePermissions = RolePagePermission::where('role_id', $role->id)->get()->groupBy('page_id');

        return view('settings.roles.permissions', compact('role', 'pages', 'permissions', 'rolePermissions'));
    }

    public function savePermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'array',
            'permissions.*.*' => 'exists:permissions,id',
        ]);

        RolePagePermission::where('role_id', $role->id)->delete();

        if ($request->has('permissions')) {
            foreach ($request->permissions as $pageId => $permIds) {
                foreach ($permIds as $permId) {
                    RolePagePermission::create([
                        'role_id' => $role->id,
                        'page_id' => $pageId,
                        'permission_id' => $permId,
                    ]);
                }
            }
        }

        return redirect()->route('roles.index')->with('success', 'Permissions updated successfully.');
    }

    public function matrix()
    {
        $roles = Role::all();
        $pages = Page::with('menu')->orderBy('sort_order')->get();
        $permissions = Permission::all();
        $rolePermissions = RolePagePermission::all()->groupBy('role_id');

        return view('settings.roles.matrix', compact('roles', 'pages', 'permissions', 'rolePermissions'));
    }

    public function duplicate(Role $role)
    {
        $newRole = $role->replicate();
        $newRole->role_name = $role->role_name . ' (Copy)';
        $newRole->role_code = $role->role_code . '_copy';
        $newRole->save();

        // Duplicate permissions
        $perms = RolePagePermission::where('role_id', $role->id)->get();
        foreach ($perms as $perm) {
            RolePagePermission::create([
                'role_id' => $newRole->id,
                'page_id' => $perm->page_id,
                'permission_id' => $perm->permission_id,
            ]);
        }

        return redirect()->route('roles.index')->with('success', 'Role duplicated successfully.');
    }
}