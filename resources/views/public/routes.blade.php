@extends('layouts.app')

@section('content')
<section class="py-5">
    <div class="container">
        <h1 class="fw-bold mb-4">Daftar Rute Penerbangan</h1>@include('public.partials.schedule-list', ['schedules' => $schedules, 'returnSchedules' => collect(), 'tripType' => 'one_way', 'passengers' => 1, 'ticketClassId' => null])
    </div>
</section>
@endsection
