<div class="row g-3">
    @foreach ($stats as $label => $value)
        <div class="col-md-4 col-xl-3">
            <div class="card metric shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">{{ $label }}</div>
                    <div class="fs-4 fw-bold mt-1">{{ $value }}</div>
                </div>
            </div>
        </div>
    @endforeach
</div>
