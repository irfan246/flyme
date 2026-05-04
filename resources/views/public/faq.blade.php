@extends('layouts.app')

@section('content')
<section class="section-shell">
    <div class="container">
        <div class="glass-card page-panel">
            <span class="page-eyebrow">FAQ Flyme</span>
            <h1 class="fw-bold mt-3 mb-2">Pertanyaan yang sering ditanyakan</h1>
            <p class="text-muted section-copy mb-4">Temukan jawaban cepat seputar pemesanan tiket, perjalanan, dan proses booking di Flyme.</p>

            <div class="accordion" id="faqList">
                @foreach($faqs as $faq)
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $faq->id }}">{{ $faq->question }}</button>
                        </h2>
                        <div id="faq{{ $faq->id }}" class="accordion-collapse collapse" data-bs-parent="#faqList">
                            <div class="accordion-body">{{ $faq->answer }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection
