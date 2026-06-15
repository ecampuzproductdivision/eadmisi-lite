<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SoalUjian extends Model
{
    use SoftDeletes;

    protected $table = 'soal_ujian';

    protected $fillable = [
        'paket_soal_id',
        'pertanyaan',
        'opsi_a',
        'opsi_b',
        'opsi_c',
        'opsi_d',
        'kunci_jawaban',
        'skor',
        'urutan',
        'status_aktif',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
        'skor' => 'integer',
        'urutan' => 'integer',
    ];

    /**
     * Scope: hanya soal aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('status_aktif', true);
    }

    /**
     * A SoalUjian belongs to a PaketSoal.
     */
    public function paketSoal()
    {
        return $this->belongsTo(PaketSoal::class, 'paket_soal_id');
    }
}