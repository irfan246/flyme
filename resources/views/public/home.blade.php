@extends('layouts.app')

@section('content')
    <section class="hero py-5">
        <div class="container py-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <div class="hero-copy">
                        <h1 class="display-3 fw-bold lh-1">Maskapai Ticket Booking System</h1>
                        <p class="lead mt-4 text-muted">Pesan tiket domestik dan luar negeri dengan pilihan pergi saja, pulang saja, atau pulang pergi. Kursi bisa dipilih langsung dari seat map sebelum pembayaran.</p>
                    </div>
                    <div class="d-flex flex-wrap gap-2 mt-4">
                        <a class="btn btn-success px-4" href="{{ route('register') }}">Register Customer</a>
                        <a class="btn btn-outline-secondary px-4" href="{{ route('login') }}">Login Demo</a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <form class="hero-search p-4 p-lg-5" method="GET" action="{{ route('flights.search') }}">
                        <h2 class="h4 fw-bold section-title">Cari Tiket</h2>
                        <p class="text-muted">Atur tipe perjalanan dan temukan jadwal terbaik.</p>
                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="form-label">Asal</label>
                                <select class="form-select" name="origin_airport_id" required>
                                    <option value="">Asal</option>
                                    @foreach ($airports as $airport)
                                        <option value="{{ $airport->id }}">{{ $airport->code }} - {{ $airport->city->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tujuan</label>
                                <select class="form-select" name="destination_airport_id" required>
                                    <option value="">Tujuan</option>
                                    @foreach ($airports as $airport)
                                        <option value="{{ $airport->id }}">{{ $airport->code }} - {{ $airport->city->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6"><label class="form-label">Tanggal Berangkat</label><input class="form-control" name="departure_date" type="date" required></div>
                            <div class="col-md-6"><label class="form-label">Penumpang</label><input class="form-control" name="passengers" type="number" min="1" max="9" value="1"></div>
                            <div class="col-12">
                                <label class="form-label">Kelas</label>
                                <select class="form-select" name="ticket_class_id">
                                    @foreach ($ticketClasses as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Rute</label>
                                <select class="form-select" name="route_scope">
                                    <option value="all">Semua rute</option>
                                    <option value="domestic">Domestik</option>
                                    <option value="international">Luar negeri</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tipe Trip</label>
                                <select class="form-select" name="trip_type">
                                    <option value="one_way">Pergi saja</option>
                                    <option value="return_only">Pulang saja</option>
                                    <option value="round_trip">Pulang pergi</option>
                                </select>
                            </div>
                            <div class="col-12"><label class="form-label">Tanggal Pulang</label><input class="form-control" name="return_date" type="date" title="Tanggal pulang"></div>
                        </div>
                        <button class="btn btn-success w-100 mt-3">Cari Penerbangan</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <h2 class="h3 fw-bold mb-4 section-title">Promo Aktif</h2>
            <div class="row g-3">
                @forelse ($promos as $promo)
                    <div class="col-md-4"><div class="card h-100 border-0 shadow-sm"><div class="card-body p-4"><div class="badge text-bg-success mb-3">{{ $promo->code }}</div><h3 class="h5 fw-bold">{{ $promo->title }}</h3><p class="text-muted mb-0">{{ $promo->description }}</p></div></div></div>
                @empty
                    <p class="text-muted">Belum ada promo aktif.</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection
