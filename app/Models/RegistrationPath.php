<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegistrationPath extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'kategori_jalur_id',
        'code',
        'name',
        'description',
        'registration_start',
        'registration_end',
        'fee',
        'color',
        'quota',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'registration_start' => 'date',
        'registration_end' => 'date',
        'fee' => 'decimal:2',
        'quota' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOpen($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('registration_start')
                  ->orWhere('registration_start', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('registration_end')
                  ->orWhere('registration_end', '>=', now());
            });
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriJalur::class, 'kategori_jalur_id');
    }
}