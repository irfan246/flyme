@extends('layouts.dashboard')

@section('content')
<form class="card border-0 shadow-sm card-body mb-4" method="POST" action="{{ route('admin.aircrafts.seats.generate', $aircraft) }}">@csrf<h2 class="h5 fw-bold">Seat Map {{ $aircraft->registration_code }}</h2>
    <div class="row g-2">
        <div class="col-md-3"><input class="form-control" name="seat_rows" type="number" value="{{ $aircraft->seat_rows }}"></div>
        <div class="col-md-5"><input class="form-control" name="seat_columns" value="{{ $aircraft->seat_columns }}"></div>
        <div class="col-md-4"><button class="btn btn-success w-100">Generate Seat</button></div>
    </div>
</form>
<div class="card border-0 shadow-sm card-body">
    <div class="d-grid gap-2" style="grid-template-columns: repeat({{ count(explode(',', $aircraft->seat_columns)) }}, 56px);">@foreach($aircraft->seats as $seat)<span class="btn btn-outline-success btn-sm">{{ $seat->code }}</span>@endforeach</div>
</div>
@endsection