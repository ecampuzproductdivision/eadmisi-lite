<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemplateBerkas extends Model
{
    use SoftDeletes;

    protected $table = 'template_berkas';

    protected $fillable = [
        'nama_template',
        'deskripsi',
        'status_aktif',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('status_aktif', true);
    }

    public function syaratDokumens()
    {
        return $this->hasMany(SyaratDokumen::class, 'template_berkas_id');
    }

    public function getTotalDokumenAttribute()
    {
        return $this->syaratDokumens()->count();
    }
}