<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = [
        'user_id',
        'registration_path_id',
        'program_studi_1_id',
        'program_studi_2_id',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'nik',
        'alamat',
        'kode_pos',
        'no_hp',
        'email',
        'nama_sekolah',
        'jurusan',
        'tahun_lulus',
        'status',
    ];

    protected $table = 'registrations';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function registrationPath()
    {
        return $this->belongsTo(RegistrationPath::class);
    }

    public function programStudi1()
    {
        return $this->belongsTo(ProgramStudi::class, 'program_studi_1_id');
    }

    public function programStudi2()
    {
        return $this->belongsTo(ProgramStudi::class, 'program_studi_2');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function documents()
    {
        return $this->hasMany(RegistrationDocument::class);
    }
}
