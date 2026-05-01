<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\FlightSchedule;
use App\Models\TicketClass;
use App\Models\User;
use App\Services\BookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingManagementController extends Controller
{
    public function transactions(Request $request): View
    {
        $bookings = Booking::with(['user', 'flightSchedule.route.originAirport.city', 'flightSchedule.route.destinationAirport.city', 'ticketClass', 'payment'])
            ->when($request->status, fn ($query, $status) => $query->where('status', $status))
            ->when($request->search, fn ($query, $search) => $query->where(fn ($q) => $q->where('booking_code', 'like', "%{$search}%")->orWhere('customer_name', 'like', "%{$search}%")))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.bookings.transactions', compact('bookings'));
    }

    public function offlineForm(Request $request, BookingService $bookingService): View
    {
        $schedule = $request->flight_schedule_id ? FlightSchedule::with(['aircraft.seats', 'route.originAirport.city', 'route.destinationAirport.city', 'ticketPrices.ticketClass'])->find($request->flight_schedule_id) : null;
        $takenSeatIds = $schedule ? $bookingService->bookedSeatIds($schedule) : [];

        return view('admin.bookings.offline', [
            'customers' => User::whereHas('role', fn ($role) => $role->where('name', 'customer'))->orderBy('name')->get(),
            'schedules' => FlightSchedule::with(['route.originAirport.city', 'route.destinationAirport.city', 'ticketPrices.ticketClass'])->where('departure_time', '>=', now())->orderBy('departure_time')->get(),
            'ticketClasses' => TicketClass::orderBy('id')->get(),
            'schedule' => $schedule,
            'takenSeatIds' => $takenSeatIds,
        ]);
    }

    public function offlineStore(Request $request, BookingService $bookingService): RedirectResponse
    {
        $request->merge([
            'passengers' => collect($request->input('passengers', []))
                ->filter(fn ($passenger) => filled($passenger['name'] ?? null))
                ->values()
                ->all(),
        ]);

        $validated = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'flight_schedule_id' => ['required', 'exists:flight_schedules,id'],
            'ticket_class_id' => ['required', 'exists:ticket_classes,id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:30'],
            'payment_method' => ['required', 'string', 'max:50'],
            'seat_ids' => ['required', 'array', 'min:1'],
            'seat_ids.*' => ['required', 'exists:seats,id'],
            'passengers' => ['required', 'array', 'min:1'],
            'passengers.*.name' => ['required', 'string', 'max:255'],
            'passengers.*.identity_number' => ['nullable', 'string', 'max:100'],
            'passengers.*.gender' => ['nullable', 'string', 'max:20'],
            'passengers.*.birth_date' => ['nullable', 'date'],
        ]);

        $user = isset($validated['user_id']) ? User::find($validated['user_id']) : null;
        $booking = $bookingService->createBooking($validated, $user, $request->user(), 'offline');

        return redirect()->route('admin.transactions.show', $booking)->with('success', 'Booking offline berhasil dibuat.');
    }

    public function show(Booking $booking): View
    {
        $booking->load(['user', 'flightSchedule.route.originAirport.city', 'flightSchedule.route.destinationAirport.city', 'flightSchedule.aircraft', 'ticketClass', 'passengers', 'bookingSeats.seat', 'payment']);

        return view('admin.bookings.show', compact('booking'));
    }

    public function confirmPayment(Request $request, Booking $booking, BookingService $bookingService): RedirectResponse
    {
        $bookingService->confirmPayment($booking, $request->user());

        return back()->with('success', 'Pembayaran dan booking berhasil dikonfirmasi.');
    }

    public function cancel(Booking $booking, BookingService $bookingService): RedirectResponse
    {
        $bookingService->cancel($booking);

        return back()->with('success', 'Booking berhasil dibatalkan.');
    }
}
