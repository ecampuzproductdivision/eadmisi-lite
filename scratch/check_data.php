<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Menu;
use App\Models\Role;

echo "=== USERS ===\n";
$users = User::all();
foreach ($users as $u) {
    echo "ID: {$u->id} | Name: {$u->name} | Email: {$u->email}\n";
    echo "  Roles: " . $u->roles->pluck('role_code')->implode(', ') . "\n";
}

echo "\n=== ROLES ===\n";
$roles = Role::all();
foreach ($roles as $r) {
    echo "ID: {$r->id} | Name: {$r->role_name} | Code: {$r->role_code}\n";
}

echo "\n=== MENUS ===\n";
$menus = Menu::whereNull('parent_id')->get();
foreach ($menus as $m) {
    echo "ID: {$m->id} | Name: {$m->menu_name} | URL: {$m->url}\n";
    $children = Menu::where('parent_id', $m->id)->get();
    foreach ($children as $c) {
        echo "  └─ ID: {$c->id} | Name: {$c->menu_name} | URL: {$c->url}\n";
    }
}