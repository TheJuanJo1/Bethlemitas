<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Remisión</title>
</head>
<body>
    <h1>
        Nuevo estudiante remitido.
    </h1>
    <h3>Datos del estudiante:</h3>
    <p><strong>Nombre completo:</strong> {{ $user->name }} {{ $user->last_name }}.</p>
    <p><strong>Número de documento:</strong> {{ $user->number_documment }}</p>
    <p><strong>Grado:</strong> {{ $user->degree->degree }}</p>
    <p><strong>Grupo:</strong> {{ $user->group->group }}</p>
    <p><strong>Motivo de remision:</strong> {{ $referral->reason }}</p>

    <button onclick="window.location.href='#'"> Revisar remisión </button>
</body>
</html>