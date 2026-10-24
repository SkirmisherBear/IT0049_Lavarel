<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Overclock Fitness | @yield('title', 'Account')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700&family=Roboto+Mono:wght@400;500;600;700&display=swap"
          rel="stylesheet">

    <style>
        :root {
            --sky-blue: #6ECBF5;
            --orchid: #C252E1;
            --lavender: #E0D9F6;
            --royal-blue: #586AE2;
            --midnight-blue: #2A2356;
        }

        body {
            min-height: 100vh;
            background:
                linear-gradient(rgba(42, 35, 86, 0.48), rgba(42, 35, 86, 0.48)),
                url("/images/fitness-background.jpg") center / cover no-repeat fixed;
            font-family: "Roboto Mono", monospace;
        }

        .brand-panel {
            color: white;
            text-shadow: 0 2px 12px rgba(0, 0, 0, 0.7);
        }

        .brand-name {
            font-family: "Orbitron", sans-serif;
            font-weight: 700;
            font-size: clamp(1.7rem, 3vw, 2.6rem);
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .brand-name span {
            color: var(--sky-blue);
        }

        .tagline {
            font-family: "Orbitron", sans-serif;
            font-weight: 700;
            font-size: clamp(1.2rem, 2vw, 1.8rem);
        }

        .tier-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            margin-top: 18px;
        }

        .tier-icon {
            width: 48px;
            height: 48px;
            flex: 0 0 48px;
            display: grid;
            place-items: center;
            background: rgba(194, 82, 225, 0.25);
            border-radius: 10px;
            font-size: 1.5rem;
        }

        .tier-item h3 {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .tier-item p {
            font-size: 0.85rem;
            margin: 0;
        }

        .auth-card {
            background: rgba(194, 82, 225, 0.24);
            border: none;
            border-radius: 20px;
            overflow: hidden;
            -webkit-backdrop-filter: blur(16px);
            backdrop-filter: blur(16px);
            box-shadow: 0 8px 32px rgba(42, 35, 86, 0.35);
        }

        .auth-header {
            background: rgba(194, 82, 225, 0.65);
            color: white;
            font-family: "Roboto Mono", monospace;
        }

        .auth-header h1 {
            font-family: "Orbitron", sans-serif;
        }

        .auth-card .card-body,
        .auth-card .form-label,
        .auth-card p {
            color: white;
        }

        .auth-card .form-control {
            background: rgba(255, 255, 255, 0.9);
        }

        .btn-auth {
            background: var(--royal-blue);
            border-color: var(--royal-blue);
            color: white;
        }

        .btn-auth:hover {
            background: var(--midnight-blue);
            border-color: var(--midnight-blue);
            color: white;
        }

        .auth-link {
            color: white;
        }

        .auth-link:hover {
            color: var(--sky-blue);
        }
    </style>
</head>
<body>
    <main class="container py-5">
        <div class="row min-vh-100 align-items-center justify-content-center g-5">
            <section class="col-12 col-lg-6 brand-panel">
                <div class="brand-name mb-3">
                    Overclock <span>Fitness</span>
                </div>

                <h1 class="tagline">Push beyond your factory settings.</h1>

                <p class="mb-4">
                    Join your gym community, book sessions, and track your
                    progress in one place.
                </p>

                <div class="tier-item">
                    <div class="tier-icon" aria-hidden="true">💿</div>
                    <div>
                        <h3>Stock Settings</h3>
                        <p>Base gym membership, open-gym locker usage, and entry-level cardio decks.</p>
                    </div>
                </div>

                <div class="tier-item">
                    <div class="tier-icon" aria-hidden="true">💧</div>
                    <div>
                        <h3>Liquid Cooled</h3>
                        <p>Unlimited hydration bar access, premium energy drinks, and hydro-massage beds to prevent “system overheating.”</p>
                    </div>
                </div>

                <div class="tier-item">
                    <div class="tier-icon" aria-hidden="true">🔥</div>
                    <div>
                        <h3>Max Overclock</h3>
                        <p>One-on-one sessions with a “System Engineer” (personal trainer) and priority access to high-demand power racks.</p>
                    </div>
                </div>
            </section>

            <section class="col-12 col-md-9 col-lg-5">
                <div class="card auth-card">
                    @yield('content')
                </div>
            </section>
        </div>
    </main>
</body>
</html>