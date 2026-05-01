<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <main class="container py-4">
        <h1 class="h3">{{ $title }}</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>@foreach($bookings as $booking)<tr>
                    <td>{{ $booking->booking_code }}</td>
                    <td>{{ $booking->customer_name }}</td>
                    <td>Rp {{ number_format($booking->total_amount,0,',','.') }}</td>
                    <td>{{ $booking->confirmed_at?->format('d M Y') }}</td>
                </tr>@endforeach</tbody>
        </table><button onclick="window.print()" class="btn btn-success">Print / Save PDF</button>
    </main>
</body>

</html>