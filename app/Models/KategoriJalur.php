<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriJalur extends Model
{
    protected $fillable = [
        'nama',
    ];

    protected $table = 'kategori_jalurs';

    public function registrationPaths()
    {
        return $this->hasMany(RegistrationPath::class, 'kategori_jalur_id');
    }
}