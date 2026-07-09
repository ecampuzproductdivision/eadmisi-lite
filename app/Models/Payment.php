<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'registration_id',
        'payment_type',
        'user_id',
        'invoice_number',
        'amount',
        'payment_method',
        'payment_channel',
        'transaction_id',
        'transaction_status',
        'paid_at',
        'expired_at',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
        'metadata' => 'array',
    ];

    protected $table = 'payments';

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function logs()
    {
        return $this->hasMany(PaymentLog::class);
    }

    /**
     * Generate unique invoice number.
     */
    public static function generateInvoiceNumber(): string
    {
        $date = now()->format('Ymd');
        $last = self::whereDate('created_at', today())->count();
        return 'INV/' . $date . '/' . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Check if payment is expired.
     */
    public function isExpired(): bool
    {
        return $this->expired_at && now()->gt($this->expired_at);
    }

    /**
     * Check if payment is successful.
     */
    public function isSuccess(): bool
    {
        return $this->transaction_status === 'success';
    }
}