@extends('layouts.app')
@section('title','Mis cuotas')

@section('content')
  <h2>Mis cuotas</h2>

  <p class="muted">
    Saldo a favor: <b>${{ number_format($u->wallet_balance ?? 0, 2, ',', '.') }}</b>
  </p>

  <p>
    <a href="{{ route('my.payments.new') }}">Cargar pago / comprobante</a>
  </p>

  <hr>

  @forelse($enrollments as $enr)
    <h3 style="margin:0 0 6px;">
      {{ $enr->cohort->course->title }} — {{ $enr->cohort->name }}
    </h3>

    <div class="muted" style="margin-bottom:8px;">
      Estado: {{ $enr->status }}
    </div>

    <table style="width:100%; border-collapse:collapse;">
      <tr style="text-align:left;">
        <th>#</th><th>Vence</th><th>Debe</th><th>Pagado</th><th>Estado</th>
      </tr>

      @foreach($enr->installments as $i)
        <tr style="border-top:1px solid #eee;">
          <td>{{ $i->number }}</td>
          <td>{{ $i->due_date }}</td>
          <td>${{ number_format($i->amount_due, 2, ',', '.') }}</td>
          <td>${{ number_format($i->amount_paid, 2, ',', '.') }}</td>
          <td>{{ $i->status }}</td>
        </tr>
      @endforeach
    </table>

    <hr>
  @empty
    <p>No tenés matrículas aún. Cuando te inscriban a una cohorte aparecerán tus cuotas acá.</p>
  @endforelse
@endsection
