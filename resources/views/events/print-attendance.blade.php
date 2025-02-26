<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Presença - {{ $event->title }}</title>
</head>
<body>
    <h1>Lista de Presença - {{ $event->title }}</h1>

    <table border="1" cellspacing="0" cellpadding="10">
        <thead>
            <tr>
                <th>#</th>
                <th>Nome do Participante</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendedUsers as $user)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $user->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
