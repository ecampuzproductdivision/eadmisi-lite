<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\PaymentLog;
use App\Models\Registration;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    /**
     * Create a new payment/invoice for a registration.
     * Siap diintegrasikan dengan payment aggregator seperti Midtrans/Finnet.
     */
    public function createInvoice(Registration $registration, string $paymentType = 'pendaftaran', ?int $customAmount = null): Payment
    {
        return DB::transaction(function () use ($registration, $paymentType, $customAmount) {
            $amount = $customAmount ?? ($registration->registrationPath?->fee ?? 0);
            
            // Set deadline 24 jam dari sekarang
            $expiredAt = now()->addHours(24);

            $payment = Payment::create([
                'registration_id'    => $registration->id,
                'payment_type'       => $paymentType,
                'user_id'            => $registration->user_id,
                'invoice_number'     => Payment::generateInvoiceNumber(),
                'amount'             => $amount,
                'transaction_status' => 'pending',
                'expired_at'         => $expiredAt,
            ]);

            // Log pembuatan invoice
            PaymentLog::create([
                'payment_id' => $payment->id,
                'event'      => 'invoice_created',
                'payload'    => [
                    'registration_id' => $registration->id,
                    'amount'          => $amount,
                    'expired_at'      => $expiredAt->toDateTimeString(),
                ],
            ]);

            // Update registration status
            $registration->update([
                'status'           => 'payment_pending',
                'payment_deadline' => $expiredAt,
            ]);

            return $payment;
        });
    }

    /**
     * Process payment callback from aggregator.
     * Midtrans: POST notification
     * Finnet: POST callback
     */
    public function processCallback(array $payload): Payment
    {
        return DB::transaction(function () use ($payload) {
            // Cari payment berdasarkan transaction_id atau order_id
            $transactionId = $payload['transaction_id'] ?? null;
            $invoiceNumber = $payload['invoice_number'] ?? $payload['order_id'] ?? null;

            $payment = null;
            if ($transactionId) {
                $payment = Payment::where('transaction_id', $transactionId)->first();
            }
            if (!$payment && $invoiceNumber) {
                $payment = Payment::where('invoice_number', $invoiceNumber)->first();
            }

            if (!$payment) {
                // Log callback yang tidak dikenal
                PaymentLog::create([
                    'payment_id' => 0,
                    'event'      => 'callback_unknown',
                    'payload'    => $payload,
                ]);
                throw new \Exception('Payment not found for callback');
            }

            // Simpan metadata dari aggregator
            $payment->update([
                'metadata'        => $payload,
                'transaction_id'  => $payload['transaction_id'] ?? $payment->transaction_id,
                'payment_method'  => $payload['payment_method'] ?? $payment->payment_method,
                'payment_channel' => $payload['payment_channel'] ?? $payment->payment_channel,
            ]);

            // Tentukan status berdasarkan response aggregator
            $transactionStatus = $this->mapAggregatorStatus($payload);

            if ($transactionStatus === 'success') {
                $payment->update([
                    'transaction_status' => 'success',
                    'paid_at'            => now(),
                ]);

                // Update registration
                $payment->registration->update([
                    'status' => 'payment_verified',
                    'paid_at' => now(),
                ]);

                $event = 'payment_success';
            } elseif ($transactionStatus === 'failed' || $transactionStatus === 'expired') {
                $payment->update([
                    'transaction_status' => $transactionStatus,
                ]);

                // Kembalikan status registration ke sebelumnya
                $payment->registration->update([
                    'status' => 'documents_uploaded',
                ]);

                $event = 'payment_' . $transactionStatus;
            } else {
                $event = 'callback_pending';
            }

            // Log callback
            PaymentLog::create([
                'payment_id' => $payment->id,
                'event'      => $event,
                'payload'    => $payload,
            ]);

            return $payment->fresh();
        });
    }

    /**
     * Map status dari payment aggregator ke internal status.
     * 
     * Midtrans:
     *   - settlement / capture → success
     *   - expire → expired
     *   - deny / cancel → failed
     *   - pending / challenge → pending
     * 
     * Finnet:
     *   - 00 / success → success
     *   - timeout / expired → expired
     *   - 01-99 / failed → failed
     */
    private function mapAggregatorStatus(array $payload): string
    {
        $statusCode = $payload['transaction_status'] ?? $payload['status_code'] ?? 'pending';
        $fraudStatus = $payload['fraud_status'] ?? null;

        // Mapping untuk Midtrans
        if (in_array($statusCode, ['settlement', 'capture'])) {
            return 'success';
        }
        if ($statusCode === 'expire') {
            return 'expired';
        }
        if (in_array($statusCode, ['deny', 'cancel'])) {
            return 'failed';
        }

        // Mapping untuk Finnet (atau generic)
        if ($statusCode === '00' || $statusCode === 'success') {
            return 'success';
        }
        if (in_array($statusCode, ['timeout', 'expired'])) {
            return 'expired';
        }
        if ($statusCode !== 'pending' && $statusCode !== 'challenge') {
            return 'failed';
        }

        // Cek fraud_status Midtrans
        if ($fraudStatus === 'deny' || $fraudStatus === 'reject') {
            return 'failed';
        }

        return 'pending';
    }

    /**
     * Check payment status from aggregator.
     * 
     * Midtrans: GET /{order_id}/status
     * Finnet: POST inquiry
     */
    public function checkStatus(Payment $payment): Payment
    {
        // TODO: Implement actual API call to payment aggregator
        // Midtrans: \Midtrans\Transaction::status($payment->invoice_number);
        // Finnet: Kirim inquiry request

        // Placeholder - akan dipanggil saat integrasi
        PaymentLog::create([
            'payment_id' => $payment->id,
            'event'      => 'status_check',
            'payload'    => ['message' => 'Status check called - implement API integration'],
        ]);

        return $payment->fresh();
    }

    /**
     * Manually verify payment by admin.
     */
    public function manualVerify(Payment $payment): Payment
    {
        return DB::transaction(function () use ($payment) {
            $payment->update([
                'transaction_status' => 'success',
                'paid_at'            => now(),
                'payment_method'     => 'manual',
                'payment_channel'    => 'admin_verification',
            ]);

            $payment->registration->update([
                'status' => 'payment_verified',
                'paid_at' => now(),
            ]);

            PaymentLog::create([
                'payment_id' => $payment->id,
                'event'      => 'manual_verify',
                'payload'    => [
                    'verified_by' => auth()->id(),
                    'verified_at' => now()->toDateTimeString(),
                ],
            ]);

            return $payment->fresh();
        });
    }

    /**
     * Get or create pending payment for a registration.
     */
    public function getOrCreateInvoice(Registration $registration, string $paymentType = 'pendaftaran', ?int $customAmount = null): Payment
    {
        // Cek apakah sudah ada payment pending untuk tipe ini
        $existingPayment = Payment::where('registration_id', $registration->id)
            ->where('payment_type', $paymentType)
            ->where('transaction_status', 'pending')
            ->first();

        if ($existingPayment) {
            return $existingPayment;
        }

        return $this->createInvoice($registration, $paymentType, $customAmount);
    }
}