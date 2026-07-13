<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlottingPotongan extends Model
{
    protected $table = 'plotting_potongans';

    protected $fillable = [
        'registration_id',
        'master_potongan_id',
        'nominal_potongan',
        'keterangan',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    public function masterPotongan()
    {
        return $this->belongsTo(MasterPotongan::class, 'master_potongan_id');
    }
}
