@extends('layouts.app')

@section('title', 'Students')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-semibold">Students</h2>
        <a href="{{ route('students.create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg"></i> Add Student
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('students.index') }}" class="row g-2 align-items-center">
                <div class="col-md-8">
                    <input name="q" value="{{ $q ?? '' }}" class="form-control" placeholder="Search name, email or roll no">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100" type="submit">Search</button>
                </div>
                <div class="col-md-2 text-end">
                    <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Roll No</th>
                        <th>Joined</th>
                        <th style="width:130px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td>{{ $loop->iteration + ($students->currentPage()-1) * $students->perPage() }}</td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->email ?? '—' }}</td>
                            <td>{{ $student->roll_no ?? '—' }}</td>
                            <td>{{ $student->created_at ? $student->created_at->format('Y-m-d') : '—' }}</td>
                            <td>
                                <a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-warning">Edit</a>

                                <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this student?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No students found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $students->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
