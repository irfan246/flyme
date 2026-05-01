@extends('layouts.auth')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4 p-md-5">
            <div class="mb-4 text-center">
                <h1 class="h3 fw-bold">Login</h1>
                <p class="text-muted mb-0">Masuk ke dashboard sesuai role Anda.</p>
            </div>
            <form method="POST" action="{{ route('login.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="email">Email</label>
                    <input class="form-control" id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">Password</label>
                    <input class="form-control" id="password" name="password" type="password" required>
                </div>
                <div class="form-check mb-4">
                    <input class="form-check-input" id="remember" name="remember" type="checkbox" value="1">
                    <label class="form-check-label" for="remember">Ingat saya</label>
                </div>
                <button class="btn btn-success w-100" type="submit">Login</button>
            </form>
            <div class="text-center mt-4">
                <a href="{{ route('register') }}">Belum punya akun customer?</a>
            </div>
            <hr>
            <p class="small text-muted mb-0">Demo: customer@example.com, admin@example.com, manager@example.com, ceo@example.com. Password semua akun: password.</p>
        </div>
    </div>
@endsection
