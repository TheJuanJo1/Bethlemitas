<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Remisión</title>
</head>
<body>
    <h1>Nuevo estudiante remitido</h1>

    <p><strong>Nombre:</strong> {{ $student->name }} {{ $student->last_name }}</p>
    <p><strong>Documento:</strong> {{ $student->number_documment }}</p>
    <p><strong>Grado:</strong> {{ $student->degree->degree }}</p>
    <p><strong>Grupo:</strong> {{ $student->group->group }}</p>

    <hr>

    <p><strong>Motivo:</strong> {{ $referral->reason }}</p>

    <a href="{{ url('/psico/remisiones') }}"
       style="display:inline-block;padding:10px 20px;background:#007bff;color:#fff;text-decoration:none;border-radius:5px;">
        Revisar remisión
    </a>
</body>
</html>
