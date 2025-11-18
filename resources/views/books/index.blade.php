@extends('layouts.app')

@section('title','Books')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h2 class="mb-0">Books</h2>
    <div class="small-muted">Manage catalogue — each book can have multiple authors</div>
  </div>
  <div>
    <a href="{{ route('books.create') }}" class="btn btn-primary">Add New Book</a>
  </div>
</div>

<div class="card-pro p-3 mb-3">
  <form method="GET" action="{{ route('books.index') }}" class="d-flex gap-2 mb-3">
    <input name="q" value="{{ $q ?? '' }}" placeholder="Search books or authors..." class="form-control" />
    <button class="btn btn-outline-light">Search</button>
  </form>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if($books->count())
    <div class="table-responsive">
      <table class="table table-dark table-striped align-middle">
        <thead>
          <tr>
            <th>Title</th>
            <th>Authors</th>
            <th>ISBN</th>
            <th>Qty</th>
            <th>Added</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @foreach($books as $b)
            <tr>
              <td>{{ $b->title }}</td>
              <td>
                @foreach($b->authors as $a)
                  <span class="badge bg-secondary">{{ $a->name }}</span>
                @endforeach
              </td>
              <td>{{ $b->isbn }}</td>
              <td>{{ $b->quantity }}</td>
              <td>{{ $b->created_at->format('Y-m-d') }}</td>
              <td class="text-end">
                <a href="{{ route('books.edit', $b) }}" class="btn btn-sm btn-outline-light">Edit</a>
                <form method="POST" action="{{ route('books.destroy', $b) }}" style="display:inline-block" onsubmit="return confirm('Delete this book?')">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-danger">Delete</button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="mt-3">
      {{ $books->links() }}
    </div>
  @else
    <div class="p-3 muted">No books found.</div>
  @endif
</div>
@endsection
