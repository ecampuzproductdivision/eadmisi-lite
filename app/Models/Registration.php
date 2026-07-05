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
        'nisn',
        'nama_ibu_kandung',
        'penerima_kps',
        'kebutuhan_khusus',
        'kewarganegaraan',
        'regency_id',
        'kecamatan_id',
        'kelurahan_id',
        'nim',
        're_registration_submitted_at',
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

    /**
     * A registration has one wawancara (interview) record.
     */
    public function wawancara()
    {
        return $this->hasOne(Wawancara::class, 'pendaftaran_id');
    }

    /**
     * A registration has many exam results.
     */
    public function examResults()
    {
        return $this->hasMany(ExamResult::class);
    }

    public function regency()
    {
        return $this->belongsTo(Regency::class, 'regency_id');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class, 'kelurahan_id');
    }
}
