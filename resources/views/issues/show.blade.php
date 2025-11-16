@extends('layouts.lms')

@section('title', 'My Profile')

@section('lms-content')

<h2>👤 My Profile</h2>

<div class="card" style="display:flex;gap:20px;align-items:flex-start;">
    <div style="width:140px;">
        @if(auth()->user()->avatar)
            <img src="{{ asset('images/avatars/' . auth()->user()->avatar) }}" alt="avatar" style="width:140px;height:140px;object-fit:cover;border-radius:8px;">
        @else
            <div style="width:140px;height:140px;background:#efefef;display:flex;align-items:center;justify-content:center;color:#888;border-radius:8px;">No Avatar</div>
        @endif
    </div>

    <div style="flex:1;">
        <h3 style="margin-top:0;">{{ auth()->user()->name }}</h3>
        <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
        <p><strong>Phone:</strong> {{ auth()->user()->phone ?? '—' }}</p>
        <p><strong>Address:</strong> {{ auth()->user()->address ?? '—' }}</p>

        <div style="margin-top:12px;">
            <a class="btn-primary" href="{{ route('profile.edit') }}">Edit Profile</a>
        </div>
    </div>
</div>

@endsection
