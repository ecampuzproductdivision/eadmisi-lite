<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterPotongan extends Model
{
    protected $table = 'master_potongans';

    protected $fillable = [
        'nama_potongan',
        'tipe_potongan',
        'nilai_potongan',
        'keterangan',
    ];

    public function plottings()
    {
        return $this->hasMany(PlottingPotongan::class, 'master_potongan_id');
    }
}
