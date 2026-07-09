<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegistrationPath extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'periode_id',
        'kategori_jalur_id',
        'form_pendaftaran_id',
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
        'metode_pengumuman',
        'gunakan_wawancara',
        'nilai_ambang_batas',
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
        'metode_pengumuman' => 'string',
        'gunakan_wawancara' => 'boolean',
        'nilai_ambang_batas' => 'integer',
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
     * A RegistrationPath belongs to a Form (Form Pendaftaran template).
     */
    public function formPendaftaran()
    {
        return $this->belongsTo(Form::class, 'form_pendaftaran_id');
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
     * Alias for programStudis relationship to match feedback patterns.
     */
    public function programStudi()
    {
        return $this->belongsToMany(ProgramStudi::class, 'jalur_prodi', 'registration_path_id', 'program_studi_id')
            ->withTimestamps();
    }

    /**
     * Alias for jumlah_pilihan_prodi to match feedback patterns.
     */
    public function getMaksimalPilihanAttribute()
    {
        return $this->jumlah_pilihan_prodi;
    }

    public function getIsUploadBerkasAttribute()
    {
        return $this->gunakan_berkas;
    }

    public function getIsUjianOnlineAttribute()
    {
        return $this->gunakan_ujian;
    }

    public function getIsWawancaraAttribute()
    {
        return $this->gunakan_wawancara;
    }

    public function getSyaratBerkasAttribute()
    {
        if ($this->templateBerkas) {
            return $this->templateBerkas->syaratDokumens;
        }
        return collect();
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

    /**
     * A RegistrationPath belongs to a Periode (Academic Period).
     */
    public function periode()
    {
        return $this->belongsTo(Periode::class, 'periode_id');
    }

    /**
     * A RegistrationPath has many biaya components (via pivot).
     */
    public function komponenBiayas()
    {
        return $this->belongsToMany(KomponenBiaya::class, 'jalur_pendaftaran_biayas', 'registration_path_id', 'komponen_biaya_id')
            ->withPivot('nominal', 'id')
            ->withTimestamps();
    }

    /**
     * A RegistrationPath has many biaya pivot records directly.
     */
    public function biayaPivots()
    {
        return $this->hasMany(\App\Models\JalurPendaftaranBiaya::class, 'registration_path_id');
    }

    /**
     * A RegistrationPath has many Registrations (pendaftaran).
     */
    public function registrations()
    {
        return $this->hasMany(Registration::class, 'registration_path_id');
    }

    /**
     * Scope to filter by the currently active period.
     */
    public function scopeByActivePeriode($query)
    {
        $activePeriodeId = \App\Helpers\PeriodeHelper::getActiveId();
        if ($activePeriodeId) {
            return $query->where('periode_id', $activePeriodeId);
        }
        return $query;
    }
}
