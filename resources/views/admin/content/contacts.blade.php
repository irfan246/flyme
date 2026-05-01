@extends('layouts.dashboard')

@section('content')
<div class="card border-0 shadow-sm">
    <table class="table mb-0">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Subjek</th>
                <th>Pesan</th>
            </tr>
        </thead>
        <tbody>@foreach($messages as $message)<tr>
                <td>{{ $message->name }}<br><small>{{ $message->email }}</small></td>
                <td>{{ $message->subject }}</td>
                <td>{{ $message->message }}</td>
            </tr>@endforeach</tbody>
    </table>
</div>{{ $messages->links() }}
@endsection