@extends('layouts.app')
@section('title','Pagos')

@section('content')
  <h2>Pagos ({{ $status }})</h2>

  <p class="muted">
    <a href="{{ route('finance.payments.index', ['status'=>'pending_review']) }}">Pendientes</a> |
    <a href="{{ route('finance.payments.index', ['status'=>'approved']) }}">Aprobados</a> |
    <a href="{{ route('finance.payments.index', ['status'=>'rejected']) }}">Rechazados</a>
  </p>

  <table style="width:100%; border-collapse:collapse;">
    <tr style="text-align:left;">
      <th>Fecha</th><th>Alumno</th><th>Monto</th><th>Método</th><th>Ref</th><th></th>
    </tr>
    @foreach($payments as $p)
      <tr style="border-top:1px solid #eee;">
        <td>{{ $p->created_at }}</td>
        <td>{{ $p->user->dni }} — {{ $p->user->name }}</td>
        <td>${{ number_format($p->amount, 2, ',', '.') }}</td>
        <td>{{ $p->method }}</td>
        <td>{{ $p->reference }}</td>
        <td><a href="{{ route('finance.payments.show', $p) }}">Ver</a></td>
      </tr>
    @endforeach
  </table>

  <div style="margin-top:12px;">{{ $payments->links() }}</div>
@endsection
