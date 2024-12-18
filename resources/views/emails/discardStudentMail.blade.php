<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Remisión</title>
</head>
<body>
    <h1>Estudiante descartado para entrar a proceso PIAR.</h1>
    <p>Datos del estudiante:</p>
    <p><span style="font-weight: bold;">Nombre completo:</span> {{ $student->name }} {{ $student->last_name }}.</p>
    <p><span style="font-weight: bold;">Número de documento:</span> {{ $student->number_documment }}</p>
    <p><span style="font-weight: bold;">Grado:</span> {{ $student->degree->degree }}</p>
    <p><span style="font-weight: bold;">Grupo:</span> {{ $student->group->group }}</p>

    <a href="https://laravel.com/docs/11.x/mail#queueing-mail" 
       style="display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; font-size: 16px; text-align: center;">
        Revisar remisión
    </a>
</body>
</html>