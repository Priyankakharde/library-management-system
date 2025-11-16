@extends('layouts.app')
@section('title','Students')
@section('content')
<div class="container">
  <h3>Manage Students</h3>
  <p>List of students (placeholder)</p>
  @if($students->count())
    <ul class="list-group">
      @foreach($students as $s)
        <li class="list-group-item">{{ $s->name ?? $s->email }}</li>
      @endforeach
    </ul>
    {{ $students->links() }}
  @else
    <div class="alert alert-info">No students yet.</div>
  @endif
</div>
@endsection
