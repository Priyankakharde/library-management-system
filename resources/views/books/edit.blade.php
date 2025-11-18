@extends('layouts.app')
@section('title','Edit Book')
@section('content')
<div class="d-flex justify-content-between mb-3">
  <h3>Edit Book</h3>
  <a href="{{ route('books.index') }}" class="btn btn-outline-light">Back</a>
</div>

<div class="card-pro p-3">
  <form method="POST" action="{{ route('books.update', $book) }}">
    @csrf @method('PUT')
    @include('books._form', ['book'=>$book])
    <div class="mt-3"><button class="btn btn-primary">Update Book</button></div>
  </form>
</div>
@endsection
