@extends('layouts.app')

@section('content')
    <section class="hero py-5">
        <div class="container py-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <h1 class="display-4 fw-bold">Airline Management & Ticket Booking System</h1>
                    <p class="lead mt-3 opacity-75">Pesan tiket domestik dan luar negeri dengan pilihan pergi saja, pulang saja, atau pulang pergi. Kursi bisa dipilih langsung dari seat map sebelum pembayaran.</p>
                    <div class="d-flex flex-wrap gap-2 mt-4">
                        <a class="btn btn-light" href="{{ route('register') }}">Register Customer</a>
                        <a class="btn btn-outline-light" href="{{ route('login') }}">Login Demo</a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <form class="bg-white text-dark rounded-4 shadow p-4 p-lg-5" method="GET" action="{{ route('flights.search') }}">
                        <h2 class="h4 fw-bold">Cari Tiket</h2>
                        <p class="text-muted">Atur tipe perjalanan dan temukan jadwal terbaik.</p>
                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <select class="form-select" name="origin_airport_id" required>
                                    <option value="">Asal</option>
                                    @foreach ($airports as $airport)
                                        <option value="{{ $airport->id }}">{{ $airport->code }} - {{ $airport->city->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <select class="form-select" name="destination_airport_id" required>
                                    <option value="">Tujuan</option>
                                    @foreach ($airports as $airport)
                                        <option value="{{ $airport->id }}">{{ $airport->code }} - {{ $airport->city->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6"><input class="form-control" name="departure_date" type="date" required></div>
                            <div class="col-md-6"><input class="form-control" name="passengers" type="number" min="1" max="9" value="1"></div>
                            <div class="col-12">
                                <select class="form-select" name="ticket_class_id">
                                    @foreach ($ticketClasses as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <select class="form-select" name="route_scope">
                                    <option value="all">Semua rute</option>
                                    <option value="domestic">Domestik</option>
                                    <option value="international">Luar negeri</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <select class="form-select" name="trip_type">
                                    <option value="one_way">Pergi saja</option>
                                    <option value="return_only">Pulang saja</option>
                                    <option value="round_trip">Pulang pergi</option>
                                </select>
                            </div>
                            <div class="col-12"><input class="form-control" name="return_date" type="date" title="Tanggal pulang"></div>
                        </div>
                        <button class="btn btn-success w-100 mt-3">Cari Penerbangan</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <h2 class="h4 fw-bold mb-3">Promo Aktif</h2>
            <div class="row g-3">
                @forelse ($promos as $promo)
                    <div class="col-md-4"><div class="card h-100 border-0 shadow-sm"><div class="card-body"><div class="badge text-bg-success mb-2">{{ $promo->code }}</div><h3 class="h5">{{ $promo->title }}</h3><p class="text-muted">{{ $promo->description }}</p></div></div></div>
                @empty
                    <p class="text-muted">Belum ada promo aktif.</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection
