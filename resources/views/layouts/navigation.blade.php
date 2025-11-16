<!-- resources/views/layouts/navigation.blade.php -->
<nav class="main-nav" role="navigation" aria-label="Primary navigation" style="background:transparent;">
  <div style="display:flex;align-items:center;gap:12px;padding:10px 16px;">
    {{-- Mobile menu toggle (works with layouts that include #sidebarToggle) --}}
    <button id="navSidebarToggle" aria-label="Toggle sidebar" style="font-size:20px;border:none;background:transparent;cursor:pointer">☰</button>

    {{-- Brand / home --}}
    <a href="{{ Route::has('home') ? route('home') : url('/') }}" class="brand-link" style="text-decoration:none;color:inherit;font-weight:700;font-size:18px;">
      {{ config('app.name', 'Library Management System') }}
    </a>

    {{-- Small spacer --}}
    <div style="flex:1"></div>

    {{-- Quick links (show only if routes exist) --}}
    <div style="display:flex;gap:8px;align-items:center;">
      @if(Route::has('books.index'))
        <a href="{{ route('books.index') }}" class="nav-item small" title="Manage Books" style="text-decoration:none;color:inherit;padding:6px 8px;border-radius:6px;">📚 Manage Books</a>
      @endif

      @if(Route::has('authors.index'))
        <a href="{{ route('authors.index') }}" class="nav-item small" title="Manage Authors" style="text-decoration:none;color:inherit;padding:6px 8px;border-radius:6px;">✍️ Manage Authors</a>
      @endif

      @if(Route::has('students.index'))
        <a href="{{ route('students.index') }}" class="nav-item small" title="Manage Students" style="text-decoration:none;color:inherit;padding:6px 8px;border-radius:6px;">🎓 Manage Students</a>
      @endif

      @if(Route::has('issues.index'))
        <a href="{{ route('issues.index') }}" class="nav-item small" title="Issue Book" style="text-decoration:none;color:inherit;padding:6px 8px;border-radius:6px;">📘 Issue Book</a>
      @endif
    </div>

    {{-- Auth area --}}
    <div style="display:flex;align-items:center;gap:8px;margin-left:12px;">
      @if(Route::has('login') || Route::has('register'))
        @auth
          {{-- If profile route exists, link to it --}}
          @if(Route::has('profile.show'))
            <a href="{{ route('profile.show') }}" class="nav-item" style="text-decoration:none;color:inherit;padding:6px 8px;border-radius:6px;">
              <span style="display:inline-block;margin-right:6px;">👤</span>
              <span class="small">Hi, {{ auth()->user()->name }}</span>
            </a>
          @else
            <span class="small" style="padding:6px 8px;">👤 {{ auth()->user()->name }}</span>
          @endif

          {{-- Logout form: only render button if route exists; otherwise provide Dashboard link --}}
          @if(Route::has('logout'))
            <form method="POST" action="{{ route('logout') }}" style="display:inline;margin:0;">
              @csrf
              <button type="submit" class="nav-logout small" style="background:#ef4444;color:#fff;padding:6px 10px;border-radius:6px;border:none;cursor:pointer;">
                Logout
              </button>
            </form>
          @else
            <a href="{{ route('dashboard') }}" class="small nav-item" style="text-decoration:none;color:inherit;padding:6px 8px;">Dashboard</a>
          @endif
        @else
          {{-- Guest links (show only if corresponding routes exist) --}}
          @if(Route::has('login'))
            <a href="{{ route('login') }}" class="small nav-item" style="text-decoration:none;color:inherit;padding:6px 8px;">Login</a>
          @endif
          @if(Route::has('register'))
            <a href="{{ route('register') }}" class="small nav-item" style="text-decoration:none;color:inherit;padding:6px 8px;">Register</a>
          @endif
        @endauth
      @else
        {{-- No auth routes; still show profile link when authenticated --}}
        @if(auth()->check() && Route::has('profile.show'))
          <a href="{{ route('profile.show') }}" class="nav-item small" style="text-decoration:none;color:inherit;padding:6px 8px;">Profile</a>
        @else
          {{-- Fallback dashboard link --}}
          <a href="{{ route('dashboard') }}" class="nav-item small" style="text-decoration:none;color:inherit;padding:6px 8px;">Dashboard</a>
        @endif
      @endif
    </div>
  </div>

  {{-- Optional sub-navigation or breadcrumbs area (yieldable) --}}
  <div style="border-top:1px solid rgba(0,0,0,0.04);padding:6px 16px;background:transparent;">
    @hasSection('nav-extra')
      @yield('nav-extra')
    @else
      {{-- default small breadcrumb / helper text --}}
      <div class="small" style="color:rgba(0,0,0,0.6);">Use the left menu to navigate the system — Manage books, students, and issues.</div>
    @endif
  </div>

  {{-- Minimal JS to toggle sidebar if a sidebar toggle exists on the page --}}
  <script>
    (function(){
      const btn = document.getElementById('navSidebarToggle');
      if (!btn) return;
      btn.addEventListener('click', function(){
        // prefer #sidebarToggle used in other layouts
        const mainToggle = document.getElementById('sidebarToggle') || document.getElementById('navSidebarToggle');
        if (mainToggle && typeof mainToggle.click === 'function') {
          mainToggle.click();
          return;
        }
        // fallback: toggle element with class .lms-sidebar
        const sb = document.querySelector('.lms-sidebar');
        if (!sb) return;
        if (getComputedStyle(sb).display === 'none') {
          sb.style.display = 'flex';
        } else {
          sb.style.display = 'none';
        }
      });
    })();
  </script>
</nav>
