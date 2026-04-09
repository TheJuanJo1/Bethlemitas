<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificación de Aceptación PIAR</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Helvetica, Arial, sans-serif; background-color: #f8fafc; color: #334155;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
                    
                    <tr>
                        <td style="background-color: #10b981; padding: 30px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 22px; font-weight: 700; letter-spacing: -0.5px;">
                                Estudiante en Proceso PIAR
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 40px;">
                            <p style="margin-top: 0; margin-bottom: 24px; font-size: 16px; line-height: 1.5;">
                                Estimado docente, le informamos que el psicorientador ha <strong>aceptado</strong> la remisión y el estudiante ha iniciado formalmente su proceso:
                            </p>

                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; border: 1px solid #f1f5f9; border-radius: 12px; padding: 20px; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding-bottom: 12px;">
                                        <span style="color: #94a3b8; font-weight: 700; text-transform: uppercase; font-size: 10px; tracking-widest: 1px;">Nombre Completo</span><br>
                                        <strong style="font-size: 17px; color: #0f172a;">
                                            {{ $student->name ?? 'No disponible' }} {{ $student->last_name ?? '' }}
                                        </strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom: 12px;">
                                        <span style="color: #94a3b8; font-weight: 700; text-transform: uppercase; font-size: 10px;">Número de Documento</span><br>
                                        <span style="color: #334155; font-weight: 500;">{{ $student->number_documment ?? 'No disponible' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span style="color: #94a3b8; font-weight: 700; text-transform: uppercase; font-size: 10px;">Ubicación</span><br>
                                        <span style="color: #334155; font-weight: 500;">
                                            {{ optional($student->degree)->degree ?? 'No asignado' }} — Grupo {{ optional($student->group)->group ?? 'No asignado' }}
                                        </span>
                                    </td>
                                </tr>
                            </table>

                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url('/index/students/remitted/psico') }}"
                                           style="display: inline-block; padding: 14px 32px; background-color: #0f172a; color: #ffffff; text-decoration: none; border-radius: 10px; font-weight: 700; font-size: 15px;">
                                            Ver detalles del proceso
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin-top: 30px; margin-bottom: 0; font-size: 14px; color: #64748b; text-align: center; font-style: italic;">
                                Ya puede comenzar a cargar evidencias o realizar el seguimiento correspondiente.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 20px 40px; background-color: #f1f5f9; text-align: center;">
                            <p style="margin: 0; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 600;">
                                Sistema de Gestión Académica PiarManager
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>