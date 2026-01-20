<?php

namespace App\Http\Controllers\My;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Payment;
use Illuminate\Http\Request;

class MyPaymentsController extends Controller
{
    public function create(Request $request)
    {
        $u = $request->user();
        $enrollments = $u->enrollments()->with('cohort.course')->latest()->get();
        return view('my.payments_new', compact('u','enrollments'));
    }

    public function store(Request $request)
    {
        $u = $request->user();

        $request->validate([
            'enrollment_id' => ['nullable','integer'],
            'amount' => ['required','numeric','min:1'],
            'method' => ['required','string'],
            'reference' => ['nullable','string','max:120'],
            'paid_at' => ['nullable','date'],
            'receipt' => ['required','file','max:5120','mimes:pdf,jpg,jpeg,png'],
        ]);

        $enr = null;
        if ($request->filled('enrollment_id')) {
            $enr = Enrollment::where('id', $request->enrollment_id)
                ->where('user_id', $u->id)
                ->firstOrFail();
        }

        $year = date('Y');
        $path = $request->file('receipt')->store("receipts/$year", 'public');

        Payment::create([
            'user_id' => $u->id,
            'enrollment_id' => $enr?->id,
            'type' => 'payment',
            'provider' => 'manual',
            'method' => $request->method,
            'amount' => $request->amount,
            'reference' => $request->reference,
            'receipt_path' => $path,
            'status' => 'pending_review',
            'paid_at' => $request->paid_at,
            'created_by' => $u->id,
            'notes' => 'Pago cargado por alumno',
        ]);

        return redirect()->route('my.installments')
            ->with('ok', 'Pago enviado. Queda pendiente de aprobación.');
    }
}
