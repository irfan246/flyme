<div class="vstack gap-3">
    @forelse ($schedules as $schedule)
        @php
            $price = $ticketClassId ? $schedule->ticketPrices->firstWhere('ticket_class_id', $ticketClassId) : $schedule->ticketPrices->first();
            $returnSchedule = null;
            $returnPrice = null;

            if (($tripType ?? 'one_way') === 'round_trip') {
                $returnSchedule = ($returnSchedules ?? collect())
                    ->filter(fn ($candidate) => $candidate->route->origin_airport_id === $schedule->route->destination_airport_id && $candidate->route->destination_airport_id === $schedule->route->origin_airport_id)
                    ->first();

                if ($returnSchedule) {
                    $returnPrice = $ticketClassId ? $returnSchedule->ticketPrices->firstWhere('ticket_class_id', $ticketClassId) : $returnSchedule->ticketPrices->first();
                }
            }
        @endphp
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex flex-column flex-lg-row justify-content-between gap-3">
                <div>
                    <div class="badge text-bg-success">{{ $schedule->flight_number }}</div>
                    <h2 class="h5 mt-2">{{ $schedule->route->originAirport->city->name }} ({{ $schedule->route->originAirport->code }}) ke {{ $schedule->route->destinationAirport->city->name }} ({{ $schedule->route->destinationAirport->code }})</h2>
                    <div class="text-muted">{{ $schedule->departure_time->format('d M Y H:i') }} - {{ $schedule->arrival_time->format('H:i') }} | {{ $schedule->aircraft->model }}</div>
                    @if(($tripType ?? 'one_way') === 'round_trip')
                        @if($returnSchedule)
                            <div class="mt-3 rounded-3 bg-light p-3">
                                <div class="small text-muted">Penerbangan pulang</div>
                                <strong>{{ $returnSchedule->flight_number }}</strong>
                                <span class="text-muted">{{ $returnSchedule->route->originAirport->code }} ke {{ $returnSchedule->route->destinationAirport->code }} | {{ $returnSchedule->departure_time->format('d M Y H:i') }}</span>
                            </div>
                        @else
                            <div class="alert alert-warning mt-3 mb-0 py-2">Belum ada jadwal pulang yang cocok.</div>
                        @endif
                    @endif
                </div>
                <div class="text-lg-end">
                    <div class="small text-muted">Mulai dari</div>
                    <div class="fs-5 fw-bold">Rp {{ number_format(($price?->price ?? 0) + ($returnPrice?->price ?? 0), 0, ',', '.') }}</div>
                    @auth
                        @if(auth()->user()->hasRole('customer'))
                        <a class="btn btn-success btn-sm mt-2 {{ (($tripType ?? 'one_way') === 'round_trip' && ! $returnSchedule) ? 'disabled' : '' }}" href="{{ route('customer.bookings.create', ['schedule' => $schedule, 'passengers' => $passengers, 'ticket_class_id' => $price?->ticket_class_id, 'return_schedule_id' => $returnSchedule?->id, 'trip_type' => $tripType ?? 'one_way']) }}">Pilih Kursi</a>
                        @else
                            <a class="btn btn-outline-secondary btn-sm mt-2" href="{{ route(auth()->user()->dashboardRoute()) }}">Buka Dashboard</a>
                        @endif
                    @else
                        <a class="btn btn-outline-success btn-sm mt-2" href="{{ route('login') }}">Login untuk booking</a>
                    @endauth
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info">Tidak ada jadwal yang cocok.</div>
    @endforelse
</div>
<div class="mt-4">{{ $schedules->links() }}</div>
