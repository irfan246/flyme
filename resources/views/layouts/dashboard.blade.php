<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle ?? 'Dashboard' }} - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --mk-ink:#17211f; --mk-ink-2:#273532; --mk-muted:#6c7772; --mk-line:#dbe3de; --mk-paper:#fbfaf6; --mk-soft:#f1f6f0; --mk-teal:#08766f; --mk-coral:#ee6b4d; --mk-coral-dark:#d65338; --mk-amber:#f3b95f; }
        body { background: linear-gradient(180deg, var(--mk-paper), #f1eee7); color: var(--mk-ink); font-family: "Plus Jakarta Sans", Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        .sidebar { min-height: 100vh; background: linear-gradient(180deg, #17211f, #24332f); box-shadow: 16px 0 40px rgba(23,33,31,.14); }
        .sidebar a { color: rgba(255,255,255,.72); text-decoration: none; font-weight: 700; border-radius: 10px; padding: .72rem .85rem; }
        .sidebar a:hover, .sidebar .active { color: #fff; background: rgba(10,167,157,.18); }
        .navbar { min-height: 72px; box-shadow: 0 12px 32px rgba(31,42,37,.06); }
        .navbar.bg-white { background: rgba(251,250,246,.9) !important; backdrop-filter: blur(16px); }
        .card { border-radius: 12px; border: 1px solid var(--mk-line) !important; box-shadow: 0 18px 42px rgba(31,42,37,.08) !important; background: rgba(255,255,255,.78); }
        .metric { border: 0; border-radius: 12px; overflow: hidden; }
        .metric::before { content: ""; display: block; height: 4px; background: linear-gradient(90deg, var(--mk-teal), var(--mk-amber), var(--mk-coral)); }
        .form-control, .form-select { border-radius: 10px; border-color: var(--mk-line); min-height: 44px; background-color: #fffdf9; }
        .form-control:focus, .form-select:focus { border-color: var(--mk-teal); box-shadow: 0 0 0 .2rem rgba(8,118,111,.14); }
        .btn { border-radius: 10px; font-weight: 750; }
        .btn-success, .text-bg-success { background: var(--mk-coral) !important; border-color: var(--mk-coral) !important; color: #fff !important; }
        .btn-success:hover { background: var(--mk-coral-dark) !important; border-color: var(--mk-coral-dark) !important; }
        .btn-outline-success { color: var(--mk-teal); border-color: var(--mk-teal); }
        .btn-outline-success:hover { background: var(--mk-teal); color: #fff; }
        .table { --bs-table-bg: transparent; }
        .table thead th { color: var(--mk-muted); font-size: .78rem; text-transform: uppercase; letter-spacing: .04em; }
        .text-muted { color: var(--mk-muted) !important; }
        .badge { border-radius: 999px; padding: .45rem .7rem; }
        .pagination { gap: .35rem; flex-wrap: wrap; }
        .page-link { border-radius: 10px; border-color: var(--mk-line); color: var(--mk-teal); font-weight: 700; }
        .active > .page-link, .page-link.active { background: var(--mk-teal); border-color: var(--mk-teal); }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <aside class="col-lg-3 col-xl-2 sidebar p-0">
                <div class="p-4 text-white border-bottom border-secondary border-opacity-25">
                    <div class="fw-bold fs-5">Maskapai</div>
                    <small class="text-white-50">{{ auth()->user()->role->display_name }}</small>
                </div>
                <nav class="p-3 d-grid gap-1">
                    @include('partials.sidebar')
                </nav>
            </aside>
            <section class="col-lg-9 col-xl-10 p-0">
                <nav class="navbar bg-white border-bottom px-4">
                    <span class="navbar-brand mb-0 h1">{{ $pageTitle ?? 'Dashboard' }}</span>
                    <div class="d-flex align-items-center gap-3">
                        <span class="small text-muted">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-outline-danger btn-sm">Logout</button>
                        </form>
                    </div>
                </nav>
                @include('partials.flash')
                <main class="p-4">
                    @yield('content')
                </main>
            </section>
        </div>
    </div>
</body>
</html>
