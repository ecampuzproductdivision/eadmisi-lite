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
        'jumlah_pilihan_prodi',
        'is_active',
        'gunakan_ujian',
        'paket_soal_id',
        'gunakan_berkas',
        'template_berkas_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'registration_start' => 'date',
        'registration_end' => 'date',
        'fee' => 'decimal:2',
        'quota' => 'integer',
        'jumlah_pilihan_prodi' => 'integer',
        'gunakan_ujian' => 'boolean',
        'gunakan_berkas' => 'boolean',
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

    /**
     * Many-to-many: Jalur Pendaftaran memiliki banyak Program Studi yang ditawarkan.
     */
    public function programStudis()
    {
        return $this->belongsToMany(ProgramStudi::class, 'jalur_prodi', 'registration_path_id', 'program_studi_id')
            ->withTimestamps();
    }

    /**
     * A RegistrationPath belongs to a PaketSoal (optional).
     */
    public function paketSoal()
    {
        return $this->belongsTo(PaketSoal::class, 'paket_soal_id');
    }

    /**
     * A RegistrationPath belongs to a TemplateBerkas (optional).
     */
    public function templateBerkas()
    {
        return $this->belongsTo(TemplateBerkas::class, 'template_berkas_id');
    }
}
