@extends('layouts.app')

@section('content')
    <section class="py-5">
        <div class="container py-4">
            <h1 class="fw-bold">{{ $title }}</h1>
            <p class="lead text-muted mt-3">{{ $body }}</p>
        </div>
    </section>
@endsection
