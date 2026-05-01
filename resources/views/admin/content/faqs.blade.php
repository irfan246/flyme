@extends('layouts.dashboard')

@section('content')
<form class="card border-0 shadow-sm card-body mb-4" method="POST" action="{{ route('admin.faqs.store') }}">@csrf<h2 class="h5">Tambah FAQ</h2><input class="form-control mb-2" name="question" placeholder="Pertanyaan" required><textarea class="form-control mb-2" name="answer" placeholder="Jawaban" required></textarea><label><input type="checkbox" name="is_active" value="1" checked> Aktif</label><button class="btn btn-success mt-2">Simpan</button></form>
<div class="card border-0 shadow-sm">
    <table class="table mb-0">
        <tbody>@foreach($faqs as $faq)<tr>
                <td>{{ $faq->question }}<br><small class="text-muted">{{ $faq->answer }}</small></td>
                <td class="text-end">
                    <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Hapus</button></form>
                </td>
            </tr>@endforeach</tbody>
    </table>
</div>{{ $faqs->links() }}
@endsection