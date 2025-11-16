{{-- resources/views/books/create.blade.php --}}
@extends('layouts.app')

@section('title','Add Book')

@section('content')
<div class="container" style="max-width:900px;">
  <div class="card">
    <div class="card-body">
      <h4>Add Book</h4>

      @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
      @endif

      <form action="{{ route('books.store') }}" method="POST">
        @csrf

        <div class="mb-3">
          <label>Title</label>
          <input name="title" class="form-control" value="{{ old('title') }}" required>
        </div>

        <div class="mb-3">
          <label>Authors</label>
          <select name="authors[]" id="authors" class="form-control" multiple>
            @foreach($authors as $a)
              <option value="{{ $a->id }}" {{ (collect(old('authors'))->contains($a->id)) ? 'selected' : '' }}>
                {{ $a->name }}
              </option>
            @endforeach
          </select>
          <small class="text-muted d-block mt-1">Or enter one author name below</small>
        </div>

        <div class="mb-3">
          <label>Author (free text)</label>
          <input name="author_text" class="form-control" value="{{ old('author_text') }}" placeholder="e.g. J. K. Rowling">
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label>ISBN</label>
            <input name="isbn" class="form-control" value="{{ old('isbn') }}">
          </div>
          <div class="col-md-6 mb-3">
            <label>Quantity</label>
            <input type="number" name="quantity" class="form-control" min="0" value="{{ old('quantity', 1) }}">
          </div>
        </div>

        <button class="btn btn-success">Save</button>
        <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">Cancel</a>
      </form>
    </div>
  </div>
</div>

{{-- minimal select2 to help multi-select (optional but nicer) --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(function(){ $('#authors').select2({ placeholder: 'Select authors', width: '100%' }); });
</script>
@endsection
