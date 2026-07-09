<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Registration;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Create invoice and redirect to payment gateway.
     * Accepts optional payment_type from request (pendaftaran / registrasi_ulang).
     */
    public function createInvoice(Request $request, $registrationId)
    {
        $registration = Registration::where('user_id', auth()->id())
            ->findOrFail($registrationId);

        $paymentType = $request->input('payment_type', 'pendaftaran');
        $amount = null;

        // For registrasi_ulang, calculate total from komponen biaya
        if ($paymentType === 'registrasi_ulang' && $registration->registrationPath) {
            $biayaTotal = \App\Models\JalurPendaftaranBiaya::where('registration_path_id', $registration->registrationPath->id)
                ->sum('nominal');
            if ($biayaTotal > 0) {
                $amount = $biayaTotal;
            }
        }

        $payment = $this->paymentService->getOrCreateInvoice($registration, $paymentType, $amount);

        session()->flash('active_tab', 'ulang');

        return redirect()->route('tagihan.index')
            ->with('success', 'Invoice berhasil dibuat. Invoice: ' . $payment->invoice_number);
    }

    /**
     * Handle payment callback/webhook from aggregator.
     */
    public function callback(Request $request)
    {
        try {
            $payload = $request->all();
            $payment = $this->paymentService->processCallback($payload);

            return response()->json([
                'success' => true,
                'message' => 'Callback processed',
                'payment_status' => $payment->transaction_status,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Check payment status.
     */
    public function checkStatus($paymentId)
    {
        $payment = Payment::where('user_id', auth()->id())
            ->findOrFail($paymentId);

        $payment = $this->paymentService->checkStatus($payment);

        return response()->json([
            'success' => true,
            'payment' => [
                'id' => $payment->id,
                'invoice_number' => $payment->invoice_number,
                'amount' => $payment->amount,
                'status' => $payment->transaction_status,
                'paid_at' => $payment->paid_at?->toDateTimeString(),
                'expired_at' => $payment->expired_at?->toDateTimeString(),
            ],
        ]);
    }

    /**
     * Admin: Manual verify payment.
     */
    public function manualVerify($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        $this->paymentService->manualVerify($payment);

        return redirect()->back()
            ->with('success', 'Pembayaran ' . $payment->invoice_number . ' berhasil diverifikasi.');
    }
}