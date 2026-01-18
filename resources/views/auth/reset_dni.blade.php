<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><title>Recuperar contraseña</title></head>
<body style="font-family: Arial; max-width: 480px; margin: 40px auto;">
  <h2>Recuperar contraseña</h2>

  @if (session('info'))
    <p style="background:#cff4fc; padding:10px;">{{ session('info') }}</p>
  @endif

  @if ($errors->any())
    <div style="color:#b00020;"><ul>
      @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
    </ul></div>
  @endif

  <form method="post" action="{{ route('reset.send') }}">
    @csrf
    <label>DNI</label><br>
    <input name="dni" value="{{ old('dni') }}" style="width:100%; padding:10px; margin:8px 0;">
    <button style="padding:10px 14px;">Enviar código</button>
  </form>

  <hr>
  <p>¿Es tu primer ingreso? <a href="/first-access">Primer acceso</a></p>
</body>
</html>
