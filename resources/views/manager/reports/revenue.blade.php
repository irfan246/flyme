@extends('layouts.dashboard')

@section('content')
<div class="card border-0 shadow-sm">
    <table class="table mb-0">
        <thead>
            <tr>
                <th>Periode</th>
                <th>Booking</th>
                <th>Revenue</th>
            </tr>
        </thead>
        <tbody>@foreach($daily as $row)<tr>
                <td>{{ $row->period }}</td>
                <td>{{ $row->bookings }}</td>
                <td>Rp {{ number_format($row->revenue,0,',','.') }}</td>
            </tr>@endforeach</tbody>
    </table>
</div>
@endsection