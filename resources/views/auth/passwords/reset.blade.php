@extends('layouts.app')
@section('content')
<div class="container" style="padding-top:40px">
  <div class="card card-pro" style="max-width:600px;margin:0 auto">
    <div class="card-body">
      <h3>Set new password</h3>
      <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
        <div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" required></div>
        <div class="mb-3"><label>Confirm Password</label><input type="password" name="password_confirmation" class="form-control" required></div>
        <button class="btn btn-primary">Reset password</button>
      </form>
    </div>
  </div>
</div>
@endsection
