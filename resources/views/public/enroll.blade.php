@extends('layouts.app')

@section('content')
<style>
  .wrap { max-width: 720px; margin: 0 auto; padding: 22px 16px; }
  .card { border: 1px solid rgba(148,163,184,.35); border-radius: 16px; padding: 18px; background: rgba(148,163,184,.10); }
  .muted { opacity: .8; }
  label { display:block; font-weight: 700; margin: 12px 0 6px; }
  input { width:100%; max-width: 520px; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 12px; background: #fff; }
  .row { display:flex; gap: 12px; flex-wrap: wrap; }
  .col { flex: 1 1 220px; }
  .btn { display:inline-block; padding: 11px 14px; border-radius: 12px; border: 1px solid rgba(0,0,0,.12); text-decoration:none; font-weight: 800; cursor:pointer; }
  .btn-primary { background: #111827; color: #fff; }
  .btn-outline { background: transparent; color: #111827; }
  .alert { padding: 12px 14px; border-radius: 12px; margin: 0 0 12px; }
  .ok { background: #dcfce7; }
  .err { background: #fee2e2; }
  small { display:block; margin-top: 6px; opacity: .75; }
</style>

<div class="wrap">
  <div class="card">
    <div class="row" style="align-items:flex-start; justify-content:space-between;">
      <div>
        <h1 style="margin:0 0 6px; font-size:22px;">Inscripción</h1>
        <div class="muted">
          Estás por completar una <b>preinscripción</b> para:
        </div>
        <div style="margin-top:6px; font-size:16px;">
          <b>{{ $course->title }}</b>
        </div>

        <div class="muted" style="margin-top:10px;">
          Al enviar este formulario, tu inscripción queda en estado <b>preinscripto</b>.
          Luego el equipo te envía las instrucciones de pago; cuando se confirme, pasás a <b>inscripto</b>
          y recién ahí se generan tus cuotas.
        </div>
      </div>

      <div>
        <a class="btn btn-outline" href="{{ route('catalog.index') }}">Volver a cursos</a>
      </div>
    </div>

    @if(session('ok'))
      <div class="alert ok">{{ session('ok') }}</div>
    @endif

    {{-- Opcional: si el controller guarda un link para cargar comprobante --}}
    @if(session('receipt_url'))
      <div class="alert ok">
        Si ya tenés el comprobante, podés cargarlo acá:
        <div style="margin-top:8px;">
          <a class="btn btn-primary" href="{{ session('receipt_url') }}">Cargar comprobante</a>
        </div>
      </div>
    @endif

    @if($errors->any())
      <div class="alert err">
        <b>Revisá estos campos:</b>
        <ul style="margin:8px 0 0 18px;">
          @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('public.enroll.store', $course) }}">
      @csrf

      <label>Nombre y apellido</label>
      <input name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Ej: Juan Pérez">

      <div class="row">
        <div class="col">
          <label>DNI</label>
          <input name="dni"
                 value="{{ old('dni') }}"
                 required
                 inputmode="numeric"
                 autocomplete="off"
                 maxlength="10"
                 placeholder="Ej: 12345678">
          <small>Tu DNI será tu identificador de acceso.</small>
        </div>

        <div class="col">
          <label>WhatsApp</label>
          <input name="phone_whatsapp"
                 value="{{ old('phone_whatsapp') }}"
                 required
                 autocomplete="tel"
                 placeholder="Ej: +54 9 280 451-4348">
          <small>Te contactamos por este número para enviarte instrucciones de pago.</small>
        </div>
      </div>

      <label>Email</label>
      <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Ej: nombre@correo.com">

      <div style="margin-top:16px; display:flex; gap:10px; flex-wrap:wrap;">
        <button class="btn btn-primary" type="submit">Enviar preinscripción</button>
        <a class="btn btn-outline" href="{{ route('catalog.show', $course->code) }}">Ver detalles del curso</a>
      </div>
    </form>
  </div>
</div>
@endsection
