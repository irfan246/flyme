<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle ?? 'Dashboard' }} - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --air-blue-950:#071a3d; --air-blue-800:#0b3d91; --air-blue-600:#1565d8; --air-blue-100:#e8f2ff; --air-border:#d9e5f5; --air-ink:#12213b; }
        body { background: linear-gradient(180deg, #f7fbff, #edf5ff); color: var(--air-ink); font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        .sidebar { min-height: 100vh; background: linear-gradient(180deg, var(--air-blue-950), #0b2c69); box-shadow: 16px 0 40px rgba(7,26,61,.16); }
        .sidebar a { color: #dceaff; text-decoration: none; font-weight: 650; }
        .sidebar a:hover, .sidebar .active { color: #fff; background: rgba(255,255,255,.12); }
        .navbar { min-height: 72px; box-shadow: 0 12px 32px rgba(14, 70, 150, .07); }
        .card { border-radius: 18px; border: 1px solid var(--air-border) !important; box-shadow: 0 18px 45px rgba(15,54,110,.08) !important; }
        .metric { border: 0; border-radius: 18px; }
        .form-control, .form-select { border-radius: 12px; border-color: var(--air-border); min-height: 44px; }
        .btn { border-radius: 12px; font-weight: 700; }
        .btn-success, .text-bg-success { background: var(--air-blue-600) !important; border-color: var(--air-blue-600) !important; color: #fff !important; }
        .btn-outline-success { color: var(--air-blue-600); border-color: var(--air-blue-600); }
        .btn-outline-success:hover { background: var(--air-blue-600); color: #fff; }
        .table { --bs-table-bg: transparent; }
        .pagination { gap: .35rem; flex-wrap: wrap; }
        .page-link { border-radius: 10px; border-color: var(--air-border); color: var(--air-blue-600); font-weight: 700; }
        .active > .page-link, .page-link.active { background: var(--air-blue-600); border-color: var(--air-blue-600); }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <aside class="col-lg-3 col-xl-2 sidebar p-0">
                <div class="p-4 text-white border-bottom border-secondary">
                    <div class="fw-bold">Airline System</div>
                    <small class="text-secondary">{{ auth()->user()->role->display_name }}</small>
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
