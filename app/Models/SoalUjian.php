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
     * Alias: soal_ujian.pertanyaan → question (for view compatibility).
     */
    public function getQuestionAttribute()
    {
        return $this->pertanyaan;
    }

    /**
     * Default category (soal_ujian doesn't have this column).
     */
    public function getCategoryAttribute()
    {
        return 'General';
    }

    /**
     * Accessor: generate options array from opsi_a, opsi_b, opsi_c, opsi_d columns.
     * Format: ["A. <value>", "B. <value>", ...] to match the tes-online view.
     */
    public function getOptionsAttribute()
    {
        $result = [];
        if ($this->opsi_a) $result[] = 'A. ' . $this->opsi_a;
        if ($this->opsi_b) $result[] = 'B. ' . $this->opsi_b;
        if ($this->opsi_c) $result[] = 'C. ' . $this->opsi_c;
        if ($this->opsi_d) $result[] = 'D. ' . $this->opsi_d;
        return $result;
    }

    /**
     * A SoalUjian belongs to a PaketSoal.
     */
    public function paketSoal()
    {
        return $this->belongsTo(PaketSoal::class, 'paket_soal_id');
    }
}
