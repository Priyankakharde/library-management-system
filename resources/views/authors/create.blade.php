@extends('layouts.app')
@section('title','Authors')
@section('content')
<div class="d-flex justify-content-between mb-3">
  <h3>Authors</h3>
  <a href="{{ route('authors.create') }}" class="btn btn-primary">Add Author</a>
</div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

<div class="card-pro p-3">
  <table class="table table-dark table-striped">
    <thead><tr><th>Name</th><th>Books</th><th></th></tr></thead>
    <tbody>
      @foreach($authors as $a)
      <tr>
        <td>{{ $a->name }}</td>
        <td>{{ $a->books()->count() }}</td>
        <td class="text-end">
          <a href="{{ route('authors.edit', $a) }}" class="btn btn-sm btn-outline-light">Edit</a>
          <form method="POST" action="{{ route('authors.destroy', $a) }}" style="display:inline-block" onsubmit="return confirm('Delete author?');">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-danger">Delete</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  <div class="mt-3">{{ $authors->links() }}</div>
</div>
@endsection
