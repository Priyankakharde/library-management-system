@extends('layouts.app')

@section('title','Add Author')

@section('content')
<div class="container" style="max-width:720px;">
    <div class="card">
        <div class="card-body">
            <h4>Add Author</h4>

            <form method="POST" action="{{ route('authors.store') }}">
                @csrf
                <div class="mb-3">
                    <label>Name</label>
                    <input name="name" value="{{ old('name') }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input name="email" value="{{ old('email') }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Bio</label>
                    <textarea name="bio" class="form-control">{{ old('bio') }}</textarea>
                </div>

                <button class="btn btn-success">Save</button>
                <a href="{{ route('authors.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
