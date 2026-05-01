@extends('layouts.dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <form class="d-flex gap-2" method="GET"><input class="form-control" name="search" value="{{ request('search') }}" placeholder="Cari {{ $title }}"><button class="btn btn-outline-secondary">Cari</button></form>
    <a class="btn btn-success" href="{{ route('admin.master.create', $resource) }}">Tambah {{ $title }}</a>
</div>
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Info</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>
                        @switch($resource)
                        @case('customers') {{ $item->name }}<br><small class="text-muted">{{ $item->email }}</small> @break
                        @case('cities') {{ $item->name }}<br><small class="text-muted">{{ $item->province }}</small> @break
                        @case('airports') {{ $item->code }} - {{ $item->name }}<br><small class="text-muted">{{ $item->city->name }}</small> @break
                        @case('aircrafts') {{ $item->registration_code }}<br><small class="text-muted">{{ $item->model }}</small> @break
                        @case('routes') {{ $item->code }}<br><small class="text-muted">{{ $item->originAirport->code }} ke {{ $item->destinationAirport->code }}</small> @break
                        @case('flight-schedules') {{ $item->flight_number }}<br><small class="text-muted">{{ $item->departure_time->format('d M Y H:i') }}</small> @break
                        @case('ticket-classes') {{ $item->name }}<br><small class="text-muted">{{ $item->code }}</small> @break
                        @case('ticket-prices') {{ $item->flightSchedule->flight_number }}<br><small class="text-muted">{{ $item->ticketClass->name }}</small> @break
                        @endswitch
                    </td>
                    <td>
                        @switch($resource)
                        @case('aircrafts') {{ $item->capacity }} seat | {{ $item->status }} @break
                        @case('routes') {{ $item->distance_km }} km | {{ $item->duration_minutes }} menit @break
                        @case('ticket-prices') Rp {{ number_format($item->price, 0, ',', '.') }} | quota {{ $item->quota }} @break
                        @default {{ $item->updated_at->format('d M Y') }}
                        @endswitch
                    </td>
                    <td class="text-end">
                        @if($resource === 'aircrafts')<a class="btn btn-sm btn-outline-success" href="{{ route('admin.aircrafts.seats', $item) }}">Seat</a>@endif
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.master.edit', [$resource, $item->id]) }}">Edit</a>
                        <form class="d-inline" method="POST" action="{{ route('admin.master.destroy', [$resource, $item->id]) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus data?')">Hapus</button></form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $items->links() }}</div>
@endsection