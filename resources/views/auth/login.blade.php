@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container" style="max-width:480px; margin:40px auto;">
    <div class="card p-4">
        <h4 class="mb-3">Login</h4>

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.attempt') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input name="email" type="email" class="form-control" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input name="password" type="password" class="form-control" required>
            </div>

            <div class="mb-3 d-flex justify-content-between align-items-center">
                <div>
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember"> Remember me</label>
                </div>
                <div>
                    <a href="{{ route('password.request') }}">Forgot password?</a>
                </div>
            </div>

            <div>
                <button class="btn btn-primary">Sign in</button>
            </div>
        </form>
    </div>
</div>
@endsection
