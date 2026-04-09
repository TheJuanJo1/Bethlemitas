<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remisión de Estudiante</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Helvetica, Arial, sans-serif; background-color: #f8fafc; color: #334155;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border-radius: 16px; overflow: hidden; shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid #e2e8f0;">
                    
                    <tr>
                        <td style="background-color: #4f46e5; padding: 30px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 22px; font-weight: 700; letter-spacing: -0.5px;">
                                Nuevo Estudiante Remitido
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 40px;">
                            <p style="margin-top: 0; margin-bottom: 24px; font-size: 16px; line-height: 1.5;">
                                Cordial saludo, se ha registrado una nueva remisión en la plataforma <strong>PiarManager</strong> con los siguientes detalles:
                            </p>

                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f1f5f9; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
                                <tr>
                                    <td style="padding-bottom: 10px; font-size: 14px;">
                                        <span style="color: #64748b; font-weight: 600; text-transform: uppercase; font-size: 11px;">Estudiante:</span><br>
                                        <strong style="font-size: 16px; color: #1e293b;">
                                            {{ $student->name ?? 'No disponible' }} {{ $student->last_name ?? '' }}
                                        </strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom: 10px; font-size: 14px;">
                                        <span style="color: #64748b; font-weight: 600; text-transform: uppercase; font-size: 11px;">Documento:</span><br>
                                        <span style="color: #334155;">{{ $student->number_documment ?? 'No disponible' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 14px;">
                                        <span style="color: #64748b; font-weight: 600; text-transform: uppercase; font-size: 11px;">Ubicación Académica:</span><br>
                                        <span style="color: #334155;">
                                            Grado: {{ optional($student->degree)->degree ?? 'No asignado' }} | 
                                            Grupo: {{ optional($student->group)->group ?? 'No asignado' }}
                                        </span>
                                    </td>
                                </tr>
                            </table>

                            <div style="border-left: 4px solid #4f46e5; padding-left: 16px; margin-bottom: 30px;">
                                <h3 style="margin: 0 0 8px 0; font-size: 14px; color: #4f46e5; text-transform: uppercase;">Motivo de la Remisión</h3>
                                <p style="margin: 0; font-size: 15px; color: #475569; font-style: italic; line-height: 1.6;">
                                    "{{ $referral->reason ?? 'Sin motivo especificado' }}"
                                </p>
                            </div>

                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url('/index/students/remitted/psico') }}"
                                           style="display: inline-block; padding: 14px 32px; background-color: #4f46e5; color: #ffffff; text-decoration: none; border-radius: 10px; font-weight: 700; font-size: 15px; transition: background-color 0.2s;">
                                            Revisar Remisión en PiarManager
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 20px 40px; background-color: #f8fafc; border-top: 1px solid #e2e8f0; text-align: center;">
                            <p style="margin: 0; font-size: 12px; color: #94a3b8;">
                                Este es un mensaje automático generado por <strong>PiarManager</strong>.<br>
                                Por favor, no responda a este correo.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>