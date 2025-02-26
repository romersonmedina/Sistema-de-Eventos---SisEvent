<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Participantes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Relatório de Participantes - Evento: {{ $event->name }}</h2>

    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Status de Participação</th>
                <th>Data de Inscrição</th>
            </tr>
        </thead>
        <tbody>
            @foreach($participants as $checkin)
                <tr>
                    <td>{{ $checkin->user->name }}</td>
                    <td>{{ $checkin->status }}</td>
                    <td>{{ $checkin->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
