@extends('layouts.dashboard')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <form class="card border-0 shadow-sm card-body" method="POST" action="{{ route('customer.bookings.payment.upload', $booking) }}" enctype="multipart/form-data">@csrf<h2 class="h5 fw-bold">Simulasi Pembayaran</h2>
            <p class="text-muted">Transfer simulasi sebesar Rp {{ number_format($booking->total_amount, 0, ',', '.') }} lalu upload bukti pembayaran.</p><select class="form-select mb-3" name="method">
                <option value="bank_transfer">Bank Transfer</option>
                <option value="virtual_account">Virtual Account</option>
            </select><input class="form-control mb-3" type="file" name="proof" required><button class="btn btn-success">Upload Bukti</button>
        </form>
    </div>
</div>
@endsection