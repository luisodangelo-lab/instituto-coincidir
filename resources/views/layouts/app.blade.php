<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Instituto Coincidir')</title>

  <style>
    :root{
      --bg:#f6f7fb;
      --card:#ffffff;
      --text:#0f172a;
      --muted:#64748b;
      --border:#e5e7eb;
      --brand:#0b1220;
      --brand2:#111827;
      --primary:#2563eb;
      --primary2:#1d4ed8;

      --ok-bg:#ecfdf5; --ok-bd:#a7f3d0; --ok-tx:#065f46;
      --info-bg:#eff6ff; --info-bd:#bfdbfe; --info-tx:#1e40af;
      --err-bg:#fef2f2; --err-bd:#fecaca; --err-tx:#991b1b;
    }

    *{ box-sizing:border-box; }
    body{
      margin:0;
      font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, "Noto Sans", "Helvetica Neue", sans-serif;
      background: radial-gradient(900px 300px at 20% 0%, rgba(37,99,235,.10), transparent 60%),
                  radial-gradient(700px 240px at 80% 10%, rgba(14,165,233,.10), transparent 55%),
                  var(--bg);
      color:var(--text);
      line-height:1.45;
    }

    a{ color:inherit; }

    /* Header */
    header{
      position: sticky;
      top:0;
      z-index: 50;
      background: rgba(11,18,32,.92);
      backdrop-filter: blur(10px);
      border-bottom: 1px solid rgba(255,255,255,.08);
      color:#fff;
    }
    .topbar{
      max-width: 1040px;
      margin:0 auto;
      padding: 12px 16px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
    }
    .brand{
      display:flex;
      align-items:center;
      gap:10px;
      font-weight:800;
      letter-spacing:.2px;
      white-space:nowrap;
    }
    .brand-badge{
      width:34px; height:34px;
      border-radius:10px;
      background: linear-gradient(135deg, rgba(37,99,235,.95), rgba(14,165,233,.90));
      box-shadow: 0 8px 24px rgba(37,99,235,.25);
      display:inline-flex;
      align-items:center;
      justify-content:center;
      font-weight:900;
    }
    .brand small{
      display:block;
      font-weight:600;
      opacity:.75;
      font-size:12px;
      margin-top:-2px;
    }

    nav{
      display:flex;
      align-items:center;
      gap:10px;
      flex-wrap:wrap;
      justify-content:flex-end;
    }
    nav a{
      text-decoration:none;
      opacity:.92;
      padding:8px 10px;
      border-radius:10px;
    }
    nav a:hover{
      opacity:1;
      background: rgba(255,255,255,.10);
    }

    nav a.active{
    background: rgba(255,255,255,.12);
    opacity: 1;
    }

.nav-user{
  display:flex;
  align-items:center;
  gap:10px;
  margin-left:8px;
}

.avatar{
  width:34px;
  height:34px;
  border-radius:999px;
  overflow:hidden;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  border:1px solid rgba(255,255,255,.18);
  background: rgba(255,255,255,.10);
  color:#fff;
  font-weight:800;
  font-size:12px;
  letter-spacing:.6px;
  text-transform:uppercase;
}

.avatar img{
  width:100%;
  height:100%;
  object-fit:cover;
  display:block;
}

.nav-username{
  font-weight:700;
  font-size:13px;
  color:#fff;
  opacity:.92;
  max-width:180px;
  white-space:nowrap;
  overflow:hidden;
  text-overflow:ellipsis;
}

@media (max-width: 640px){
  .nav-username{ display:none; } /* en móvil dejamos solo el círculo */
}


    /* Layout */
    .container{
      max-width: 1040px;
      margin: 18px auto;
      padding: 0 16px;
    }
    .page-head{
      margin: 10px 0 14px;
      display:flex;
      align-items:flex-end;
      justify-content:space-between;
      gap:12px;
    }
    .page-head h1{
      font-size: 18px;
      margin:0;
      letter-spacing:.1px;
    }
    .page-head .hint{
      color:var(--muted);
      font-size: 13px;
    }

    .card{
      background:var(--card);
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 18px;
      box-shadow: 0 10px 28px rgba(2,8,23,.06);
    }

    /* Flash + errors */
    .flash{
      padding: 10px 12px;
      border-radius: 12px;
      margin-bottom: 12px;
      border: 1px solid transparent;
      font-size: 14px;
    }
    .ok{ background:var(--ok-bg); border-color:var(--ok-bd); color:var(--ok-tx); }
    .info{ background:var(--info-bg); border-color:var(--info-bd); color:var(--info-tx); }
    .err{ background:var(--err-bg); border-color:var(--err-bd); color:var(--err-tx); }

    /* Forms */
    label{ display:block; font-weight:600; margin: 10px 0 6px; font-size: 13px; color:#0f172a; }
    input, select{
      width:100%;
      padding: 11px 12px;
      border-radius: 12px;
      border: 1px solid var(--border);
      background:#fff;
      outline:none;
      transition: border-color .15s, box-shadow .15s;
    }
    input:focus, select:focus{
      border-color: rgba(37,99,235,.55);
      box-shadow: 0 0 0 4px rgba(37,99,235,.15);
    }
    .muted{ color:var(--muted); font-size: 12px; }

    .actions{
      display:flex;
      gap:10px;
      flex-wrap:wrap;
      align-items:center;
      margin-top: 14px;
    }

    button{
      appearance:none;
      border:0;
      cursor:pointer;
      padding: 10px 14px;
      border-radius: 12px;
      font-weight:700;
    }
    .btn-primary{
      background: linear-gradient(180deg, var(--primary), var(--primary2));
      color:#fff;
      box-shadow: 0 10px 24px rgba(37,99,235,.22);
    }
    .btn-primary:hover{ filter: brightness(1.03); }
    .btn-ghost{
      background: #f1f5f9;
      color:#0f172a;
      border: 1px solid var(--border);
    }
    .btn-ghost:hover{ background:#eaf0f7; }

    hr{ border:0; border-top:1px solid var(--border); margin: 14px 0; }

    footer{ color:var(--muted); font-size: 12px; padding: 18px 0; }


  @media (max-width: 640px){
  nav{ justify-content:flex-start; }
  nav a{ padding:6px 8px; }
  }

    /* Small screens */
    @media (max-width: 560px){
      .brand span{ display:none; } /* deja solo el badge + nombre corto */
      .page-head{ flex-direction:column; align-items:flex-start; }
      .card{ padding: 14px; border-radius: 14px; }
    }
  </style>
</head>

<body>
<header>
  <div class="topbar">
    <div class="brand">
      <div class="brand-badge" style="background: transparent; box-shadow:none;">
  <img src="{{ asset('assets/logoi.png') }}"
       alt="Instituto Coincidir"
       style="height:32px; width:auto; display:block;">
</div>

      <div>
        <div>Instituto Coincidir</div>
        <small>Campus & Gestión Académica</small>
      </div>
    </div>

    <nav>
      
      @auth
  <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>

  @php
    $u = auth()->user();
    $r = $u->role ?? 'alumno';
    $name = trim($u->name ?? '');
    $parts = preg_split('/\s+/', $name);
    $initials = '';
    if (!empty($parts[0])) $initials .= mb_substr($parts[0], 0, 1);
    if (!empty($parts[1])) $initials .= mb_substr($parts[1], 0, 1);
    $initials = $initials ?: 'IC';
  @endphp

  @if(in_array($r, ['admin','staff_l1','staff_l2','administrativo']))
    <a href="{{ route('admin.users.create') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Usuarios</a>
  @endif

  @if(in_array($r, ['admin','staff_l1']))
    <a href="/admin/finance">Finanzas</a>
  @endif

  <span class="nav-user">
    <span class="avatar" title="{{ $u->name }}">
      @if(!empty($u->avatar_path))
        <img src="{{ asset('storage/'.$u->avatar_path) }}" alt="{{ $u->name }}">
      @else
        {{ $initials }}
      @endif
    </span>
    <span class="nav-username">{{ $u->name }}</span>

    <form method="post" action="{{ route('logout') }}" style="display:inline; margin:0;">
      @csrf
      <button type="submit" class="btn-ghost" style="background: rgba(255,255,255,.12); color:#fff; border-color: rgba(255,255,255,.18);">
        Salir
      </button>
    </form>
  </span>
@endauth


      @guest
      <a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'active' : '' }}">Ingresar</a>
      <a href="{{ route('first_access.show') }}" class="{{ request()->routeIs('first_access.*') ? 'active' : '' }}">Primer acceso</a>
      <a href="{{ route('reset.show') }}" class="{{ request()->routeIs('reset.*') ? 'active' : '' }}">Recuperar</a>

      @endguest
    </nav>
  </div>
</header>

<main class="container">

  <div class="page-head">
    <div>
      <h1>@yield('page_title', 'Bienvenido')</h1>
      <div class="hint">@yield('page_hint', 'Accedé con DNI para ingresar al campus.')</div>
    </div>

    @if(app()->environment('local'))
    <div class="hint" style="opacity:.75;">Entorno: local</div>
    @endif

  </div>

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
      <strong>Revisá lo siguiente:</strong>
      <ul style="margin:8px 0 0; padding-left:18px;">
        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  <div class="card">
    @yield('content')
  </div>

  <footer>
    <div class="muted">© {{ date('Y') }} Fundación Coincidir</div>
  </footer>
</main>

</body>
</html>
