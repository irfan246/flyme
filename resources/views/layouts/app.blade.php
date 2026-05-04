<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --flyme-ink: #18231f;
            --flyme-ink-2: #30433d;
            --flyme-muted: #65736d;
            --flyme-line: #d8e2dc;
            --flyme-paper: #fbf8f2;
            --flyme-soft: #eef4f0;
            --flyme-soft-2: #f6fbf8;
            --flyme-teal: #0a786f;
            --flyme-teal-2: #11a59a;
            --flyme-coral: #eb6a4b;
            --flyme-coral-dark: #cf5538;
            --flyme-amber: #f4c164;
            --flyme-shadow: 0 18px 48px rgba(24, 35, 31, .10);
        }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            background:
                radial-gradient(circle at top left, rgba(17,165,154,.1), transparent 30%),
                linear-gradient(180deg, var(--flyme-paper) 0%, #f4efe6 100%);
            color: var(--flyme-ink);
            font-family: "Plus Jakarta Sans", Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
        main { overflow: clip; }
        .navbar {
            backdrop-filter: blur(18px);
            box-shadow: 0 10px 35px rgba(31, 42, 37, .06);
        }
        .navbar.bg-white { background: rgba(251, 248, 242, .9) !important; }
        .navbar .container { padding-top: .6rem; padding-bottom: .6rem; }
        .brand-mark {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: linear-gradient(145deg, var(--flyme-ink), #25332f);
            display: inline-grid;
            place-items: center;
            color: var(--flyme-amber);
            font-weight: 900;
            box-shadow: 0 14px 30px rgba(24, 35, 31, .18);
        }
        .navbar-brand {
            color: var(--flyme-ink);
            font-size: 1.05rem;
            letter-spacing: -.02em;
        }
        .nav-link {
            color: var(--flyme-muted);
            font-weight: 700;
            padding: .65rem .95rem !important;
            border-radius: 999px;
        }
        .nav-link.active,
        .nav-link:hover {
            color: var(--flyme-teal) !important;
            background: rgba(10, 120, 111, .08);
        }
        .hero {
            position: relative;
            overflow: hidden;
            color: var(--flyme-ink);
            background:
                radial-gradient(circle at 12% 18%, rgba(244,193,100,.2), transparent 22%),
                linear-gradient(115deg, rgba(251,248,242,.98) 0%, rgba(251,248,242,.92) 40%, rgba(17,165,154,.2) 100%),
                url('https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=1800&q=82');
            background-size: cover;
            background-position: center;
        }
        .hero::before,
        .hero::after {
            content: "";
            position: absolute;
            pointer-events: none;
        }
        .hero::before {
            inset: 12% auto auto -8%;
            width: 260px;
            height: 260px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .22);
            filter: blur(12px);
        }
        .hero::after {
            inset: auto -8% -24% 35%;
            width: 520px;
            height: 520px;
            border-radius: 50%;
            border: 1px solid rgba(10, 120, 111, .16);
            transform: rotate(-8deg);
        }
        .hero .container {
            position: relative;
            z-index: 1;
            min-height: 760px;
            display: flex;
            align-items: center;
        }
        .hero-copy { max-width: 680px; }
        .hero-kicker {
            display: inline-flex;
            align-items: center;
            gap: .55rem;
            padding: .55rem .9rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, .75);
            border: 1px solid rgba(216, 226, 220, .9);
            color: var(--flyme-ink-2);
            font-size: .86rem;
            font-weight: 700;
            box-shadow: 0 8px 24px rgba(24, 35, 31, .06);
        }
        .display-hero {
            font-size: clamp(2.6rem, 5vw, 4.9rem);
            line-height: .96;
            letter-spacing: -.05em;
        }
        .hero-copy .lead {
            max-width: 590px;
            font-size: clamp(1rem, 1.5vw, 1.18rem);
        }
        .hero-actions,
        .hero-highlights {
            display: flex;
            flex-wrap: wrap;
            gap: .85rem;
        }
        .hero-highlight {
            padding: .85rem 1rem;
            border-radius: 18px;
            background: rgba(255, 255, 255, .7);
            border: 1px solid rgba(216, 226, 220, .88);
            min-width: 150px;
            box-shadow: 0 12px 26px rgba(24, 35, 31, .05);
        }
        .hero-highlight strong {
            display: block;
            font-size: 1.05rem;
        }
        .hero-search,
        .glass-card {
            background: rgba(255, 255, 255, .88);
            border: 1px solid rgba(216, 226, 220, .94);
            border-radius: 24px;
            box-shadow: 0 24px 70px rgba(24, 35, 31, .14);
            backdrop-filter: blur(10px);
        }
        .section-shell {
            padding: 5rem 0;
        }
        .section-shell.compact {
            padding-top: 2rem;
            padding-bottom: 4rem;
        }
        .section-title {
            color: var(--flyme-ink);
            letter-spacing: -.03em;
        }
        .section-copy {
            max-width: 620px;
        }
        .feature-card {
            height: 100%;
            padding: 1.7rem;
            border-radius: 22px;
            background: linear-gradient(180deg, rgba(255,255,255,.92), rgba(246,251,248,.92));
            border: 1px solid rgba(216, 226, 220, .95);
            box-shadow: var(--flyme-shadow);
        }
        .feature-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: inline-grid;
            place-items: center;
            background: rgba(10, 120, 111, .1);
            color: var(--flyme-teal);
            font-weight: 800;
        }
        .page-panel {
            padding: clamp(1.5rem, 3vw, 2.5rem);
            border-radius: 28px;
        }
        .page-eyebrow {
            font-size: .82rem;
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--flyme-teal);
        }
        .card {
            border-radius: 20px;
            border: 1px solid rgba(216, 226, 220, .95) !important;
            box-shadow: var(--flyme-shadow) !important;
            background: rgba(255, 255, 255, .82);
        }
        .schedule-card .card-body { padding: 1.5rem; }
        .form-label {
            color: var(--flyme-ink-2);
            font-size: .82rem;
            font-weight: 750;
            margin-bottom: .45rem;
        }
        .form-control,
        .form-select {
            border-radius: 14px;
            border-color: var(--flyme-line);
            min-height: 48px;
            color: var(--flyme-ink);
            background-color: #fffdfa;
            padding-inline: .95rem;
        }
        .form-control:focus,
        .form-select:focus {
            border-color: var(--flyme-teal-2);
            box-shadow: 0 0 0 .2rem rgba(17, 165, 154, .14);
        }
        .btn {
            border-radius: 14px;
            font-weight: 750;
            letter-spacing: -.01em;
            padding: .8rem 1.2rem;
        }
        .btn-sm { padding: .55rem .9rem; border-radius: 12px; }
        .btn-success,
        .text-bg-success {
            background: var(--flyme-coral) !important;
            border-color: var(--flyme-coral) !important;
            color: #fff !important;
        }
        .btn-success:hover {
            background: var(--flyme-coral-dark) !important;
            border-color: var(--flyme-coral-dark) !important;
        }
        .btn-outline-success {
            color: var(--flyme-teal);
            border-color: var(--flyme-teal);
        }
        .btn-outline-success:hover {
            background: var(--flyme-teal);
            border-color: var(--flyme-teal);
            color: #fff;
        }
        .btn-outline-secondary {
            color: var(--flyme-ink);
            border-color: var(--flyme-line);
            background: rgba(255, 255, 255, .6);
        }
        .btn-outline-secondary:hover {
            background: var(--flyme-ink);
            border-color: var(--flyme-ink);
            color: #fff;
        }
        .text-muted { color: var(--flyme-muted) !important; }
        .badge {
            border-radius: 999px;
            padding: .5rem .8rem;
        }
        .pagination {
            gap: .35rem;
            flex-wrap: wrap;
        }
        .page-link {
            border-radius: 12px;
            border-color: var(--flyme-line);
            color: var(--flyme-teal);
            font-weight: 700;
        }
        .active > .page-link,
        .page-link.active {
            background: var(--flyme-teal);
            border-color: var(--flyme-teal);
        }
        .table { --bs-table-bg: transparent; }
        .table thead th {
            color: var(--flyme-muted);
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .04em;
        }
        .navbar-toggler {
            border-radius: 12px;
            border-color: rgba(216, 226, 220, .9);
        }
        .navbar-toggler:focus {
            box-shadow: 0 0 0 .2rem rgba(17, 165, 154, .14);
        }
        @media (max-width: 991.98px) {
            .hero .container { min-height: auto; padding-top: 3rem; padding-bottom: 3rem; }
            .hero-actions .btn { flex: 1 1 220px; }
            .navbar .container { padding-top: .8rem; padding-bottom: .8rem; }
            .navbar-collapse {
                margin-top: 1rem;
                padding: 1rem;
                border-radius: 20px;
                background: rgba(255, 255, 255, .9);
                border: 1px solid rgba(216, 226, 220, .92);
            }
        }
        @media (max-width: 767.98px) {
            .section-shell { padding: 4rem 0; }
            .section-shell.compact { padding-top: 1.5rem; padding-bottom: 3rem; }
            .hero-copy { text-align: left; }
            .hero-search,
            .page-panel,
            .glass-card { border-radius: 22px; }
            .hero-highlight { width: 100%; }
            .schedule-card .card-body { padding: 1.2rem; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                <span class="brand-mark">F</span>
                <span class="fw-bold">Flyme</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <li class="nav-item"><a class="nav-link" href="{{ route('routes.index') }}">Jadwal</a></li>
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
