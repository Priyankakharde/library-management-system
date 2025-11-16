<div class="topbar">
  <div style="display:flex; align-items:center; gap:12px;">
    <button class="btn btn-sm btn-outline-secondary" id="toggleSidebar">☰</button>
    <div style="font-weight:600;">Dashboard</div>
  </div>

  <div style="padding-right:8px; color:#333;">
    Welcome, @auth{{ auth()->user()->name ?? 'Admin' }}@else Admin @endauth
  </div>
</div>
