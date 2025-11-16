@extends('layouts.lms')

@section('title', 'Book Details')

@section('lms-content')
<h2>📘 Book Details</h2>

<div class="card" style="display:flex;gap:20px;align-items:flex-start;">
    <div style="width:160px;">
        @if(!empty($book->cover))
            <img src="{{ asset('storage/' . $book->cover) }}" alt="cover" style="width:150px;height:200px;object-fit:cover;">
        @else
            <div style="width:150px;height:200px;background:#efefef;display:flex;align-items:center;justify-content:center;color:#888;">No Cover</div>
        @endif
    </div>

    <div style="flex:1;">
        <h3 style="margin-top:0;">{{ $book->title ?? $book->name }}</h3>
        <p><strong>Author:</strong>
            @if(isset($book->author) && is_object($book->author))
                {{ $book->author->name }}
            @else
                {{ $book->author_name ?? '—' }}
            @endif
        </p>
        <p><strong>Quantity:</strong> {{ $book->quantity ?? $book->qty ?? 0 }}</p>
        <p><strong>ISBN/Code:</strong> {{ $book->isbn ?? '—' }}</p>
        <p><strong>Description:</strong></p>
        <div style="background:#fafafa;padding:12px;border-radius:6px;">{{ $book->description ?? '—' }}</div>

        <div style="margin-top:12px;">
            <a class="btn-primary" href="{{ route('books.edit', $book->id) }}">Edit</a>
            <a class="btn" href="{{ route('books.index') }}">Back to list</a>
        </div>
    </div>
</div>
@endsection
