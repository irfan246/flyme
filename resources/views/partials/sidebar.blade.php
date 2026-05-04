@php
    $role = auth()->user()?->role?->name ?? '';
    $resource = request()->route('resource');
    $linkClass = fn (bool $active) => 'rounded px-3 py-2'.($active ? ' active' : '');
@endphp

@if ($role === 'customer')
    <a class="{{ $linkClass(request()->routeIs('customer.dashboard')) }}" href="{{ route('customer.dashboard') }}">Dashboard</a>
    <a class="{{ $linkClass(request()->routeIs('customer.flights.*') || request()->routeIs('customer.bookings.create') || request()->routeIs('customer.bookings.store')) }}" href="{{ route('customer.flights.search') }}">Cari Tiket Flyme</a>
    <a class="{{ $linkClass(request()->routeIs('customer.bookings.history') || request()->routeIs('customer.bookings.show') || request()->routeIs('customer.bookings.payment') || request()->routeIs('customer.bookings.ticket')) }}" href="{{ route('customer.bookings.history') }}">Riwayat Booking</a>
@elseif ($role === 'admin')
    <a class="{{ $linkClass(request()->routeIs('admin.dashboard')) }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a class="{{ $linkClass(request()->routeIs('admin.booking-offline.*')) }}" href="{{ route('admin.booking-offline.create') }}">Booking Offline</a>
    <a class="{{ $linkClass(request()->routeIs('admin.transactions.*')) }}" href="{{ route('admin.transactions.index') }}">Transaksi</a>
    <a class="{{ $linkClass(request()->routeIs('admin.master.*') && $resource === 'customers') }}" href="{{ route('admin.master.index', 'customers') }}">Customer</a>
    <a class="{{ $linkClass((request()->routeIs('admin.master.*') && $resource === 'aircrafts') || request()->routeIs('admin.aircrafts.*')) }}" href="{{ route('admin.master.index', 'aircrafts') }}">Pesawat</a>
    <a class="{{ $linkClass(request()->routeIs('admin.master.*') && $resource === 'airports') }}" href="{{ route('admin.master.index', 'airports') }}">Bandara</a>
    <a class="{{ $linkClass(request()->routeIs('admin.master.*') && $resource === 'cities') }}" href="{{ route('admin.master.index', 'cities') }}">Kota</a>
    <a class="{{ $linkClass(request()->routeIs('admin.master.*') && $resource === 'routes') }}" href="{{ route('admin.master.index', 'routes') }}">Rute</a>
    <a class="{{ $linkClass(request()->routeIs('admin.master.*') && $resource === 'flight-schedules') }}" href="{{ route('admin.master.index', 'flight-schedules') }}">Jadwal</a>
    <a class="{{ $linkClass(request()->routeIs('admin.master.*') && $resource === 'ticket-prices') }}" href="{{ route('admin.master.index', 'ticket-prices') }}">Harga Tiket</a>
    <a class="{{ $linkClass(request()->routeIs('admin.promos.*')) }}" href="{{ route('admin.promos.index') }}">Promo</a>
    <a class="{{ $linkClass(request()->routeIs('admin.faqs.*')) }}" href="{{ route('admin.faqs.index') }}">FAQ</a>
    <a class="{{ $linkClass(request()->routeIs('admin.contacts.*')) }}" href="{{ route('admin.contacts.index') }}">Kontak</a>
@elseif ($role === 'manager')
    <a class="{{ $linkClass(request()->routeIs('manager.dashboard')) }}" href="{{ route('manager.dashboard') }}">Dashboard</a>
    <a class="{{ $linkClass(request()->routeIs('manager.reports.sales*')) }}" href="{{ route('manager.reports.sales') }}">Laporan Penjualan</a>
    <a class="{{ $linkClass(request()->routeIs('manager.reports.occupancy')) }}" href="{{ route('manager.reports.occupancy') }}">Okupansi Kursi</a>
    <a class="{{ $linkClass(request()->routeIs('manager.reports.routes')) }}" href="{{ route('manager.reports.routes') }}">Rute Terlaris</a>
    <a class="{{ $linkClass(request()->routeIs('manager.reports.revenue')) }}" href="{{ route('manager.reports.revenue') }}">Pendapatan</a>
    <a class="{{ $linkClass(request()->routeIs('manager.promos.*')) }}" href="{{ route('manager.promos.index') }}">Approval Promo</a>
@elseif ($role === 'ceo')
    <a class="{{ $linkClass(request()->routeIs('ceo.dashboard')) }}" href="{{ route('ceo.dashboard') }}">Dashboard</a>
    <a class="{{ $linkClass(request()->routeIs('ceo.reports.strategic') || request()->routeIs('ceo.reports.export') || request()->routeIs('ceo.reports.print')) }}" href="{{ route('ceo.reports.strategic') }}">Laporan Strategis</a>
    <a class="{{ $linkClass(request()->routeIs('ceo.reports.performance')) }}" href="{{ route('ceo.reports.performance') }}">Performa Tim</a>
@endif

<a class="{{ $linkClass(request()->routeIs('home')) }}" href="{{ route('home') }}">Website Publik</a>
