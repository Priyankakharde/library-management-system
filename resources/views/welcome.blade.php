@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
<div style="padding:40px;">
    <h1 style="font-weight:700;">Welcome to the Library Management System</h1>
    <p class="text-muted" style="font-size:17px; margin-top:10px;">
        This is the public landing page of the LMS.
    </p>

    <div style="margin-top:25px;">
        <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
            Go to Login
        </a>
    </div>
</div>
@endsection
