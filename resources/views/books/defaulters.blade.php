@extends('layouts.lms')

@section('title', 'Book Defaulters')

@section('lms-content')
<h2>⚠ Book Defaulters</h2>
<p>List of students with overdue books (due date passed and not returned).</p>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Issue ID</th>
                <th>Book</th>
                <th>Student</th>
                <th>Due Date</th>
                <th>Days Overdue</th>
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
                        $days = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($issue->due_date)->startOfDay());
                    @endphp
                    {{ $days }}
                </td>
                <td>
                    <a class="btn-sm" href="{{ route('issues.show', $issue->id) }}">View</a>
                    <form action="{{ route('issues.return', $issue->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button class="btn-sm btn-success" onclick="return confirm('Mark as returned?')">Mark Returned</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6">No defaulters found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
