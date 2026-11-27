@extends('auth.layout')

@section('title', 'Log out')

@section('content')
    <div class="auth-header text-center p-4">
        <h1 class="h3 mb-1">Log out</h1>
        <p class="mb-0">Confirm to end your session</p>
    </div>

    <div class="card-body p-4">
        <p class="text-center">You are signed in as {{ auth()->user()->name }}.</p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-auth w-100">Confirm log out</button>
        </form>

        <p class="text-center mt-4 mb-0">
            <a class="auth-link" href="{{ url('/') }}">Return to my dashboard</a>
        </p>
    </div>
@endsection
