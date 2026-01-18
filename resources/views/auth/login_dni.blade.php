@extends('layouts.app')

@section('title', 'Ingresar')

@section('content')
  <h2>Ingresar</h2>
  <p>Ingresá con tu DNI y contraseña.</p>

  <form method="post" action="{{ route('login.post') }}">
    @csrf

    <p><a href="/reset-password">Olvidé mi contraseña</a></p>

    <label>DNI</label>
    <input name="dni" value="{{ old('dni') }}">

    <div style="height:10px"></div>

    <label>Contraseña</label>
    <input type="password" name="password">

    <div style="height:14px"></div>

    <button>Entrar</button>
  </form>

  <hr>
  <p>¿Es tu primer ingreso? <a href="/first-access">Hacé primer acceso</a></p>
@endsection
