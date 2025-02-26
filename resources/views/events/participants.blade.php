@extends('layouts.main')

@section('title', 'Participantes do Evento')

@section('content')
<div class="col-md-10 offset-md-1">
    <h1>Participantes do Evento: {{ $event->title }}</h1>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nome</th>
                <th scope="col">Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($event->users as $user)
                <tr>
                    <td scope="row">{{ $loop->index + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Você pode adicionar um botão para imprimir a lista -->
    <button onclick="window.print()" class="btn btn-primary">Imprimir Lista</button>
</div>
@endsection
