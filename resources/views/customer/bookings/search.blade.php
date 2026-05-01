@extends('layouts.dashboard')

@section('content')
    <form class="card border-0 shadow-sm card-body mb-4" method="GET">
        <div class="row g-2">
            <div class="col-md-3"><select class="form-select" name="origin_airport_id"><option value="">Asal</option>@foreach($airports as $airport)<option value="{{ $airport->id }}" @selected(request('origin_airport_id') == $airport->id)>{{ $airport->code }} - {{ $airport->city->name }}</option>@endforeach</select></div>
            <div class="col-md-3"><select class="form-select" name="destination_airport_id"><option value="">Tujuan</option>@foreach($airports as $airport)<option value="{{ $airport->id }}" @selected(request('destination_airport_id') == $airport->id)>{{ $airport->code }} - {{ $airport->city->name }}</option>@endforeach</select></div>
            <div class="col-md-2"><input class="form-control" type="date" name="departure_date" value="{{ request('departure_date') }}"></div>
            <div class="col-md-2"><select class="form-select" name="route_scope"><option value="all" @selected(request('route_scope', 'all') === 'all')>Semua</option><option value="domestic" @selected(request('route_scope') === 'domestic')>Domestik</option><option value="international" @selected(request('route_scope') === 'international')>Luar negeri</option></select></div>
            <div class="col-md-2"><select class="form-select" name="trip_type"><option value="one_way" @selected(request('trip_type', 'one_way') === 'one_way')>Pergi saja</option><option value="return_only" @selected(request('trip_type') === 'return_only')>Pulang saja</option><option value="round_trip" @selected(request('trip_type') === 'round_trip')>Pulang pergi</option></select></div>
            <div class="col-md-2"><input class="form-control" type="date" name="return_date" value="{{ request('return_date') }}" title="Tanggal pulang"></div>
            <div class="col-md-2"><input class="form-control" type="number" name="passengers" min="1" max="9" value="{{ request('passengers', 1) }}"></div>
            <div class="col-md-2"><button class="btn btn-success w-100">Cari</button></div>
        </div>
    </form>
    @include('public.partials.schedule-list', ['schedules' => $schedules, 'returnSchedules' => $returnSchedules ?? collect(), 'tripType' => request('trip_type', 'one_way'), 'passengers' => request('passengers', 1), 'ticketClassId' => request('ticket_class_id')])
@endsection
