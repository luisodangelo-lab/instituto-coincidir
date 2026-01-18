@extends('layouts.app')

@section('title', 'Crear contraseña')

@section('content')
  <h2>Crear contraseña</h2>
  <p>Elegí una contraseña para tu cuenta.</p>

  <form method="post" action="{{ route('first_access.password.post') }}">
    @csrf

    <label>Contraseña</label>
    <input type="password" name="password">

    <div style="height:10px"></div>

    <label>Repetir contraseña</label>
    <input type="password" name="password_confirmation">

    <div style="height:14px"></div>

    <button>Guardar y entrar</button>
  </form>
@endsection
