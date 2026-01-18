<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Ingresar</title>
</head>
<body style="font-family: Arial; max-width: 480px; margin: 40px auto;">
  <h2>Ingresar</h2>
  <p>Ingresá con tu DNI y contraseña.</p>

  @if ($errors->any())
    <div style="color:#b00020;">
      <ul>
        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  <form method="post" action="{{ route('login.post') }}">
    @csrf

    <label>DNI</label><br>
    <input name="dni" value="{{ old('dni') }}" style="width:100%; padding:10px; margin:8px 0;">

    <label>Contraseña</label><br>
    <input type="password" name="password" style="width:100%; padding:10px; margin:8px 0;">

    <button style="padding:10px 14px;">Entrar</button>
  </form>

  <hr>
  <p>¿Es tu primer ingreso? <a href="/first-access">Hacé primer acceso</a></p>
</body>
</html>
