<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Instituto Coincidir')</title>

  <style>
    body { font-family: Arial, sans-serif; margin: 0; background:#f6f7fb; color:#111; }
    header { background:#111; color:#fff; padding: 12px 16px; }
    .brand { font-weight: 700; letter-spacing: .2px; }
    nav a { color:#fff; text-decoration:none; margin-right: 12px; opacity:.95; }
    nav a:hover { text-decoration: underline; opacity:1; }
    .container { max-width: 980px; margin: 18px auto; padding: 0 14px; }
    .card { background:#fff; border-radius: 10px; padding: 16px; box-shadow: 0 2px 10px rgba(0,0,0,.06); }
    .flash { padding: 10px 12px; border-radius: 8px; margin-bottom: 12px; }
    .ok { background:#d1e7dd; }
    .info { background:#cff4fc; }
    .err { background:#f8d7da; }
    footer { color:#666; font-size: 12px; padding: 18px 0; }
    .muted { color:#777; font-size: 12px; }
    button { padding:10px 14px; border-radius:8px; border:0; cursor:pointer; }
    input, select { width:100%; padding:10px; border-radius:8px; border:1px solid #d7d7d7; }
    hr { border:0; border-top:1px solid #eee; margin: 14px 0; }
  </style>
</head>

<body>
<header>
  <div class="container" style="margin:0 auto; display:flex; align-items:center; justify-content:space-between;">
    <div class="brand">Instituto Coincidir</div>

    <nav>
      @auth
        <a href="/dashboard">Dashboard</a>

        @php $r = auth()->user()->role ?? 'alumno'; @endphp

        @if(in_array($r, ['admin','staff_l1','staff_l2','administrativo']))
          <a href="/admin/users/create">Usuarios</a>
        @endif

        @if(in_array($r, ['admin','staff_l1']))
          <a href="/admin/finance">Finanzas</a>
        @endif

        <form method="post" action="{{ route('logout') }}" style="display:inline;">
          @csrf
          <button type="submit" style="background:#444; color:#fff;">Salir</button>
        </form>
      @endauth

      @guest
        <a href="/login">Ingresar</a>
        <a href="/first-access">Primer acceso</a>
        <a href="/reset-password">Recuperar contraseña</a>
      @endguest
    </nav>
  </div>
</header>

<main class="container">
  @if(session('ok'))
    <div class="flash ok">{{ session('ok') }}</div>
  @endif
  @if(session('info'))
    <div class="flash info">{{ session('info') }}</div>
  @endif
  @if(session('error'))
    <div class="flash err">{{ session('error') }}</div>
  @endif

  @if($errors->any())
    <div class="flash err">
      <ul style="margin:0; padding-left:18px;">
        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  <div class="card">
    @yield('content')
  </div>

  <footer>
    <div class="muted">Entorno: {{ app()->environment() }}</div>
  </footer>
</main>

</body>
</html>

