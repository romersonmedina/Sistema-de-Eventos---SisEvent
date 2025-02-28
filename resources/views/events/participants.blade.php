@extends('layouts.main')

@section('title', 'Participantes do Evento')

@section('content')

<div class="col-md-10 offset-md-1">
    <!-- Cabeçalho com logo e nome do sistema -->


    <h1>Participantes do Evento: {{ $event->title }}</h1>
    <p><strong>Data do Evento:</strong> {{ \Carbon\Carbon::parse($event->date)->format('d/m/Y') }}</p>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nome</th>
                <th scope="col">Email</th>
                <th scope="col">Data da Inscrição</th> <!-- Nova coluna para a data de inscrição -->
            </tr>
        </thead>
        <tbody>
            @foreach ($event->users as $user)
                <tr>
                    <td scope="row">{{ $loop->index + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ \Carbon\Carbon::parse($user->pivot->created_at)->format('d/m/Y H:i') }}</td> <!-- Exibe a data de inscrição -->
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Botão de impressão -->
    <div style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" class="btn btn-primary">Imprimir Lista</button>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Estilos personalizados para garantir uma boa aparência na impressão */
    @media print {
        body {
            font-family: Arial, sans-serif;
        }
        .col-md-10 {
            width: 100%;
        }
        .btn {
            display: none; /* Esconde o botão de impressão na versão impressa */
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
        }
    }
</style>
@endsection
