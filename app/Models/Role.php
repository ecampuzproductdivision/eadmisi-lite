<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['role_name', 'role_code', 'description', 'status'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles');
    }

    public function pagePermissions()
    {
        return $this->hasMany(RolePagePermission::class);
    }
}