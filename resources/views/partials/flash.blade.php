@if (session('success'))
    <div class="container mt-3">
        <div class="alert alert-success mb-0">{{ session('success') }}</div>
    </div>
@endif

@if (session('error'))
    <div class="container mt-3">
        <div class="alert alert-danger mb-0">{{ session('error') }}</div>
    </div>
@endif

@if ($errors->any())
    <div class="container mt-3">
        <div class="alert alert-danger mb-0">
            <strong>Periksa kembali input Anda.</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
