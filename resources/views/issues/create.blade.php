@extends('layouts.app')
@section('title','Issue Book')
@section('content')
<div class="container">
  <h3>Issue Book</h3>
  <form method="POST" action="#">
    @csrf
    <div class="mb-3">
      <label>Book</label>
      <select class="form-control" name="book_id">
        @foreach($books as $b)<option value="{{ $b->id }}">{{ $b->title }}</option>@endforeach
      </select>
    </div>
    <div class="mb-3">
      <label>Student</label>
      <select class="form-control" name="student_id">
        @foreach($students as $s)<option value="{{ $s->id }}">{{ $s->name }}</option>@endforeach
      </select>
    </div>
    <button class="btn btn-primary">Issue (dev)</button>
  </form>
</div>
@endsection
