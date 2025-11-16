{{-- resources/views/layouts/app.blade.php --}}
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', config('app.name', 'Library'))</title>

    <!-- Bootstrap (cdn) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- Inline custom styles (paste to external file if you prefer) -->
    <style>
    :root{
      --sidebar-bg: #1f2937;       /* dark slate */
      --sidebar-accent: #2b6cb0;   /* blue accent */
      --sidebar-text: #cbd5e1;
      --sidebar-active-bg: #334155;
      --brand-gradient: linear-gradient(135deg,#00b4d8,#0077b6);
    }

    html,body{height:100%;background:#f8fafc;color:#0f172a;}
    .app-shell{min-height:100vh;display:flex;gap:1rem;}
    /* Sidebar */
    .sidebar{
      width:260px;
      background:var(--sidebar-bg);
      color:var(--sidebar-text);
      padding:24px 18px;
      display:flex;
      flex-direction:column;
      justify-content:space-between;
      transition:width .18s ease;
      box-shadow: 0 4px 14px rgba(15,23,42,0.06);
      border-right: 1px solid rgba(255,255,255,0.03);
    }
    .sidebar .brand{
      display:flex;align-items:center;gap:12px;margin-bottom:12px;
    }
    .brand .logo{
      width:44px;height:44px;border-radius:8px;
      background:var(--brand-gradient);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;
      box-shadow: 0 6px 20px rgba(3,122,178,0.12);
    }
    .brand .title{font-weight:700;color:#fff;line-height:1;}
    .brand .subtitle{font-size:13px;color:rgba(255,255,255,0.8);display:block;margin-top:-3px}

    .nav-list{margin-top:14px;}
    .nav-item{display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:8px;color:var(--sidebar-text);margin-bottom:8px;cursor:pointer;}
    .nav-item i{width:22px;text-align:center;font-size:18px;color:rgba(255,255,255,0.85);}

    .nav-item:hover{background:rgba(255,255,255,0.03);color:#fff;}
    .nav-item.active{background:var(--sidebar-accent);color:white;box-shadow:0 6px 18px rgba(59,130,246,0.12);}

    .nav-item .label{font-weight:600;}
    .sidebar .footer{font-size:14px;color:rgba(255,255,255,0.65);padding-top:12px;}

    /* Main content */
    .main {
      flex:1;
      padding:28px;
      min-height:100vh;
      display:flex;
      flex-direction:column;
      gap:18px;
      background: #f8fafc;
    }

    /* topbar */
    .topbar{
      display:flex;justify-content:flex-end;align-items:center;gap:12px;padding-right:6px;
    }
    .topbar .welcome{font-weight:600;color:#475569;margin-right:10px}
    .topbar .user-bubble{background:white;padding:8px 12px;border-radius:999px;box-shadow:0 4px 12px rgba(2,6,23,0.06);display:flex;gap:8px;align-items:center}

    /* Card look for content area */
    .card-clean{border-radius:12px;border:1px solid rgba(15,23,42,0.04);box-shadow:0 6px 26px rgba(2,6,23,0.03);}

    /* responsive: collapse sidebar on small screens */
    @media (max-width:900px){
      .sidebar{width:72px;padding:18px;}
      .sidebar .brand .title,.sidebar .brand .subtitle,.nav-item .label{display:none;}
      .sidebar .brand .logo{width:40px;height:40px;}
    }
    </style>

    @stack('head')
</head>
<body>
  <div class="app-shell">
    @include('layouts.partials.sidebar')

    <main class="main">
      <div class="topbar align-items-center">
        <div class="welcome d-none d-md-block">Welcome, <strong>{{ Auth::user()?->name ?? 'Admin' }}</strong></div>
        <div class="user-bubble">
          <i class="fa-regular fa-user"></i>
          <div class="d-none d-md-block">{{ Auth::user()?->name ?? 'Librarian' }}</div>
        </div>
      </div>

      <div class="container-fluid p-0">
        @yield('content')
      </div>

      <footer class="text-muted small mt-auto">
        <div class="pt-4 text-end">&copy; {{ date('Y') }} Library</div>
      </footer>
    </main>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Small script: highlight active item by URL (works if route names map to path segments) -->
  <script>
    (function(){
      const path = window.location.pathname.replace(/^\//,'').split('/')[0];
      document.querySelectorAll('.sidebar .nav-item').forEach(el=>{
        const target = el.getAttribute('data-path') || '';
        if(target === path || (path === '' && target === 'dashboard')) {
          el.classList.add('active');
        } else {
          el.classList.remove('active');
        }
      });

      // make entire .nav-item clickable if it has data-href
      document.querySelectorAll('.sidebar .nav-item[data-href]').forEach(el=>{
        el.addEventListener('click', ()=> {
          const href=el.getAttribute('data-href');
          if(href) window.location.href = href;
        });
      });
    })();
  </script>

  @stack('scripts')
</body>
</html>
