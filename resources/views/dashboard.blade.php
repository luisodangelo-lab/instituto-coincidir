<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Dashboard</title>
</head>
<body style="font-family: Arial; max-width: 720px; margin: 40px auto;">
  <h2>Dashboard</h2>

  @auth
    <p><b>Usuario:</b> {{ auth()->user()->name }}</p>
    <p><b>DNI:</b> {{ auth()->user()->dni }}</p>
    <p><b>Rol:</b> {{ auth()->user()->role }}</p>
    <p><b>Estado:</b> {{ auth()->user()->account_state }}</p>

    <form method="post" action="/logout">
      @csrf
      <button>Salir</button>
    </form>
  @else
    <p>No estás logueado. <a href="/first-access">Primer acceso</a></p>
  @endauth
</body>
</html>
