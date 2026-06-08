<?php

namespace App\Http\ViewComposers;

use App\Models\Menu;
use App\Models\Page;
use App\Models\RolePagePermission;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MenuComposer
{
    public function compose(View $view)
    {
        $sideMenus = collect();

        $user = Auth::user();

        $allMenus = Menu::with(['children' => function ($q) {
            $q->where('is_active', true)->orderBy('sort_order');
        }])
        ->whereNull('parent_id')
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->get();

        if ($user) {
            // Check if user is SUPER_ADMIN
            $isSuperAdmin = $user->roles()->where('role_code', 'SUPER_ADMIN')->exists();

            if ($isSuperAdmin) {
                $sideMenus = $allMenus;
            } else {
                // Get user's role IDs
                $userRoleIds = $user->roles()->pluck('roles.id');

                // Get page URLs that user has permission to view
                $allowedUrls = Page::whereIn('id', function ($q) use ($userRoleIds) {
                    $q->select('page_id')
                      ->from('role_page_permissions')
                      ->whereIn('role_id', $userRoleIds)
                      ->where('can_view', true);
                })->pluck('url')->toArray();

                // Filter menus: only show if user has permission
                foreach ($allMenus as $menu) {
                    // Check if parent menu itself has accessible URL
                    $menuHasAccess = $menu->url && in_array($menu->url, $allowedUrls);

                    // Filter children
                    $filteredChildren = $menu->children->filter(function ($child) use ($allowedUrls) {
                        return $child->url && in_array($child->url, $allowedUrls);
                    });

                    if ($menuHasAccess || $filteredChildren->isNotEmpty()) {
                        $menu->setRelation('children', $filteredChildren);
                        $sideMenus->push($menu);
                    }
                }
            }
        }

        $view->with('sideMenus', $sideMenus);
    }
}
