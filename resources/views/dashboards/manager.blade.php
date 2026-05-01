@extends('layouts.dashboard')

@section('content')
    @include('dashboards.partials.metrics')
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">
            <h2 class="h5 fw-bold">Monitoring Manager</h2>
            <p class="text-muted mb-0">Role manager bersifat monitoring dan approval. Modul laporan dan promo approval akan ditambahkan pada tahap laporan.</p>
        </div>
    </div>
@endsection
