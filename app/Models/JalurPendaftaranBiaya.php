<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JalurPendaftaranBiaya extends Model
{
    protected $table = 'jalur_pendaftaran_biayas';

    protected $fillable = [
        'registration_path_id',
        'komponen_biaya_id',
        'nominal',
    ];

    protected $casts = [
        'nominal' => 'integer',
    ];

    public function registrationPath()
    {
        return $this->belongsTo(RegistrationPath::class);
    }

    public function komponenBiaya()
    {
        return $this->belongsTo(KomponenBiaya::class);
    }
}