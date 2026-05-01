<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Airport;
use App\Models\Booking;
use App\Models\FlightSchedule;
use App\Models\Seat;
use App\Models\TicketClass;
use App\Models\TicketPrice;
use App\Services\BookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function search(Request $request, BookingService $bookingService): View
    {
        $bookingService->expirePendingBookings();

        $schedules = FlightSchedule::with(['route.originAirport.city', 'route.destinationAirport.city', 'aircraft', 'ticketPrices.ticketClass'])
            ->where('departure_time', '>=', now())
            ->when($this->originAirport($request), fn ($query, $airport) => $query->whereHas('route', fn ($route) => $route->where('origin_airport_id', $airport)))
            ->when($this->destinationAirport($request), fn ($query, $airport) => $query->whereHas('route', fn ($route) => $route->where('destination_airport_id', $airport)))
            ->when($request->departure_date, fn ($query, $date) => $query->whereDate('departure_time', $date))
            ->when($request->route_scope === 'domestic', fn ($query) => $query->whereHas('route', fn ($route) => $route
                ->whereHas('originAirport.city', fn ($city) => $city->where('country', 'Indonesia'))
                ->whereHas('destinationAirport.city', fn ($city) => $city->where('country', 'Indonesia'))))
            ->when($request->route_scope === 'international', fn ($query) => $query->whereHas('route', fn ($route) => $route
                ->where(fn ($nested) => $nested
                    ->whereHas('originAirport.city', fn ($city) => $city->where('country', '!=', 'Indonesia'))
                    ->orWhereHas('destinationAirport.city', fn ($city) => $city->where('country', '!=', 'Indonesia')))))
            ->orderBy('departure_time')
            ->paginate(10)
            ->withQueryString();

        $returnSchedules = collect();
        if ($request->trip_type === 'round_trip' && $request->origin_airport_id && $request->destination_airport_id) {
            $returnSchedules = FlightSchedule::with(['route.originAirport.city', 'route.destinationAirport.city', 'aircraft', 'ticketPrices.ticketClass'])
                ->where('departure_time', '>=', $request->return_date ?: now()->toDateString())
                ->whereHas('route', fn ($route) => $route
                    ->where('origin_airport_id', $request->destination_airport_id)
                    ->where('destination_airport_id', $request->origin_airport_id))
                ->orderBy('departure_time')
                ->get();
        }

        return view('customer.bookings.search', [
            'airports' => Airport::with('city')->orderBy('code')->get(),
            'ticketClasses' => TicketClass::orderBy('id')->get(),
            'schedules' => $schedules,
            'returnSchedules' => $returnSchedules,
        ]);
    }

    public function create(FlightSchedule $schedule, Request $request, BookingService $bookingService): View
    {
        $schedule->load(['aircraft.seats', 'route.originAirport.city', 'route.destinationAirport.city', 'ticketPrices.ticketClass']);
        $returnSchedule = $request->integer('return_schedule_id')
            ? FlightSchedule::with(['aircraft.seats', 'route.originAirport.city', 'route.destinationAirport.city', 'ticketPrices.ticketClass'])->findOrFail($request->integer('return_schedule_id'))
            : null;
        $ticketClassId = $request->integer('ticket_class_id') ?: $schedule->ticketPrices->first()?->ticket_class_id;
        $passengers = max(1, min(9, $request->integer('passengers') ?: 1));
        $selectedClass = TicketClass::findOrFail($ticketClassId);
        $price = TicketPrice::where('flight_schedule_id', $schedule->id)->where('ticket_class_id', $ticketClassId)->firstOrFail();
        $returnPrice = $returnSchedule ? TicketPrice::where('flight_schedule_id', $returnSchedule->id)->where('ticket_class_id', $ticketClassId)->firstOrFail() : null;
        $takenSeatIds = $bookingService->bookedSeatIds($schedule);
        $takenReturnSeatIds = $returnSchedule ? $bookingService->bookedSeatIds($returnSchedule) : [];

        return view('customer.bookings.create', compact('schedule', 'returnSchedule', 'selectedClass', 'price', 'returnPrice', 'takenSeatIds', 'takenReturnSeatIds', 'passengers'));
    }

    public function store(Request $request, BookingService $bookingService): RedirectResponse
    {
        $validated = $request->validate($this->bookingRules());
        $booking = $bookingService->createBooking($validated, $request->user(), null, 'online');

        return redirect()->route('customer.bookings.show', $booking)->with('success', 'Booking berhasil dibuat. Silakan lakukan pembayaran.');
    }

    public function history(Request $request): View
    {
        $bookings = Booking::with(['flightSchedule.route.originAirport.city', 'flightSchedule.route.destinationAirport.city', 'ticketClass', 'payment'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('customer.bookings.history', compact('bookings'));
    }

    public function show(Request $request, Booking $booking): View
    {
        abort_unless($booking->user_id === $request->user()->id, 403);

        $booking->load(['flightSchedule.route.originAirport.city', 'flightSchedule.route.destinationAirport.city', 'returnFlightSchedule.route.originAirport.city', 'returnFlightSchedule.route.destinationAirport.city', 'flightSchedule.aircraft', 'returnFlightSchedule.aircraft', 'ticketClass', 'passengers', 'bookingSeats.seat', 'payment']);

        return view('customer.bookings.show', compact('booking'));
    }

    public function cancel(Request $request, Booking $booking, BookingService $bookingService): RedirectResponse
    {
        abort_unless($booking->user_id === $request->user()->id, 403);
        $bookingService->cancel($booking);

        return redirect()->route('customer.bookings.history')->with('success', 'Booking berhasil dibatalkan.');
    }

    public function payment(Request $request, Booking $booking): View
    {
        abort_unless($booking->user_id === $request->user()->id, 403);
        $booking->load('payment');

        return view('customer.bookings.payment', compact('booking'));
    }

    public function uploadPayment(Request $request, Booking $booking): RedirectResponse
    {
        abort_unless($booking->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'method' => ['required', Rule::in(['bank_transfer', 'virtual_account', 'cash'])],
            'proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        $path = $request->file('proof')->store('payment-proofs', 'public');

        $booking->payment()->update([
            'method' => $data['method'],
            'status' => 'paid',
            'proof_path' => $path,
            'paid_at' => now(),
        ]);
        $booking->update(['status' => 'paid']);

        return redirect()->route('customer.bookings.show', $booking)->with('success', 'Bukti pembayaran berhasil diupload. Tunggu konfirmasi admin.');
    }

    public function ticket(Request $request, Booking $booking): View
    {
        abort_unless($booking->user_id === $request->user()->id || $request->user()->hasRole(['admin', 'manager', 'ceo']), 403);
        abort_unless($booking->status === 'confirmed', 403);

        $booking->load(['flightSchedule.route.originAirport.city', 'flightSchedule.route.destinationAirport.city', 'returnFlightSchedule.route.originAirport.city', 'returnFlightSchedule.route.destinationAirport.city', 'flightSchedule.aircraft', 'returnFlightSchedule.aircraft', 'ticketClass', 'passengers', 'bookingSeats.seat', 'payment']);

        return view('tickets.eticket', compact('booking'));
    }

    private function bookingRules(): array
    {
        return [
            'flight_schedule_id' => ['required', 'exists:flight_schedules,id'],
            'ticket_class_id' => ['required', 'exists:ticket_classes,id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:30'],
            'trip_type' => ['nullable', 'in:one_way,return_only,round_trip'],
            'return_flight_schedule_id' => ['nullable', 'exists:flight_schedules,id'],
            'outbound_seat_ids' => ['required', 'array', 'min:1'],
            'outbound_seat_ids.*' => ['required', 'exists:seats,id'],
            'return_seat_ids' => ['nullable', 'array'],
            'return_seat_ids.*' => ['required', 'exists:seats,id'],
            'passengers' => ['required', 'array', 'min:1'],
            'passengers.*.name' => ['required', 'string', 'max:255'],
            'passengers.*.identity_number' => ['nullable', 'string', 'max:100'],
            'passengers.*.gender' => ['nullable', 'string', 'max:20'],
            'passengers.*.birth_date' => ['nullable', 'date'],
        ];
    }

    private function originAirport(Request $request): ?int
    {
        if ($request->trip_type === 'return_only') {
            return $request->integer('destination_airport_id') ?: null;
        }

        return $request->integer('origin_airport_id') ?: null;
    }

    private function destinationAirport(Request $request): ?int
    {
        if ($request->trip_type === 'return_only') {
            return $request->integer('origin_airport_id') ?: null;
        }

        return $request->integer('destination_airport_id') ?: null;
    }
}
