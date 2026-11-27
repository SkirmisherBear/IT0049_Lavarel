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

    <style>
        :root {
            color-scheme: light;
            --bg: #F4EFFB;
            --surface: #FFFFFF;
            --surface-soft: #EDE5F7;
            --text: #251734;
            --muted: #6C5B7E;
            --border: #DCD0EC;
            --primary: #8B42B3;
            --primary-hover: #733298;
            --blue: #4E60D8;
            --cyan: #0E8AB8;
            --sidebar: #FFFFFF;
            --sidebar-text: #2D1E3E;
            --sidebar-muted: #7B6A8E;
            --sidebar-hover: #EFE6FA;
            --sidebar-border: #E4D9F2;
            --success: #257A60;
            --danger: #C24666;
            --warning: #B37728;
            --shadow: 0 8px 26px rgba(68, 36, 98, .07);
        }

        [data-theme="dark"] {
            color-scheme: dark;
            --bg: #1B1426;
            --surface: #2D253B;
            --surface-soft: #382D49;
            --text: #F7F1FC;
            --muted: #C0B2CF;
            --border: #493951;
            --primary: #AE68CC;
            --primary-hover: #BF7DDA;
            --blue: #7584EC;
            --cyan: #76C9E8;
            --sidebar: #21172F;
            --sidebar-text: #F9F4FF;
            --sidebar-muted: #B9A2CC;
            --sidebar-hover: #493456;
            --sidebar-border: rgba(255, 255, 255, 0.06);
            --success: #73C6A5;
            --danger: #E28BA0;
            --warning: #E1B56C;
            --shadow: 0 6px 22px rgba(0, 0, 0, .22);
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            min-height: 100%;
            width: 100%;
        }

        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background: var(--bg);
            color: var(--text);
            overflow-x: hidden;
            transition: background .2s, color .2s;
        }

        /* OVERCLOCK AMBIENT TECH BACKGROUND (DARK MODE) */
        [data-theme="dark"] body {
            background-color: #1B1426;
            background-image:
                radial-gradient(circle at 18% 15%, rgba(174, 104, 204, 0.14), transparent 38%),
                radial-gradient(circle at 88% 82%, rgba(118, 201, 232, 0.10), transparent 40%),
                linear-gradient(rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
            background-size: 100% 100%, 100% 100%, 36px 36px, 36px 36px;
        }

        /* OVERCLOCK AMBIENT TECH BACKGROUND (LIGHT MODE) */
        [data-theme="light"] body {
            background-color: #F4EFFB;
            background-image:
                radial-gradient(circle at 18% 15%, rgba(150, 80, 185, 0.10), transparent 40%),
                radial-gradient(circle at 88% 82%, rgba(78, 96, 216, 0.09), transparent 42%),
                linear-gradient(rgba(139, 66, 179, 0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(139, 66, 179, 0.04) 1px, transparent 1px);
            background-size: 100% 100%, 100% 100%, 36px 36px, 36px 36px;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button, input, select {
            font: inherit;
        }

        /* MAIN GRID: SIDEBAR + CONTENT */
        .app-layout {
            display: grid;
            grid-template-columns: 248px minmax(0, 1fr);
            width: 100%;
            min-height: 100vh;
            align-items: start;
        }

        /* ADAPTIVE SIDEBAR */
        .sidebar {
            position: sticky;
            top: 0;
            height: 100vh;
            width: 248px;
            background: var(--sidebar);
            color: var(--sidebar-text);
            padding: 28px 17px;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            z-index: 20;
            border-right: 1px solid var(--sidebar-border);
            transition: background .2s, color .2s, border-color .2s;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 5px 9px;
            margin-bottom: 38px;
            font-weight: 800;
            font-size: 18px;
            letter-spacing: .5px;
        }

        .brand-mark {
            display: grid;
            place-items: center;
            width: 36px;
            height: 36px;
            flex-shrink: 0;
            border-radius: 10px;
            background: linear-gradient(135deg, #AB51C2, #526BDF);
            color: white;
            box-shadow: 0 4px 14px rgba(171, 81, 194, 0.35);
        }

        .brand-name span {
            color: var(--cyan);
        }

        .sidebar-label {
            color: var(--sidebar-muted);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.4px;
            padding: 0 12px;
            margin: 13px 0 12px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 13px;
            margin-bottom: 5px;
            border-radius: 9px;
            color: var(--sidebar-text);
            font-size: 13px;
            font-weight: 500;
            transition: .2s;
        }

        .nav-icon {
            width: 19px;
            text-align: center;
            flex-shrink: 0;
        }

        .nav-link:hover,
        .nav-link.active {
            background: var(--sidebar-hover);
            color: var(--primary);
            font-weight: 650;
        }

        [data-theme="dark"] .nav-link:hover,
        [data-theme="dark"] .nav-link.active {
            color: #FFFFFF;
        }

        .nav-link.active {
            border-left: 3px solid var(--cyan);
            padding-left: 10px;
        }

        .sidebar-bottom {
            margin-top: auto;
            padding-top: 20px;
        }

        .logout-button {
            border: 0;
            background: transparent;
            cursor: pointer;
            width: 100%;
            text-align: left;
        }

        /* MAIN COLUMN */
        .main-column {
            min-width: 0;
            width: 100%;
        }

        /* TOP HEADER: ALWAYS ABOVE CONTENT */
        .topbar {
            min-height: 76px;
            width: 100%;
            padding: 14px 30px;
            background: linear-gradient(110deg, #7742A0, #4F62C9);
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            position: sticky;
            top: 0;
            z-index: 15;
            box-shadow: 0 3px 15px rgba(46, 28, 69, .18);
        }

        .topbar-title {
            font-size: 16px;
            font-weight: 700;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .user-name {
            color: #F4EAFE;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .role-pill {
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.6px;
            background: rgba(255, 255, 255, 0.2);
            color: #FFFFFF;
            text-transform: uppercase;
        }

        .theme-button {
            border: 1px solid rgba(255,255,255,.3);
            border-radius: 9px;
            background: rgba(255,255,255,.15);
            color: white;
            padding: 9px 13px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: .2s;
        }

        .theme-button:hover {
            background: rgba(255,255,255,.25);
        }

        /* PAGE CONTENT */
        .content {
            width: 100%;
            max-width: 1550px;
            min-width: 0;
            margin: 0 auto;
            padding: 32px;
        }

        .page-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 27px;
        }

        .page-heading h1 {
            font-size: 27px;
            line-height: 1.25;
            margin: 0 0 8px;
            font-weight: 750;
        }

        .muted {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.6;
        }

        /* BUTTONS */
        .btn {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 7px;
            background: linear-gradient(110deg, var(--primary), var(--blue));
            color: white;
            padding: 11px 17px;
            border-radius: 9px;
            border: 0;
            font-size: 12px;
            font-weight: 650;
            cursor: pointer;
            white-space: nowrap;
            transition: .2s;
            box-shadow: 0 4px 12px rgba(117, 132, 236, 0.22);
        }

        .btn:hover {
            filter: brightness(1.08);
            color: white;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: var(--surface-soft);
            color: var(--text);
            border: 1px solid var(--border);
            box-shadow: none;
        }

        .btn-secondary:hover {
            border-color: var(--primary);
            color: var(--text);
        }

        .btn-danger {
            background: rgba(226, 139, 160, 0.16);
            color: var(--danger);
            border: 1px solid rgba(226, 139, 160, 0.35);
            box-shadow: none;
        }

        .btn-danger:hover {
            background: var(--danger);
            color: #fff;
        }

        /* STAT CARDS */
        .stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
            width: 100%;
            min-width: 0;
        }

        .card {
            min-width: 0;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 22px;
            box-shadow: var(--shadow);
            transition: transform 0.18s ease, border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            border-color: rgba(118, 201, 232, 0.35);
        }

        .stat-icon {
            width: 43px;
            height: 43px;
            display: grid;
            place-items: center;
            background: var(--surface-soft);
            color: var(--primary);
            border-radius: 11px;
            margin-bottom: 16px;
            font-size: 20px;
        }

        .stat-label {
            color: var(--muted);
            font-size: 12px;
        }

        .stat-value {
            font-size: 26px;
            font-weight: 750;
            margin-top: 9px;
            overflow-wrap: anywhere;
        }

        /* TABLES */
        .panel {
            width: 100%;
            min-width: 0;
            margin-top: 24px;
        }

        .panel-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 18px;
        }

        .table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 650px;
            border-collapse: collapse;
            font-size: 12.5px;
        }

        th {
            background: var(--surface-soft);
            color: var(--muted);
            text-align: left;
            padding: 14px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .7px;
        }

        td {
            padding: 15px 14px;
            border-bottom: 1px solid var(--border);
        }

        tr:hover td {
            background: rgba(174, 104, 204, 0.05);
        }

        .actions-cell {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        /* STATUS BADGES */
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        .badge-ok {
            background: rgba(115, 198, 165, 0.16);
            color: var(--success);
            border: 1px solid rgba(115, 198, 165, 0.35);
        }

        .badge-low {
            background: rgba(226, 139, 160, 0.16);
            color: var(--danger);
            border: 1px solid rgba(226, 139, 160, 0.35);
        }

        /* SLEEK FORM CONTROLS & CUSTOM DROPDOWNS */
        .form-card {
            max-width: 680px;
            padding: 28px;
        }

        .form-grid-2 {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 8px;
            color: var(--muted);
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: var(--surface-soft);
            color: var(--text);
            font-size: 13.5px;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }

        .form-control:hover {
            border-color: var(--primary);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--cyan);
            box-shadow: 0 0 0 3px rgba(14, 138, 184, 0.18);
        }

        /* CUSTOM STYLED SELECT DROPDOWN */
        select.form-control {
            appearance: none;
            -webkit-appearance: none;
            padding-right: 40px;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2376C9E8' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            background-size: 16px;
        }

        [data-theme="light"] select.form-control {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%238B42B3' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        }

        [data-theme="dark"] select.form-control option {
            background: #2D253B;
            color: #F7F1FC;
        }

        [data-theme="light"] select.form-control option {
            background: #FFFFFF;
            color: #251734;
        }

        /* COOL FILE UPLOAD BUTTON */
        input[type="file"].form-control {
            padding: 9px 12px;
            border-style: dashed;
            cursor: pointer;
        }

        input[type="file"].form-control::file-selector-button {
            background: linear-gradient(110deg, var(--primary), var(--blue));
            color: #fff;
            border: 0;
            padding: 7px 14px;
            margin-right: 12px;
            border-radius: 7px;
            font-size: 12px;
            font-weight: 650;
            cursor: pointer;
            transition: filter 0.2s;
        }

        input[type="file"].form-control::file-selector-button:hover {
            filter: brightness(1.12);
        }

        /* QUANTITY STEPPER & CHECKOUT SUMMARY */
        .qty-stepper {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .qty-btn {
            width: 44px;
            height: 44px;
            flex-shrink: 0;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: var(--surface-soft);
            color: var(--cyan);
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.15s;
        }

        .qty-btn:hover {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
        }

        .checkout-summary {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 20px;
            border-radius: 12px;
            background: linear-gradient(110deg, rgba(174, 104, 204, 0.14), rgba(117, 132, 236, 0.14));
            border: 1px solid rgba(14, 138, 184, 0.35);
            margin: 20px 0;
        }

        .alert {
            padding: 13px 16px;
            border-radius: 10px;
            background: var(--surface-soft);
            border: 1px solid var(--border);
            margin-bottom: 17px;
            font-size: 13px;
        }

        /* TABLET */
        @media (max-width: 1150px) {
            .app-layout {
                grid-template-columns: 215px minmax(0, 1fr);
            }

            .sidebar {
                width: 215px;
            }

            .content {
                padding: 25px 22px;
            }

            .stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        /* MOBILE */
        @media (max-width: 700px) {
            .app-layout {
                grid-template-columns: 68px minmax(0, 1fr);
            }

            .sidebar {
                width: 68px;
                padding: 20px 7px;
            }

            .brand {
                justify-content: center;
                padding: 5px 0;
            }

            .brand-name,
            .sidebar-label,
            .nav-text {
                display: none;
            }

            .nav-link {
                justify-content: center;
                padding: 13px 4px;
            }

            .topbar {
                padding: 12px 13px;
            }

            .content {
                padding: 22px 14px;
            }

            .page-heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .form-grid-2 {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .stats {
                grid-template-columns: minmax(0, 1fr);
            }

            .user-name {
                display: none;
            }

            .topbar-title {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>

<div class="app-layout">

    <aside class="sidebar">

        <div class="brand">
            <div class="brand-mark">⚡</div>
            <div class="brand-name">OVERCLOCK <span>POS</span></div>
        </div>

        <div class="sidebar-label">{{ auth()->user()?->role === 'admin' ? 'MANAGEMENT' : 'STAFF WORKSPACE' }}</div>

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
            <span class="nav-text">{{ auth()->user()?->role === 'admin' ? 'All Sales' : 'My Sales' }}</span>
        </a>

        @if(auth()->user()?->role === 'admin')
            <div class="sidebar-label">ADMINISTRATION</div>

            <a href="{{ route('staff.index') }}"
               class="nav-link {{ request()->routeIs('staff.*') ? 'active' : '' }}">
                <span class="nav-icon">⚙</span>
                <span class="nav-text">Staff & Admins</span>
            </a>
        @endif

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
                    {{ auth()->user()->name ?? auth()->user()->username ?? 'Staff' }}
                    <span class="role-pill">{{ auth()->user()->role ?? 'staff' }}</span>
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
