<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Dashboard') | Overclock Fitness POS</title>

    <script>
        try {
            document.documentElement.dataset.theme =
                localStorage.getItem('overclock-theme') || 'light';
        } catch (e) {}
    </script>

        <link rel="stylesheet" href="{{ asset('css/pos.css') }}">
</head>

<body>

<div class="app-layout">

    <aside class="sidebar">

        <div class="brand">
            <div class="brand-mark">⚡</div>
            <div class="brand-name">OVERCLOCK <span>POS</span></div>
        </div>

        <div class="sidebar-label">WORKSPACE</div>

        <a href="{{ route('pos.dashboard') }}"
           class="nav-link {{ request()->routeIs('pos.dashboard') ? 'active' : '' }}">
            <span class="nav-icon">▦</span>
            <span class="nav-text">Dashboard</span>
        </a>

        <a href="{{ route('products.index') }}"
           class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
            <span class="nav-icon">▤</span>
            <span class="nav-text">Products</span>
        </a>

        <a href="{{ route('customers.index') }}"
           class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
            <span class="nav-icon">♙</span>
            <span class="nav-text">Customers</span>
        </a>

        <a href="{{ route('sales.create') }}"
           class="nav-link {{ request()->routeIs('sales.create') ? 'active' : '' }}">
            <span class="nav-icon">🛒</span>
            <span class="nav-text">New Sale</span>
        </a>

        <a href="{{ route('sales.index') }}"
           class="nav-link {{ request()->routeIs('sales.index') ? 'active' : '' }}">
            <span class="nav-icon">▧</span>
            <span class="nav-text">Sales History</span>
        </a>

        <div class="sidebar-bottom">
            <div class="sidebar-label">ACCOUNT</div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link logout-button">
                    <span class="nav-icon">⇥</span>
                    <span class="nav-text">Log out</span>
                </button>
            </form>
        </div>

    </aside>

    <div class="main-column">

        <header class="topbar">
            <div class="topbar-title">@yield('heading', 'Dashboard')</div>

            <div class="topbar-right">
                <button type="button" class="theme-button" id="themeToggle">
                    ☾ Dark Mode
                </button>

                <span class="user-name">
                    {{ auth()->user()->username ?? auth()->user()->name ?? 'Staff' }}
                </span>
            </div>
        </header>

        <main class="content">

            @if(session('success'))
                <div class="alert">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="alert">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @yield('content')

        </main>

    </div>

</div>

<script>
    const root = document.documentElement;
    const toggle = document.getElementById('themeToggle');

    function setTheme(theme) {
        root.dataset.theme = theme;

        try {
            localStorage.setItem('overclock-theme', theme);
        } catch (e) {}

        toggle.textContent = theme === 'dark'
            ? '☀ Light Mode'
            : '☾ Dark Mode';
    }

    setTheme(root.dataset.theme || 'light');

    toggle.addEventListener('click', function () {
        setTheme(root.dataset.theme === 'dark' ? 'light' : 'dark');
    });
</script>

</body>
</html>