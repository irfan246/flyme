@extends('layouts.app')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-5">
                <h1 class="fw-bold">Kontak</h1>
                <p class="text-muted">Kirim pertanyaan, saran, atau kebutuhan bantuan booking.</p>
            </div>
            <div class="col-lg-7">
                <form class="card border-0 shadow-sm card-body" method="POST" action="{{ route('contact.send') }}">@csrf<div class="row g-3">
                        <div class="col-md-6"><input class="form-control" name="name" placeholder="Nama" required></div>
                        <div class="col-md-6"><input class="form-control" name="email" type="email" placeholder="Email" required></div>
                        <div class="col-12"><input class="form-control" name="subject" placeholder="Subjek" required></div>
                        <div class="col-12"><textarea class="form-control" name="message" rows="5" placeholder="Pesan" required></textarea></div>
                        <div class="col-12"><button class="btn btn-success">Kirim</button></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection