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
    public function index()
    {
        $roles = Role::withCount('users')->paginate(10);
        return view('settings.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('settings.roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_name' => ['required', 'string', 'max:255'],
            'role_code' => ['required', 'string', 'max:255', 'unique:roles'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive']
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
            'role_name' => ['required', 'string', 'max:255'],
            'role_code' => ['required', 'string', 'max:255', 'unique:roles,role_code,' . $role->id],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive']
        ]);

        $role->update($request->all());
        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        if ($role->role_code === 'SUPER_ADMIN') {
            return redirect()->route('roles.index')->with('error', 'Super Admin role cannot be deleted.');
        }

        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }

    public function duplicate(Role $role)
    {
        DB::transaction(function() use ($role) {
            $newRole = Role::create([
                'role_name' => $role->role_name . ' (Copy)',
                'role_code' => $role->role_code . '_COPY_' . strtoupper(uniqid()),
                'description' => $role->description,
                'status' => $role->status,
            ]);

            foreach ($role->pagePermissions as $p) {
                RolePagePermission::create([
                    'role_id' => $newRole->id,
                    'page_id' => $p->page_id,
                    'can_view' => $p->can_view,
                    'can_create' => $p->can_create,
                    'can_edit' => $p->can_edit,
                    'can_delete' => $p->can_delete,
                ]);
            }
        });

        return redirect()->route('roles.index')->with('success', 'Role duplicated successfully.');
    }

    public function permissions(Role $role)
    {
        if ($role->role_code === 'SUPER_ADMIN') {
            return redirect()->route('roles.index')->with('warning', 'Super Admin has all permissions.');
        }

        $pages = Page::with('menu')->where('is_active', true)->get()->groupBy(function($item) {
            return $item->menu ? $item->menu->menu_name : 'No Category';
        });

        $permissions = Permission::all();

        // Load active mapping for this role (format: page_id => [perm_ids])
        $activePermissions = RolePagePermission::where('role_id', $role->id)
            ->get()
            ->mapWithKeys(function($item) {
                $permIds = [];
                if ($item->can_view) $permIds[] = 1;
                if ($item->can_create) $permIds[] = 2;
                if ($item->can_edit) $permIds[] = 3;
                if ($item->can_delete) $permIds[] = 4;
                return [$item->page_id => $permIds];
            })
            ->toArray();

        return view('settings.roles.permissions', compact('role', 'pages', 'permissions', 'activePermissions'));
    }

    public function updatePermissions(Request $request, Role $role)
    {
        if ($role->role_code === 'SUPER_ADMIN') {
            return redirect()->route('roles.index')->with('error', 'Cannot update Super Admin permissions.');
        }

        DB::transaction(function() use ($request, $role) {
            RolePagePermission::where('role_id', $role->id)->delete();

            if ($request->has('permissions')) {
                foreach ($request->input('permissions') as $pageId => $perms) {
                    RolePagePermission::create([
                        'role_id' => $role->id,
                        'page_id' => $pageId,
                        'can_view' => in_array('1', $perms) || isset($perms[1]),
                        'can_create' => in_array('2', $perms) || isset($perms[2]),
                        'can_edit' => in_array('3', $perms) || isset($perms[3]),
                        'can_delete' => in_array('4', $perms) || isset($perms[4]),
                    ]);
                }
            }
        });

        return redirect()->route('roles.index')->with('success', 'Role permissions updated successfully.');
    }

    public function matrix()
    {
        $roles = Role::where('status', 'active')->get();
        $pages = Page::where('is_active', true)->get();

        $matrix = [];
        $raw = RolePagePermission::all();
        foreach ($raw as $item) {
            $matrix[$item->role_id][$item->page_id] = $item;
        }

        return view('settings.roles.matrix', compact('roles', 'pages', 'matrix'));
    }
}
