@extends('layouts.dashboard')

@section('content')
    @include('dashboards.partials.metrics')
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">
            <h2 class="h5 fw-bold">Notifikasi</h2>
            @forelse ($notifications as $notification)
                <div class="border-bottom py-2">
                    <div class="fw-semibold">{{ $notification->title }}</div>
                    <div class="text-muted small">{{ $notification->message }}</div>
                </div>
            @empty
                <p class="text-muted mb-0">Belum ada notifikasi.</p>
            @endforelse
        </div>
    </div>
@endsection
