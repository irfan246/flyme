<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Eksekutif</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <main class="container py-4">
        <h1 class="h3">Laporan Eksekutif</h1>
        <table class="table table-bordered">
            <tr>
                <th>Total Revenue</th>
                <td>Rp {{ number_format($totalRevenue,0,',','.') }}</td>
            </tr>
            <tr>
                <th>Total Booking</th>
                <td>{{ $totalBooking }}</td>
            </tr>
            <tr>
                <th>Total Jadwal Flyme</th>
                <td>{{ $totalFlights }}</td>
            </tr>
        </table><button onclick="window.print()" class="btn btn-success">Print / Save PDF</button>
    </main>
</body>

</html>
