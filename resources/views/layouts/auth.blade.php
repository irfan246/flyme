<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Auth' }} - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --mk-ink:#17211f; --mk-line:#dbe3de; --mk-paper:#fbfaf6; --mk-teal:#08766f; --mk-coral:#ee6b4d; --mk-coral-dark:#d65338; --mk-amber:#f3b95f; }
        body {
            background:
                radial-gradient(circle at 18% 12%, rgba(243,185,95,.26), transparent 28%),
                radial-gradient(circle at 82% 18%, rgba(8,118,111,.24), transparent 26%),
                linear-gradient(135deg, #17211f, #2f3d38);
            font-family: "Plus Jakarta Sans", Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: var(--mk-ink);
        }
        .card { border-radius: 14px; border: 1px solid rgba(255,255,255,.58); box-shadow: 0 24px 70px rgba(23,33,31,.28); background: rgba(251,250,246,.94); }
        .form-control { border-radius: 10px; min-height: 46px; border-color: var(--mk-line); background-color: #fffdf9; }
        .form-control:focus { border-color: var(--mk-teal); box-shadow: 0 0 0 .2rem rgba(8,118,111,.14); }
        .btn-success { background: var(--mk-coral); border-color: var(--mk-coral); border-radius: 10px; font-weight: 750; }
        .btn-success:hover { background: var(--mk-coral-dark); border-color: var(--mk-coral-dark); }
        a { color: var(--mk-teal); }
    </style>
</head>
<body>
    <main class="min-vh-100 d-flex align-items-center py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-5">
                    @include('partials.flash')
                    @yield('content')
                </div>
            </div>
        </div>
    </main>
</body>
</html>
