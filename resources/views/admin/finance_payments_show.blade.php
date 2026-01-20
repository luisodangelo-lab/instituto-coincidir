@extends('layouts.app')
@section('title','Pago')

@section('content')
  <h2>Pago #{{ $payment->id }}</h2>

  <p>
    <b>Alumno:</b> {{ $payment->user->dni }} — {{ $payment->user->name }}<br>
    <b>Monto:</b> ${{ number_format($payment->amount, 2, ',', '.') }}<br>
    <b>Método:</b> {{ $payment->method }}<br>
    <b>Ref:</b> {{ $payment->reference }}<br>
    <b>Estado:</b> {{ $payment->status }}
  </p>

  @if($receiptUrl)
    <p><a href="{{ $receiptUrl }}" target="_blank">Abrir comprobante</a></p>
  @endif

  @if($payment->status === 'pending_review')
    <form method="post" action="{{ route('finance.payments.approve', $payment) }}" style="display:inline;">
      @csrf
      <button>✅ Aprobar</button>
    </form>

    <form method="post" action="{{ route('finance.payments.reject', $payment) }}" style="display:inline;">
      @csrf
      <button style="background:#f8d7da;">✖ Rechazar</button>
    </form>
  @endif

  @if($payment->status === 'approved')
    <hr>
    <h3>Devolución</h3>
    <form method="post" action="{{ route('finance.payments.refund', $payment) }}">
      @csrf
      <label>Importe a devolver</label>
      <input name="refund_amount" value="0">

      <div style="height:10px"></div>

      <label>Motivo (opcional)</label>
      <input name="reason">

      <div style="height:14px"></div>

      <button style="background:#f8d7da;">↩ Devolver</button>
    </form>
  @endif
@endsection
