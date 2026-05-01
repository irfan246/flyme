<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use App\Models\ContactMessage;
use App\Models\Faq;
use App\Models\FlightSchedule;
use App\Models\Promo;
use App\Models\TicketClass;
use App\Services\BookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicPageController extends Controller
{
    public function home(BookingService $bookingService): View
    {
        $bookingService->expirePendingBookings();

        return view('public.home', [
            'airports' => Airport::with('city')->orderBy('code')->get(),
            'ticketClasses' => TicketClass::orderBy('id')->get(),
            'promos' => Promo::where('status', 'approved')->latest()->limit(3)->get(),
        ]);
    }

    public function about(): View
    {
        return view('public.simple', [
            'title' => 'Tentang Maskapai',
            'body' => 'Airline Management & Ticket Booking System disiapkan sebagai platform maskapai terpadu untuk customer, admin, manager, dan CEO.',
        ]);
    }

    public function routes(): View
    {
        return view('public.routes', [
            'schedules' => FlightSchedule::with(['route.originAirport.city', 'route.destinationAirport.city', 'aircraft', 'ticketPrices.ticketClass'])
                ->where('departure_time', '>=', now())
                ->orderBy('departure_time')
                ->paginate(10),
        ]);
    }

    public function promos(): View
    {
        return view('public.promos', [
            'promos' => Promo::where('status', 'approved')->latest()->paginate(9),
        ]);
    }

    public function faq(): View
    {
        return view('public.faq', [
            'faqs' => Faq::where('is_active', true)->latest()->get(),
        ]);
    }

    public function contact(): View
    {
        return view('public.contact');
    }

    public function sendContact(Request $request): RedirectResponse
    {
        ContactMessage::create($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:3000'],
        ]));

        return back()->with('success', 'Pesan Anda berhasil dikirim.');
    }

    public function search(Request $request, BookingService $bookingService): View
    {
        $bookingService->expirePendingBookings();

        $filters = $request->validate([
            'origin_airport_id' => ['nullable', 'exists:airports,id'],
            'destination_airport_id' => ['nullable', 'exists:airports,id'],
            'departure_date' => ['nullable', 'date'],
            'passengers' => ['nullable', 'integer', 'min:1', 'max:9'],
            'ticket_class_id' => ['nullable', 'exists:ticket_classes,id'],
            'route_scope' => ['nullable', 'in:all,domestic,international'],
            'trip_type' => ['nullable', 'in:one_way,return_only,round_trip'],
            'return_date' => ['nullable', 'date'],
        ]);

        $originAirportId = $filters['origin_airport_id'] ?? null;
        $destinationAirportId = $filters['destination_airport_id'] ?? null;

        if (($filters['trip_type'] ?? 'one_way') === 'return_only') {
            [$originAirportId, $destinationAirportId] = [$destinationAirportId, $originAirportId];
        }

        $schedules = FlightSchedule::with(['route.originAirport.city', 'route.destinationAirport.city', 'aircraft', 'ticketPrices.ticketClass'])
            ->where('departure_time', '>=', now())
            ->when($originAirportId, fn ($query, $airport) => $query->whereHas('route', fn ($route) => $route->where('origin_airport_id', $airport)))
            ->when($destinationAirportId, fn ($query, $airport) => $query->whereHas('route', fn ($route) => $route->where('destination_airport_id', $airport)))
            ->when($filters['departure_date'] ?? null, fn ($query, $date) => $query->whereDate('departure_time', $date))
            ->when($filters['ticket_class_id'] ?? null, fn ($query, $class) => $query->whereHas('ticketPrices', fn ($price) => $price->where('ticket_class_id', $class)))
            ->when(($filters['route_scope'] ?? 'all') === 'domestic', fn ($query) => $query->whereHas('route', fn ($route) => $route
                ->whereHas('originAirport.city', fn ($city) => $city->where('country', 'Indonesia'))
                ->whereHas('destinationAirport.city', fn ($city) => $city->where('country', 'Indonesia'))))
            ->when(($filters['route_scope'] ?? 'all') === 'international', fn ($query) => $query->whereHas('route', fn ($route) => $route
                ->where(fn ($nested) => $nested
                    ->whereHas('originAirport.city', fn ($city) => $city->where('country', '!=', 'Indonesia'))
                    ->orWhereHas('destinationAirport.city', fn ($city) => $city->where('country', '!=', 'Indonesia')))))
            ->orderBy('departure_time')
            ->paginate(10)
            ->withQueryString();

        $returnSchedules = collect();
        if (($filters['trip_type'] ?? null) === 'round_trip' && $originAirportId && $destinationAirportId) {
            $returnSchedules = FlightSchedule::with(['route.originAirport.city', 'route.destinationAirport.city', 'aircraft', 'ticketPrices.ticketClass'])
                ->where('departure_time', '>=', $filters['return_date'] ?? now()->toDateString())
                ->whereHas('route', fn ($route) => $route
                    ->where('origin_airport_id', $destinationAirportId)
                    ->where('destination_airport_id', $originAirportId))
                ->orderBy('departure_time')
                ->get();
        }

        return view('public.search', [
            'schedules' => $schedules,
            'returnSchedules' => $returnSchedules,
            'airports' => Airport::with('city')->orderBy('code')->get(),
            'ticketClasses' => TicketClass::orderBy('id')->get(),
            'filters' => $filters,
        ]);
    }
}
