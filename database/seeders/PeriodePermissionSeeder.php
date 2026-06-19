<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Role;
use App\Models\RolePagePermission;
use Illuminate\Database\Seeder;

/**
 * Grant Periode page permissions to all roles.
 * This is needed because the menu is filtered by permissions.
 */
class PeriodePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $page = Page::where('page_code', 'SETTINGS_PERIODE')->first();

        if (!$page) {
            $this->command->error('Periode page not found!');
            return;
        }

        $roles = Role::all();

        foreach ($roles as $role) {
            RolePagePermission::updateOrCreate(
                ['role_id' => $role->id, 'page_id' => $page->id],
                ['can_view' => true, 'can_create' => true, 'can_edit' => true, 'can_delete' => true]
            );
            $this->command->info("Permission granted for role: {$role->role_name}");
        }

        $this->command->info('PeriodePermissionSeeder completed!');
    }
}