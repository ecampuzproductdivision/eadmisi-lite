<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['page_name', 'page_code', 'url', 'menu_id', 'icon', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function rolePagePermissions()
    {
        return $this->hasMany(RolePagePermission::class);
    }
}