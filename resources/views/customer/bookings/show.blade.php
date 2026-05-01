@extends('layouts.dashboard')

@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h2 class="h5 fw-bold">{{ $booking->booking_code }}</h2>
                <p><strong>Pergi:</strong> {{ $booking->flightSchedule->route->originAirport->name }} ke {{ $booking->flightSchedule->route->destinationAirport->name }}</p>
                @if($booking->returnFlightSchedule)
                    <p><strong>Pulang:</strong> {{ $booking->returnFlightSchedule->route->originAirport->name }} ke {{ $booking->returnFlightSchedule->route->destinationAirport->name }}</p>
                @endif
                <div class="row g-2">@foreach($booking->bookingSeats as $bookingSeat)<div class="col-auto"><span class="badge text-bg-success">{{ $bookingSeat->flight_schedule_id === $booking->return_flight_schedule_id ? 'Pulang ' : 'Pergi ' }}{{ $bookingSeat->seat->code }}</span></div>@endforeach</div>
                <hr>
                <h3 class="h6">Penumpang</h3>
                <ul>@foreach($booking->passengers as $passenger)<li>{{ $passenger->name }} {{ $passenger->identity_number ? '- '.$passenger->identity_number : '' }}</li>@endforeach</ul>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between"><span>Tipe</span><strong>{{ str_replace('_', ' ', $booking->trip_type) }}</strong></div>
                <div class="d-flex justify-content-between"><span>Status</span><strong>{{ $booking->status }}</strong></div>
                <div class="d-flex justify-content-between"><span>Total</span><strong>Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</strong></div>
                <div class="d-grid gap-2 mt-3">@if($booking->status === 'pending')<a class="btn btn-success" href="{{ route('customer.bookings.payment', $booking) }}">Upload Pembayaran</a>
                    <form method="POST" action="{{ route('customer.bookings.cancel', $booking) }}">@csrf<button class="btn btn-outline-danger w-100">Cancel Booking</button></form>@endif @if($booking->status === 'confirmed')<a class="btn btn-outline-success" href="{{ route('customer.bookings.ticket', $booking) }}">Download / Print E-ticket</a>@endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
