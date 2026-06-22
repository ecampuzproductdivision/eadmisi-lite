<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingFeature extends Model
{
    protected $table = 'landing_features';

    protected $fillable = [
        'nama_icon',
        'judul_poin',
        'deskripsi_poin',
        'warna_skema',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}