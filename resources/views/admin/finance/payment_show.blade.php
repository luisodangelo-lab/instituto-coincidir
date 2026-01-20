@extends('layouts.app')
@section('title','Pago')

@section('content')
  <h2>Pago #{{ $payment->id }}</h2>

  <p><b>Alumno:</b> {{ $payment->user->name }} — DNI {{ $payment->user->dni }}</p>
  <p><b>Monto:</b> ${{ number_format($payment->amount,2,',','.') }}</p>
  <p><b>Método:</b> {{ $payment->method }} · <b>Proveedor:</b> {{ $payment->provider }}</p>
  <p><b>Estado:</b> {{ $payment->status }}</p>
  <p><b>Referencia:</b> {{ $payment->reference }}</p>

  @if($payment->enrollment && $payment->enrollment->cohort && $payment->enrollment->cohort->course)
    <p><b>Curso:</b> {{ $payment->enrollment->cohort->course->title }} — {{ $payment->enrollment->cohort->name }}</p>
  @endif

  <hr>

  <h3>Comprobante</h3>
  @if($payment->receipt_path && Storage::disk('public')->exists($payment->receipt_path))
    @php $url = Storage::disk('public')->url($payment->receipt_path); @endphp
    <p><a href="{{ $url }}" target="_blank">Abrir comprobante</a></p>
    <div class="card" style="padding:10px;">
      <img src="{{ $url }}" alt="Comprobante" style="max-width:100%; height:auto;">
    </div>
  @else
    <p class="muted">No hay comprobante cargado.</p>
  @endif

  <hr>

  @if($payment->status === 'pending_review')
    <form method="post" action="{{ route('finance.payments.approve', $payment) }}" style="display:inline;">
      @csrf
      <button>Aprobar</button>
    </form>

    <form method="post" action="{{ route('finance.payments.reject', $payment) }}" style="display:inline; margin-left:8px;">
      @csrf
      <input name="notes" placeholder="Motivo (opcional)" style="width:260px; display:inline-block;">
      <button>Rechazar</button>
    </form>
  @endif

  @if($payment->status === 'approved')
    <hr>
    <h3>Devolver</h3>
    <form method="post" action="{{ route('finance.payments.refund', $payment) }}">
      @csrf
      <label>Monto a devolver</label>
      <input name="refund_amount" value="{{ $payment->amount }}">
      <div style="height:8px;"></div>
      <label>Motivo (opcional)</label>
      <input name="notes">
      <div style="height:12px;"></div>
      <button>Registrar devolución</button>
    </form>
  @endif
@endsection
