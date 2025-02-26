<!-- resources/views/emails/attendance_liberated.blade.php -->

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Presença Liberada</title>
</head>
<body>
    <h1>Olá, {{ $user->name }}!</h1>
    <p>A lista de presença para o evento <strong>"{{ $eventTitle }}"</strong> foi liberada!</p>
    <p>Você pode assinar a lista de presença acessando o link abaixo:</p>
    <p><a href="{{ $eventLink }}">Clique aqui para assinar a lista de presença</a></p>
</body>
</html>
