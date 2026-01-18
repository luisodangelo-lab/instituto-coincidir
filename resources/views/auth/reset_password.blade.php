<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><title>Nueva contraseña</title></head>
<body style="font-family: Arial; max-width: 480px; margin: 40px auto;">
  <h2>Nueva contraseña</h2>

  @if ($errors->any())
    <div style="color:#b00020;"><ul>
      @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
    </ul></div>
  @endif

  <form method="post" action="{{ route('reset.password.post') }}">
    @csrf
    <label>Contraseña</label><br>
    <input type="password" name="password" style="width:100%; padding:10px; margin:8px 0;">

    <label>Repetir contraseña</label><br>
    <input type="password" name="password_confirmation" style="width:100%; padding:10px; margin:8px 0;">

    <button style="padding:10px 14px;">Guardar</button>
  </form>
</body>
</html>
