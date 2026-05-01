@extends('layouts.dashboard')

@section('content')
<div class="card border-0 shadow-sm">
    <table class="table mb-0">
        <thead>
            <tr>
                <th>Route ID</th>
                <th>Total Booking</th>
                <th>Revenue</th>
            </tr>
        </thead>
        <tbody>@foreach($routes as $route)<tr>
                <td>{{ $route->route_id }}</td>
                <td>{{ $route->total_booking }}</td>
                <td>Rp {{ number_format($route->revenue,0,',','.') }}</td>
            </tr>@endforeach</tbody>
    </table>
</div>{{ $routes->links() }}
@endsection