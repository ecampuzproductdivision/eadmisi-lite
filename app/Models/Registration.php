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
        'status_kelulusan',
        'status_pendaftaran',
        'status_registrasi_ulang',
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

    /**
     * Status registrasi ulang labels for display.
     */
    const STATUS_REGISTRASI_ULANG_LABELS = [
        'belum_registrasi'             => 'Belum Registrasi Ulang',
        'menunggu_pembayaran'          => 'Menunggu Pembayaran Registrasi Ulang',
        'sudah_registrasi_no_tagihan'  => 'Sudah Registrasi Ulang',
        'sudah_registrasi_lunas'       => 'Sudah Melakukan Registrasi Ulang',
    ];

    /**
     * Get human-readable label for status_registrasi_ulang.
     */
    public function getStatusRegistrasiUlangLabelAttribute(): string
    {
        return self::STATUS_REGISTRASI_ULANG_LABELS[$this->status_registrasi_ulang] ?? '-';
    }

    /**
     * Get badge class for status_registrasi_ulang.
     */
    public function getStatusRegistrasiUlangBadgeAttribute(): string
    {
        return match ($this->status_registrasi_ulang) {
            'belum_registrasi'            => 'bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle',
            'menunggu_pembayaran'         => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
            'sudah_registrasi_no_tagihan' => 'bg-info-subtle text-info-emphasis border border-info-subtle',
            'sudah_registrasi_lunas'      => 'bg-success-subtle text-success-emphasis border border-success-subtle',
            default                       => 'bg-light text-muted',
        };
    }

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

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'regency_id');
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