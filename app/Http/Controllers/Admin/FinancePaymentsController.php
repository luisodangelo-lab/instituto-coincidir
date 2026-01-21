<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\BillingService;
use Illuminate\Http\Request;

class FinancePaymentsController extends Controller
{
    public function index(Request $request)
    {
        $q = Payment::query()->with(['user', 'enrollment.cohort.course']);

        $status = $request->get('status', 'pending_review');
        $q->where('status', $status)->where('type', 'payment');

        $payments = $q->orderByDesc('created_at')->paginate(20);

        return view('admin.finance.payments_index', compact('payments', 'status'));
    }

    public function show(Payment $payment)
    {
        $payment->load(['user', 'enrollment.cohort.course']);
        return view('admin.finance.payment_show', compact('payment'));
    }

    public function approve(Request $request, Payment $payment, BillingService $billing)
    {
        $billing->approvePayment($payment, $request->user()->id);

        return redirect()
            ->route('finance.payments.show', $payment)
            ->with('ok', 'Pago aprobado e imputado.');
    }

    public function reject(Request $request, Payment $payment)
    {
        $request->validate(['notes' => ['nullable', 'string', 'max:500']]);

        $payment->update([
            'status' => 'rejected',
            'approved_at' => null,
            'approved_by' => null,
            'notes' => $request->notes ?: 'Rechazado',
        ]);

        return redirect()
            ->route('finance.payments.show', $payment)
            ->with('ok', 'Pago rechazado.');
    }

    public function refund(Request $request, Payment $payment, BillingService $billing)
    {
        $request->validate([
            'refund_amount' => ['required', 'numeric', 'min:1'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $billing->registerRefund(
            $payment,
            (float) $request->refund_amount,
            $request->user()->id,
            $request->notes
        );

        return redirect()
            ->route('finance.payments.show', $payment)
            ->with('ok', 'Devolución registrada (wallet).');
    }
}