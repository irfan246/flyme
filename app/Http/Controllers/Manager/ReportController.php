<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\FlightSchedule;
use App\Models\Promo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function sales(Request $request): View
    {
        return view('manager.reports.sales', [
            'bookings' => $this->confirmedBookings($request)->paginate(15)->withQueryString(),
            'totalRevenue' => (clone $this->confirmedBookings($request))->sum('total_amount'),
        ]);
    }

    public function occupancy(): View
    {
        $schedules = FlightSchedule::with(['aircraft', 'route.originAirport.city', 'route.destinationAirport.city'])
            ->withCount(['bookings as confirmed_bookings_count' => fn ($query) => $query->where('status', 'confirmed')])
            ->orderByDesc('departure_time')
            ->paginate(15);

        return view('manager.reports.occupancy', compact('schedules'));
    }

    public function popularRoutes(): View
    {
        $routes = Booking::query()
            ->select('flight_schedules.route_id', DB::raw('COUNT(bookings.id) as total_booking'), DB::raw('SUM(bookings.total_amount) as revenue'))
            ->join('flight_schedules', 'flight_schedules.id', '=', 'bookings.flight_schedule_id')
            ->where('bookings.status', 'confirmed')
            ->groupBy('flight_schedules.route_id')
            ->with('flightSchedule.route.originAirport.city', 'flightSchedule.route.destinationAirport.city')
            ->orderByDesc('revenue')
            ->paginate(15);

        return view('manager.reports.routes', compact('routes'));
    }

    public function revenue(): View
    {
        $daily = Booking::where('status', 'confirmed')
            ->selectRaw('DATE(confirmed_at) as period, SUM(total_amount) as revenue, COUNT(*) as bookings')
            ->groupBy('period')
            ->orderByDesc('period')
            ->limit(30)
            ->get();

        return view('manager.reports.revenue', compact('daily'));
    }

    public function promos(): View
    {
        return view('manager.promos.index', ['promos' => Promo::latest()->paginate(10)]);
    }

    public function approvePromo(Request $request, Promo $promo): RedirectResponse
    {
        $promo->update(['status' => 'approved', 'approved_by' => $request->user()->id]);

        return back()->with('success', 'Promo disetujui.');
    }

    public function rejectPromo(Request $request, Promo $promo): RedirectResponse
    {
        $promo->update(['status' => 'rejected', 'approved_by' => $request->user()->id]);

        return back()->with('success', 'Promo ditolak.');
    }

    public function exportSales(Request $request)
    {
        $bookings = $this->confirmedBookings($request)->get();

        return Response::streamDownload(function () use ($bookings): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Kode Booking', 'Customer', 'Email', 'Status', 'Total', 'Tanggal Konfirmasi']);
            foreach ($bookings as $booking) {
                fputcsv($handle, [$booking->booking_code, $booking->customer_name, $booking->customer_email, $booking->status, $booking->total_amount, optional($booking->confirmed_at)->format('Y-m-d H:i')]);
            }
            fclose($handle);
        }, 'laporan-penjualan.csv', ['Content-Type' => 'text/csv']);
    }

    public function printSales(Request $request): View
    {
        return view('reports.print-sales', [
            'bookings' => $this->confirmedBookings($request)->get(),
            'title' => 'Laporan Penjualan Tiket',
        ]);
    }

    private function confirmedBookings(Request $request)
    {
        return Booking::with(['flightSchedule.route.originAirport.city', 'flightSchedule.route.destinationAirport.city', 'ticketClass'])
            ->where('status', 'confirmed')
            ->when($request->from, fn ($query, $date) => $query->whereDate('confirmed_at', '>=', $date))
            ->when($request->to, fn ($query, $date) => $query->whereDate('confirmed_at', '<=', $date))
            ->latest('confirmed_at');
    }
}
