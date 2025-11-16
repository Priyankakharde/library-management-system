{{-- resources/views/auth/passwords/reset.blade.php --}}
@extends('layouts.app')

@section('title', 'Set New Password')

@section('content')
<div class="container" style="max-width:720px; margin:40px auto;">
    <div class="card p-4">
        <h4 class="mb-3">Set New Password</h4>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            {{-- keep token hidden for compatibility (not validated in this simple impl) --}}
            <input type="hidden" name="token" value="{{ $token ?? '' }}">

            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email', $email) }}" required autofocus>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">New password</label>
                <input id="password" type="password" class="form-control" name="password" required>
            </div>

            <div class="mb-3">
                <label for="password-confirm" class="form-label">Confirm new password</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">Set Password</button>
                <a href="{{ route('login') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
