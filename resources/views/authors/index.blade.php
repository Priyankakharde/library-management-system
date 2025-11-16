@extends('layouts.app')

@section('title','Authors')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Authors</h4>
        <a href="{{ route('authors.create') }}" class="btn btn-primary">Add Author</a>
    </div>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

    <div class="card">
        <div class="card-body">
            @if($authors->count())
                <ul class="list-group">
                    @foreach($authors as $a)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $a->name }}</strong><br>
                                <small class="text-muted">{{ $a->email }}</small>
                            </div>
                            <div>
                                <a href="{{ route('authors.edit', $a) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                <form action="{{ route('authors.destroy', $a) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete author?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="alert alert-info">No authors yet.</div>
            @endif
        </div>
    </div>
</div>
@endsection
