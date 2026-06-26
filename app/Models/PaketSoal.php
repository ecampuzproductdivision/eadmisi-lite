<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaketSoal extends Model
{
    use SoftDeletes;

    protected $table = 'paket_soal';

    protected $fillable = [
        'nama_paket',
        'deskripsi',
        'status_aktif',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
    ];

    /**
     * Scope: only active packages.
     */
    public function scopeActive($query)
    {
        return $query->where('status_aktif', true);
    }

    /**
     * A PaketSoal has many SoalUjian.
     */
    public function soalUjians()
    {
        return $this->hasMany(SoalUjian::class, 'paket_soal_id');
    }

    /**
     * Get total aggregated skor of all questions in this package.
     */
    public function getTotalSkorAttribute()
    {
        return (int) $this->soalUjians()->sum('skor');
    }

    /**
     * Get total count of questions in this package.
     */
    public function getTotalSoalAttribute()
    {
        return $this->soalUjians()->count();
    }
}