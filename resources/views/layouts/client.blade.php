<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Member Area') | Overclock Fitness</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/client.css') }}">
</head>
<body>
<div class="client-layout">
    <aside class="client-sidebar">
        <a class="client-brand" href="{{ route('client.dashboard') }}"><span>⚡</span> OVERCLOCK <b>FITNESS</b></a>
        <div class="client-nav-label">MEMBER AREA</div>
        <nav class="client-nav">
            <a class="{{ request()->routeIs('client.dashboard') ? 'active' : '' }}" href="{{ route('client.dashboard') }}">▦ <span>Dashboard</span></a>
            <a class="{{ request()->routeIs('client.bookings*') ? 'active' : '' }}" href="{{ route('client.bookings') }}">◷ <span>My Bookings</span></a>
            <a class="{{ request()->routeIs('client.progress*') ? 'active' : '' }}" href="{{ route('client.progress') }}">↗ <span>My Progress</span></a>
            <a class="{{ request()->routeIs('client.profile*') ? 'active' : '' }}" href="{{ route('client.profile.edit') }}">♙ <span>Profile</span></a>
            <a class="{{ request()->routeIs('client.store*') ? 'active' : '' }}" href="{{ route('client.store') }}">🛍 <span>Store</span></a>
        </nav>
        <form method="POST" action="{{ route('logout') }}" class="client-logout">
            @csrf
            <button type="submit">⇥ <span>Log out</span></button>
        </form>
    </aside>

    <main class="client-main">
        <header class="client-topbar">
            <div>@yield('heading', 'Dashboard')</div>
            <span>{{ auth()->user()->username ?? auth()->user()->name }}</span>
        </header>
        <section class="client-content">
            @if (session('success'))
                <div class="client-alert success" role="status">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="client-alert error" role="alert">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="client-alert error" role="alert">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
            @yield('content')
        </section>
    </main>
</div>
@stack('scripts')
</body>
</html>
