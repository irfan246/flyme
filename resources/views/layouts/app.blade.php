<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --air-blue-950: #071a3d;
            --air-blue-900: #0b2457;
            --air-blue-700: #0d47a1;
            --air-blue-600: #1565d8;
            --air-blue-500: #1e88e5;
            --air-blue-100: #e8f2ff;
            --air-sky: #66c7ff;
            --air-ink: #12213b;
            --air-muted: #667085;
            --air-border: #d9e5f5;
        }
        body {
            background: linear-gradient(180deg, #f7fbff 0%, #eef6ff 100%);
            color: var(--air-ink);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
        .navbar { backdrop-filter: blur(16px); box-shadow: 0 10px 30px rgba(16, 60, 120, .06); }
        .brand-mark { width: 42px; height: 42px; border-radius: 14px; background: linear-gradient(135deg, var(--air-blue-600), var(--air-sky)); display: inline-grid; place-items: center; color: #fff; font-weight: 900; box-shadow: 0 14px 28px rgba(21,101,216,.28); }
        .hero {
            min-height: 680px;
            color: #fff;
            background:
                linear-gradient(90deg, rgba(7,26,61,.92) 0%, rgba(7,26,61,.74) 45%, rgba(21,101,216,.25) 100%),
                url('https://source.unsplash.com/1800x1100/?airplane,sky,clouds');
            background-size: cover;
            background-position: center;
        }
        .hero .container { min-height: 680px; display: flex; align-items: center; }
        .card { border-radius: 18px; border: 1px solid rgba(217,229,245,.9) !important; box-shadow: 0 18px 50px rgba(15, 54, 110, .08) !important; }
        .form-control, .form-select { border-radius: 12px; border-color: var(--air-border); min-height: 46px; }
        .form-control:focus, .form-select:focus { border-color: var(--air-blue-500); box-shadow: 0 0 0 .2rem rgba(30,136,229,.16); }
        .btn { border-radius: 12px; font-weight: 700; }
        .btn-success, .text-bg-success { background: var(--air-blue-600) !important; border-color: var(--air-blue-600) !important; color: #fff !important; }
        .btn-outline-success { color: var(--air-blue-600); border-color: var(--air-blue-600); }
        .btn-outline-success:hover { background: var(--air-blue-600); border-color: var(--air-blue-600); color: #fff; }
        .nav-link { color: #344767; font-weight: 600; }
        .nav-link.active, .nav-link:hover { color: var(--air-blue-600) !important; }
        .text-muted { color: var(--air-muted) !important; }
        .pagination { gap: .35rem; flex-wrap: wrap; }
        .page-link { border-radius: 10px; border-color: var(--air-border); color: var(--air-blue-600); font-weight: 700; }
        .active > .page-link, .page-link.active { background: var(--air-blue-600); border-color: var(--air-blue-600); }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                <span class="brand-mark">A</span>
                <span class="fw-bold">Airline System</span>
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
