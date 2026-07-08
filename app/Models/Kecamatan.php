<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table = 'kecamatans';

    protected $fillable = ['kabupaten_id', 'nama_kecamatan', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class);
    }

    public function kelurahans()
    {
        return $this->hasMany(Kelurahan::class);
    }
}