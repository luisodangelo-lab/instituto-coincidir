@extends('layouts.app')
@section('title','Editar curso')

@section('content')
  <h2>Editar curso</h2>

  <form method="post" action="{{ route('admin.courses.update', $course) }}">
    @csrf

    <label>Código</label>
    <input name="code" value="{{ old('code', $course->code) }}">

    <div style="height:10px"></div>

    <label>Título</label>
    <input name="title" value="{{ old('title', $course->title) }}">

    <div style="height:10px"></div>

    <label>Tipo</label>
    <select name="type">
      @foreach(['curso','tecnicatura'] as $t)
        <option value="{{ $t }}" @selected(old('type', $course->type)===$t)>{{ $t }}</option>
      @endforeach
    </select>

    <div style="height:10px"></div>

    <label>Descripción</label>
    <input name="description" value="{{ old('description', $course->description) }}">

    <div style="height:10px"></div>

    <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $course->is_active))> Activo</label>

    <div style="height:14px"></div>

    <button>Guardar cambios</button>
  </form>
@endsection
