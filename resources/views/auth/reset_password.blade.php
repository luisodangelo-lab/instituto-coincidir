@extends('layouts.app')

@section('title', 'Nueva contraseña')
@section('page_title', 'Nueva contraseña')
@section('page_hint', 'Paso 3 de 3 — Definí tu nueva contraseña.')

@section('content')
  <div style="display:flex; align-items:center; justify-content:space-between; gap:10px; margin-bottom:12px;">
    <div>
      <h2 style="margin:0 0 6px; font-size:18px;">Nueva contraseña</h2>
      <p class="muted" style="margin:0;">
        Elegí una contraseña nueva para tu cuenta.
      </p>
    </div>

    <div style="min-width:160px;">
      <div class="muted" style="display:flex; justify-content:space-between; margin-bottom:6px;">
        <span>Paso</span><strong style="color:#0f172a;">3/3</strong>
      </div>
      <div style="height:8px; background:#eef2ff; border-radius:999px; overflow:hidden; border:1px solid #e5e7eb;">
        <div style="height:100%; width:100%; background: linear-gradient(180deg, #2563eb, #1d4ed8);"></div>
      </div>
    </div>
  </div>

  <form method="post" action="{{ route('reset.password.post') }}">
    @csrf

    <label for="password">Contraseña nueva</label>
    <input
      id="password"
      type="password"
      name="password"
      autocomplete="new-password"
      placeholder="Mínimo 8 caracteres (recomendado)"
      required
    >
    <div class="muted" style="margin-top:6px;">
      Sugerencia: combiná letras y números.
    </div>

    <label for="password_confirmation" style="margin-top:12px;">Repetir contraseña</label>
    <input
      id="password_confirmation"
      type="password"
      name="password_confirmation"
      autocomplete="new-password"
      placeholder="Repetí la contraseña"
      required
    >

    <div class="actions">
      <button type="submit" class="btn-primary">Guardar</button>
      <a href="{{ route('login') }}" class="btn-ghost" style="text-decoration:none; display:inline-flex; align-items:center;">
        Volver a ingresar
      </a>
    </div>
  </form>
@endsection