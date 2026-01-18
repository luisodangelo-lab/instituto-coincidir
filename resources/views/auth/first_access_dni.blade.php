<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Primer acceso</title>
</head>
<body style="font-family: Arial; max-width: 480px; margin: 40px auto;">
  <h2>Primer acceso</h2>
  <p>Ingresá tu DNI para recibir un código por WhatsApp.</p>

  @if ($errors->any())
    <div style="color:#b00020;">
      <ul>
        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  <form method="post" action="{{ route('first_access.send') }}">
    @csrf
    <label>DNI</label><br>
    <input name="dni" value="{{ old('dni') }}" style="width:100%; padding:10px; margin:8px 0;">
    <button style="padding:10px 14px;">Enviar código</button>
  </form>
</body>
</html>
