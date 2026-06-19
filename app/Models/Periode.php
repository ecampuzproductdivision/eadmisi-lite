<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Periode (Academic Period) Model
 *
 * Manages academic years and semesters for registration periods.
 * Only ONE period can have status_aktif = true at any time.
 */
class Periode extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'periode';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'tahun_akademik',
        'semester',
        'status_aktif',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status_aktif' => 'boolean',
    ];

    /**
     * Get the year start from tahun_akademik format "YYYY/YYYY".
     */
    public function getTahunMulaiAttribute(): string
    {
        return explode('/', $this->tahun_akademik)[0] ?? '';
    }

    /**
     * Get the year end from tahun_akademik format "YYYY/YYYY".
     */
    public function getTahunSelesaiAttribute(): string
    {
        return explode('/', $this->tahun_akademik)[1] ?? '';
    }

    /**
     * Display label for the period.
     */
    public function getLabelAttribute(): string
    {
        return "{$this->tahun_akademik} - {$this->semester}";
    }

    // ──────────────────────────────────────────────
    //  Scopes
    // ──────────────────────────────────────────────

    /**
     * Scope to filter only the active period.
     */
    public function scopeActive($query)
    {
        return $query->where('status_aktif', true);
    }

    // ──────────────────────────────────────────────
    //  Relations
    // ──────────────────────────────────────────────

    /**
     * A period has many registration paths (Jalur Pendaftaran).
     */
    public function registrationPaths()
    {
        return $this->hasMany(RegistrationPath::class, 'periode_id');
    }
}