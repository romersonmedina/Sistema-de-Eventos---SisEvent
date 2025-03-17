<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Presença - {{ $event->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color: #0044cc;
            color: white;
            padding: 10px 20px;
            text-align: center;
        }

        header img {
            max-height: 50px;
            vertical-align: middle;
        }

        h1 {
            margin-top: 20px;
            text-align: center;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: white;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #0044cc;
            color: white;
        }

        td {
            background-color: #f9f9f9;
        }

        tr:nth-child(even) td {
            background-color: #f1f1f1;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 0.9em;
            color: #888;
        }

    </style>
</head>
<body>
    <header>
        <h1>Lista de Presença Assinada do Evento - {{ $event->title }}</h1>
    </header>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nome do Participante</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendedUsers as $user)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>SisEvents - Sistema de Gestão de Eventos Acadêmicos</p>
        <p>&copy; {{ date('Y') }} - Todos os direitos reservados</p>
        <p>Impresso em {{ date('d/m/Y') }} às {{ date('H:i') }}</p>
    </div>
</body>
</html>
