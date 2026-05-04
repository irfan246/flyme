@extends('layouts.app')

@section('content')
    <section class="section-shell">
        <div class="container">
            <div class="glass-card page-panel mb-4">
                <span class="page-eyebrow">Search Result</span>
                <h1 class="fw-bold mt-3 mb-2">Hasil pencarian tiket Flyme</h1>
                <p class="text-muted mb-0 section-copy">Atur ulang filter perjalanan di bawah ini untuk menemukan jadwal Flyme yang paling sesuai.</p>
            </div>

            <form class="glass-card page-panel row g-3 mb-4" method="GET" action="{{ route('flights.search') }}">
                <div class="col-12 col-md-6 col-xl-3">
                    <label class="form-label">Asal</label>
                    <select class="form-select" name="origin_airport_id">
                        <option value="">Asal</option>
                        @foreach($airports as $airport)
                            <option value="{{ $airport->id }}" @selected(($filters['origin_airport_id'] ?? '') == $airport->id)>{{ $airport->code }} - {{ $airport->city->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-6 col-xl-3">
                    <label class="form-label">Tujuan</label>
                    <select class="form-select" name="destination_airport_id">
                        <option value="">Tujuan</option>
                        @foreach($airports as $airport)
                            <option value="{{ $airport->id }}" @selected(($filters['destination_airport_id'] ?? '') == $airport->id)>{{ $airport->code }} - {{ $airport->city->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-6 col-xl-2">
                    <label class="form-label">Tanggal Berangkat</label>
                    <input class="form-control" type="date" name="departure_date" value="{{ $filters['departure_date'] ?? '' }}">
                </div>
                <div class="col-12 col-md-6 col-xl-2">
                    <label class="form-label">Tanggal Pulang</label>
                    <input class="form-control" type="date" name="return_date" value="{{ $filters['return_date'] ?? '' }}" title="Tanggal pulang">
                </div>
                <div class="col-12 col-md-6 col-xl-2">
                    <label class="form-label">Penumpang</label>
                    <input class="form-control" type="number" name="passengers" min="1" max="9" value="{{ $filters['passengers'] ?? 1 }}">
                </div>
                <div class="col-12 col-md-6 col-xl-3">
                    <label class="form-label">Kelas</label>
                    <select class="form-select" name="ticket_class_id">
                        <option value="">Semua kelas</option>
                        @foreach($ticketClasses as $class)
                            <option value="{{ $class->id }}" @selected(($filters['ticket_class_id'] ?? '') == $class->id)>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-6 col-xl-3">
                    <label class="form-label">Cakupan Rute</label>
                    <select class="form-select" name="route_scope">
                        <option value="all" @selected(($filters['route_scope'] ?? 'all') === 'all')>Semua</option>
                        <option value="domestic" @selected(($filters['route_scope'] ?? '') === 'domestic')>Domestik</option>
                        <option value="international" @selected(($filters['route_scope'] ?? '') === 'international')>Luar negeri</option>
                    </select>
                </div>
                <div class="col-12 col-md-6 col-xl-3">
                    <label class="form-label">Tipe Trip</label>
                    <select class="form-select" name="trip_type">
                        <option value="one_way" @selected(($filters['trip_type'] ?? 'one_way') === 'one_way')>Pergi saja</option>
                        <option value="return_only" @selected(($filters['trip_type'] ?? '') === 'return_only')>Pulang saja</option>
                        <option value="round_trip" @selected(($filters['trip_type'] ?? '') === 'round_trip')>Pulang pergi</option>
                    </select>
                </div>
                <div class="col-12 col-md-6 col-xl-3 d-flex align-items-end">
                    <button class="btn btn-success w-100">Perbarui Pencarian Flyme</button>
                </div>
            </form>

            @include('public.partials.schedule-list', ['schedules' => $schedules, 'returnSchedules' => $returnSchedules ?? collect(), 'tripType' => $filters['trip_type'] ?? 'one_way', 'passengers' => $filters['passengers'] ?? 1, 'ticketClassId' => $filters['ticket_class_id'] ?? null])
        </div>
    </section>
@endsection
