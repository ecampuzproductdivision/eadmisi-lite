<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
    protected $table = 'kabupatens';

    protected $fillable = ['nama_kabupaten'];

    public function kecamatans()
    {
        return $this->hasMany(Kecamatan::class);
    }
}