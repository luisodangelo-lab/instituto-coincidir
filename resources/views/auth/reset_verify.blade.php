@extends('layouts.app')

@section('title', 'Verificar código')
@section('page_title', 'Verificar código')
@section('page_hint', 'Paso 2 de 3 — Ingresá el código de 6 dígitos.')

@section('content')
  <div style="display:flex; align-items:center; justify-content:space-between; gap:10px; margin-bottom:12px;">
    <div>
      <h2 style="margin:0 0 6px; font-size:18px;">Verificar código</h2>
      <p class="muted" style="margin:0;">
        Te enviamos un código por WhatsApp.
      </p>
    </div>

    <div style="min-width:160px;">
      <div class="muted" style="display:flex; justify-content:space-between; margin-bottom:6px;">
        <span>Paso</span><strong style="color:#0f172a;">2/3</strong>
      </div>
      <div style="height:8px; background:#eef2ff; border-radius:999px; overflow:hidden; border:1px solid #e5e7eb;">
        <div style="height:100%; width:66%; background: linear-gradient(180deg, #2563eb, #1d4ed8);"></div>
      </div>
    </div>
  </div>

  @if (config('otp.show_dev_code') && session('pr_dev_code'))
    <div class="flash info" style="margin-bottom:12px;">
      <strong>DEV:</strong> código = <span style="font-weight:800; letter-spacing:1px;">{{ session('pr_dev_code') }}</span>
      <div class="muted" style="margin-top:6px;">Solo visible en entorno local.</div>
    </div>
  @endif

  <form method="post" action="{{ route('reset.verify.post') }}">
    @csrf

    <label for="code">Código (6 dígitos)</label>
    <input
      id="code"
      name="code"
      inputmode="numeric"
      autocomplete="one-time-code"
      maxlength="6"
      value="{{ old('code') }}"
      placeholder="Ej: 123456"
      required
    >

    <div class="actions">
      <button type="submit" class="btn-primary">Verificar</button>
      <a href="{{ route('reset.show') }}" class="btn-ghost" style="text-decoration:none; display:inline-flex; align-items:center;">
        Volver
      </a>
    </div>

    <p class="muted" style="margin:12px 0 0;">
      ¿No te llegó? Volvé y solicitá un nuevo código.
    </p>
  </form>
@endsection
