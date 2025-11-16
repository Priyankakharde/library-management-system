@extends('layouts.lms')

@section('title', 'Student Details')

@section('lms-content')

<h2>👤 Student Details</h2>

<div class="card" style="display:flex;gap:20px;align-items:flex-start;">
    <div style="width:140px;">
        @if(!empty($student->photo))
            <img src="{{ asset('storage/' . $student->photo) }}" alt="photo" style="width:140px;height:140px;object-fit:cover;border-radius:8px;">
        @else
            <div style="width:140px;height:140px;background:#efefef;display:flex;align-items:center;justify-content:center;color:#888;border-radius:8px;">No Photo</div>
        @endif
    </div>

    <div style="flex:1;">
        <h3 style="margin-top:0;">{{ $student->name }}</h3>
        <p><strong>Student ID:</strong> {{ $student->student_code ?? $student->id }}</p>
        <p><strong>Email:</strong> {{ $student->email ?? '—' }}</p>
        <p><strong>Phone:</strong> {{ $student->phone ?? '—' }}</p>
        <p><strong>Course:</strong> {{ $student->course ?? '—' }}</p>
        <p><strong>Branch:</strong> {{ $student->branch ?? '—' }}</p>

        <div style="margin-top:12px;">
            <a class="btn-primary" href="{{ route('students.edit', $student->id) }}">Edit</a>
            <a class="btn" href="{{ route('students.index') }}">Back to list</a>
        </div>
    </div>
</div>

<h3 style="margin-top:18px;">📚 Issued Books</h3>
<div class="card">
    @if($student->issues && $student->issues->count())
        <table class="table">
            <thead>
                <tr>
                    <th>Issue ID</th>
                    <th>Book</th>
                    <th>Issued At</th>
                    <th>Due Date</th>
                    <th>Returned</th>
                </tr>
            </thead>
            <tbody>
                @foreach($student->issues as $issue)
                <tr>
                    <td>{{ $issue->id }}</td>
                    <td>{{ $issue->book?->title ?? $issue->book_name ?? '—' }}</td>
                    <td>{{ optional($issue->issue_date)->format('Y-m-d') ?? ($issue->created_at?->format('Y-m-d') ?? '—') }}</td>
                    <td>{{ optional($issue->due_date)->format('Y-m-d') ?? '—' }}</td>
                    <td>{{ $issue->returned_at ? 'Yes' : 'No' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No issued books found for this student.</p>
    @endif
</div>

@endsection
