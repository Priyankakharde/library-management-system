@extends('layouts.app')
@section('title','Issues')
@section('content')
<div class="container">
  <h3>Issued Books</h3>
  <p>All issued records (placeholder)</p>
  @if($issues->count())
    <table class="table">
      <thead><tr><th>Book</th><th>Student</th><th>Issued At</th><th>Due Date</th></tr></thead>
      <tbody>
      @foreach($issues as $i)
        <tr>
          <td>{{ $i->book->title ?? '—' }}</td>
          <td>{{ $i->student->name ?? '—' }}</td>
          <td>{{ optional($i->issued_at)->format('d M Y') }}</td>
          <td>{{ optional($i->due_date)->format('d M Y') }}</td>
        </tr>
      @endforeach
      </tbody>
    </table>
    {{ $issues->links() }}
  @else
    <div class="alert alert-info">No issued books yet.</div>
  @endif
</div>
@endsection
