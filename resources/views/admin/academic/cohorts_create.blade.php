@extends('layouts.app')

@section('content')
  <h1 style="margin-bottom:12px;">Crear cohorte</h1>
  <div style="margin-bottom:12px;">
    Curso: <b>{{ $course->code }}</b> — {{ $course->title }}
  </div>

  @if($errors->any())
    <div style="padding:10px;border:1px solid #ffb6b6;background:#ffecec;margin-bottom:12px;">
      <ul style="margin:0;padding-left:18px;">
        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('admin.academic.cohorts.store', $course) }}">
    @csrf

    <div style="margin-bottom:10px;">
      <label>Nombre</label><br>
      <input name="name" value="{{ old('name','Cohorte '.date('Y-m')) }}" style="width:420px;">
    </div>

    <div style="margin-bottom:10px;">
      <label>Inicio</label><br>
      <input type="date" name="start_date" value="{{ old('start_date') }}">
    </div>

    <div style="margin-bottom:10px;">
      <label>Fin</label><br>
      <input type="date" name="end_date" value="{{ old('end_date') }}">
    </div>

    <hr>

    <div style="margin-bottom:10px;">
      <label>Total a cobrar</label><br>
      <input name="price_total" value="{{ old('price_total', 50000) }}">
    </div>

    <div style="margin-bottom:10px;">
      <label>Cantidad de cuotas</label><br>
      <input name="installments_count" value="{{ old('installments_count', 5) }}">
    </div>

    <div style="margin-bottom:10px;">
      <label>Día de vencimiento</label><br>
      <input name="installment_due_day" value="{{ old('installment_due_day', 10) }}">
    </div>

    <div style="margin-bottom:10px;">
      <label>
        <input type="checkbox" name="enrollment_open" value="1" checked>
        Inscripción abierta
      </label>
    </div>

    <div style="margin-bottom:10px;">
      <label>Cupo máximo (opcional)</label><br>
      <input name="max_seats" value="{{ old('max_seats') }}">
    </div>

    <button type="submit">Guardar cohorte</button>
  </form>
@endsection
