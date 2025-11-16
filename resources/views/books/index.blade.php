{{-- resources/views/books/index.blade.php --}}
@extends('layouts.app')

@section('title','Books')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Books</h3>
        <a href="{{ route('books.create') }}" class="btn btn-success">Add Book</a>
    </div>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-body">

            <form method="GET" class="row g-2 mb-3" id="filterForm">
                <div class="col-md-6">
                    <input name="q" value="{{ old('q', $q) }}" class="form-control" placeholder="Search title or ISBN">
                </div>

                <div class="col-md-3">
                    <select name="author" id="authorSelect" class="form-control">
                        <option value="">All authors</option>
                        @foreach($authors as $a)
                            <option value="{{ $a->id }}" {{ (string)$a->id === (string)$author ? 'selected' : '' }}>
                                {{ $a->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 d-flex">
                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                    <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>ISBN</th>
                            <th style="width:100px">Quantity</th>
                            <th style="width:170px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($books as $book)
                            <tr>
                                <td>{{ $book->title }}</td>
                                <td>{{ $book->author_names ?: ($book->author ?? '—') }}</td>
                                <td>{{ $book->isbn ?? '—' }}</td>
                                <td>{{ $book->quantity ?? 0 }}</td>
                                <td>
                                    <a href="{{ route('books.edit', $book) }}" class="btn btn-sm btn-warning">Edit</a>

                                    <form action="{{ route('books.destroy', $book) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this book?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No books found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $books->links() }}
            </div>
        </div>
    </div>
</div>

{{-- Optional: small enhancement: keep author dropdown searchable using Select2 CDN --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.jQuery && $('#authorSelect').length) {
            $('#authorSelect').select2({ placeholder: 'Filter by author', width: '100%', allowClear: true });
        }
    });
</script>
@endsection
