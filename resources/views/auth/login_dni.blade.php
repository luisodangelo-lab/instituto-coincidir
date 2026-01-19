@extends('layouts.app')

@section('title', 'Ingresar')
@section('page_title', 'Ingresar al Campus')
@section('page_hint', 'Accedé con tu DNI y contraseña.')

@section('content')
  <h2 style="margin:0 0 6px; font-size:18px;">Ingresar</h2>
  <p class="muted" style="margin:0 0 14px;">
    Ingresá con tu DNI y contraseña.
  </p>

  <form method="post" action="{{ route('login.post') }}">
    @csrf

    <div style="display:flex; justify-content:flex-end; margin-bottom:8px;">
      <a href="{{ route('reset.show') }}" class="muted" style="text-decoration:none;">
        ¿Olvidaste tu contraseña?
      </a>
    </div>

    <label for="dni">DNI</label>
    <input
      id="dni"
      name="dni"
      inputmode="numeric"
      autocomplete="username"
      value="{{ old('dni') }}"
      placeholder="Ej: 30123456"
      required
    >

    <label for="password">Contraseña</label>
    <input
      id="password"
      type="password"
      name="password"
      autocomplete="current-password"
      placeholder="Tu contraseña"
      required
    >

    <div class="actions">
      <button type="submit" class="btn-primary">Entrar</button>
      <a href="{{ route('first_access.show') }}" class="btn-ghost" style="text-decoration:none; display:inline-flex; align-items:center;">
        Primer acceso
      </a>
    </div>
  </form>

  <hr>

  <p class="muted" style="margin:0;">
    ¿Es tu primer ingreso? <a href="{{ route('first_access.show') }}">Hacé primer acceso</a>
  </p>
@endsection
