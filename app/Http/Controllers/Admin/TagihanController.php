<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    /**
     * Display list of all payments for admin review with tab filtering.
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'pendaftaran');

        $query = Payment::with(['registration.registrationPath', 'registration', 'user'])
            ->where('payment_type', $tab);

        if ($request->filled('status')) {
            $query->where('transaction_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('registration', function ($q2) use ($search) {
                      $q2->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('no_pendaftaran', 'like', "%{$search}%");
                  })
                  ->orWhereHas('user', function ($q3) use ($search) {
                      $q3->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $payments = \App\Helpers\SortHelper::apply($query, ['invoice_number', 'created_at', 'transaction_status'], 'created_at', 'desc')->paginate(15)->withQueryString();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('settings.tagihan.partials.payment_rows', compact('payments', 'tab'))->render(),
                'next_page' => $payments->nextPageUrl(),
                'has_more' => $payments->hasMorePages(),
            ]);
        }

        return view('settings.tagihan.index', compact('payments', 'tab'));
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