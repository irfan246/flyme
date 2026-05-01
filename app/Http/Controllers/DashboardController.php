<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\FlightSchedule;
use App\Models\Notification;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function customer(): View
    {
        return view('dashboards.customer', [
            'pageTitle' => 'Customer Dashboard',
            'stats' => [
                'Booking Aktif' => Booking::where('user_id', auth()->id())->whereIn('status', ['pending', 'paid', 'confirmed'])->count(),
                'Menunggu Pembayaran' => Booking::where('user_id', auth()->id())->where('status', 'pending')->count(),
                'E-ticket' => Booking::where('user_id', auth()->id())->where('status', 'confirmed')->count(),
            ],
            'notifications' => Notification::where('user_id', auth()->id())->latest()->limit(5)->get(),
        ]);
    }

    public function admin(): View
    {
        return view('dashboards.admin', [
            'pageTitle' => 'Admin Dashboard',
            'stats' => [
                'Customer' => User::whereHas('role', fn ($query) => $query->where('name', 'customer'))->count(),
                'Booking Hari Ini' => Booking::whereDate('created_at', today())->count(),
                'Pembayaran Pending' => Booking::whereIn('status', ['pending', 'paid'])->count(),
            ],
        ]);
    }

    public function manager(): View
    {
        return view('dashboards.manager', [
            'pageTitle' => 'Manager Dashboard',
            'stats' => [
                'Penjualan Bulan Ini' => 'Rp '.number_format(Booking::where('status', 'confirmed')->whereMonth('confirmed_at', now()->month)->sum('total_amount'), 0, ',', '.'),
                'Okupansi Rata-rata' => FlightSchedule::count() ? round((Booking::where('status', 'confirmed')->count() / max(1, FlightSchedule::with('aircraft')->get()->sum(fn ($schedule) => $schedule->aircraft?->capacity ?? 0))) * 100, 1).'%' : '0%',
                'Promo Menunggu Approval' => \App\Models\Promo::where('status', 'pending')->count(),
            ],
        ]);
    }

    public function ceo(): View
    {
        return view('dashboards.ceo', [
            'pageTitle' => 'CEO Dashboard',
            'stats' => [
                'Total Revenue' => 'Rp '.number_format(Booking::where('status', 'confirmed')->sum('total_amount'), 0, ',', '.'),
                'Total Booking' => Booking::count(),
                'Total Customer' => User::whereHas('role', fn ($query) => $query->where('name', 'customer'))->count(),
                'Total Penerbangan' => FlightSchedule::count(),
            ],
        ]);
    }
}
