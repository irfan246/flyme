<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --mk-ink: #17211f;
            --mk-ink-2: #273532;
            --mk-muted: #6c7772;
            --mk-line: #dbe3de;
            --mk-paper: #fbfaf6;
            --mk-soft: #f1f6f0;
            --mk-teal: #08766f;
            --mk-teal-2: #0aa79d;
            --mk-coral: #ee6b4d;
            --mk-coral-dark: #d65338;
            --mk-amber: #f3b95f;
            --mk-shadow: 0 18px 45px rgba(31, 42, 37, .09);
        }
        body {
            background:
                linear-gradient(115deg, rgba(8,118,111,.05), transparent 34%),
                linear-gradient(180deg, var(--mk-paper) 0%, #f6f3eb 100%);
            color: var(--mk-ink);
            font-family: "Plus Jakarta Sans", Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
        .navbar { backdrop-filter: blur(18px); box-shadow: 0 10px 35px rgba(31,42,37,.06); }
        .navbar.bg-white { background: rgba(251,250,246,.86) !important; }
        .brand-mark { width: 42px; height: 42px; border-radius: 12px; background: var(--mk-ink); display: inline-grid; place-items: center; color: var(--mk-amber); font-weight: 900; box-shadow: 0 12px 26px rgba(23,33,31,.18); }
        .hero {
            min-height: 700px;
            color: var(--mk-ink);
            background:
                radial-gradient(circle at 16% 18%, rgba(238,107,77,.14), transparent 24%),
                linear-gradient(115deg, rgba(251,250,246,.98) 0%, rgba(251,250,246,.9) 42%, rgba(8,118,111,.2) 100%),
                url('https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=1800&q=82');
            background-size: cover;
            background-position: center;
            position: relative;
            overflow: hidden;
        }
        .hero::after {
            content: "";
            position: absolute;
            inset: auto -8% -42% 28%;
            height: 420px;
            border: 1px solid rgba(8,118,111,.18);
            border-radius: 50%;
            transform: rotate(-8deg);
            pointer-events: none;
        }
        .hero .container { min-height: 700px; display: flex; align-items: center; position: relative; z-index: 1; }
        .hero-copy { max-width: 720px; }
        .hero-search { background: rgba(255,255,255,.9); border: 1px solid rgba(219,227,222,.86); border-radius: 14px; box-shadow: 0 24px 70px rgba(23,33,31,.14); }
        .card { border-radius: 12px; border: 1px solid rgba(219,227,222,.95) !important; box-shadow: var(--mk-shadow) !important; background: rgba(255,255,255,.78); }
        .form-label { color: var(--mk-ink-2); font-size: .82rem; font-weight: 750; }
        .form-control, .form-select { border-radius: 10px; border-color: var(--mk-line); min-height: 46px; color: var(--mk-ink); background-color: #fffdf9; }
        .form-control:focus, .form-select:focus { border-color: var(--mk-teal-2); box-shadow: 0 0 0 .2rem rgba(10,167,157,.14); }
        .btn { border-radius: 10px; font-weight: 750; letter-spacing: 0; }
        .btn-success, .text-bg-success { background: var(--mk-coral) !important; border-color: var(--mk-coral) !important; color: #fff !important; }
        .btn-success:hover { background: var(--mk-coral-dark) !important; border-color: var(--mk-coral-dark) !important; }
        .btn-outline-success { color: var(--mk-teal); border-color: var(--mk-teal); }
        .btn-outline-success:hover { background: var(--mk-teal); border-color: var(--mk-teal); color: #fff; }
        .btn-outline-secondary { color: var(--mk-ink); border-color: var(--mk-line); background: rgba(255,255,255,.5); }
        .btn-outline-secondary:hover { background: var(--mk-ink); border-color: var(--mk-ink); color: #fff; }
        .nav-link { color: var(--mk-muted); font-weight: 700; }
        .nav-link.active, .nav-link:hover { color: var(--mk-teal) !important; }
        .text-muted { color: var(--mk-muted) !important; }
        .badge { border-radius: 999px; padding: .45rem .7rem; }
        .pagination { gap: .35rem; flex-wrap: wrap; }
        .page-link { border-radius: 10px; border-color: var(--mk-line); color: var(--mk-teal); font-weight: 700; }
        .active > .page-link, .page-link.active { background: var(--mk-teal); border-color: var(--mk-teal); }
        .table { --bs-table-bg: transparent; }
        .table thead th { color: var(--mk-muted); font-size: .78rem; text-transform: uppercase; letter-spacing: .04em; }
        .section-title { color: var(--mk-ink); letter-spacing: -.02em; }
        @media (max-width: 991.98px) {
            .hero, .hero .container { min-height: auto; }
            .hero { padding-top: 2rem; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                <span class="brand-mark">M</span>
                <span class="fw-bold">Maskapai</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <li class="nav-item"><a class="nav-link" href="{{ route('routes.index') }}">Rute</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('promos.index') }}">Promo</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('faq') }}">FAQ</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Kontak</a></li>
                    @auth
                        <li class="nav-item"><a class="btn btn-outline-success btn-sm" href="{{ route(auth()->user()->dashboardRoute()) }}">Dashboard</a></li>
                    @else
                        <li class="nav-item"><a class="btn btn-outline-secondary btn-sm" href="{{ route('login') }}">Login</a></li>
                        <li class="nav-item"><a class="btn btn-success btn-sm" href="{{ route('register') }}">Register</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    @include('partials.flash')

    <main>
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
