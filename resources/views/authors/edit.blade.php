@extends('layouts.lms')

@section('title', 'Edit Author')

@section('lms-content')

<h2>✏️ Edit Author</h2>

<form action="{{ route('authors.update', $author->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <label>Name</label>
    <input type="text" name="name" value="{{ $author->name }}" required>

    <label>Email</label>
    <input type="email" name="email" value="{{ $author->email }}">

    <label>Contact</label>
    <input type="text" name="contact" value="{{ $author->contact }}">

    <label>Address</label>
    <textarea name="address">{{ $author->address }}</textarea>

    <label>Bio</label>
    <textarea name="bio">{{ $author->bio }}</textarea>

    <label>Photo</label><br>
    @if($author->photo)
        <img src="{{ asset('storage/' . $author->photo) }}" class="thumb"><br>
    @endif
    <input type="file" name="photo">

    <button class="btn-primary">Update</button>
</form>

@endsection
