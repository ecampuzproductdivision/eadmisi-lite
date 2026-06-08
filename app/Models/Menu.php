<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['menu_name', 'menu_code', 'parent_id', 'icon', 'url', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('sort_order');
    }

    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }
}