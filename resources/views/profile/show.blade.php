@extends('layouts.app')

@section('title', 'Mi perfil')
@section('page_title', 'Mi perfil')
@section('page_hint', 'Actualizá tu foto de perfil (opcional).')

@section('content')
  @php $u = auth()->user(); @endphp

  <h2 style="margin:0 0 6px; font-size:18px;">Datos de cuenta</h2>
  <p class="muted" style="margin:0 0 14px;">
    Usuario: <strong style="color:#0f172a;">{{ $u->name }}</strong> · DNI: <strong style="color:#0f172a;">{{ $u->dni }}</strong>
  </p>

  <div style="display:flex; gap:14px; align-items:center; flex-wrap:wrap; margin-bottom:14px;">
    <div class="avatar" style="width:72px; height:72px; border-color:#e5e7eb; background:#f1f5f9; color:#0f172a;">
   @if(!empty($u->avatar_path) && Storage::disk('public')->exists($u->avatar_path))
  <img src="{{ Storage::disk('public')->url($u->avatar_path) }}" alt="{{ $u->name }}">
@else
@php
  $ini = mb_strtoupper(mb_substr(trim($u->name ?? 'U'), 0, 1));
@endphp
{{ $ini }}

@endif

    </div>

    <div>
      <div style="font-weight:800; margin-bottom:4px;">Foto de perfil</div>
      <div class="muted">Formatos: JPG/PNG/WEBP · Máximo 2MB</div>
    </div>
  </div>

  <form method="post" action="{{ route('profile.avatar') }}" enctype="multipart/form-data">
    @csrf

    <label for="avatar">Subir nueva foto</label>
    <input id="avatar" type="file" name="avatar" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" required>


    <div class="actions">
      <button class="btn-primary" type="submit">Guardar foto</button>

      @if(!empty($u->avatar_path))
        <form method="post" action="{{ route('profile.avatar.remove') }}" style="display:inline; margin:0;">
          @csrf
          <button class="btn-ghost" type="submit">Quitar foto</button>
        </form>
      @endif

      <a class="btn-ghost" href="{{ route('dashboard') }}" style="text-decoration:none;">Volver</a>
    </div>
  </form>
@endsection
