@extends('layouts.dashboard')

@section('content')
    @include('dashboards.partials.metrics')
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">
            <h2 class="h5 fw-bold">Area Kerja Admin</h2>
            <p class="text-muted mb-0">Fondasi admin sudah diproteksi role. CRUD master data, booking offline, dan konfirmasi pembayaran masuk pada Tahap 2-4.</p>
        </div>
    </div>
@endsection
