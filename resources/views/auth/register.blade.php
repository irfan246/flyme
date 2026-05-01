@extends('layouts.auth')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4 p-md-5">
            <div class="mb-4 text-center">
                <h1 class="h3 fw-bold">Register Customer</h1>
                <p class="text-muted mb-0">Akun baru otomatis mendapatkan role customer.</p>
            </div>
            <form method="POST" action="{{ route('register.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="name">Nama</label>
                    <input class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="email">Email</label>
                    <input class="form-control" id="email" name="email" type="email" value="{{ old('email') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="phone">Nomor Telepon</label>
                    <input class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="address">Alamat</label>
                    <textarea class="form-control" id="address" name="address" rows="3">{{ old('address') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">Password</label>
                    <input class="form-control" id="password" name="password" type="password" required>
                </div>
                <div class="mb-4">
                    <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
                    <input class="form-control" id="password_confirmation" name="password_confirmation" type="password" required>
                </div>
                <button class="btn btn-success w-100" type="submit">Buat Akun</button>
            </form>
            <div class="text-center mt-4">
                <a href="{{ route('login') }}">Sudah punya akun?</a>
            </div>
        </div>
    </div>
@endsection
