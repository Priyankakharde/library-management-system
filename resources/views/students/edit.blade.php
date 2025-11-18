@extends('layouts.app')

@section('title', 'Edit Student')

@section('content')
<div class="container py-4">
    <h2 class="fw-semibold mb-4">Edit Student</h2>

    <div class="card shadow-sm">
        <div class="card-body">

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('students.update', $student->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Name</label>
                    <input name="name" value="{{ old('name', $student->name) }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email (optional)</label>
                    <input name="email" value="{{ old('email', $student->email) }}" type="email" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Roll No (optional)</label>
                    <input name="roll_no" value="{{ old('roll_no', $student->roll_no) }}" class="form-control">
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary">Update</button>
                    <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
