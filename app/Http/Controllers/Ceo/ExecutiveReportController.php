<?php

namespace App\Http\Controllers\Ceo;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\FlightSchedule;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;

class ExecutiveReportController extends Controller
{
    public function strategic(): View
    {
        return view('ceo.reports.strategic', [
            'totalRevenue' => Booking::where('status', 'confirmed')->sum('total_amount'),
            'totalBooking' => Booking::count(),
            'totalCustomer' => User::whereHas('role', fn ($role) => $role->where('name', 'customer'))->count(),
            'totalFlights' => FlightSchedule::count(),
            'revenueSeries' => Booking::where('status', 'confirmed')
                ->selectRaw('DATE(confirmed_at) as period, SUM(total_amount) as revenue')
                ->groupBy('period')
                ->orderBy('period')
                ->limit(12)
                ->get(),
            'topRoutes' => Booking::query()
                ->select('flight_schedules.route_id', DB::raw('SUM(bookings.total_amount) as revenue'), DB::raw('COUNT(bookings.id) as bookings'))
                ->join('flight_schedules', 'flight_schedules.id', '=', 'bookings.flight_schedule_id')
                ->where('bookings.status', 'confirmed')
                ->groupBy('flight_schedules.route_id')
                ->orderByDesc('revenue')
                ->limit(5)
                ->get(),
        ]);
    }

    public function performance(): View
    {
        $admins = User::whereHas('role', fn ($role) => $role->whereIn('name', ['admin', 'manager']))
            ->withCount('createdBookings as handled_bookings_count')
            ->get();

        return view('ceo.reports.performance', compact('admins'));
    }

    public function exportExecutive()
    {
        $rows = [
            ['Metric', 'Value'],
            ['Total Revenue', Booking::where('status', 'confirmed')->sum('total_amount')],
            ['Total Booking', Booking::count()],
            ['Total Jadwal Flyme', FlightSchedule::count()],
        ];

        return Response::streamDownload(function () use ($rows): void {
            $handle = fopen('php://output', 'w');
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }, 'laporan-eksekutif.csv', ['Content-Type' => 'text/csv']);
    }

    public function printExecutive(): View
    {
        return view('reports.print-executive', [
            'totalRevenue' => Booking::where('status', 'confirmed')->sum('total_amount'),
            'totalBooking' => Booking::count(),
            'totalFlights' => FlightSchedule::count(),
        ]);
    }
}
