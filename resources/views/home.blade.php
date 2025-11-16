@extends('layouts.app')

@section('title', 'Home')

@section('content')

<div class="page-card">
  <h3>Welcome, Admin 👋</h3>
  <p class="text-muted">Here’s a quick summary of your library status.</p>

  <div class="summary-cards">
    <div class="card-quick">
      <div style="font-weight:600;">📚 Total Books</div>
      <div style="font-size:22px; font-weight:700; margin-top:8px;">
        {{ $books_count ?? 0 }}
      </div>
    </div>

    <div class="card-quick" style="background:#e9fff0;">
      <div style="font-weight:600;">🎓 Students</div>
      <div style="font-size:22px; font-weight:700; margin-top:8px;">
        {{ $students_count ?? 0 }}
      </div>
    </div>

    <div class="card-quick" style="background:#fff7d9;">
      <div style="font-weight:600;">📘 Issued Books</div>
      <div style="font-size:22px; font-weight:700; margin-top:8px;">
        {{ $issued_count ?? 0 }}
      </div>
    </div>

    <div class="card-quick" style="background:#ffe9ea;">
      <div style="font-weight:600;">⚠ Overdue Books</div>
      <div style="font-size:22px; font-weight:700; margin-top:8px;">
        {{ $defaulters_count ?? 0 }}
      </div>
    </div>
  </div>

  <div style="margin-top:18px;">
    <h5>Quick Actions</h5>
    <div style="display:flex; gap:10px;">
      <a class="btn btn-primary" href="{{ url('/books/create') }}">+ Add Book</a>
      <a class="btn btn-warning" href="{{ url('/issues/create') }}">Issue Book</a>
      <a class="btn btn-danger" href="{{ url('/issues/defaulters') }}">View Defaulters</a>
    </div>
  </div>

  <div style="margin-top:20px;" class="page-card">
    <strong>System Information</strong>
    <div style="margin-top:8px;">
      <div>📅 Date: {{ now()->format('d M Y, h:i A') }}</div>
      <div>🔑 Role: Librarian</div>
    </div>
  </div>
</div>

@endsection
