<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingProgramStudi extends Model
{
    protected $table = 'landing_program_studi';

    protected $fillable = [
        'program_studi_id',
        'deskripsi_singkat',
        'kode_icon',
        'akreditasi',
        'jumlah_semester',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'jumlah_semester' => 'integer',
    ];

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'program_studi_id');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}