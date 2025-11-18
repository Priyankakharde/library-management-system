<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>@yield('title', config('app.name','Laravel'))</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    :root{
      --sidebar-width: 260px;
      --accent-blue: #2e8ef7;
      --accent-orange: #ffb020;
      --accent-pink: #ff5c8a;
      --card-bg-dark: rgba(255,255,255,0.03);
      --glass: rgba(255,255,255,0.03);
    }

    /* Body gradients */
    body {
      font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      background: linear-gradient(180deg,#031226 0%, #071226 60%);
      color: #e6eef6;
      min-height: 100vh;
      margin: 0;
    }

    /* TOPBAR */
    .app-topbar {
      height: 64px;
      background: linear-gradient(90deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
      display:flex; align-items:center; justify-content:space-between;
      padding: 0 18px; position: sticky; top:0; z-index:1050;
      border-bottom: 1px solid rgba(255,255,255,0.03);
      backdrop-filter: blur(6px);
    }

    .brand-avatar { width:44px; height:44px; border-radius:8px; display:flex; align-items:center; justify-content:center; background:linear-gradient(45deg,#2e8ef7,#7c3aed); color:#fff; font-weight:700; box-shadow: 0 6px 18px rgba(0,0,0,0.5); }

    /* SIDEBAR */
    .app-sidebar {
      width: var(--sidebar-width);
      background: linear-gradient(180deg,#0b1320,#061022);
      color: #cfe8ff;
      min-height: 100vh; position: fixed; left:0; top:0; padding:18px 16px;
      box-shadow: 3px 0 20px rgba(0,0,0,0.6);
      z-index:1040;
    }

    .app-sidebar .nav-link {
      color: #c9d6e6;
      border-radius:10px; padding:10px 12px; margin:6px 0;
      display:flex; align-items:center; gap:12px; text-decoration:none;
    }
    .app-sidebar .nav-link .bi { font-size:1.1rem; color:#92a9c6; min-width:26px; text-align:center; }
    .app-sidebar .nav-link:hover { background: rgba(255,255,255,0.02); color:#fff; transform: translateY(-1px); }
    .app-sidebar .nav-link.active { background: linear-gradient(90deg,#102035,#0b1626); color:#fff; box-shadow: inset 0 0 0 1px rgba(255,255,255,0.02); }

    .sidebar-shortcuts { margin-top:18px; display:flex; flex-direction:column; gap:10px; }
    .shortcut-btn { padding:9px 12px; border-radius:10px; background:rgba(255,255,255,0.02); color:#dbe8ff; display:flex; gap:8px; align-items:center; text-decoration:none; }

    /* MAIN */
    .app-main { margin-left: var(--sidebar-width); padding:20px 28px 80px 28px; min-height:100vh; }

    .card-pro { background: var(--card-bg-dark); border-radius:10px; border:1px solid rgba(255,255,255,0.03); padding:14px; }

    /* small screens */
    @media (max-width: 991px) {
      .app-sidebar { transform: translateX(-120%); transition: transform .18s ease; position: fixed; z-index:2000; }
      .app-sidebar.show { transform: translateX(0); }
      .app-main { margin-left:0; padding:14px; }
      .sidebar-toggle { display:inline-flex; }
    }

    /* light theme */
    body.light-theme {
      background: linear-gradient(180deg,#f7fbff 0%,#eef4fb 60%);
      color:#0b1220;
    }
    body.light-theme .app-sidebar { background: linear-gradient(180deg,#fff,#f3f6fb); color:#0b1220; }
    body.light-theme .card-pro { background: #fff; color:#0b1220; border-color:#e7eefb; }

    /* stat cards */
    .stat-card { border-radius:12px; padding:18px; display:flex; align-items:center; gap:14px; }
    .stat-icon { width:52px; height:52px; border-radius:10px; display:flex; align-items:center; justify-content:center; color:#fff; font-size:1.25rem; box-shadow: 0 8px 22px rgba(0,0,0,0.45); }

    /* colorful variants */
    .bg-accent-blue { background: linear-gradient(135deg,#2e8ef7,#6fb1ff); }
    .bg-accent-orange { background: linear-gradient(135deg,#ffb020,#ff7a59); }
    .bg-accent-green { background: linear-gradient(135deg,#16a34a,#4ade80); }
    .bg-accent-pink { background: linear-gradient(135deg,#ff5c8a,#ff9bb3); }

    /* card header stylings */
    .card-title-lg { font-size:28px; font-weight:700; margin-bottom:6px; }

    /* action buttons */
    .btn-accent-1 { background: linear-gradient(90deg,#2e8ef7,#6fb1ff); color:#fff; border:none; box-shadow: 0 6px 20px rgba(46,142,247,0.15); }
    .btn-accent-2 { background: linear-gradient(90deg,#ffb020,#ff7a59); color:#0b1220; border:none; }
    .btn-accent-3 { background: linear-gradient(90deg,#ff5c8a,#ff9bb3); color:#fff; border:none; }

    /* small helper */
    .muted { color: rgba(255,255,255,0.6); }
    .muted-sm { color: rgba(255,255,255,0.45); font-size:0.9rem; }

  </style>

  @stack('head')
</head>
<body>
  <!-- Sidebar -->
  <aside class="app-sidebar">
    <div class="d-flex align-items-center mb-3">
      <div class="brand-avatar me-2">L</div>
      <div>
        <div style="font-weight:700;">Laravel</div>
        <div style="font-size:12px;color: #9fb2c9">Learning Management System</div>
      </div>
    </div>

    <nav class="nav flex-column">
      <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
        <i class="bi bi-house-door"></i> Home Page
      </a>

      <a class="nav-link {{ request()->is('books*') ? 'active' : '' }}" href="{{ route('books.index') }}">
        <i class="bi bi-journal-bookmark"></i> Manage Books
      </a>

      <a class="nav-link {{ request()->is('students*') ? 'active' : '' }}" href="{{ route('students.index') }}">
        <i class="bi bi-people"></i> Manage Students
      </a>

      <a class="nav-link {{ request()->is('issues*') ? 'active' : '' }}" href="{{ route('issues.index') }}">
        <i class="bi bi-arrow-right-square"></i> Issue Book
      </a>

      <a class="nav-link {{ request()->is('defaulters*') ? 'active' : '' }}" href="{{ route('defaulters.index') }}">
        <i class="bi bi-exclamation-triangle"></i> Defaulter List
      </a>

      <a class="nav-link" href="{{ route('logout') }}"
         onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="bi bi-box-arrow-right"></i> Logout
      </a>
    </nav>

    <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display:none">@csrf</form>

    <div class="sidebar-shortcuts mt-4">
      <a href="{{ route('books.create') }}" class="shortcut-btn"><i class="bi bi-plus-lg"></i> Add Book</a>
      <a href="{{ route('issues.create') }}" class="shortcut-btn"><i class="bi bi-arrow-right-circle"></i> Issue Book</a>
    </div>

    <div style="position:absolute;bottom:18px;left:16px;right:16px;font-size:12px;color:#7f93a6">
      Version 1.0 • Built for demo
    </div>
  </aside>

  <!-- topbar -->
  <header class="app-topbar">
    <div class="d-flex align-items-center gap-3">
      <button class="btn btn-sm btn-outline-light d-lg-none sidebar-toggle" id="btnSidebarToggle"><i class="bi bi-list"></i></button>
      <form action="{{ url()->current() }}" method="GET" class="d-none d-md-block">
        <div class="input-group" style="width:560px;">
          <input name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Search books, students, authors...">
          <button class="btn btn-primary btn-sm">Search</button>
        </div>
      </form>
    </div>

    <div class="d-flex align-items-center gap-3">
      <button class="btn btn-sm btn-outline-light" id="themeToggle" title="Toggle theme"><i class="bi bi-moon-stars" id="themeIcon"></i></button>

      <div class="d-flex align-items-center gap-2">
        <div class="small muted-sm">Welcome, <strong>{{ auth()->user()->name ?? 'Admin' }}</strong></div>
        <div style="width:38px;height:38px;border-radius:8px;background:#1f2937;display:flex;align-items:center;justify-content:center;color:#fff">
          {{ strtoupper(substr(auth()->user()->name ?? 'A',0,1)) }}
        </div>
      </div>
    </div>
  </header>

  <!-- main -->
  <main class="app-main">
    <div class="container-fluid px-0">
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      @yield('content')
    </div>
  </main>

  <!-- scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // sidebar toggle for mobile
    const btnSidebarToggle = document.getElementById('btnSidebarToggle');
    const sidebar = document.querySelector('.app-sidebar');
    if(btnSidebarToggle) btnSidebarToggle.addEventListener('click', ()=> sidebar.classList.toggle('show'));

    // theme toggle
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');
    function applyTheme(theme) {
      if(theme === 'light') {
        document.body.classList.add('light-theme');
        themeIcon.className = 'bi bi-sun';
      } else {
        document.body.classList.remove('light-theme');
        themeIcon.className = 'bi bi-moon-stars';
      }
    }
    const saved = localStorage.getItem('lms_theme') || 'dark';
    applyTheme(saved);
    if(themeToggle) themeToggle.addEventListener('click', () => {
      const current = document.body.classList.contains('light-theme') ? 'light' : 'dark';
      const next = current === 'dark' ? 'light' : 'dark';
      localStorage.setItem('lms_theme', next);
      applyTheme(next);
    });
  </script>

  @stack('scripts')
  @yield('scripts')
</body>
</html>
