<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePagePermission extends Model
{
    protected $fillable = ['role_id', 'page_id', 'can_view', 'can_create', 'can_edit', 'can_delete'];

    protected $casts = [
        'can_view' => 'boolean',
        'can_create' => 'boolean',
        'can_edit' => 'boolean',
        'can_delete' => 'boolean',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}