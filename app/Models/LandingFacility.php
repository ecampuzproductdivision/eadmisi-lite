<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingFacility extends Model
{
    protected $table = 'landing_facilities';

    protected $fillable = [
        'nama_fasilitas',
        'deskripsi_fasilitas',
        'kode_icon',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'urutan' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('urutan');
    }
}