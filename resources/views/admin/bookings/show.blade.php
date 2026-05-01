@extends('layouts.dashboard')

@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h2 class="h5">{{ $booking->booking_code }}</h2>
                <p>{{ $booking->customer_name }} - {{ $booking->customer_email }}</p>
                <p>{{ $booking->flightSchedule->flight_number }} | {{ $booking->flightSchedule->route->originAirport->name }} ke {{ $booking->flightSchedule->route->destinationAirport->name }}</p>
                <h3 class="h6">Kursi</h3>@foreach($booking->bookingSeats as $seat)<span class="badge text-bg-success">{{ $seat->seat->code }}</span>@endforeach
                <hr>
                <h3 class="h6">Penumpang</h3>
                <ul>@foreach($booking->passengers as $passenger)<li>{{ $passenger->name }}</li>@endforeach</ul>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between"><span>Status</span><strong>{{ $booking->status }}</strong></div>
                <div class="d-flex justify-content-between"><span>Payment</span><strong>{{ $booking->payment?->status }}</strong></div>
                <div class="d-flex justify-content-between"><span>Total</span><strong>Rp {{ number_format($booking->total_amount,0,',','.') }}</strong></div>
                <div class="d-grid gap-2 mt-3">@if(in_array($booking->status, ['paid','pending']))<form method="POST" action="{{ route('admin.transactions.confirm-payment', $booking) }}">@csrf<button class="btn btn-success w-100">Konfirmasi</button></form>@endif @if($booking->status !== 'confirmed')<form method="POST" action="{{ route('admin.transactions.cancel', $booking) }}">@csrf<button class="btn btn-outline-danger w-100">Batalkan</button></form>@endif @if($booking->status === 'confirmed')<a class="btn btn-outline-success" href="{{ route('admin.transactions.ticket', $booking) }}">Cetak Tiket</a>@endif</div>
            </div>
        </div>
    </div>
</div>
@endsection