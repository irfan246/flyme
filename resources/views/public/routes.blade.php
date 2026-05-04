@extends('layouts.app')

@section('content')
<section class="section-shell">
    <div class="container">
        <div class="glass-card page-panel mb-4">
            <span class="page-eyebrow">Jadwal Flyme</span>
            <h1 class="fw-bold mt-3 mb-2">Daftar rute dan jadwal Flyme</h1>
            <p class="text-muted mb-0 section-copy">Lihat pilihan perjalanan Flyme yang tersedia untuk keberangkatan berikutnya dalam tampilan yang lebih rapi dan mudah dipindai.</p>
        </div>

        @include('public.partials.schedule-list', ['schedules' => $schedules, 'returnSchedules' => collect(), 'tripType' => 'one_way', 'passengers' => 1, 'ticketClassId' => null])
    </div>
</section>
@endsection
