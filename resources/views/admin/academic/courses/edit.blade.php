@extends('layouts.app')
@section('title','Editar curso')
@section('page_title','Editar curso')
@section('page_hint','Actualizá datos, carátula, normativa y contenidos.')

@section('content')
  <div style="display:flex;justify-content:flex-end;margin-bottom:12px;">
    <a class="btn btn-ghost" href="{{ route('admin.academic.courses.index') }}">Volver</a>
  </div>

  <form method="POST" action="{{ route('admin.academic.courses.update', $course) }}" enctype="multipart/form-data">

    @csrf
    @method('PUT')

    @include('admin.academic.courses._form', ['course' => $course])

    <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:12px;">
      <button class="btn btn-primary">Guardar cambios</button>
      <a class="btn btn-ghost" href="{{ route('admin.academic.courses.index') }}">Cancelar</a>
    </div>
  </form>
@endsection
