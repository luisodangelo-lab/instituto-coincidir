<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name', 'Instituto Coincidir') }}</title>
  <meta name="description" content="Instituto Coincidir - formación virtual. Ingreso con DNI. Preinscripción simple.">

  <style>
    :root{
      --bg:#0b1220;
      --card: rgba(255,255,255,.07);
      --line: rgba(255,255,255,.14);
      --txt: rgba(255,255,255,.92);
      --muted: rgba(255,255,255,.72);
      --accent: rgba(99,102,241,.35);
      --accentLine: rgba(99,102,241,.65);
    }
    body{
      margin:0;
      font-family: system-ui,-apple-system,Segoe UI,Roboto,Arial;
      background: radial-gradient(1100px 680px at 15% 10%, #1c2b58 0%, var(--bg) 62%);
      color:var(--txt);
    }
    .wrap{max-width:980px;margin:0 auto;padding:40px 18px;}
    .top{display:flex;align-items:center;justify-content:space-between;gap:12px;}
    .brand{font-weight:800;letter-spacing:.2px;font-size:18px;opacity:.95;}
    .pill{padding:8px 12px;border:1px solid var(--line);border-radius:999px;background:rgba(255,255,255,.05);color:var(--txt);text-decoration:none;font-weight:700;}
    .hero{margin-top:28px;padding:24px;border:1px solid var(--line);background:var(--card);border-radius:18px;}
    h1{margin:0 0 10px;font-size:34px;line-height:1.1;}
    p{margin:0 0 16px;color:var(--muted);font-size:16px;line-height:1.45;}
    .btns{display:flex;gap:10px;flex-wrap:wrap;margin-top:10px;}
    .btn{display:inline-block;padding:11px 14px;border-radius:12px;text-decoration:none;font-weight:800;border:1px solid var(--line);background:rgba(255,255,255,.06);color:var(--txt);}
    .btn.primary{background:var(--accent);border-color:var(--accentLine);}
    .grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:14px;}
    .card{border:1px solid var(--line);background:rgba(0,0,0,.18);border-radius:16px;padding:14px;}
    .card b{display:block;margin-bottom:6px;}
    footer{margin-top:16px;color:var(--muted);font-size:13px;display:flex;justify-content:space-between;flex-wrap:wrap;gap:10px;}
    @media (max-width: 820px){.grid{grid-template-columns:1fr;}h1{font-size:28px;}}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="top">
      <div class="brand">Instituto Coincidir</div>

      @auth
        <a class="pill" href="{{ route('dashboard') }}">Mi panel</a>
      @else
        <a class="pill" href="{{ route('login') }}">Ingresar</a>
      @endauth
    </div>

    <section class="hero">
      <h1>Formación virtual, simple y acompañada</h1>
      <p>
        Ingreso con <b>DNI</b>. Podés ver la oferta y comenzar el proceso desde cada curso.
        Si es tu primera vez, usá “Primer acceso”.
      </p>

      <div class="btns">
        <a class="btn primary" href="{{ route('catalog.index') }}">Ver cursos</a>

        @guest
          <a class="btn" href="{{ route('first_access.show') }}">Primer acceso</a>
          <a class="btn" href="{{ route('login') }}">Ingresar</a>
          <a class="btn" href="{{ route('reset.show') }}">Olvidé mi contraseña</a>
        @endguest

        @auth
          <a class="btn" href="{{ route('my.installments') }}">Mis cuotas</a>
        @endauth
      </div>

      <div class="grid">
        <div class="card">
          <b>📌 Inscripción</b>
          <div>Elegís un curso y completás tus datos desde la pantalla “Inscribirme”.</div>
        </div>
        <div class="card">
          <b>✅ Confirmación</b>
          <div>Al confirmar el pago, se habilita el acceso y se generan las cuotas.</div>
        </div>
        <div class="card">
          <b>🤝 Soporte</b>
          <div>Consultas por WhatsApp: <b>+54 9 280 451-4348</b>.</div>
        </div>
      </div>

      <footer>
        <span>© {{ date('Y') }} Instituto Coincidir</span>
        <span>Fundación Coincidir</span>
      </footer>
    </section>
  </div>
</body>
</html>
