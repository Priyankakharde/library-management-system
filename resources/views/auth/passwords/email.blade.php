{{-- resources/views/auth/passwords/email.blade.php --}}
@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="container" style="max-width:720px; margin:40px auto;">
    <div class="card p-4">
        <h4 class="mb-3">Reset Password</h4>

        @if (session('status'))
            <div class="alert alert-info">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                       name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Request Password Reset</button>
                <a href="{{ route('login') }}" class="btn btn-outline-secondary">Back to login</a>
            </div>
        </form>
    </div>
</div>
@endsection
