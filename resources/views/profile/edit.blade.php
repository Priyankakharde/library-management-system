@extends('layouts.lms')

@section('title', 'Edit Profile')

@section('lms-content')

<h2>✏️ Edit Profile</h2>

<div class="card">
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label>Name</label>
        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required>

        <label>Email</label>
        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>

        <label>Phone</label>
        <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}">

        <label>Address</label>
        <textarea name="address">{{ old('address', auth()->user()->address) }}</textarea>

        <label>Avatar</label>
        @if(auth()->user()->avatar)
            <div style="margin-bottom:8px;">
                <img src="{{ asset('images/avatars/' . auth()->user()->avatar) }}" alt="avatar" style="width:90px;height:90px;object-fit:cover;border-radius:6px;">
            </div>
        @endif
        <input type="file" name="avatar" accept="image/*">

        <div style="margin-top:12px;">
            <button class="btn-primary">Save Profile</button>
            <a class="btn" href="{{ route('profile.show') }}">Cancel</a>
        </div>
    </form>
</div>

@endsection
