{{-- resources/views/layouts/partials/sidebar.blade.php --}}
@php
    $user = Auth::user();
@endphp

<aside class="sidebar bg-dark text-light" style="width:250px; min-height:100vh; position:fixed; left:0; top:0; padding:20px;">
    <div class="sidebar-brand mb-4 d-flex align-items-center">
        <div class="me-2">
            {{-- optional: put logo.png in public/ if you want a logo --}}
            <img src="{{ asset('logo.png') }}" alt="Logo" style="width:40px; height:40px; object-fit:cover; border-radius:6px;">
        </div>
        <div>
            <h5 class="mb-0" style="color:#fff;">Library Management</h5>
            <small class="text-muted" style="color:#cbd5e1;">System</small>
        </div>
    </div>

    <nav class="nav flex-column">
        {{-- Home / Dashboard --}}
        <li class="nav-item mb-1">
            <a class="nav-link d-flex align-items-center {{ request()->routeIs('dashboard') ? 'active bg-danger text-white' : 'text-light' }}" href="{{ route('dashboard') }}" style="border-radius:6px; padding:10px 12px;">
                <i class="fa fa-home me-2" aria-hidden="true"></i>
                <span>Home Page</span>
            </a>
        </li>

        <hr style="border-color: rgba(255,255,255,0.06);">

        <div class="text-uppercase small text-muted mb-2" style="font-size:11px; color:#9aa4b2;">Features</div>

        {{-- Manage Books --}}
        <li class="nav-item mb-1">
            <a class="nav-link d-flex align-items-center {{ request()->routeIs('books.*') ? 'active bg-secondary text-white' : 'text-light' }}" href="{{ route('books.index') }}" style="border-radius:6px; padding:10px 12px;">
                <i class="fa fa-book me-2" aria-hidden="true"></i>
                <span>Manage Books</span>
            </a>
        </li>

        {{-- Manage Students --}}
        <li class="nav-item mb-1">
            <a class="nav-link d-flex align-items-center {{ request()->routeIs('students.*') ? 'active bg-secondary text-white' : 'text-light' }}" href="{{ route('students.index') }}" style="border-radius:6px; padding:10px 12px;">
                <i class="fa fa-user-graduate me-2" aria-hidden="true"></i>
                <span>Manage Students</span>
            </a>
        </li>

        {{-- Issue Book --}}
        <li class="nav-item mb-1">
            <a class="nav-link d-flex align-items-center {{ request()->routeIs('issues.*') ? 'active bg-secondary text-white' : 'text-light' }}" href="{{ route('issues.index') }}" style="border-radius:6px; padding:10px 12px;">
                <i class="fa fa-book-reader me-2" aria-hidden="true"></i>
                <span>Issue Book</span>
            </a>
        </li>

        {{-- Defaulter List --}}
        <li class="nav-item mb-1">
            <a class="nav-link d-flex align-items-center {{ request()->routeIs('defaulters.*') ? 'active bg-secondary text-white' : 'text-light' }}" href="{{ route('defaulters.index') }}" style="border-radius:6px; padding:10px 12px;">
                <i class="fa fa-exclamation-triangle me-2" aria-hidden="true"></i>
                <span>Defaulter List</span>
            </a>
        </li>

        <div class="mt-3"></div>

        {{-- Logout --}}
        <li class="nav-item mt-auto">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>

            <a href="#" class="nav-link d-flex align-items-center text-light" style="border-radius:6px; padding:10px 12px;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa fa-sign-out-alt me-2" aria-hidden="true"></i>
                <span>Logout</span>
            </a>
        </li>
    </nav>

    <div class="sidebar-footer mt-4" style="position:absolute; bottom:20px; left:20px; right:20px;">
        <div class="small text-muted" style="color:#9aa4b2;">Welcome,</div>
        <div style="color:#fff; font-weight:600;">{{ $user->name ?? 'Librarian' }}</div>
    </div>
</aside>

{{-- spacer so main content doesn't hide under fixed sidebar --}}
<div style="width:250px; min-height:1px; display:inline-block;"></div>

