<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table = 'kecamatans';

    protected $fillable = ['regency_id', 'name', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function regency()
    {
        return $this->belongsTo(Regency::class);
    }

    public function kelurahans()
    {
        return $this->hasMany(Kelurahan::class);
    }
}
