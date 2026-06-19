<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Wawancara - Interview scheduling and result model.
 *
 * Manages interview schedules, locations, and results for applicants
 * whose registration path has 'gunakan_wawancara' enabled.
 * If status_wawancara = 'Tidak Lolos', the applicant is automatically
 * marked as failed regardless of exam score.
 */
class Wawancara extends Model
{
    protected $table = 'wawancara';

    protected $fillable = [
        'pendaftaran_id',
        'tanggal_wawancara',
        'jam_wawancara',
        'lokasi_wawancara',
        'nama_pewawancara',
        'status_wawancara',
        'catatan_pewawancara',
    ];

    protected $casts = [
        'tanggal_wawancara' => 'date',
        'jam_wawancara' => 'string',
    ];

    // ──────────────────────────────────────────────
    //  Relations
    // ──────────────────────────────────────────────

    /**
     * A wawancara record belongs to a registration (pendaftaran).
     */
    public function pendaftaran()
    {
        return $this->belongsTo(Registration::class, 'pendaftaran_id');
    }

    /**
     * Get the applicant's user through the registration.
     */
    public function user()
    {
        return $this->hasOneThrough(User::class, Registration::class, 'id', 'id', 'pendaftaran_id', 'user_id');
    }
}