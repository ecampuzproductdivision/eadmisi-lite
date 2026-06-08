<?php

namespace App\Http\Middleware;

use App\Models\Page;
use App\Models\RolePagePermission;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class CheckPermission
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Super Admin has all permissions
        if ($user->roles()->where('role_code', 'SUPER_ADMIN')->exists()) {
            return $next($request);
        }

        // Get current route URI path
        $currentPath = '/' . trim($request->path(), '/');

        // Find page by URL
        $page = Page::where('url', $currentPath)->where('is_active', true)->first();

        if (!$page) {
            // Allow if no page record found (e.g., home, account-settings)
            return $next($request);
        }

        // Check if user has any role with permission to view this page
        $userRoleIds = $user->roles()->pluck('roles.id');

        $hasPermission = RolePagePermission::whereIn('role_id', $userRoleIds)
            ->where('page_id', $page->id)
            ->where('can_view', true)
            ->exists();

        if (!$hasPermission) {
            abort(403, 'Unauthorized. You do not have permission to access this page.');
        }

        return $next($request);
    }
}