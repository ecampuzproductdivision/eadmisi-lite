<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
    protected $fillable = [
        'payment_id',
        'event',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    protected $table = 'payment_logs';

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}