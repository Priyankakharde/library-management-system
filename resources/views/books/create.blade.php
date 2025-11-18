@extends('layouts.app')
@section('title','Add Book')
@section('content')
<div class="d-flex justify-content-between mb-3">
  <h3>Add Book</h3>
  <a href="{{ route('books.index') }}" class="btn btn-outline-light">Back</a>
</div>

<div class="card-pro p-3">
  <form method="POST" action="{{ route('books.store') }}">
    @csrf
    @include('books._form', ['book'=>null])
    <div class="mt-3"><button class="btn btn-primary">Create Book</button></div>
  </form>
</div>
@endsection
