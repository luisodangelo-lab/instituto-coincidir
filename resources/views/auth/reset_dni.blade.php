@extends('layouts.app')

@section('title', 'Recuperar contraseña')

@section('content')
  <h2>Recuperar contraseña</h2>
  <p>Ingresá tu DNI para recibir un código por WhatsApp.</p>

  @if (session('info'))
    <div class="flash info">{{ session('info') }}</div>
  @endif

  <form method="post" action="{{ route('reset.send') }}">
    @csrf

    <label>DNI</label>
    <input name="dni" value="{{ old('dni') }}">

    <div style="height:14px"></div>

    <button>Enviar código</button>
  </form>

  <hr>
  <p>¿Es tu primer ingreso? <a href="/first-access">Primer acceso</a></p>
@endsection
