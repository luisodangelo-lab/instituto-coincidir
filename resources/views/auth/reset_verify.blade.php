<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><title>Verificar código</title></head>
<body style="font-family: Arial; max-width: 480px; margin: 40px auto;">
  <h2>Verificar código</h2>
  <p>Te enviamos un código por WhatsApp.</p>

  @if (config('otp.show_dev_code') && session('pr_dev_code'))
    <p style="background:#fff3cd; padding:10px;"><b>DEV:</b> código = {{ session('pr_dev_code') }}</p>
  @endif

  @if ($errors->any())
    <div style="color:#b00020;"><ul>
      @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
    </ul></div>
  @endif

  <form method="post" action="{{ route('reset.verify.post') }}">
    @csrf
    <label>Código (6 dígitos)</label><br>
    <input name="code" maxlength="6" style="width:100%; padding:10px; margin:8px 0;">
    <button style="padding:10px 14px;">Verificar</button>
  </form>
</body>
</html>
