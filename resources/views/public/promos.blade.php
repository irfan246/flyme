@extends('layouts.app')

@section('content')
<section class="py-5">
    <div class="container">
        <h1 class="fw-bold mb-4">Promo</h1>
        <div class="row g-3">@forelse($promos as $promo)<div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body"><span class="badge text-bg-success">{{ $promo->code }}</span>
                        <h2 class="h5 mt-2">{{ $promo->title }}</h2>
                        <p class="text-muted">{{ $promo->description }}</p>
                        <div class="fw-bold">{{ $promo->discount_percent }}% OFF</div>
                    </div>
                </div>
            </div>@empty <p class="text-muted">Belum ada promo.</p>@endforelse</div>
        <div class="mt-4">{{ $promos->links() }}</div>
    </div>
</section>
@endsection