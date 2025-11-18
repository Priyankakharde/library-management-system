{{-- resources/views/books/_form.blade.php --}}
@php
  use Carbon\Carbon;
@endphp

<div class="mb-3">
  <label for="title" class="form-label">Title</label>
  <input id="title" name="title" class="form-control" value="{{ old('title', $book->title ?? '') }}" required>
</div>

<div class="mb-3">
  <label for="authors" class="form-label">Authors</label>
  {{-- assume $allAuthors is passed and contains all authors --}}
  <select id="authors" name="authors[]" class="form-select" multiple>
    @foreach($allAuthors as $author)
      @php
        $selected = false;
        if(old('authors')) {
          $selected = in_array($author->id, old('authors', []));
        } elseif(isset($book) && $book->authors) {
          $selected = $book->authors->pluck('id')->contains($author->id);
        }
      @endphp
      <option value="{{ $author->id }}" @if($selected) selected @endif>{{ $author->name }}</option>
    @endforeach
  </select>
</div>

<div class="row">
  <div class="col-md-6 mb-3">
    <label for="isbn" class="form-label">ISBN</label>
    <input id="isbn" name="isbn" class="form-control" value="{{ old('isbn', $book->isbn ?? '') }}">
  </div>

  <div class="col-md-6 mb-3">
    <label for="quantity" class="form-label">Quantity</label>
    <input id="quantity" name="quantity" type="number" min="0" class="form-control" value="{{ old('quantity', $book->quantity ?? 0) }}">
  </div>
</div>

{{-- Published at - safe formatting --}}
@php
  // Determine a Y-m-d string safely:
  $published_date_value = old('published_at') 
      ?? (isset($book) && $book->published_at
          ? (
              // If already Carbon or DateTime, format; if string parse then format
              (method_exists($book->published_at, 'format')
                ? $book->published_at->format('Y-m-d')
                : (string) (Carbon::parse($book->published_at)->format('Y-m-d'))
              )
            )
          : null
        );
@endphp

<div class="mb-3">
  <label for="published_at" class="form-label">Published date</label>
  <input id="published_at" name="published_at" type="date" class="form-control" value="{{ $published_date_value ?? '' }}">
</div>

<div class="mb-3">
  <label for="description" class="form-label">Description</label>
  <textarea id="description" name="description" rows="4" class="form-control">{{ old('description', $book->description ?? '') }}</textarea>
</div>

{{-- submit button placed in parent create/edit view --}}
