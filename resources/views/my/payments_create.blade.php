@extends('layouts.app')
@section('title','Cargar pago')

@section('content')
  <style>
    .card-soft { border:1px solid rgba(148,163,184,.35); border-radius:16px; padding:14px 16px; background: rgba(148,163,184,.10); }
    .muted { opacity:.75; }
    label { display:block; font-weight:600; margin:10px 0 6px; }
    input, select { width:100%; max-width:520px; padding:10px 12px; border:1px solid #ddd; border-radius:12px; }
    .btn-primary { display:inline-block; padding:10px 14px; border-radius:12px; border:1px solid rgba(17,24,39,.25); background: rgba(17,24,39,.95); color:#fff; cursor:pointer; }
    .btn-primary:hover { filter: brightness(1.05); }
    .row { max-width: 720px; }
    .err { color:#b91c1c; font-size:13px; margin-top:6px; }
  </style>

  <div class="row">
    <h2 style="margin-bottom:6px;">Cargar pago / comprobante</h2>
    <p class="muted" style="margin-top:0;">Subí PDF/JPG/PNG. Queda pendiente de aprobación.</p>

    @php
      $prefEnrollment = old('enrollment_id', $prefill['enrollment_id'] ?? '');
      $prefAmount     = old('amount', $prefill['amount'] ?? '');
      $prefReference  = old('reference', $prefill['reference'] ?? '');

      // Sugerencia simple de método cuando viene referencia
      $suggestMethod = old('method');
      if (!$suggestMethod) {
        $refLower = mb_strtolower((string)$prefReference);
        if (str_contains($refLower, 'mp') || str_contains($refLower, 'mercado')) $suggestMethod = 'mercadopago';
        else $suggestMethod = 'transferencia';
      }
    @endphp

    @if(!empty($prefAmount) || !empty($prefReference))
      <div class="card-soft" style="margin:12px 0 14px;">
        <div style="font-weight:700; margin-bottom:4px;">Pago sugerido</div>
        @if(!empty($prefAmount))
          <div class="muted">Monto: <b>${{ number_format((float)$prefAmount, 2, ',', '.') }}</b></div>
        @endif
        @if(!empty($prefReference))
          <div class="muted">Referencia: <b>{{ $prefReference }}</b></div>
        @endif
      </div>
    @endif

    <form method="post" action="{{ route('my.payments.store') }}" enctype="multipart/form-data">
      @csrf

      <label>Curso / matrícula (opcional)</label>
      <select name="enrollment_id">
        <option value="">(sin seleccionar)</option>
        @foreach($enrollments as $enr)
          <option value="{{ $enr->id }}" @selected((string)$prefEnrollment === (string)$enr->id)>
            {{ $enr->cohort->course->title }} — {{ $enr->cohort->name }}
          </option>
        @endforeach
      </select>
      @error('enrollment_id') <div class="err">{{ $message }}</div> @enderror

      <label>Monto</label>
      <input name="amount" value="{{ $prefAmount }}" inputmode="decimal">
      @error('amount') <div class="err">{{ $message }}</div> @enderror

      <label>Método</label>
      <select name="method">
        @foreach(['transferencia','mercadopago','efectivo','otro'] as $m)
          <option value="{{ $m }}" @selected($suggestMethod === $m)>{{ $m }}</option>
        @endforeach
      </select>
      @error('method') <div class="err">{{ $message }}</div> @enderror

      <label>Referencia (alias/ID MP/observación)</label>
      <input name="reference" value="{{ $prefReference }}">
      @error('reference') <div class="err">{{ $message }}</div> @enderror

      <label>Fecha de pago (opcional)</label>
      <input type="date" name="paid_at" value="{{ old('paid_at') }}">
      @error('paid_at') <div class="err">{{ $message }}</div> @enderror

      <label>Comprobante (PDF/JPG/PNG)</label>
      <input type="file" name="receipt" accept=".pdf,.jpg,.jpeg,.png" required>
      @error('receipt') <div class="err">{{ $message }}</div> @enderror

      <div style="height:14px"></div>

      <button class="btn-primary" type="submit">Enviar para aprobación</button>
    </form>
  </div>
@endsection
