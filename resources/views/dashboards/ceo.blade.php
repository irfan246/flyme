@extends('layouts.dashboard')

@section('content')
    @include('dashboards.partials.metrics')
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">
            <h2 class="h5 fw-bold">Ringkasan Eksekutif</h2>
            <p class="text-muted mb-0">CEO memiliki akses read-only untuk ringkasan bisnis. Grafik revenue dan laporan strategis akan terhubung setelah data booking tersedia.</p>
        </div>
    </div>
@endsection
