@extends('layouts.app')

@section('title', 'Verificar código')

@section('content')
  <h2>Verificar código</h2>
  <p>Te enviamos un código por WhatsApp. Ingresalo para continuar.</p>

  @if (config('otp.show_dev_code') && session('fa_dev_code'))
    <p style="background:#fff3cd; padding:10px; border-radius:8px;">
      <b>DEV:</b> código = {{ session('fa_dev_code') }}
    </p>
  @endif

  <form method="post" action="{{ route('first_access.verify.post') }}">
    @csrf

    <label>Código (6 dígitos)</label>
    <input name="code" maxlength="6" value="{{ old('code') }}">

    <div style="height:14px"></div>

    <button>Verificar</button>
  </form>
@endsection
