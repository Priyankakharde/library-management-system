@extends('layouts.lms')

@section('title', 'Defaulter List')

@section('lms-content')

<h2>⚠ Defaulter List</h2>
<p>Students who have not returned overdue books.</p>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Student</th>
                <th>Student ID</th>
                <th>Book(s) Overdue</th>
                <th>Last Due Date</th>
                <th>Days Overdue</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($defaulters as $row)
            <tr>
                <td>
                    @if($row->student)
                        <a href="{{ route('students.show', $row->student->id) }}">{{ $row->student->name }}</a>
                    @else
                        {{ $row->student_name ?? '—' }}
                    @endif
                </td>
                <td>{{ $row->student->student_code ?? $row->student_id ?? '—' }}</td>
                <td>
                    @foreach($row->overdueBooks as $b)
                        <div>{{ $b->title ?? $b->book_name }} (Due {{ optional($b->due_date)->format('Y-m-d') }})</div>
                    @endforeach
                </td>
                <td>{{ optional($row->last_due)->format('Y-m-d') }}</td>
                <td>{{ $row->days_overdue ?? '-' }}</td>
                <td>
                    <a class="btn-sm" href="{{ route('students.show', $row->student->id ?? '#') }}">View Student</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="6">No defaulters found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
