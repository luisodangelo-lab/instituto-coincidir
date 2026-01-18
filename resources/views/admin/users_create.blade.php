<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Admin - Crear usuario</title>
</head>
<body style="font-family: Arial; max-width: 720px; margin: 40px auto;">
  <h2>Admin · Crear usuario</h2>

  @if (session('ok'))
    <p style="background:#d1e7dd; padding:10px;">{{ session('ok') }}</p>
  @endif

  @if ($errors->any())
    <div style="color:#b00020;">
      <ul>
        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  <form method="post" action="{{ route('admin.users.store') }}">
    @csrf

    <label>Nombre</label><br>
    <input name="name" value="{{ old('name') }}" style="width:100%; padding:10px; margin:8px 0;">

    <label>DNI</label><br>
    <input name="dni" value="{{ old('dni') }}" style="width:100%; padding:10px; margin:8px 0;">

    <label>WhatsApp</label><br>
    <input name="phone_whatsapp" value="{{ old('phone_whatsapp') }}" placeholder="2804514348 o +5492804514348" style="width:100%; padding:10px; margin:8px 0;">

    <label>Email (opcional)</label><br>
    <input name="email" value="{{ old('email') }}" style="width:100%; padding:10px; margin:8px 0;">

    <label>Rol</label><br>
    <select name="role" style="width:100%; padding:10px; margin:8px 0;">
      @foreach (['alumno','docente','administrativo','staff_l1','staff_l2','admin'] as $r)
        <option value="{{ $r }}" @selected(old('role','alumno')===$r)>{{ $r }}</option>
      @endforeach
    </select>

    <button style="padding:10px 14px;">Crear</button>
  </form>

  <hr>
  <p><a href="/dashboard">Volver al Dashboard</a></p>
</body>
</html>
