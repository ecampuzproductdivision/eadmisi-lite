<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Page;
use App\Models\Role;
use App\Models\RolePagePermission;
use Illuminate\Database\Seeder;

class CrmLeadMenuSeeder extends Seeder
{
    public function run(): void
    {
        // Check if menu already exists
        $existing = Menu::where('menu_code', 'CRM_LEADS')->first();
        if ($existing) {
            $this->command->info('CRM Leads menu already exists.');
            return;
        }

        // Create as root menu item (top level) - directly below "Kelola Wawancara" (sort_order 17)
        $menu = Menu::create([
            'menu_name'  => 'CRM Leads',
            'menu_code'  => 'CRM_LEADS',
            'parent_id'  => null,
            'icon'       => 'ti ti-users',
            'url'        => '/settings/crm-leads',
            'sort_order' => 18,
            'is_active'  => true,
        ]);

        $this->command->info("CRM Leads menu created (ID: {$menu->id})");

        // Create Page record
        $page = Page::create([
            'page_name'  => 'CRM Leads',
            'page_code'  => 'CRM_LEADS',
            'url'        => '/settings/crm-leads',
            'menu_id'    => $menu->id,
            'icon'       => 'ti ti-users',
            'sort_order' => 1,
            'is_active'  => true,
        ]);

        $this->command->info("CRM Leads page created (ID: {$page->id})");

        // Grant permissions to all roles
        $roles = Role::all();
        foreach ($roles as $role) {
            RolePagePermission::updateOrCreate(
                ['role_id' => $role->id, 'page_id' => $page->id],
                ['can_view' => true, 'can_create' => true, 'can_edit' => true, 'can_delete' => true]
            );
        }

        $this->command->info('Permissions granted to ' . $roles->count() . ' roles.');
        $this->command->info('CrmLeadMenuSeeder completed!');
    }
}