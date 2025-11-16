<!-- resources/views/layouts/guest.blade.php -->
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title', config('app.name', 'Library Management System'))</title>

  {{-- Vite if available, otherwise fallback to asset --}}
  @if (class_exists(\Illuminate\Foundation\Vite::class))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  @else
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
  @endif

  <style>
    :root{
      --brand-blue:#3f51ff;
      --muted:#6b7280;
      --card-bg:#ffffff;
    }
    html,body{height:100%;margin:0;font-family:Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",Roboto,Arial;color:#111;}
    .guest-topbar{height:64px;background:var(--brand-blue);color:#fff;display:flex;align-items:center;justify-content:space-between;padding:0 18px}
    .brand{font-weight:600;font-size:18px}
    .top-actions{display:flex;gap:10px;align-items:center}
    .btn{background:#fff;color:var(--brand-blue);padding:8px 12px;border-radius:6px;text-decoration:none;font-weight:600}
    .btn-ghost{background:transparent;color:#fff;border:1px solid rgba(255,255,255,0.18);padding:6px 10px;border-radius:6px;text-decoration:none}
    .container{max-width:1160px;margin:18px auto;padding:0 16px}
    .hero{background:linear-gradient(90deg,#f8fafc,#ffffff);padding:28px;border-radius:8px;margin-bottom:18px;display:flex;align-items:center;gap:18px}
    .hero h1{margin:0;font-size:22px}
    .panel{background:var(--card-bg);padding:18px;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,0.04);margin-bottom:14px}
    .small{font-size:13px;color:var(--muted)}
    .quick-links{display:flex;gap:12px;flex-wrap:wrap;margin-top:12px}
    .quick-card{background:#fff;border:1px solid #eef2f5;padding:10px 12px;border-radius:8px;min-width:160px}
    .alerts .alert{padding:12px;border-radius:6px;margin-bottom:12px}
    .alert-success{background:#ecfdf5;border:1px solid #bbf7d0;color:#065f46}
    .alert-error{background:#fff1f2;border:1px solid #fecaca;color:#7f1d1d}
    a { color: inherit; }
    @media (max-width:900px){ .hero{flex-direction:column;align-items:flex-start} .quick-links{flex-direction:column} }
  </style>
</head>
<body>
  <header class="guest-topbar" role="banner" aria-label="Topbar">
    <div class="brand">{{ config('app.name', 'Library Management System') }}</div>

    <div class="top-actions" role="navigation" aria-label="Top actions">
      {{-- Show profile/dashboard links only if routes exist --}}
      @if (Route::has('dashboard'))
        <a class="btn-ghost" href="{{ route('dashboard') }}">Dashboard</a>
      @endif

      @auth
        @if(Route::has('profile.show'))
          <a class="btn" href="{{ route('profile.show') }}">Profile</a>
        @endif

        @if(Route::has('logout'))
          <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit" class="btn" aria-label="Logout">Logout</button>
          </form>
        @else
          <a class="btn" href="{{ route('dashboard') }}">Continue</a>
        @endif
      @else
        {{-- Show login/register only if routes are registered --}}
        @if (Route::has('login'))
          <a class="btn" href="{{ route('login') }}">Login</a>
        @endif
        @if (Route::has('register'))
          <a class="btn" href="{{ route('register') }}">Register</a>
        @endif
      @endauth
    </div>
  </header>

  <main class="container" role="main">
    {{-- Hero / welcome area --}}
    <section class="hero" aria-labelledby="welcome-heading">
      <div>
        <h1 id="welcome-heading">Welcome to {{ config('app.name', 'Library') }}</h1>
        <p class="small">Browse books, view students, and check issued records. Use the quick links below to explore the system.</p>
        <div class="quick-links" style="margin-top:12px;">
          @if(Route::has('books.index')) <a class="quick-card" href="{{ route('books.index') }}">📚 Manage Books</a> @endif
          @if(Route::has('students.index')) <a class="quick-card" href="{{ route('students.index') }}">🎓 Manage Students</a> @endif
          @if(Route::has('issues.index')) <a class="quick-card" href="{{ route('issues.index') }}">📘 Issue Book</a> @endif
          @if(Route::has('authors.index')) <a class="quick-card" href="{{ route('authors.index') }}">✍️ Manage Authors</a> @endif
        </div>
      </div>

      {{-- Optional right-side highlights --}}
      <div style="margin-left:auto;text-align:right;min-width:220px;">
        <div style="font-size:12px;color:var(--muted)">Total Books</div>
        <div style="font-weight:700;font-size:20px">{{ $shared_totalBooks ?? 0 }}</div>

        <div style="height:12px"></div>

        <div style="font-size:12px;color:var(--muted)">Total Students</div>
        <div style="font-weight:700;font-size:20px">{{ $shared_totalStudents ?? 0 }}</div>
      </div>
    </section>

    {{-- Flash messages --}}
    <div class="alerts" aria-live="polite">
      @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if (session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
      @endif
      @if ($errors->any())
        <div class="alert alert-error">
          <ul style="margin:0;padding-left:18px;">
            @foreach ($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
      @endif
    </div>

    {{-- Page content --}}
    <div class="panel" role="region" aria-label="Page content">
      @yield('content')
    </div>

    <footer style="text-align:center;margin-top:18px;color:var(--muted);" aria-label="Footer">
      &copy; {{ date('Y') }} {{ config('app.name', 'Library Management System') }} — All rights reserved
    </footer>
  </main>

  {{-- Minimal JS for progressive behaviour (no dependency) --}}
  <script>
    // nothing required for guest layout currently
  </script>
</body>
</html>
