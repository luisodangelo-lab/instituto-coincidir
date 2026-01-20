@extends('layouts.app')
@section('title','Crear curso')

@section('content')
  <h2>Crear curso</h2>

  <form method="post" action="{{ route('admin.courses.store') }}">
    @csrf

    <label>Código</label>
    <input name="code" value="{{ old('code') }}">

    <div style="height:10px"></div>

    <label>Título</label>
    <input name="title" value="{{ old('title') }}">

    <div style="height:10px"></div>

    <label>Tipo</label>
    <select name="type">
      @foreach(['curso','tecnicatura'] as $t)
        <option value="{{ $t }}" @selected(old('type','curso')===$t)>{{ $t }}</option>
      @endforeach
    </select>

    <div style="height:10px"></div>

    <label>Descripción</label>
    <input name="description" value="{{ old('description') }}">

    <div style="height:10px"></div>

    <label><input type="checkbox" name="is_active" value="1" checked> Activo</label>

    <div style="height:14px"></div>

    <button>Guardar</button>
  </form>
@endsection
