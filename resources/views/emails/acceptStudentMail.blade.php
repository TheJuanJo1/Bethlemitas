<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Remisión</title>
</head>
<body>
    <h1>Nuevo estudiante en proceso PIAR.</h1>

    <p>Datos del estudiante:</p>

    <p>
        <span style="font-weight: bold;">Nombre completo:</span>
        {{ $student->name ?? 'No disponible' }}
        {{ $student->last_name ?? '' }}.
    </p>

    <p>
        <span style="font-weight: bold;">Número de documento:</span>
        {{ $student->number_documment ?? 'No disponible' }}
    </p>

    <p>
        <span style="font-weight: bold;">Grado:</span>
        {{ optional($student->degree)->degree ?? 'No asignado' }}
    </p>

    <p>
        <span style="font-weight: bold;">Grupo:</span>
        {{ optional($student->group)->group ?? 'No asignado' }}
    </p>

    <a href="{{ url('/index/students/remitted/psico') }}"
       style="display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; font-size: 16px; text-align: center;">
        Revisar remisión
    </a>
</body>
</html>