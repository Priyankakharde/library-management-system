@extends('layouts.lms')

@section('title', 'Add Student')

@section('lms-content')

<h2>➕ Add New Student</h2>

<form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data" class="card">
    @csrf

    <label>Student ID (optional)</label>
    <input type="text" name="student_code" value="{{ old('student_code') }}" placeholder="e.g. S1234">

    <label>Name</label>
    <input type="text" name="name" value="{{ old('name') }}" required>

    <label>Email</label>
    <input type="email" name="email" value="{{ old('email') }}">

    <label>Phone</label>
    <input type="text" name="phone" value="{{ old('phone') }}">

    <label>Course</label>
    <input type="text" name="course" value="{{ old('course') }}">

    <label>Branch</label>
    <input type="text" name="branch" value="{{ old('branch') }}">

    <label>Photo</label>
    <input type="file" name="photo" accept="image/*">

    <div style="margin-top:12px;">
        <button class="btn-primary">Save Student</button>
        <a class="btn" href="{{ route('students.index') }}">Cancel</a>
    </div>
</form>

@endsection
