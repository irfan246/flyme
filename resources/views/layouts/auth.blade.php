<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Auth' }} - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: radial-gradient(circle at top left, #dff0ff, transparent 30%), linear-gradient(135deg, #071a3d, #1565d8); font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        .card { border-radius: 22px; border: 1px solid rgba(255,255,255,.5); box-shadow: 0 24px 70px rgba(7,26,61,.24); }
        .form-control { border-radius: 12px; min-height: 46px; }
        .btn-success { background: #1565d8; border-color: #1565d8; border-radius: 12px; font-weight: 700; }
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
