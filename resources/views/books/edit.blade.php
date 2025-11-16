{{-- resources/views/books/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Book')

@section('content')
<div class="container" style="max-width:900px;">
  <div class="card">
    <div class="card-body">
      <h4>Edit Book</h4>

      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">@foreach ($errors->all() as $err) <li>{{ $err }}</li> @endforeach</ul>
        </div>
      @endif

      <form action="{{ route('books.update', $book) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
          <label>Title</label>
          <input name="title" class="form-control" value="{{ old('title', $book->title) }}" required>
        </div>

        <div class="mb-3">
          <label>Authors</label>
          <select name="authors[]" id="authors" class="form-control" multiple>
            @php $selected = old('authors', $book->authors->pluck('id')->toArray()); @endphp
            @foreach($authors as $a)
              <option value="{{ $a->id }}" {{ in_array($a->id, (array)$selected) ? 'selected' : '' }}>
                {{ $a->name }}
              </option>
            @endforeach
          </select>
          <small class="text-muted d-block mt-1">Or enter one author name below</small>
        </div>

        <div class="mb-3">
          <label>Author (free text)</label>
          <input name="author_text" class="form-control" value="{{ old('author_text', '') }}" placeholder="e.g. J. K. Rowling">
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label>ISBN</label>
            <input name="isbn" class="form-control" value="{{ old('isbn', $book->isbn) }}">
          </div>
          <div class="col-md-6 mb-3">
            <label>Quantity</label>
            <input type="number" name="quantity" class="form-control" min="0" value="{{ old('quantity', $book->quantity ?? 1) }}">
          </div>
        </div>

        <button class="btn btn-success">Save changes</button>
        <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">Cancel</a>
      </form>
    </div>
  </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(function(){ $('#authors').select2({ placeholder: 'Select authors', width: '100%' }); });
</script>
@endsection
