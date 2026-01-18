@extends('layouts.app')

@section('title', 'Nueva contraseña')

@section('content')
  <h2>Nueva contraseña</h2>
  <p>Elegí una contraseña nueva para tu cuenta.</p>

  <form method="post" action="{{ route('reset.password.post') }}">
    @csrf

    <label>Contraseña</label>
    <input type="password" name="password">

    <div style="height:10px"></div>

    <label>Repetir contraseña</label>
    <input type="password" name="password_confirmation">

    <div style="height:14px"></div>

    <button>Guardar</button>
  </form>
@endsection
