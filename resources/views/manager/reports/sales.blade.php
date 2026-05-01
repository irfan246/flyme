@extends('layouts.dashboard')

@section('content')
<form class="row g-2 mb-3" method="GET">
    <div class="col-md-3"><input class="form-control" type="date" name="from" value="{{ request('from') }}"></div>
    <div class="col-md-3"><input class="form-control" type="date" name="to" value="{{ request('to') }}"></div>
    <div class="col-md-2"><button class="btn btn-outline-secondary w-100">Filter</button></div>
    <div class="col-md-2"><a class="btn btn-outline-success w-100" href="{{ route('manager.reports.sales.export', request()->query()) }}">Excel CSV</a></div>
    <div class="col-md-2"><a class="btn btn-outline-primary w-100" href="{{ route('manager.reports.sales.print', request()->query()) }}">PDF Print</a></div>
</form>
<div class="alert alert-success">Total revenue: <strong>Rp {{ number_format($totalRevenue,0,',','.') }}</strong></div>
<div class="card border-0 shadow-sm">
    <table class="table mb-0">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Customer</th>
                <th>Rute</th>
                <th>Total</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>@foreach($bookings as $booking)<tr>
                <td>{{ $booking->booking_code }}</td>
                <td>{{ $booking->customer_name }}</td>
                <td>{{ $booking->flightSchedule->route->originAirport->code }}-{{ $booking->flightSchedule->route->destinationAirport->code }}</td>
                <td>Rp {{ number_format($booking->total_amount,0,',','.') }}</td>
                <td>{{ $booking->confirmed_at?->format('d M Y') }}</td>
            </tr>@endforeach</tbody>
    </table>
</div>{{ $bookings->links() }}
@endsection