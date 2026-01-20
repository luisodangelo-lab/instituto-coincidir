<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class FinancePaymentsController extends Controller
{
    public function index(Request $request)
    {
        $q = Payment::query()->with(['user','enrollment.cohort.course']);

        $status = $request->get('status', 'pending_review');
        $q->where('status', $status)->where('type', 'payment');

        $payments = $q->orderByDesc('created_at')->paginate(20);

        return view('admin.finance.payments_index', compact('payments','status'));
    }

    public function show(Payment $payment)
    {
        $payment->load(['user','enrollment.cohort.course']);
        return view('admin.finance.payment_show', compact('payment'));
    }

    public function approve(Request $request, Payment $payment)
    {
        $payment->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $request->user()->id,
        ]);

        return redirect()->route('finance.payments.show', $payment)->with('ok','Pago aprobado.');
    }

    public function reject(Request $request, Payment $payment)
    {
        $request->validate(['notes' => ['nullable','string','max:500']]);

        $payment->update([
            'status' => 'rejected',
            'approved_at' => null,
            'approved_by' => null,
            'notes' => $request->notes ?: 'Rechazado',
        ]);

        return redirect()->route('finance.payments.show', $payment)->with('ok','Pago rechazado.');
    }

    public function refund(Request $request, Payment $payment)
    {
        $request->validate([
            'refund_amount' => ['required','numeric','min:1'],
            'notes' => ['nullable','string','max:500'],
        ]);

        // MVP: registrar devolución como "refund" (sin imputación a cuotas todavía)
        Payment::create([
            'user_id' => $payment->user_id,
            'enrollment_id' => $payment->enrollment_id,
            'type' => 'refund',
            'provider' => $payment->provider,
            'method' => $payment->method,
            'amount' => $request->refund_amount,
            'status' => 'approved',
            'reference' => 'Refund de pago #'.$payment->id,
            'refund_of_payment_id' => $payment->id,
            'refunded_at' => now(),
            'refunded_by' => $request->user()->id,
            'notes' => $request->notes ?: 'Devolución',
        ]);

        return redirect()->route('finance.payments.show', $payment)->with('ok','Devolución registrada.');
    }
}
