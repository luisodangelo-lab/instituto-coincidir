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

      /* Marca / Navbar */
      --nav:#0b1f3a;
      --nav2:#08172c;
      --navLine: rgba(255,255,255,.10);
      --navHover: rgba(255,255,255,.10);

      --primary:#2563eb;
      --primary2:#1d4ed8;

      --danger:#ef4444;
      --danger2:#dc2626;

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

    /* Focus visible (teclado) */
    :focus-visible{
      outline: 3px solid rgba(37,99,235,.35);
      outline-offset: 2px;
      border-radius: 10px;
    }

    /* ===== Botones globales (para TODAS las vistas) ===== */
    .btn{
      appearance:none;
      border:1px solid transparent;
      cursor:pointer;
      padding: 10px 14px;
      border-radius: 12px;
      font-weight:800;
      text-decoration:none;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:8px;
      transition: transform .06s, filter .15s, background .15s, border-color .15s, box-shadow .15s;
      user-select:none;
      line-height:1;
    }
    .btn:active{ transform: translateY(1px); }

    .btn-sm{ padding: 8px 10px; border-radius:10px; font-size:13px; }

    .btn-primary{
      background: linear-gradient(180deg, var(--primary), var(--primary2));
      color:#fff;
      box-shadow: 0 10px 22px rgba(37,99,235,.18);
    }
    .btn-primary:hover{ filter: brightness(1.03); }

    .btn-soft{
      background: rgba(37,99,235,.10);
      border-color: rgba(37,99,235,.22);
      color:#0f172a;
    }
    .btn-soft:hover{ background: rgba(37,99,235,.14); }

    .btn-ghost{
      background:#f1f5f9;
      border-color: rgba(148,163,184,.35);
      color:#0f172a;
    }
    .btn-ghost:hover{ background:#eaf0f7; }

    .btn-danger{
      background: rgba(239,68,68,.14);
      border-color: rgba(239,68,68,.25);
      color:#fff;
      box-shadow: 0 10px 22px rgba(239,68,68,.12);
    }
    .btn-danger:hover{ filter: brightness(1.05); }

    /* ===== Header (compacto) ===== */
    header{
      position: sticky;
      top:0;
      z-index: 50;
      background: rgba(11,31,58,.92);
      backdrop-filter: blur(10px);
      border-bottom: 1px solid var(--navLine);
      color:#fff;
    }

    .topbar{
      max-width: 1040px;
      margin:0 auto;
      padding: 8px 14px;
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
      min-width: 220px;
    }

    .brand-badge{
      width:30px; height:30px;
      border-radius:10px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      overflow:hidden;
    }

    .brand h2{
      margin:0;
      font-size:14px;
      line-height:1.15;
      font-weight:800;
    }
    .brand small{
      display:block;
      font-weight:600;
      opacity:.78;
      font-size:11px;
      margin-top:1px;
    }

    /* NAV */
    nav{
      display:flex;
      align-items:center;
      gap:8px;
      flex-wrap:wrap;
      justify-content:flex-end;
    }

    .nav-link, .nav-btn{
      text-decoration:none;
      opacity:.92;
      padding:7px 10px;
      border-radius:10px;
      font-size:13px;
      line-height:1;
      border:1px solid transparent;
      background: transparent;
      color:#fff;
      display:inline-flex;
      align-items:center;
      gap:8px;
      cursor:pointer;
      user-select:none;
    }

    .nav-link:hover, .nav-btn:hover{
      opacity:1;
      background: var(--navHover);
      border-color: rgba(255,255,255,.10);
    }

    .nav-link.active, .nav-btn.active{
      background: rgba(255,255,255,.14);
      opacity: 1;
      border-color: rgba(255,255,255,.12);
    }

    /* Dropdown */
    .dd{ position:relative; display:inline-flex; align-items:center; }
    .dd-menu{
      position:absolute;
      right:0;
      top: calc(100% + 8px);
      min-width: 220px;
      background: rgba(8,23,44,.98);
      border: 1px solid rgba(255,255,255,.12);
      border-radius: 14px;
      padding: 6px;
      box-shadow: 0 18px 46px rgba(0,0,0,.35);
      display:none;
    }
    .dd.open .dd-menu{ display:block; }

    .dd-item{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:10px;
      padding:10px 10px;
      border-radius: 12px;
      text-decoration:none;
      color:#fff;
      font-size:13px;
      opacity:.92;
    }
    .dd-item:hover{ background: rgba(255,255,255,.10); opacity:1; }

    .dd-item-btn{
      width:100%;
      text-align:left;
      background:transparent;
      border:0;
      cursor:pointer;
    }

    .dd-sep{ height:1px; background: rgba(255,255,255,.10); margin: 6px 6px; }
    .dd-hint{ font-size:11px; opacity:.70; padding: 6px 10px 4px; }

    .dd-danger:hover{
      background: rgba(239,68,68,.18);
    }

    /* User chip */
    .nav-user{
      display:flex;
      align-items:center;
      gap:10px;
      margin-left:6px;
      padding-left:6px;
      border-left: 1px solid rgba(255,255,255,.10);
    }

    .avatar{
      width:32px;
      height:32px;
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
    .avatar img{ width:100%; height:100%; object-fit:cover; display:block; }

    .nav-username{
      font-weight:700;
      font-size:13px;
      color:#fff;
      opacity:.92;
      max-width:160px;
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
    }

    /* Layout */
    .container{
      max-width: 1040px;
      margin: 16px auto;
      padding: 0 16px;
    }
    .page-head{
      margin: 10px 0 14px;
      display:flex;
      align-items:flex-end;
      justify-content:space-between;
      gap:12px;
    }
    .page-head h1{ font-size: 18px; margin:0; letter-spacing:.1px; }
    .page-head .hint{ color:var(--muted); font-size: 13px; }

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

    hr{ border:0; border-top:1px solid var(--border); margin: 14px 0; }
    footer{ color:var(--muted); font-size: 12px; padding: 18px 0; }

    /* Responsive */
    @media (max-width: 820px){
      .brand small{ display:none; }
    }
    @media (max-width: 640px){
      .nav-username{ display:none; }
      nav{ justify-content:flex-start; }
      .topbar{ padding: 8px 12px; }
      .brand{ min-width:auto; }
    }
    @media (max-width: 560px){
      .page-head{ flex-direction:column; align-items:flex-start; }
      .card{ padding: 14px; border-radius: 14px; }
    }
  </style>

  @stack('head')
</head>

<body>

<header>
  <div class="topbar">
    <div class="brand">
      <div class="brand-badge" style="background: transparent;">
        <img src="{{ asset('assets/logoi.png') }}"
             alt="Instituto Coincidir"
             style="height:28px; width:auto; display:block;">
      </div>
      <div>
        <h2>Instituto Coincidir</h2>
        <small>Campus & Gestión Académica</small>
      </div>
    </div>

    <nav>
      @auth
        @php
          $u = auth()->user();
          $r = $u->role ?? 'alumno';

          $name = trim($u->name ?? '');
          $parts = preg_split('/\s+/', $name);
          $initials = '';
          if (!empty($parts[0])) $initials .= mb_substr($parts[0], 0, 1);
          if (!empty($parts[1])) $initials .= mb_substr($parts[1], 0, 1);
          $initials = $initials ?: 'IC';

          // Permisos por rol + existencia de rutas
          $canAcademic = in_array($r, ['admin','staff_l1','staff_l2','administrativo','docente'], true)
            && \Illuminate\Support\Facades\Route::has('admin.academic.home');

          $canUsers = in_array($r, ['admin','staff_l1','staff_l2','administrativo'], true)
            && \Illuminate\Support\Facades\Route::has('admin.users.create');

          $canFinance = in_array($r, ['admin','staff_l1','administrativo'], true)
            && \Illuminate\Support\Facades\Route::has('finance.payments.index');

          $hasMgmt = ($canAcademic || $canUsers || $canFinance);
        @endphp

        {{-- Accesos principales --}}
        <a href="{{ route('dashboard') }}"
           class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
          Dashboard
        </a>

        <a href="{{ route('my.installments') }}"
           class="nav-link {{ request()->routeIs('my.installments') ? 'active' : '' }}">
          Mis cuotas
        </a>

        {{-- Gestión (solo si corresponde) --}}
        @if($hasMgmt)
          <div class="dd" data-dd>
            <button type="button"
                    class="nav-btn {{ request()->routeIs('admin.academic.*') || request()->routeIs('admin.users.*') || request()->routeIs('finance.*') ? 'active' : '' }}"
                    data-dd-btn>
              Gestión <span style="opacity:.8;">▾</span>
            </button>

            <div class="dd-menu" data-dd-menu>
              <div class="dd-hint">Accesos según tu rol</div>

              @if($canAcademic)
                <a class="dd-item" href="{{ route('admin.academic.home') }}">
                  <span>🎓 Académico</span><span style="opacity:.65;">→</span>
                </a>
              @endif

              @if($canUsers)
                <a class="dd-item" href="{{ route('admin.users.create') }}">
                  <span>👥 Usuarios</span><span style="opacity:.65;">→</span>
                </a>
              @endif

              @if($canFinance)
                <a class="dd-item" href="{{ route('finance.payments.index') }}">
                  <span>💳 Finanzas</span><span style="opacity:.65;">→</span>
                </a>
              @endif
            </div>
          </div>
        @endif

        {{-- Dropdown Mi cuenta --}}
        <div class="dd nav-user" data-dd>
          <span class="avatar" title="{{ $u->name }}">
            @if(!empty($u->avatar_path) && \Illuminate\Support\Facades\Storage::disk('public')->exists($u->avatar_path))
              <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($u->avatar_path) }}" alt="{{ $u->name }}">
            @else
              {{ $initials }}
            @endif
          </span>

          <span class="nav-username">{{ $u->name }}</span>

          <button type="button" class="nav-btn" data-dd-btn>
            Mi cuenta <span style="opacity:.8;">▾</span>
          </button>

          <div class="dd-menu" data-dd-menu>
            <a class="dd-item" href="{{ route('profile.show') }}">
              <span>Mi perfil</span><span style="opacity:.65;">→</span>
            </a>
            <div class="dd-sep"></div>

            <form method="post" action="{{ route('logout') }}" style="margin:0;">
              @csrf
              <button type="submit" class="dd-item dd-item-btn dd-danger">
                <span>Salir</span><span style="opacity:.65;">⎋</span>
              </button>
            </form>
          </div>
        </div>

      @endauth

      @guest
        <a href="{{ route('login') }}" class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}">Ingresar</a>
        <a href="{{ route('first_access.show') }}" class="nav-link {{ request()->routeIs('first_access.*') ? 'active' : '' }}">Primer acceso</a>
        <a href="{{ route('reset.show') }}" class="nav-link {{ request()->routeIs('reset.*') ? 'active' : '' }}">Recuperar</a>
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

<script>
  // Dropdowns: abre/cierra y cierra al click afuera o ESC (sin librerías)
  (function(){
    const dds = document.querySelectorAll('[data-dd]');
    function closeAll(except){
      dds.forEach(dd => { if(dd !== except) dd.classList.remove('open'); });
    }

    dds.forEach(dd => {
      const btn = dd.querySelector('[data-dd-btn]');
      if(!btn) return;
      btn.addEventListener('click', (e) => {
        e.stopPropagation();
        const isOpen = dd.classList.contains('open');
        closeAll(dd);
        dd.classList.toggle('open', !isOpen);
      });
    });

    document.addEventListener('click', () => closeAll(null));
    document.addEventListener('keydown', (e) => {
      if(e.key === 'Escape') closeAll(null);
    });
  })();
</script>

@stack('scripts')

</body>
</html>
