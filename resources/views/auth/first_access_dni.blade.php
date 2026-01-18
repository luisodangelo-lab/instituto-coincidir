@extends('layouts.app')

@section('title', 'Primer acceso')

@section('content')
  <h2>Primer acceso</h2>
  <p>Ingresá tu DNI para recibir un código por WhatsApp.</p>

  <form method="post" action="{{ route('first_access.send') }}">
    @csrf

    <label>DNI</label>
    <input name="dni" value="{{ old('dni') }}">

    <div style="height:14px"></div>

    <button>Enviar código</button>
  </form>
@endsection
