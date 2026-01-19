@extends('layouts.app')

@section('title', 'Admin · Crear usuario')
@section('page_title', 'Admin · Crear usuario')
@section('page_hint', 'Alta rápida de usuarios (DNI como identificador).')

@section('content')
  <h2 style="margin:0 0 6px; font-size:18px;">Crear usuario</h2>
  <p class="muted" style="margin:0 0 14px;">
    Completá los datos básicos. El DNI se usa como identificador de acceso.
  </p>

  <form method="post" action="{{ route('admin.users.store') }}">
    @csrf

    <div style="display:grid; gap:12px;">
      <div>
        <label for="name">Nombre y apellido</label>
        <input
          id="name"
          name="name"
          value="{{ old('name') }}"
          placeholder="Ej: Juan Pérez"
          required
        >
      </div>

      <div style="display:grid; gap:12px; grid-template-columns: 1fr 1fr;">
        <div>
          <label for="dni">DNI</label>
          <input
            id="dni"
            name="dni"
            value="{{ old('dni') }}"
            inputmode="numeric"
            autocomplete="off"
            placeholder="Ej: 30123456"
            required
          >
        </div>

        <div>
          <label for="phone_whatsapp">WhatsApp</label>
          <input
            id="phone_whatsapp"
            name="phone_whatsapp"
            value="{{ old('phone_whatsapp') }}"
            placeholder="Ej: 2804514348 o +5492804514348"
          >
          <div class="muted" style="margin-top:6px;">
            Recomendado para OTP y notificaciones.
          </div>
        </div>
      </div>

      <div style="display:grid; gap:12px; grid-template-columns: 1fr 1fr;">
        <div>
          <label for="email">Email (opcional)</label>
          <input
            id="email"
            name="email"
            value="{{ old('email') }}"
            autocomplete="email"
            placeholder="Ej: nombre@dominio.com"
          >
        </div>

        <div>
          <label for="role">Rol</label>
          <select id="role" name="role">
            @foreach (['alumno','docente','administrativo','staff_l1','staff_l2','admin'] as $r)
              <option value="{{ $r }}" @selected(old('role','alumno')===$r)>{{ $r }}</option>
            @endforeach
          </select>
          <div class="muted" style="margin-top:6px;">
            staff_l2: sin finanzas (según criterio del campus).
          </div>
        </div>
      </div>
    </div>

    <div class="actions">
      <button type="submit" class="btn-primary">Crear usuario</button>
      <a href="{{ route('dashboard') }}" class="btn-ghost" style="text-decoration:none; display:inline-flex; align-items:center;">
        Volver al Dashboard
      </a>
    </div>

    <hr>

    <div class="muted">
      Nota: si el usuario no tiene contraseña, deberá hacer <a href="{{ route('first_access.show') }}">Primer acceso</a>.
    </div>
  </form>

  <style>
    /* Solo para que el grid no se rompa en mobile */
    @media (max-width: 720px){
      form div[style*="grid-template-columns"]{ grid-template-columns: 1fr !important; }
    }
  </style>
@endsection
