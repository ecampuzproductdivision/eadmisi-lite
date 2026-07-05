<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelurahan extends Model
{
    protected $table = 'kelurahans';

    protected $fillable = ['kecamatan_id', 'name', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }
}
