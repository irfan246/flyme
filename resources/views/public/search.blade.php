@extends('layouts.app')

@section('content')
    <section class="py-5">
        <div class="container">
            <h1 class="fw-bold">Hasil Pencarian Tiket</h1>
            <form class="row g-2 my-4" method="GET" action="{{ route('flights.search') }}">
                <div class="col-md-3"><select class="form-select" name="origin_airport_id"><option value="">Asal</option>@foreach($airports as $airport)<option value="{{ $airport->id }}" @selected(($filters['origin_airport_id'] ?? '') == $airport->id)>{{ $airport->code }} - {{ $airport->city->name }}</option>@endforeach</select></div>
                <div class="col-md-3"><select class="form-select" name="destination_airport_id"><option value="">Tujuan</option>@foreach($airports as $airport)<option value="{{ $airport->id }}" @selected(($filters['destination_airport_id'] ?? '') == $airport->id)>{{ $airport->code }} - {{ $airport->city->name }}</option>@endforeach</select></div>
                <div class="col-md-2"><input class="form-control" type="date" name="departure_date" value="{{ $filters['departure_date'] ?? '' }}"></div>
                <div class="col-md-2"><select class="form-select" name="route_scope"><option value="all" @selected(($filters['route_scope'] ?? 'all') === 'all')>Semua</option><option value="domestic" @selected(($filters['route_scope'] ?? '') === 'domestic')>Domestik</option><option value="international" @selected(($filters['route_scope'] ?? '') === 'international')>Luar negeri</option></select></div>
                <div class="col-md-2"><select class="form-select" name="trip_type"><option value="one_way" @selected(($filters['trip_type'] ?? 'one_way') === 'one_way')>Pergi saja</option><option value="return_only" @selected(($filters['trip_type'] ?? '') === 'return_only')>Pulang saja</option><option value="round_trip" @selected(($filters['trip_type'] ?? '') === 'round_trip')>Pulang pergi</option></select></div>
                <div class="col-md-2"><input class="form-control" type="date" name="return_date" value="{{ $filters['return_date'] ?? '' }}" title="Tanggal pulang"></div>
                <div class="col-md-2"><input class="form-control" type="number" name="passengers" min="1" max="9" value="{{ $filters['passengers'] ?? 1 }}"></div>
                <div class="col-md-2"><button class="btn btn-success w-100">Cari</button></div>
            </form>
            @include('public.partials.schedule-list', ['schedules' => $schedules, 'returnSchedules' => $returnSchedules ?? collect(), 'tripType' => $filters['trip_type'] ?? 'one_way', 'passengers' => $filters['passengers'] ?? 1, 'ticketClassId' => $filters['ticket_class_id'] ?? null])
        </div>
    </section>
@endsection
