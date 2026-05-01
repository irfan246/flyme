@extends('layouts.dashboard')

@section('content')
<div class="card border-0 shadow-sm">
    <table class="table mb-0">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Handled Booking</th>
            </tr>
        </thead>
        <tbody>@foreach($admins as $admin)<tr>
                <td>{{ $admin->name }}</td>
                <td>{{ $admin->email }}</td>
                <td>{{ $admin->handled_bookings_count }}</td>
            </tr>@endforeach</tbody>
    </table>
</div>
@endsection