<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KomponenBiaya extends Model
{
    protected $fillable = [
        'kode_komponen',
        'nama_komponen',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}