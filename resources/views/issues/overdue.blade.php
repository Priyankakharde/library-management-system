@extends('layouts.lms')

@section('title', 'Overdue Items')

@section('lms-content')

<h2>⏰ Overdue Items</h2>
<p>Books with a due date in the past and not returned.</p>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Issue ID</th>
                <th>Book</th>
                <th>Student</th>
                <th>Due Date</th>
                <th>Days Late</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($overdues as $issue)
            <tr>
                <td>{{ $issue->id }}</td>
                <td>{{ $issue->book?->title ?? $issue->book_name ?? '—' }}</td>
                <td>{{ $issue->student?->name ?? $issue->student_name ?? '—' }}</td>
                <td>{{ optional($issue->due_date)->format('Y-m-d') }}</td>
                <td>
                    @php
                        $daysLate = \Carbon\Carbon::now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($issue->due_date)->startOfDay());
                    @endphp
                    {{ $daysLate }}
                </td>
                <td>
                    <a class="btn-sm" href="{{ route('issues.show', $issue->id) }}">View</a>
                    <form action="{{ route('issues.return', $issue->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button class="btn-sm btn-success" onclick="return confirm('Mark returned?')">Return</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6">No overdue items found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
