<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Presença Liberada</title>
</head>
<body>
    <p>Olá {{ $event->user->name }},</p>
    <p>A lista de presença para o evento "{{ $event->title }}" foi liberada. Clique no link abaixo para marcar sua presença:</p>
    <<a href="{{ url('events/' . $event->id . '/mark-attendance') }}" class="btn btn-primary">Marcar presença</a>
</body>
</html>
