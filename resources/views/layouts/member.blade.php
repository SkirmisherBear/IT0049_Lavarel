<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Member Portal') — Overclock Fitness</title>
    <script>
        (function () {
            const saved = localStorage.getItem('oc_theme') || 'dark';
            document.documentElement.setAttribute('data-theme', saved);
        })();
    </script>
    <style>
        :root {
            --bg-main: #181124;
            --bg-card: #211732;
            --bg-input: #150E20;
            --border: rgba(154, 123, 214, 0.22);
            --text: #F4F1FA;
            --muted: #A498BD;
            --purple: #8B42B3;
            --indigo: #4E60D8;
            --cyan: #76C9E8;
            --danger: #E05263;
            --ok: #38C172;
        }
        [data-theme="light"] {
            --bg-main: #F3F1F8;
            --bg-card: #FFFFFF;
            --bg-input: #F7F5FC;
            --border: rgba(78, 96, 216, 0.18);
            --text: #1E162B;
            --muted: #6A5E80;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: var(--bg-main);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .member-navbar {
            background: linear-gradient(90deg, #5F359B 0%, #4E60D8 100%);
            padding: 14px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.28);
            position: sticky;
            top: 0;
            z-index: 40;
        }
        .member-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #FFFFFF;
        }
        .member-badge {
            width: 38px;
            height: 38px;
            border-radius: 11px;
            background: rgba(255,255,255,0.18);
            display: grid;
            place-items: center;
            font-size: 18px;
            font-weight: 800;
        }
        .member-brand-text {
            font-weight: 800;
            font-size: 15px;
            letter-spacing: 0.8px;
            line-height: 1.15;
        }
        .member-brand-text span {
            font-size: 11px;
            font-weight: 700;
            color: #B9ECFF;
            letter-spacing: 1.4px;
        }
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .pill-btn {
            background: rgba(255,255,255,0.16);
            border: 1px solid rgba(255,255,255,0.28);
            color: #FFFFFF;
            padding: 7px 14px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s;
        }
        .pill-btn:hover { background: rgba(255,255,255,0.28); }
        .pill-logout {
            background: rgba(224, 82, 99, 0.25);
            border-color: rgba(255, 160, 170, 0.45);
        }
        .pill-logout:hover { background: rgba(224, 82, 99, 0.5); }

        .member-container {
            width: 100%;
            max-width: 1180px;
            margin: 0 auto;
            padding: 32px 24px 60px;
            flex: 1;
        }
        .page-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 24px;
        }
        .page-heading h1 { margin: 0 0 4px; font-size: 26px; }
        .muted { color: var(--muted); font-size: 13px; margin: 0; }
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 22px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.16);
        }
        .panel { margin-top: 24px; }
        .panel-title {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 16px;
        }
        .form-grid-2 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 20px;
        }
        .form-group { margin-bottom: 14px; }
        .form-group label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--muted);
            margin-bottom: 6px;
        }
        .form-control {
            width: 100%;
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: var(--bg-input);
            color: var(--text);
            font-size: 13px;
        }
        .btn {
            background: linear-gradient(90deg, var(--purple), var(--indigo));
            color: #FFFFFF;
            border: none;
            padding: 11px 18px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
        }
        .btn:hover { opacity: 0.92; }
        .btn-danger {
            background: rgba(224, 82, 99, 0.18);
            border: 1px solid rgba(224, 82, 99, 0.45);
            color: #FF8A98;
            padding: 6px 12px;
            font-size: 12px;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
        }
        .badge-ok { background: rgba(56, 193, 114, 0.16); color: #5CE696; border: 1px solid rgba(56, 193, 114, 0.35); }
        .badge-low { background: rgba(224, 82, 99, 0.16); color: #FF8A98; border: 1px solid rgba(224, 82, 99, 0.35); }
        .alert-success {
            background: rgba(56, 193, 114, 0.16);
            border: 1px solid rgba(56, 193, 114, 0.4);
            color: #5CE696;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 13px;
            font-weight: 600;
        }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th {
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--muted);
            padding: 10px 12px;
            border-bottom: 1px solid var(--border);
        }
        td {
            padding: 12px;
            border-bottom: 1px solid var(--border);
        }
    </style>
</head>
<body>
    <header class="member-navbar">
        <a href="{{ route('member.portal') }}" class="member-brand">
            <div class="member-badge">⚡</div>
            <div class="member-brand-text">
                OVERCLOCK FITNESS<br>
                <span>MEMBER PORTAL</span>
            </div>
        </a>

        <div class="nav-actions">
            <button type="button" class="pill-btn" id="themeToggle">☀ Light Mode</button>
            <span class="pill-btn" style="cursor:default;">👤 {{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                @csrf
                <button type="submit" class="pill-btn pill-logout">↪ Log out</button>
            </form>
        </div>
    </header>

    <main class="member-container">
        @if(session('success'))
            <div class="alert-success">✓ {{ session('success') }}</div>
        @endif

        @yield('content')
    </main>

    <script>
        const toggleBtn = document.getElementById('themeToggle');
        function syncLabel() {
            const isLight = document.documentElement.getAttribute('data-theme') === 'light';
            toggleBtn.textContent = isLight ? '🌙 Dark Mode' : '☀ Light Mode';
        }
        syncLabel();
        toggleBtn.addEventListener('click', () => {
            const next = document.documentElement.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', next);
            localStorage.setItem('oc_theme', next);
            syncLabel();
        });
    </script>
</body>
</html>