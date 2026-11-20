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

        <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
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