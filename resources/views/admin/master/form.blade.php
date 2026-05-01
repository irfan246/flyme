@extends('layouts.dashboard')

@section('content')
@php($isEdit = filled($item))
<form class="card border-0 shadow-sm card-body" method="POST" action="{{ $isEdit ? route('admin.master.update', [$resource, $item->id]) : route('admin.master.store', $resource) }}">
    @csrf @if($isEdit) @method('PUT') @endif
    <h2 class="h5 fw-bold mb-3">{{ $isEdit ? 'Edit' : 'Tambah' }} {{ $title }}</h2>
    <div class="row g-3">
        @if($resource === 'customers')
        <div class="col-md-6"><label class="form-label">Nama</label><input class="form-control" name="name" value="{{ old('name', $item->name ?? '') }}" required></div>
        <div class="col-md-6"><label class="form-label">Email</label><input class="form-control" name="email" type="email" value="{{ old('email', $item->email ?? '') }}" required></div>
        <div class="col-md-6"><label class="form-label">Telepon</label><input class="form-control" name="phone" value="{{ old('phone', $item->phone ?? '') }}"></div>
        <div class="col-md-6"><label class="form-label">Password</label><input class="form-control" name="password" type="password" @required(!$isEdit)></div>
        <div class="col-12"><label class="form-label">Alamat</label><textarea class="form-control" name="address">{{ old('address', $item->address ?? '') }}</textarea></div>
        @elseif($resource === 'cities')
        <div class="col-md-4"><label class="form-label">Nama</label><input class="form-control" name="name" value="{{ old('name', $item->name ?? '') }}" required></div>
        <div class="col-md-4"><label class="form-label">Provinsi</label><input class="form-control" name="province" value="{{ old('province', $item->province ?? '') }}"></div>
        <div class="col-md-4"><label class="form-label">Negara</label><input class="form-control" name="country" value="{{ old('country', $item->country ?? 'Indonesia') }}" required></div>
        @elseif($resource === 'airports')
        <div class="col-md-4"><label class="form-label">Kota</label><select class="form-select" name="city_id">@foreach($cities as $city)<option value="{{ $city->id }}" @selected(old('city_id', $item->city_id ?? '') == $city->id)>{{ $city->name }}</option>@endforeach</select></div>
        <div class="col-md-3"><label class="form-label">Kode</label><input class="form-control" name="code" value="{{ old('code', $item->code ?? '') }}" required></div>
        <div class="col-md-5"><label class="form-label">Nama</label><input class="form-control" name="name" value="{{ old('name', $item->name ?? '') }}" required></div>
        <div class="col-12"><label class="form-label">Alamat</label><textarea class="form-control" name="address">{{ old('address', $item->address ?? '') }}</textarea></div>
        @elseif($resource === 'aircrafts')
        <div class="col-md-3"><label class="form-label">Registrasi</label><input class="form-control" name="registration_code" value="{{ old('registration_code', $item->registration_code ?? '') }}" required></div>
        <div class="col-md-3"><label class="form-label">Model</label><input class="form-control" name="model" value="{{ old('model', $item->model ?? '') }}" required></div>
        <div class="col-md-2"><label class="form-label">Rows</label><input class="form-control" name="seat_rows" type="number" value="{{ old('seat_rows', $item->seat_rows ?? 8) }}"></div>
        <div class="col-md-2"><label class="form-label">Columns</label><input class="form-control" name="seat_columns" value="{{ old('seat_columns', $item->seat_columns ?? 'A,B,C,D') }}"></div>
        <div class="col-md-2"><label class="form-label">Capacity</label><input class="form-control" name="capacity" type="number" value="{{ old('capacity', $item->capacity ?? 32) }}"></div>
        <div class="col-md-3"><label class="form-label">Status</label><input class="form-control" name="status" value="{{ old('status', $item->status ?? 'active') }}"></div>
        @elseif($resource === 'routes')
        <div class="col-md-4"><label class="form-label">Asal</label><select class="form-select" name="origin_airport_id">@foreach($airports as $airport)<option value="{{ $airport->id }}" @selected(old('origin_airport_id', $item->origin_airport_id ?? '') == $airport->id)>{{ $airport->code }} - {{ $airport->city->name }}</option>@endforeach</select></div>
        <div class="col-md-4"><label class="form-label">Tujuan</label><select class="form-select" name="destination_airport_id">@foreach($airports as $airport)<option value="{{ $airport->id }}" @selected(old('destination_airport_id', $item->destination_airport_id ?? '') == $airport->id)>{{ $airport->code }} - {{ $airport->city->name }}</option>@endforeach</select></div>
        <div class="col-md-4"><label class="form-label">Kode</label><input class="form-control" name="code" value="{{ old('code', $item->code ?? '') }}" required></div>
        <div class="col-md-4"><label class="form-label">Jarak KM</label><input class="form-control" name="distance_km" type="number" value="{{ old('distance_km', $item->distance_km ?? 1) }}"></div>
        <div class="col-md-4"><label class="form-label">Durasi Menit</label><input class="form-control" name="duration_minutes" type="number" value="{{ old('duration_minutes', $item->duration_minutes ?? 60) }}"></div>
        <div class="col-md-4 form-check mt-5"><input class="form-check-input" type="checkbox" name="is_active" value="1" @checked(old('is_active', $item->is_active ?? true))><label class="form-check-label">Aktif</label></div>
        @elseif($resource === 'flight-schedules')
        <div class="col-md-3"><label class="form-label">Flight Number</label><input class="form-control" name="flight_number" value="{{ old('flight_number', $item->flight_number ?? '') }}" required></div>
        <div class="col-md-3"><label class="form-label">Pesawat</label><select class="form-select" name="aircraft_id">@foreach($aircrafts as $aircraft)<option value="{{ $aircraft->id }}" @selected(old('aircraft_id', $item->aircraft_id ?? '') == $aircraft->id)>{{ $aircraft->registration_code }}</option>@endforeach</select></div>
        <div class="col-md-6"><label class="form-label">Rute</label><select class="form-select" name="route_id">@foreach($routes as $route)<option value="{{ $route->id }}" @selected(old('route_id', $item->route_id ?? '') == $route->id)>{{ $route->code }} - {{ $route->originAirport->code }} ke {{ $route->destinationAirport->code }}</option>@endforeach</select></div>
        <div class="col-md-4"><label class="form-label">Berangkat</label><input class="form-control" name="departure_time" type="datetime-local" value="{{ old('departure_time', isset($item) ? $item->departure_time->format('Y-m-d\\TH:i') : '') }}"></div>
        <div class="col-md-4"><label class="form-label">Tiba</label><input class="form-control" name="arrival_time" type="datetime-local" value="{{ old('arrival_time', isset($item) ? $item->arrival_time->format('Y-m-d\\TH:i') : '') }}"></div>
        <div class="col-md-4"><label class="form-label">Status</label><input class="form-control" name="status" value="{{ old('status', $item->status ?? 'scheduled') }}"></div>
        @elseif($resource === 'ticket-classes')
        <div class="col-md-4"><label class="form-label">Nama</label><input class="form-control" name="name" value="{{ old('name', $item->name ?? '') }}" required></div>
        <div class="col-md-3"><label class="form-label">Kode</label><input class="form-control" name="code" value="{{ old('code', $item->code ?? '') }}" required></div>
        <div class="col-12"><label class="form-label">Deskripsi</label><textarea class="form-control" name="description">{{ old('description', $item->description ?? '') }}</textarea></div>
        @elseif($resource === 'ticket-prices')
        <div class="col-md-5"><label class="form-label">Jadwal</label><select class="form-select" name="flight_schedule_id">@foreach($schedules as $schedule)<option value="{{ $schedule->id }}" @selected(old('flight_schedule_id', $item->flight_schedule_id ?? '') == $schedule->id)>{{ $schedule->flight_number }}</option>@endforeach</select></div>
        <div class="col-md-3"><label class="form-label">Kelas</label><select class="form-select" name="ticket_class_id">@foreach($ticketClasses as $class)<option value="{{ $class->id }}" @selected(old('ticket_class_id', $item->ticket_class_id ?? '') == $class->id)>{{ $class->name }}</option>@endforeach</select></div>
        <div class="col-md-2"><label class="form-label">Harga</label><input class="form-control" name="price" type="number" value="{{ old('price', $item->price ?? 0) }}"></div>
        <div class="col-md-2"><label class="form-label">Quota</label><input class="form-control" name="quota" type="number" value="{{ old('quota', $item->quota ?? 1) }}"></div>
        @endif
    </div>
    <div class="mt-4"><button class="btn btn-success">Simpan</button><a class="btn btn-outline-secondary" href="{{ route('admin.master.index', $resource) }}">Kembali</a></div>
</form>
@endsection