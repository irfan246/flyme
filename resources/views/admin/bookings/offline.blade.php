@extends('layouts.dashboard')

@section('content')
<form class="card border-0 shadow-sm card-body mb-3" method="GET"><label class="form-label">Pilih jadwal dulu</label>
    <div class="row g-2">
        <div class="col-md-9"><select class="form-select" name="flight_schedule_id">@foreach($schedules as $s)<option value="{{ $s->id }}" @selected(request('flight_schedule_id')==$s->id)>{{ $s->flight_number }} - {{ $s->route->originAirport->code }} ke {{ $s->route->destinationAirport->code }} - {{ $s->departure_time->format('d M Y H:i') }}</option>@endforeach</select></div>
        <div class="col-md-3"><button class="btn btn-outline-success w-100">Load Seat</button></div>
    </div>
</form>
@if($schedule)
<form class="row g-4" method="POST" action="{{ route('admin.booking-offline.store') }}">@csrf<input type="hidden" name="flight_schedule_id" value="{{ $schedule->id }}">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm card-body">
            <h2 class="h5">Pilih Kursi</h2>
            <div class="d-grid gap-2" style="grid-template-columns: repeat({{ count(explode(',', $schedule->aircraft->seat_columns)) }}, 52px);">@foreach($schedule->aircraft->seats as $seat)@php($booked = in_array($seat->id, $takenSeatIds, true))<input class="btn-check" type="checkbox" name="seat_ids[]" value="{{ $seat->id }}" id="adminseat{{ $seat->id }}" @disabled($booked)><label class="btn btn-sm {{ $booked ? 'btn-secondary' : 'btn-outline-success' }}" for="adminseat{{ $seat->id }}">{{ $seat->code }}</label>@endforeach</div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm card-body"><select class="form-select mb-2" name="ticket_class_id">@foreach($schedule->ticketPrices as $price)<option value="{{ $price->ticket_class_id }}">{{ $price->ticketClass->name }} - Rp {{ number_format($price->price,0,',','.') }}</option>@endforeach</select><select class="form-select mb-2" name="user_id">
                <option value="">Walk-in customer</option>@foreach($customers as $customer)<option value="{{ $customer->id }}">{{ $customer->name }}</option>@endforeach
            </select><input class="form-control mb-2" name="customer_name" placeholder="Nama customer" required><input class="form-control mb-2" name="customer_email" type="email" placeholder="Email" required><input class="form-control mb-2" name="customer_phone" placeholder="Telepon"><input type="hidden" name="payment_method" value="cash">
            <h3 class="h6">Penumpang</h3>@for($i=0;$i<4;$i++)<div class="border rounded p-2 mb-2"><input class="form-control mb-1" name="passengers[{{ $i }}][name]" placeholder="Nama penumpang {{ $i+1 }}" @required($i===0)><input class="form-control" name="passengers[{{ $i }}][identity_number]" placeholder="Identitas">
        </div>@endfor<small class="text-muted">Isi penumpang sesuai jumlah kursi yang dipilih.</small><button class="btn btn-success w-100 mt-3">Buat Booking Offline</button>
    </div>
    </div>
</form>
@endif
@endsection