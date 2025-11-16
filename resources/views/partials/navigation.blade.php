<div class="topbar">
  <div class="topbar-left">
    <button id="sidebarToggle" class="hamburger">☰</button>
    <div class="brand">{{ config('app.name','Library Management System') }}</div>
  </div>

  <div class="topbar-right">
    <div class="top-stats">
      <span>Books: {{ $books_count ?? 0 }}</span>
      <span>Students: {{ $students_count ?? 0 }}</span>
      <span>Issued: {{ $issued_count ?? 0 }}</span>
      <span>Overdue: {{ $defaulters_count ?? 0 }}</span>
    </div>

    <div class="top-user">Welcome, {{ auth()->user()->name ?? 'Admin' }}</div>
  </div>
</div>
