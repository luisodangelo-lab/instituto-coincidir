@extends('layouts.app')

@section('title', 'Primer acceso')
@section('page_title', 'Primer acceso')
@section('page_hint', 'Paso 1 de 3 — Te enviamos un código por WhatsApp.')

@section('content')
  <div style="display:flex; align-items:center; justify-content:space-between; gap:10px; margin-bottom:12px;">
    <div>
      <h2 style="margin:0 0 6px; font-size:18px;">Primer acceso</h2>
      <p class="muted" style="margin:0;">
        Ingresá tu DNI para recibir un código por WhatsApp.
      </p>
    </div>

    <div style="min-width:160px;">
      <div class="muted" style="display:flex; justify-content:space-between; margin-bottom:6px;">
        <span>Paso</span><strong style="color:#0f172a;">1/3</strong>
      </div>
      <div style="height:8px; background:#eef2ff; border-radius:999px; overflow:hidden; border:1px solid #e5e7eb;">
        <div style="height:100%; width:33%; background: linear-gradient(180deg, #2563eb, #1d4ed8);"></div>
      </div>
    </div>
  </div>

  <form method="post" action="{{ route('first_access.send') }}">
    @csrf

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

    <div class="actions">
      <button type="submit" class="btn-primary">Enviar código</button>
      <a href="{{ route('login') }}" class="btn-ghost" style="text-decoration:none; display:inline-flex; align-items:center;">
        Volver a ingresar
      </a>
    </div>

    <p class="muted" style="margin:12px 0 0;">
      Si no tenés acceso a WhatsApp, contactá a administración.
    </p>
  </form>
@endsection
