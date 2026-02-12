<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Remisión</title>
</head>
<body>
    <h1>Nuevo estudiante remitido</h1>

    <p>
        <strong>Nombre:</strong>
        {{ $student->name ?? 'No disponible' }}
        {{ $student->last_name ?? '' }}
    </p>

    <p>
        <strong>Documento:</strong>
        {{ $student->number_documment ?? 'No disponible' }}
    </p>

    <p>
        <strong>Grado:</strong>
        {{ optional($student->degree)->degree ?? 'No asignado' }}
    </p>

    <p>
        <strong>Grupo:</strong>
        {{ optional($student->group)->group ?? 'No asignado' }}
    </p>

    <hr>

    <p>
        <strong>Motivo:</strong>
        {{ $referral->reason ?? 'Sin motivo especificado' }}
    </p>

    <a href="{{ url('/psico/remisiones') }}"
       style="display:inline-block;padding:10px 20px;background:#007bff;color:#fff;text-decoration:none;border-radius:5px;">
        Revisar remisión
    </a>
</body>
</html>