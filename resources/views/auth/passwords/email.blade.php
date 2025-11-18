@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="d-flex justify-content-center align-items-start" style="min-height:60vh; padding-top:40px;">
    <div style="width:420px;">
        <div class="card card-pro">
            <div class="card-body">
                <h3 class="mb-3">Login</h3>

                @if(session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('login.attempt') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" type="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', 'admin@lms.test') }}" required autofocus>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               required>
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label muted" for="remember">Remember me</label>
                        </div>

                        <div>
                            <a href="{{ route('password.request') }}" class="small-muted">Forgot password?</a>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button class="btn btn-primary">Sign in</button>
                    </div>
                </form>

                <div class="mt-3 small-muted">
                    Demo admin: <strong>admin@lms.test</strong> / <strong>password</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
