@extends('layouts.lms')

@section('title', 'Edit Student')

@section('lms-content')

<h2>✏️ Edit Student</h2>

<form action="{{ route('students.update', $student->id) }}" method="POST" enctype="multipart/form-data" class="card">
    @csrf
    @method('PUT')

    <label>Student ID (optional)</label>
    <input type="text" name="student_code" value="{{ old('student_code', $student->student_code) }}" placeholder="e.g. S1234">

    <label>Name</label>
    <input type="text" name="name" value="{{ old('name', $student->name) }}" required>

    <label>Email</label>
    <input type="email" name="email" value="{{ old('email', $student->email) }}">

    <label>Phone</label>
    <input type="text" name="phone" value="{{ old('phone', $student->phone) }}">

    <label>Course</label>
    <input type="text" name="course" value="{{ old('course', $student->course) }}">

    <label>Branch</label>
    <input type="text" name="branch" value="{{ old('branch', $student->branch) }}">

    <label>Photo</label><br>
    @if($student->photo)
        <img src="{{ asset('storage/' . $student->photo) }}" alt="photo" style="width:80px;height:80px;object-fit:cover;border-radius:6px;margin-bottom:8px;">
    @endif
    <input type="file" name="photo" accept="image/*">

    <div style="margin-top:12px;">
        <button class="btn-primary">Update Student</button>
        <a class="btn" href="{{ route('students.index') }}">Cancel</a>
    </div>
</form>

@endsection
