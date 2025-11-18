@extends('layouts.app')

@section('title','Issue Book')

@section('content')
<div class="container-fluid">
  <div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
      <h1 class="mb-0">Issue Book</h1>
      <div class="text-muted">Select student and book, choose due date.</div>
    </div>
    <div>
      <a href="{{ route('issues.index') }}" class="btn btn-outline-light">Back to Issues</a>
    </div>
  </div>

  <div class="card-pro p-4" style="max-width:760px;">
    @if($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('issues.store') }}">
      @csrf

      <div class="mb-3">
        <label class="form-label">Student</label>
        <select name="student_id" class="form-select" required>
          <option value="">-- select student --</option>
          @foreach($students as $s)
            <option value="{{ $s->id }}" @selected(old('student_id') == $s->id)>{{ $s->name }} ({{ $s->roll ?? '' }})</option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Book</label>
        <select name="book_id" class="form-select" required>
          <option value="">-- select book --</option>
          @foreach($books as $b)
            <option value="{{ $b->id }}" data-qty="{{ $b->quantity }}" @selected(old('book_id') == $b->id)>
              {{ $b->title }} @if($b->authors && $b->authors->count()) — {{ $b->authors->pluck('name')->join(', ') }} @endif
              @if($b->quantity <= 0) (Out of stock) @else ({{ $b->quantity }} available) @endif
            </option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Due date</label>
        <input type="date" name="due_date" class="form-control" value="{{ old('due_date', now()->addDays(7)->format('Y-m-d')) }}" required>
        <div class="form-text">Choose date when book should be returned (default 7 days).</div>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Issue Book</button>
        <a href="{{ route('issues.index') }}" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

@endsection

@push('scripts')
<script>
  // optional UI helper: warn if selecting out-of-stock book
  document.addEventListener('DOMContentLoaded', function () {
    const bookSelect = document.querySelector('select[name="book_id"]');
    if (!bookSelect) return;
    bookSelect.addEventListener('change', function () {
      const opt = bookSelect.selectedOptions[0];
      if (!opt) return;
      const qty = parseInt(opt.getAttribute('data-qty') || '0', 10);
      if (qty <= 0) {
        alert('Warning: selected book is out of stock. Choose another book or update quantity.');
      }
    });
  });
</script>
@endpush
