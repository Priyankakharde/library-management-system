{{-- resources/views/issues/_row.blade.php
     Partial that renders a single <tr> for an Issue in issues index/table.
     Expects $issue (App\Models\Issue) OR will attempt to resolve an ID from the route/request.
--}}

@php
// Defensive: try to ensure $issue is set.
if (! isset($issue)) {
    try {
        $id = request()->get('issue_id') ?? request()->route('issue') ?? null;
        if ($id) {
            $issue = \App\Models\Issue::with(['book','student'])->find($id);
        }
    } catch (\Throwable $e) {
        $issue = null;
    }
}

// Small helpers
$book = $issue->book ?? null;
$student = $issue->student ?? null;
$issueDate = $issue->issue_date ?? $issue->created_at ?? null;
$dueDate = $issue->due_date ?? null;
$returnedAt = $issue->returned_at ?? null;

// determine status text / classes
$status = 'Issued';
$statusClass = 'badge-issued';
if ($returnedAt) {
    $status = 'Returned';
    $statusClass = 'badge-returned';
} else {
    // overdue if due date present and before today
    if ($dueDate && \Illuminate\Support\Carbon::parse($dueDate)->isPast()) {
        $status = 'Overdue';
        $statusClass = 'badge-overdue';
    }
}
@endphp

@if(! $issue)
<tr>
  <td colspan="8" class="muted small">Issue not found.</td>
</tr>
@else
<tr id="issue-row-{{ $issue->id }}">
  {{-- Issue ID --}}
  <td class="text-center" style="min-width:70px;">{{ $issue->id }}</td>

  {{-- Book --}}
  <td>
    @if($book)
      @if(Route::has('books.show'))
        <a href="{{ route('books.show', $book->id) }}">{{ $book->title ?? ('#'.$book->id) }}</a>
      @else
        {{ $book->title ?? ('#'.$book->id) }}
      @endif
      <div class="small muted" style="margin-top:4px;">ISBN: {{ $book->isbn ?? '—' }}</div>
    @else
      <span class="muted">No book</span>
    @endif
  </td>

  {{-- Student --}}
  <td>
    @if($student)
      @if(Route::has('students.show'))
        <a href="{{ route('students.show', $student->id) }}">{{ $student->name ?? ('Student #'.$student->id) }}</a>
      @else
        {{ $student->name ?? ('Student #'.$student->id) }}
      @endif
      <div class="small muted" style="margin-top:4px;">ID: {{ $student->student_id ?? $student->id ?? '—' }}</div>
    @else
      <span class="muted">No student</span>
    @endif
  </td>

  {{-- Issue Date --}}
  <td style="white-space:nowrap;">
    @if($issueDate)
      {{ \Illuminate\Support\Carbon::parse($issueDate)->format('Y-m-d') }}
      <div class="small muted">{{ \Illuminate\Support\Carbon::parse($issueDate)->diffForHumans() }}</div>
    @else
      —
    @endif
  </td>

  {{-- Due Date --}}
  <td style="white-space:nowrap;">
    @if($dueDate)
      {{ \Illuminate\Support\Carbon::parse($dueDate)->format('Y-m-d') }}
      <div class="small muted">{{ \Illuminate\Support\Carbon::parse($dueDate)->diffForHumans() }}</div>
    @else
      —
    @endif
  </td>

  {{-- Returned / Status --}}
  <td style="white-space:nowrap;">
    <span class="{{ $statusClass }}" title="Status">{{ $status }}</span>
    @if($returnedAt)
      <div class="small muted" style="margin-top:4px;">returned: {{ \Illuminate\Support\Carbon::parse($returnedAt)->format('Y-m-d') }}</div>
    @endif
  </td>

  {{-- Fine / Days overdue (optional) --}}
  <td class="text-center">
    @php
    $fineText = '—';
    if (! $returnedAt && $dueDate) {
        $days = \Illuminate\Support\Carbon::now()->diffInDays(\Illuminate\Support\Carbon::parse($dueDate), false);
        if ($days < 0) {
            $over = abs($days);
            // Example fine calc: ₹2 per day overdue (adjust if you have a field)
            $finePerDay = config('library.fine_per_day', 2);
            $fineText = $over . ' day(s) • ' . ($over * $finePerDay);
        } else {
            $fineText = '0';
        }
    } elseif ($returnedAt && $dueDate) {
        $returnDays = \Illuminate\Support\Carbon::parse($returnedAt)->diffInDays(\Illuminate\Support\Carbon::parse($dueDate), false);
        if ($returnDays < 0) {
            $ov = abs($returnDays);
            $finePerDay = config('library.fine_per_day', 2);
            $fineText = $ov . ' day(s) • ' . ($ov * $finePerDay);
        } else {
            $fineText = '0';
        }
    }
    @endphp

    <div class="small muted">{{ $fineText }}</div>
  </td>

  {{-- Actions --}}
  <td style="white-space:nowrap;">
    <div style="display:flex;gap:6px;align-items:center;">

      {{-- Show --}}
      @if(Route::has('issues.show'))
        <a href="{{ route('issues.show', $issue->id) }}" title="View" class="btn-ghost">View</a>
      @endif

      {{-- Edit --}}
      @if(Route::has('issues.edit'))
        <a href="{{ route('issues.edit', $issue->id) }}" title="Edit" class="btn-ghost">Edit</a>
      @endif

      {{-- Return (only if not returned) --}}
      @if(! $returnedAt && Route::has('issues.markReturned'))
        <form method="POST" action="{{ route('issues.markReturned', $issue->id) }}" style="display:inline;">
          @csrf
          <button type="submit" class="btn" onclick="return confirm('Mark as returned?');">Return</button>
        </form>
      @endif

      {{-- Delete --}}
      @if(Route::has('issues.destroy'))
        <form method="POST" action="{{ route('issues.destroy', $issue->id) }}" style="display:inline;" onsubmit="return confirm('Delete this issue record?');">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn-danger">Delete</button>
        </form>
      @endif
    </div>
  </td>
</tr>

{{-- Minimal styles for badges/buttons. You can remove if you have global CSS. --}}
<style>
  .badge-issued { background:#f1f5f9;color:#0f172a;padding:6px 8px;border-radius:6px;font-weight:600;display:inline-block; }
  .badge-overdue { background:#fff1f2;color:#7f1d1d;padding:6px 8px;border-radius:6px;font-weight:700;display:inline-block;border:1px solid #fecaca; }
  .badge-returned { background:#ecfdf5;color:#065f46;padding:6px 8px;border-radius:6px;font-weight:600;display:inline-block;border:1px solid #bbf7d0; }

  .btn { background:#2563eb;color:#fff;padding:6px 10px;border-radius:6px;border:none;text-decoration:none;cursor:pointer;font-size:.9rem; }
  .btn-ghost { background:transparent;border:1px solid rgba(0,0,0,0.06);padding:6px 8px;border-radius:6px;text-decoration:none;color:inherit;font-size:.9rem; }
  .btn-danger { background:#ef4444;color:#fff;padding:6px 8px;border-radius:6px;border:none;font-size:.9rem; cursor:pointer; }

  .small { font-size:.85rem; }
  .muted { color:rgba(15,23,42,0.55); }
  td { vertical-align:middle; padding:10px 8px; border-bottom:1px solid rgba(0,0,0,0.04); }
</style>
@endif
