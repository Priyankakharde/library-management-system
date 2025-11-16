@extends('layouts.app')

@section('title','Change Password')

@section('content')
<div class="panel" style="max-width:600px;margin:auto;">
    <h2 style="margin-top:0;">Change Password</h2>

    @if($errors->any())
        <div style="background:#ffecec;border:1px solid #f5b6b6;padding:10px;margin-bottom:12px;color:#8b1d1d;">
            <ul style="margin:0;padding-left:18px;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('profile.password.update') }}">
        @csrf

        <div style="margin-bottom:12px;">
            <label class="small">Current Password</label>
            <input type="password" name="current_password" class="form-input" required>
        </div>

        <div style="margin-bottom:12px;">
            <label class="small">New Password</label>
            <input type="password" name="password" class="form-input" required>
        </div>

        <div style="margin-bottom:12px;">
            <label class="small">Confirm New Password</label>
            <input type="password" name="password_confirmation" class="form-input" required>
        </div>

        <div style="margin-top:8px;">
            <button class="btn" type="submit">Change Password</button>
            <a class="btn-blue" href="{{ route('profile.show') }}" style="margin-left:8px;">Back</a>
        </div>
    </form>
</div>
@endsection
