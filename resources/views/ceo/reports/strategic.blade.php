@extends('layouts.dashboard')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm card-body"><small>Revenue</small><strong>Rp {{ number_format($totalRevenue,0,',','.') }}</strong></div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm card-body"><small>Booking</small><strong>{{ $totalBooking }}</strong></div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm card-body"><small>Customer</small><strong>{{ $totalCustomer }}</strong></div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm card-body"><small>Jadwal Flyme</small><strong>{{ $totalFlights }}</strong></div>
    </div>
</div>
<div class="d-flex gap-2 mb-3"><a class="btn btn-outline-success" href="{{ route('ceo.reports.export') }}">Export CSV</a><a class="btn btn-outline-primary" href="{{ route('ceo.reports.print') }}">PDF Print</a></div>
<div class="card border-0 shadow-sm card-body">
    <h2 class="h5">Grafik Pendapatan</h2>
    <div class="d-flex align-items-end gap-2" style="height:180px">
        @foreach($revenueSeries as $row)
            <div class="bg-success rounded-top" title="{{ $row->period }}" style="width:42px;height:{{ min(170, max(12, $row->revenue / 100000)) }}px"></div>
        @endforeach
    </div>
</div>
@endsection
