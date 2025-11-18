@extends('layouts.app')

@section('title', 'Add Student')

@section('content')
<div class="container py-4">
    <h2 class="fw-semibold mb-4">Add Student</h2>

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

            <form action="{{ route('students.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Name</label>
                    <input name="name" value="{{ old('name') }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email (optional)</label>
                    <input name="email" value="{{ old('email') }}" type="email" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Roll No (optional)</label>
                    <input name="roll_no" value="{{ old('roll_no') }}" class="form-control">
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary">Save</button>
                    <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
