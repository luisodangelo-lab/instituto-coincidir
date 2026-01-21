@extends('layouts.app')
@section('title','Académico · Matricular')

@section('content')
<h2>Matricular alumno</h2>

@if(session('ok')) <div class="flash ok">{{ session('ok') }}</div> @endif
@if(session('error')) <div class="flash err">{{ session('error') }}</div> @endif

<form method="post" action="{{ route('admin.academic.enrollments.store') }}">
  @csrf

  <label>DNI</label>
  <input name="dni" value="{{ old('dni') }}" placeholder="12345678">

  <div style="height:10px"></div>

  <label>Cohorte</label>
  <select name="cohort_id">
    @foreach($cohorts as $c)
      <option value="{{ $c->id }}" @selected(old('cohort_id')==$c->id)>
        #{{ $c->id }} — {{ $c->name }} ({{ $c->price_total }} / {{ $c->installments_count }} cuotas)
      </option>
    @endforeach
  </select>

  <div style="height:10px"></div>

  <label style="display:flex; gap:8px; align-items:center;">
    <input type="checkbox" name="generate_installments" value="1" checked>
    Generar cuotas automáticamente
  </label>

  <div style="height:14px"></div>
  <button>Crear matrícula</button>
</form>
@endsection
