<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramStudi extends Model
{
    protected $fillable = [
        'kode',
        'nama',
        'jenjang',
        'fakultas',
        'status',
    ];

    protected $table = 'program_studis';
}