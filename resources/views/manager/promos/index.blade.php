@extends('layouts.dashboard')

@section('content')
<div class="card border-0 shadow-sm">
    <table class="table mb-0 align-middle">
        <thead>
            <tr>
                <th>Promo</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>@foreach($promos as $promo)<tr>
                <td>{{ $promo->title }}<br><small>{{ $promo->code }} - {{ $promo->discount_percent }}%</small></td>
                <td>{{ $promo->status }}</td>
                <td>@if($promo->status === 'pending')<form class="d-inline" method="POST" action="{{ route('manager.promos.approve', $promo) }}">@csrf<button class="btn btn-sm btn-success">Approve</button></form>
                    <form class="d-inline" method="POST" action="{{ route('manager.promos.reject', $promo) }}">@csrf<button class="btn btn-sm btn-outline-danger">Reject</button></form>@endif
                </td>
            </tr>@endforeach</tbody>
    </table>
</div>{{ $promos->links() }}
@endsection