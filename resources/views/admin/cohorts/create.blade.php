@extends('layouts.app')
@section('title','Crear cohorte')

@section('content')
  <h2>Crear cohorte · {{ $course->title }}</h2>
@php
  $coverPath = $course->cover_path
    ?? $course->image_path
    ?? $course->cover_image_path
    ?? null;

  $coverUrl = $coverPath
    ? (str_starts_with($coverPath, 'http') ? $coverPath : \Illuminate\Support\Facades\Storage::url($coverPath))
    : null;
@endphp

<div class="d-flex align-items-start justify-content-between gap-3 mb-3">
  <div>
    <h1 class="mb-1">Crear cohorte</h1>
    <div class="text-muted">
      Curso: <strong>{{ $course->code }}</strong> — {{ $course->title }}
    </div>
  </div>

  <div class="text-end">
    @if($coverUrl)
      <img src="{{ $coverUrl }}" alt="Carátula"
           style="width:140px;height:90px;object-fit:cover;border-radius:12px;border:1px solid rgba(0,0,0,.08);">
    @else
      <div style="width:140px;height:90px;border-radius:12px;border:1px dashed rgba(0,0,0,.25);display:flex;align-items:center;justify-content:center;font-size:12px;color:#6b7280;">
        sin img
      </div>
    @endif
  </div>
</div>

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
