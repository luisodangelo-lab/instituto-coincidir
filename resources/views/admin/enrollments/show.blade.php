@extends('layouts.app')
@section('title','Matrícula')

@section('content')
  <h2>Matrícula #{{ $enrollment->id }}</h2>

  <p><b>Alumno:</b> {{ $enrollment->user->name }} — DNI {{ $enrollment->user->dni }}</p>
  <p><b>Curso:</b> {{ $enrollment->cohort->course->title }} — <b>Cohorte:</b> {{ $enrollment->cohort->name }}</p>
  <p><b>Estado:</b> {{ $enrollment->status }}</p>

  <hr>

  <h3>Cuotas</h3>
  <table style="width:100%; border-collapse:collapse;">
    <tr style="text-align:left;">
      <th>#</th><th>Vence</th><th>Debe</th><th>Pagado</th><th>Estado</th>
    </tr>
    @foreach($enrollment->installments as $i)
      <tr style="border-top:1px solid #eee;">
        <td>{{ $i->number }}</td>
        <td>{{ $i->due_date }}</td>
        <td>${{ number_format($i->amount_due,2,',','.') }}</td>
        <td>${{ number_format($i->amount_paid,2,',','.') }}</td>
        <td>{{ $i->status }}</td>
      </tr>
    @endforeach
  </table>

  <hr>

  <h3>Pagos vinculados</h3>
  <table style="width:100%; border-collapse:collapse;">
    <tr style="text-align:left;">
      <th>ID</th><th>Fecha</th><th>Monto</th><th>Estado</th><th>Ref</th>
    </tr>
    @foreach($enrollment->payments as $p)
      <tr style="border-top:1px solid #eee;">
        <td>{{ $p->id }}</td>
        <td>{{ $p->created_at }}</td>
        <td>${{ number_format($p->amount,2,',','.') }}</td>
        <td>{{ $p->status }}</td>
        <td>{{ $p->reference }}</td>
      </tr>
    @endforeach
  </table>
@endsection
