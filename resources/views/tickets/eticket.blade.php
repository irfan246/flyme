<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>E-ticket {{ $booking->booking_code }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none
            }
        }
    </style>
</head>

<body class="bg-light">
    <main class="container py-5">
        <div class="card border-0 shadow">
            <div class="card-body p-5">
                <div class="d-flex justify-content-between">
                    <div>
                        <h1 class="h3 fw-bold">E-ticket</h1>
                        <p class="text-muted">{{ config('app.name') }}</p>
                    </div>
                    <div class="text-end">
                        <div class="small text-muted">Kode Booking</div>
                        <div class="fs-4 fw-bold">{{ $booking->booking_code }}</div>
                    </div>
                </div>
                <hr>
                <div class="row g-3">
                    <div class="col-md-6"><strong>Pergi: {{ $booking->flightSchedule->route->originAirport->name }}</strong><br>{{ $booking->flightSchedule->departure_time->format('d M Y H:i') }}</div>
                    <div class="col-md-6"><strong>{{ $booking->flightSchedule->route->destinationAirport->name }}</strong><br>{{ $booking->flightSchedule->arrival_time->format('d M Y H:i') }}</div>
                    @if($booking->returnFlightSchedule)
                        <div class="col-md-6"><strong>Pulang: {{ $booking->returnFlightSchedule->route->originAirport->name }}</strong><br>{{ $booking->returnFlightSchedule->departure_time->format('d M Y H:i') }}</div>
                        <div class="col-md-6"><strong>{{ $booking->returnFlightSchedule->route->destinationAirport->name }}</strong><br>{{ $booking->returnFlightSchedule->arrival_time->format('d M Y H:i') }}</div>
                    @endif
                </div>
                <hr>
                <p>Class: <strong>{{ $booking->ticketClass->name }}</strong> | Tipe: <strong>{{ str_replace('_', ' ', $booking->trip_type) }}</strong></p>
                <h2 class="h6">Penumpang & Kursi</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Kursi</th>
                        </tr>
                    </thead>
                    <tbody>@foreach($booking->passengers as $index => $passenger)<tr>
                            <td>{{ $passenger->name }}</td>
                            <td>
                                @php($outboundSeats = $booking->bookingSeats->where('flight_schedule_id', $booking->flight_schedule_id)->values())
                                @php($returnSeats = $booking->bookingSeats->where('flight_schedule_id', $booking->return_flight_schedule_id)->values())
                                Pergi {{ $outboundSeats[$index]->seat->code ?? '-' }}
                                @if($booking->returnFlightSchedule) | Pulang {{ $returnSeats[$index]->seat->code ?? '-' }} @endif
                            </td>
                        </tr>@endforeach</tbody>
                </table><button class="btn btn-success no-print" onclick="window.print()">Print / Save PDF</button>
            </div>
        </div>
    </main>
</body>

</html>
