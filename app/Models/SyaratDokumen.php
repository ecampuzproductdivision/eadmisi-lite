<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SyaratDokumen extends Model
{
    use SoftDeletes;

    protected $table = 'syarat_dokumen';

    protected $fillable = [
        'template_berkas_id',
        'nama_dokumen',
        'ekstensi_diizinkan',
        'max_size',
        'status_wajib',
        'urutan',
    ];

    protected $casts = [
        'status_wajib' => 'boolean',
        'max_size' => 'integer',
        'urutan' => 'integer',
    ];

    public function templateBerkas()
    {
        return $this->belongsTo(TemplateBerkas::class, 'template_berkas_id');
    }
}