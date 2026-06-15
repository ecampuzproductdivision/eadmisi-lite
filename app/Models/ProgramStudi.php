<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramStudi extends Model
{
    protected $fillable = [
        'kode',
        'kode_prodi',
        'label_nim',
        'nama',
        'nama_prodi',
        'jenjang',
        'jenjang_akademik',
        'fakultas',
        'jurusan',
        'kelompok',
        'program',
        'label_prodi_no_pendaftaran',
        'status',
        'status_aktif',
    ];

    protected $table = 'program_studis';

    protected $casts = [
        'status' => 'boolean',
        'status_aktif' => 'boolean',
    ];

    /**
     * Scope a query to only include active study programs.
     */
    public function scopeActive($query)
    {
        return $query->where('status_aktif', true);
    }

    /**
     * Get the registrations for this study program (as pilihan 1).
     */
    public function registrationsAsPilihan1()
    {
        return $this->hasMany(Registration::class, 'program_studi_1_id');
    }

    /**
     * Get the registrations for this study program (as pilihan 2).
     */
    public function registrationsAsPilihan2()
    {
        return $this->hasMany(Registration::class, 'program_studi_2_id');
    }
}