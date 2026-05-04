@extends('layouts.app')

@section('content')
    <section class="section-shell">
        <div class="container py-2">
            <div class="glass-card page-panel">
                <span class="page-eyebrow">Tentang Flyme</span>
                <h1 class="fw-bold mt-3">{{ $title }}</h1>
                <p class="lead text-muted mt-3 mb-0">{{ $body }}</p>
            </div>
        </div>
    </section>
@endsection
