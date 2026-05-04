@extends('layouts.app')

@section('content')
<section class="section-shell">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="glass-card page-panel h-100">
                    <span class="page-eyebrow">Kontak Flyme</span>
                    <h1 class="fw-bold mt-3">Butuh bantuan?</h1>
                    <p class="text-muted mb-0">Kirim pertanyaan, saran, atau kebutuhan bantuan booking Flyme lewat form di samping. Tampilan form juga sudah dibuat lebih nyaman dipakai di layar kecil.</p>
                </div>
            </div>
            <div class="col-lg-7">
                <form class="glass-card page-panel h-100" method="POST" action="{{ route('contact.send') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Nama</label><input class="form-control" name="name" placeholder="Nama" required></div>
                        <div class="col-md-6"><label class="form-label">Email</label><input class="form-control" name="email" type="email" placeholder="Email" required></div>
                        <div class="col-12"><label class="form-label">Subjek</label><input class="form-control" name="subject" placeholder="Subjek" required></div>
                        <div class="col-12"><label class="form-label">Pesan</label><textarea class="form-control" name="message" rows="5" placeholder="Pesan" required></textarea></div>
                        <div class="col-12"><button class="btn btn-success w-100">Kirim Pesan</button></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
