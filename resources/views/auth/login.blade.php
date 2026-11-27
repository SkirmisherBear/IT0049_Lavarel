@extends('auth.layout')

@section('title', 'Login')

@section('content')
    <div class="auth-header text-center p-4">
        <h1 class="h3 mb-1">Welcome Back</h1>
        <p class="mb-0">Log in to your account</p>
    </div>

    <div class="card-body p-4">
        <form method="POST" action="{{ route('login.store') }}" autocomplete="off">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email"
                       class="form-control"
                       value=""
                       autocomplete="off"
                       readonly
                       onfocus="this.removeAttribute('readonly');"
                       required>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password"
                       class="form-control"
                       value=""
                       autocomplete="new-password"
                       readonly
                       onfocus="this.removeAttribute('readonly');"
                       required>
            </div>

            <button type="submit" class="btn btn-auth w-100">Log in</button>
        </form>

        <p class="text-center mt-4 mb-0">
            Don’t have an account?
            <a class="auth-link" href="{{ route('register') }}">Register</a>
        </p>
    </div>

    <script>
        window.addEventListener('pageshow', function () {
            document.getElementById('email').value = '';
            document.getElementById('password').value = '';
        });
    </script>
@endsection