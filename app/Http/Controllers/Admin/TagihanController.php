<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    /**
     * Display list of all payments for admin review.
     */
    public function index(Request $request)
    {
        $query = Payment::with(['registration.registrationPath', 'registration', 'user'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('transaction_status', $request->status);
        }

        // Search by name or invoice number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('registration', function ($q2) use ($search) {
                      $q2->where('nama_lengkap', 'like', "%{$search}%");
                  })
                  ->orWhereHas('user', function ($q3) use ($search) {
                      $q3->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $payments = $query->paginate(15)->withQueryString();

        return view('settings.tagihan.index', compact('payments'));
    }

    /**
     * Manual verify payment.
     */
    public function verify($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        app(\App\Services\PaymentService::class)->manualVerify($payment);

        return redirect()->back()
            ->with('success', 'Pembayaran ' . $payment->invoice_number . ' berhasil diverifikasi.');
    }
}