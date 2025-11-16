@extends('layouts.lms')

@section('title', 'Author Details')

@section('lms-content')

<h2>👤 Author Details</h2>

<div class="author-box">

    @if($author->photo)
        <img src="{{ asset('storage/' . $author->photo) }}" class="profile-photo">
    @endif

    <h3>{{ $author->name }}</h3>

    <p><strong>Email:</strong> {{ $author->email ?? '—' }}</p>
    <p><strong>Contact:</strong> {{ $author->contact ?? '—' }}</p>
    <p><strong>Address:</strong> {{ $author->address ?? '—' }}</p>
    <p><strong>Bio:</strong> {{ $author->bio ?? '—' }}</p>

</div>

<h3>📚 Books by Author</h3>

<ul class="book-list">
    @foreach($author->books as $book)
        <li>{{ $book->title }}</li>
    @endforeach
</ul>

@endsection
