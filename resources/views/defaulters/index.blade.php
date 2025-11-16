@extends('layouts.app')
@section('title','Defaulters')
@section('content')
<div class="container">
  <h3>Defaulter List</h3>
  @if($overdues->count())
    <ul class="list-group">
      @foreach($overdues as $o)
        <li class="list-group-item">
          <strong>{{ $o->book->title ?? '—' }}</strong> — {{ $o->student->name ?? $o->student->email }} — due {{ optional($o->due_date)->format('d M Y') }}
        </li>
      @endforeach
    </ul>
  @else
    <div class="alert alert-success">No overdue books.</div>
  @endif
</div>
@endsection
