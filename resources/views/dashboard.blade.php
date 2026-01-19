@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_hint', 'Accesos y estado de tu cuenta.')

@section('content')
  @auth
    @php
      $u = auth()->user();
      $r = $u->role ?? 'alumno';
      $isStaff = in_array($r, ['admin','staff_l1','staff_l2','administrativo']);
      $isAdmin = in_array($r, ['admin']);
    @endphp

    <div style="display:flex; gap:12px; flex-wrap:wrap;">
      <div style="flex:1 1 320px; border:1px solid #e5e7eb; border-radius:14px; padding:14px;">
        <div class="muted" style="margin-bottom:6px;">Tu cuenta</div>

        <div style="font-weight:800; font-size:16px; margin-bottom:8px;">
          {{ $u->name }}
        </div>

        <div class="muted" style="display:grid; gap:6px;">
          <div><strong style="color:#0f172a;">DNI:</strong> {{ $u->dni }}</div>
          <div><strong style="color:#0f172a;">Rol:</strong> {{ $r }}</div>
          <div><strong style="color:#0f172a;">Estado:</strong> {{ $u->account_state }}</div>
        </div>

        <div class="actions" style="margin-top:12px;">
          <form method="post" action="{{ route('logout') }}" style="margin:0;">
            @csrf
            <button type="submit" class="btn-ghost">Salir</button>
          </form>
        </div>
      </div>

      <div style="flex:1 1 320px; border:1px solid #e5e7eb; border-radius:14px; padding:14px;">
        <div class="muted" style="margin-bottom:6px;">Accesos rápidos</div>

        <div style="display:grid; gap:10px;">
          @if($isStaff)
            <a href="{{ route('admin.users.create') }}" class="btn-ghost" style="text-decoration:none; display:block;">
              Gestionar usuarios
              <div class="muted" style="margin-top:4px;">Altas y administración básica.</div>
            </a>
          @endif

          @if($isAdmin)
            <a href="/admin/finance" class="btn-ghost" style="text-decoration:none; display:block;">
              Finanzas
              <div class="muted" style="margin-top:4px;">Módulo pendiente / acceso admin.</div>
            </a>
          @endif

          @if(!$isStaff)
            <div class="btn-ghost" style="cursor:default;">
              Mis cursos (próximamente)
              <div class="muted" style="margin-top:4px;">Acá vas a ver cohortes, cuotas y certificados.</div>
            </div>
          @endif
        </div>
      </div>
    </div>

    <hr>

    <div class="muted">
      Consejo: si cambiás de PC (casa/trabajo), recordá hacer <code>git pull</code> antes de comenzar.
    </div>
  @else
    <div class="flash err">
      No estás logueado. <a href="{{ route('first_access.show') }}">Primer acceso</a> o <a href="{{ route('login') }}">Ingresar</a>.
    </div>
  @endauth
@endsection
