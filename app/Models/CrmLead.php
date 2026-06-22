<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmLead extends Model
{
    const STATUSES = ['New', 'In Progress', 'Responded', 'Converted'];

    protected $fillable = [
        'nama',
        'whatsapp',
        'pertanyaan',
        'status',
        'catatan_admin',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public static function statusBadgeClass($status): string
    {
        return match ($status) {
            'New'        => 'danger',
            'In Progress' => 'warning',
            'Responded'  => 'success',
            'Converted'  => 'primary',
            default      => 'secondary',
        };
    }
}