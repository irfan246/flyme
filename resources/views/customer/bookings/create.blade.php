@extends('layouts.dashboard')

@section('content')
    @php
        $returnSchedule = $returnSchedule ?? null;
        $returnPrice = $returnPrice ?? null;
        $takenReturnSeatIds = $takenReturnSeatIds ?? [];
    @endphp

    <form method="POST" action="{{ route('customer.bookings.store') }}" class="row g-4">
        @csrf
        <input type="hidden" name="flight_schedule_id" value="{{ $schedule->id }}">
        <input type="hidden" name="return_flight_schedule_id" value="{{ $returnSchedule?->id }}">
        <input type="hidden" name="ticket_class_id" value="{{ $selectedClass->id }}">
        <input type="hidden" name="trip_type" value="{{ $returnSchedule ? 'round_trip' : request('trip_type', 'one_way') }}">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm"><div class="card-body">
                <h2 class="h5 fw-bold">Kursi Pergi: {{ $schedule->flight_number }} - {{ $selectedClass->name }}</h2>
                <p class="text-muted">{{ $schedule->route->originAirport->city->name }} ke {{ $schedule->route->destinationAirport->city->name }} | {{ $schedule->departure_time->format('d M Y H:i') }}</p>
                <div class="seat-map d-grid gap-2" style="grid-template-columns: repeat({{ count(explode(',', $schedule->aircraft->seat_columns)) }}, 52px);">
                    @foreach($schedule->aircraft->seats->sortBy(['row_number', 'column_letter']) as $seat)
                        @php($booked = in_array($seat->id, $takenSeatIds, true))
                        <input class="btn-check" type="checkbox" name="outbound_seat_ids[]" value="{{ $seat->id }}" id="seat{{ $seat->id }}" @disabled($booked)>
                        <label class="btn btn-sm {{ $booked ? 'btn-secondary' : 'btn-outline-success' }}" for="seat{{ $seat->id }}">{{ $seat->code }}</label>
                    @endforeach
                </div>
                <div class="small text-muted mt-3">Pilih {{ $passengers }} kursi. Abu-abu berarti sudah tidak tersedia.</div>
            </div></div>

            @if($returnSchedule)
                <div class="card border-0 shadow-sm mt-4"><div class="card-body">
                    <h2 class="h5 fw-bold">Kursi Pulang: {{ $returnSchedule->flight_number }}</h2>
                    <p class="text-muted">{{ $returnSchedule->route->originAirport->city->name }} ke {{ $returnSchedule->route->destinationAirport->city->name }} | {{ $returnSchedule->departure_time->format('d M Y H:i') }}</p>
                    <div class="seat-map d-grid gap-2" style="grid-template-columns: repeat({{ count(explode(',', $returnSchedule->aircraft->seat_columns)) }}, 52px);">
                        @foreach($returnSchedule->aircraft->seats->sortBy(['row_number', 'column_letter']) as $seat)
                            @php($booked = in_array($seat->id, $takenReturnSeatIds, true))
                            <input class="btn-check" type="checkbox" name="return_seat_ids[]" value="{{ $seat->id }}" id="returnseat{{ $seat->id }}" @disabled($booked)>
                            <label class="btn btn-sm {{ $booked ? 'btn-secondary' : 'btn-outline-primary' }}" for="returnseat{{ $seat->id }}">{{ $seat->code }}</label>
                        @endforeach
                    </div>
                </div></div>
            @endif
        </div>
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm"><div class="card-body">
                <h2 class="h5 fw-bold">Data Pemesan</h2>
                <div class="mb-2"><input class="form-control" name="customer_name" value="{{ auth()->user()->name }}" required></div>
                <div class="mb-2"><input class="form-control" name="customer_email" type="email" value="{{ auth()->user()->email }}" required></div>
                <div class="mb-3"><input class="form-control" name="customer_phone" value="{{ auth()->user()->phone }}" placeholder="Nomor telepon"></div>
                <h3 class="h6 fw-bold">Penumpang</h3>
                @for($i = 0; $i < $passengers; $i++)
                    <div class="border rounded p-2 mb-2">
                        <input class="form-control mb-2" name="passengers[{{ $i }}][name]" placeholder="Nama penumpang {{ $i + 1 }}" required>
                        <input class="form-control mb-2" name="passengers[{{ $i }}][identity_number]" placeholder="Nomor identitas">
                        <select class="form-select mb-2" name="passengers[{{ $i }}][gender]"><option value="">Gender</option><option>male</option><option>female</option></select>
                        <input class="form-control" name="passengers[{{ $i }}][birth_date]" type="date">
                    </div>
                @endfor
                <div class="d-flex justify-content-between my-3"><span>Total</span><strong>Rp {{ number_format(($price->price + ($returnPrice?->price ?? 0)) * $passengers, 0, ',', '.') }}</strong></div>
                <button class="btn btn-success w-100">Buat Booking</button>
            </div></div>
        </div>
    </form>
@endsection
