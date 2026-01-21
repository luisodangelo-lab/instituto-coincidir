@extends('layouts.app')
@section('title','Cargar pago')

@section('content')
  <h2>Cargar pago / comprobante</h2>
  <p class="muted">Subí PDF/JPG/PNG (transferencia o captura MP). Queda pendiente de aprobación.</p>

  <form method="post" action="{{ route('my.payments.store') }}" enctype="multipart/form-data">
    @csrf

    {{-- Si viene desde "Pagar" de una cuota --}}
    <input type="hidden" name="installment_id" value="{{ old('installment_id', request('installment_id')) }}">

    <label>Curso / matrícula (opcional)</label>
    <select name="enrollment_id">
      <option value="">(sin seleccionar)</option>
      @foreach($enrollments as $enr)
        <option value="{{ $enr->id }}" @selected(old('enrollment_id', request('enrollment_id')) == $enr->id)>
          {{ $enr->cohort->course->title }} — {{ $enr->cohort->name }}
        </option>
      @endforeach
    </select>

    <div style="height:10px"></div>

    <label>Monto</label>
    <input name="amount" value="{{ old('amount', request('amount')) }}">

    <div style="height:10px"></div>

    <label>Método</label>
    <select name="method">
      @foreach(['transferencia','mercadopago','efectivo','otro'] as $m)
        <option value="{{ $m }}" @selected(old('method','transferencia')===$m)>{{ $m }}</option>
      @endforeach
    </select>

    <div style="height:10px"></div>

    <label>Referencia (alias/ID MP/observación)</label>
    <input name="reference" value="{{ old('reference', request('reference')) }}">

    <div style="height:10px"></div>

    <label>Fecha de pago (opcional)</label>
    <input type="date" name="paid_at" value="{{ old('paid_at') }}">

    <div style="height:10px"></div>

    <label>Comprobante (PDF/JPG/PNG)</label>
    <input type="file" name="receipt" accept=".pdf,.jpg,.jpeg,.png" required>

    <div style="height:14px"></div>

    <button>Enviar para aprobación</button>
  </form>
@endsection
