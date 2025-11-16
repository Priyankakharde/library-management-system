@extends('layouts.app')

@section('title', $title ?? 'LMS Page')

@section('content')

<div class="lms-container">

    <!-- Page Heading -->
    <h1 class="page-title">
        {{ $title ?? 'Page' }}
    </h1>

    <!-- LMS Inner Content Area -->
    <div class="lms-content">
        @yield('lms-content')
    </div>

</div>

@endsection
