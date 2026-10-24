@extends('auth.layout')

@section('title', 'Register')

@section('content')
    <div class="auth-header text-center p-4">
        <h1 class="h3 mb-1">Create an Account</h1>
        <p class="mb-0">Register to get started</p>
    </div>

    <div class="card-body p-4">
        @if ($errors->any())
            <div class="alert alert-warning" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username"
                       class="form-control @error('username') is-invalid @enderror"
                       value="{{ old('username') }}"
                       placeholder="Paulo Delas Armas"
                       autocomplete="username" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}"
                       placeholder="username@example.com"
                       autocomplete="email" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Phone number</label>
                <input type="tel" id="phone" name="phone"
                       class="form-control @error('phone') is-invalid @enderror"
                       value="{{ old('phone') }}"
                       placeholder="e.g. 0917 123 4567"
                       autocomplete="tel" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password"
                       class="form-control" placeholder="Create a password"
                       autocomplete="new-password" required>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">
                    Confirm password
                </label>
                <input type="password" id="password_confirmation"
                       name="password_confirmation" class="form-control"
                       placeholder="Enter your password again"
                       autocomplete="new-password" required>
            </div>

            <div class="form-check mb-4">
                <input type="checkbox" id="terms" name="terms"
                       class="form-check-input" value="1"
                       @checked(old('terms')) required>
                <label for="terms" class="form-check-label">
                    I agree to the
                    <a href="#" class="auth-link">Terms of Service</a>
                    and
                    <a href="#" class="auth-link">Privacy Policy</a>.
                </label>
            </div>

            <button type="submit" class="btn btn-auth w-100">Register</button>
        </form>

        <p class="text-center mt-4 mb-0">
            Have an account?
            <a class="auth-link" href="{{ route('login') }}">Log in</a>
        </p>
    </div>
@endsection