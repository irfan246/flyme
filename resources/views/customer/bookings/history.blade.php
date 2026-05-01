@extends('layouts.dashboard')

@section('content')
    <div class="card border-0 shadow-sm"><div class="table-responsive"><table class="table mb-0 align-middle"><thead><tr><th>Kode</th><th>Rute</th><th>Status</th><th>Total</th><th></th></tr></thead><tbody>@foreach($bookings as $booking)<tr><td>{{ $booking->booking_code }}</td><td>{{ $booking->flightSchedule->route->originAirport->code }} - {{ $booking->flightSchedule->route->destinationAirport->code }}</td><td><span class="badge text-bg-secondary">{{ $booking->status }}</span></td><td>Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</td><td><a class="btn btn-sm btn-outline-success" href="{{ route('customer.bookings.show', $booking) }}">Detail</a></td></tr>@endforeach</tbody></table></div></div><div class="mt-3">{{ $bookings->links() }}</div>
@endsection
