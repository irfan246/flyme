@extends('layouts.dashboard')

@section('content')
<div class="card border-0 shadow-sm">
    <table class="table mb-0">
        <thead>
            <tr>
                <th>Flight</th>
                <th>Rute</th>
                <th>Kapasitas</th>
                <th>Confirmed</th>
                <th>Okupansi</th>
            </tr>
        </thead>
        <tbody>@foreach($schedules as $schedule)<tr>
                <td>{{ $schedule->flight_number }}</td>
                <td>{{ $schedule->route->originAirport->code }}-{{ $schedule->route->destinationAirport->code }}</td>
                <td>{{ $schedule->aircraft->capacity }}</td>
                <td>{{ $schedule->confirmed_bookings_count }}</td>
                <td>{{ $schedule->aircraft->capacity ? round(($schedule->confirmed_bookings_count / $schedule->aircraft->capacity) * 100, 1) : 0 }}%</td>
            </tr>@endforeach</tbody>
    </table>
</div>{{ $schedules->links() }}
@endsection