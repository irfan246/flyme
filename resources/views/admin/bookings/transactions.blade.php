@extends('layouts.dashboard')

@section('content')
<form class="row g-2 mb-3" method="GET">
    <div class="col-md-4"><input class="form-control" name="search" value="{{ request('search') }}" placeholder="Kode / customer"></div>
    <div class="col-md-3"><select class="form-select" name="status">
            <option value="">Semua status</option>@foreach(['pending','paid','confirmed','cancelled'] as $status)<option @selected(request('status')===$status)>{{ $status }}</option>@endforeach
        </select></div>
    <div class="col-md-2"><button class="btn btn-outline-secondary w-100">Filter</button></div>
</form>
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Customer</th>
                    <th>Rute</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>@foreach($bookings as $booking)<tr>
                    <td>{{ $booking->booking_code }}</td>
                    <td>{{ $booking->customer_name }}</td>
                    <td>{{ $booking->flightSchedule->route->originAirport->code }}-{{ $booking->flightSchedule->route->destinationAirport->code }}</td>
                    <td><span class="badge text-bg-secondary">{{ $booking->status }}</span></td>
                    <td>Rp {{ number_format($booking->total_amount,0,',','.') }}</td>
                    <td><a class="btn btn-sm btn-outline-success" href="{{ route('admin.transactions.show', $booking) }}">Detail</a></td>
                </tr>@endforeach</tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $bookings->links() }}</div>
@endsection