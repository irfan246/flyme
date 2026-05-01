@extends('layouts.app')

@section('content')
<section class="py-5">
    <div class="container">
        <h1 class="fw-bold mb-4">FAQ</h1>
        <div class="accordion" id="faqList">@foreach($faqs as $faq)<div class="accordion-item">
                <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $faq->id }}">{{ $faq->question }}</button></h2>
                <div id="faq{{ $faq->id }}" class="accordion-collapse collapse" data-bs-parent="#faqList">
                    <div class="accordion-body">{{ $faq->answer }}</div>
                </div>
            </div>@endforeach</div>
    </div>
</section>
@endsection