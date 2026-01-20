@extends('layouts.app')
@section('title','Matricular')

@section('content')
  <h2>Matricular por DNI</h2>

  <form method="post" action="{{ route('admin.enrollments.store') }}">
    @csrf

    <label>DNI del alumno (debe existir)</label>
    <input name="dni" value="{{ old('dni') }}">

    <div style="height:10px"></div>

    <label>Cohorte</label>
    <select name="cohort_id">
      @foreach($cohorts as $c)
        <option value="{{ $c->id }}">
          {{ $c->course->title }} — {{ $c->name }} ({{ $c->installments_count }} cuotas · ${{ number_format($c->price_total,0,',','.') }})
        </option>
      @endforeach
    </select>

    <div style="height:10px"></div>

    <label>Cuotas (opcional, para cursos cortos 1..5)</label>
    <input name="installments_count" value="{{ old('installments_count') }}" placeholder="vacío = usa la cohorte">

    <div style="height:10px"></div>

    <label>Total (opcional)</label>
    <input name="price_total" value="{{ old('price_total') }}" placeholder="vacío = usa la cohorte">

    <div style="height:10px"></div>

    <label>Primera fecha de vencimiento (opcional)</label>
    <input type="date" name="first_due_date" value="{{ old('first_due_date') }}">

    <div style="height:14px"></div>

    <button>Crear matrícula y cuotas</button>
  </form>
@endsection
