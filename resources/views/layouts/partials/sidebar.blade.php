{{-- resources/views/layouts/partials/sidebar.blade.php --}}
<div class="sidebar">
  <div>
    <div class="brand">
      <div class="logo">
        <i class="fa-solid fa-book-open" style="font-size:20px"></i>
      </div>
      <div>
        <div class="title">Library</div>
        <div class="subtitle">Management</div>
      </div>
    </div>

    <nav class="nav-list">
      <div class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}" data-href="{{ route('dashboard') }}" data-path="dashboard">
        <i class="fa-solid fa-house"></i>
        <div class="label">Home Page</div>
      </div>

      <div class="nav-item {{ request()->is('books*') ? 'active' : '' }}" data-href="{{ route('books.index') }}" data-path="books">
        <i class="fa-solid fa-book"></i>
        <div class="label">Manage Books</div>
      </div>

      <div class="nav-item {{ request()->is('students*') ? 'active' : '' }}" data-href="{{ route('students.index') ?? '#' }}" data-path="students">
        <i class="fa-solid fa-user-graduate"></i>
        <div class="label">Manage Students</div>
      </div>

      <div class="nav-item {{ request()->is('issues*') ? 'active' : '' }}" data-href="{{ route('issues.index') ?? '#' }}" data-path="issues">
        <i class="fa-solid fa-box-open"></i>
        <div class="label">Issue Book</div>
      </div>

      <div class="nav-item {{ request()->is('defaulters*') ? 'active' : '' }}" data-href="{{ route('defaulters.index') ?? '#' }}" data-path="defaulters">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <div class="label">Defaulter List</div>
      </div>

      <div class="nav-item" onclick="document.getElementById('logout-form').submit();">
        <i class="fa-solid fa-arrow-right-from-bracket"></i>
        <div class="label">Logout</div>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
      </div>
    </nav>
  </div>

  <div class="footer">
    <div>Admin</div>
    <div class="text-muted small mt-2">Role: <strong style="color:#fff">Librarian</strong></div>
  </div>
</div>
