<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Overclock Fitness POS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body class="bg-dark text-white">
    <nav class="navbar navbar-dark bg-black px-4">
        <span class="navbar-brand">Overclock Fitness POS</span>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-light">Log out</button>
        </form>
    </nav>

    <main class="container py-5">
        <h1>POS Dashboard</h1>
        <p>Welcome, {{ auth()->user()->username }}.</p>

        <div class="row g-3 mt-3">
            <div class="col-md-4"><div class="card p-4">New Sale</div></div>
            <div class="col-md-4"><div class="card p-4">Products</div></div>
            <div class="col-md-4"><div class="card p-4">Sales History</div></div>
            <div class="col-md-4"><div class="card p-4">Memberships</div></div>
        </div>
    </main>
</body>
</html>