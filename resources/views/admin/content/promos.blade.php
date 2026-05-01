@extends('layouts.dashboard')

@section('content')
<form class="card border-0 shadow-sm card-body mb-4" method="POST" action="{{ route('admin.promos.store') }}">@csrf<h2 class="h5">Buat Promo</h2>
    <div class="row g-2">
        <div class="col-md-3"><input class="form-control" name="title" placeholder="Judul" required></div>
        <div class="col-md-2"><input class="form-control" name="code" placeholder="Kode" required></div>
        <div class="col-md-2"><input class="form-control" name="discount_percent" type="number" placeholder="%" required></div>
        <div class="col-md-2"><input class="form-control" name="start_date" type="date" required></div>
        <div class="col-md-2"><input class="form-control" name="end_date" type="date" required></div>
        <div class="col-md-1"><button class="btn btn-success w-100">+</button></div>
        <div class="col-12"><textarea class="form-control" name="description" placeholder="Deskripsi"></textarea></div>
    </div>
</form>
<div class="card border-0 shadow-sm">
    <table class="table mb-0">
        <thead>
            <tr>
                <th>Promo</th>
                <th>Status</th>
                <th>Diskon</th>
            </tr>
        </thead>
        <tbody>@foreach($promos as $promo)<tr>
                <td>{{ $promo->title }}<br><small>{{ $promo->code }}</small></td>
                <td>{{ $promo->status }}</td>
                <td>{{ $promo->discount_percent }}%</td>
            </tr>@endforeach</tbody>
    </table>
</div>{{ $promos->links() }}
@endsection