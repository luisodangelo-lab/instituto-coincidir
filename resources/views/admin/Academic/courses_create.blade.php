@extends('layouts.app')

@section('content')
  <h1 style="margin-bottom:12px;">Crear curso</h1>

  @if($errors->any())
    <div style="padding:10px;border:1px solid #ffb6b6;background:#ffecec;margin-bottom:12px;">
      <ul style="margin:0;padding-left:18px;">
        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('admin.academic.courses.store') }}">
    @csrf

    <div style="margin-bottom:10px;">
      <label>Código</label><br>
      <input name="code" value="{{ old('code') }}" style="width:320px;">
    </div>

    <div style="margin-bottom:10px;">
      <label>Título</label><br>
      <input name="title" value="{{ old('title') }}" style="width:520px;">
    </div>

    <div style="margin-bottom:10px;">
      <label>
        <input type="checkbox" name="is_active" value="1" checked>
        Activo
      </label>
    </div>

    <button type="submit">Guardar</button>
  </form>
@endsection
