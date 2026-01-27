@extends('layouts.app')
@section('title','Crear curso')
@section('page_title','Crear curso')
@section('page_hint','Cargá los datos del curso.')

@section('content')
  <div style="display:flex;justify-content:flex-end;margin-bottom:12px;">
    <a class="btn btn-ghost" href="{{ route('admin.academic.courses.index') }}">Volver</a>
  </div>

  <form method="POST" action="{{ route('admin.academic.courses.update', $course) }}" enctype="multipart/form-data">

    @csrf

    @include('admin.academic.courses._form')

    <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:12px;">
      <button class="btn btn-primary">Guardar</button>
      <a class="btn btn-ghost" href="{{ route('admin.academic.courses.index') }}">Cancelar</a>
    </div>
  </form>
@endsection
