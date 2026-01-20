@extends('layouts.app')
@section('title','Crear cohorte')

@section('content')
  <h2>Crear cohorte · {{ $course->title }}</h2>

  <form method="post" action="{{ route('admin.cohorts.store', $course) }}">
    @csrf

    <label>Nombre</label>
    <input name="name" value="{{ old('name') }}" placeholder="Ej: Marzo 2026">

    <div style="height:10px"></div>

    <label>Inicio</label>
    <input type="date" name="start_date" value="{{ old('start_date') }}">

    <div style="height:10px"></div>

    <label>Fin</label>
    <input type="date" name="end_date" value="{{ old('end_date') }}">

    <div style="height:10px"></div>

    <label>Precio total</label>
    <input name="price_total" value="{{ old('price_total', 0) }}">

    <div style="height:10px"></div>

    <label>Cantidad de cuotas (1..10)</label>
    <input name="installments_count" value="{{ old('installments_count', 1) }}">

    <div style="height:10px"></div>

    <label><input type="checkbox" name="is_active" value="1" checked> Activa</label>

    <div style="height:14px"></div>
    <button>Guardar</button>
  </form>
@endsection
