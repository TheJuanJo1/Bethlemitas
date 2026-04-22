<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        /* Optimización para impresión y PDF */
        @page {
            margin: 1cm;
            size: landscape; /* Sugerencia: Las tablas con tantas columnas rinden mejor en horizontal */
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9px; /* Bajamos un poco el tamaño para que quepa todo */
            color: #333;
            line-height: 1.2;
        }

        .header-container {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #f59e0b;
            padding-bottom: 10px;
        }

        h2 {
            margin: 0;
            color: #1a202c;
            text-transform: uppercase;
            font-size: 16px;
        }

        .info-box {
            background: #f8fafc;
            padding: 10px;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .info-box strong {
            color: #64748b;
            text-transform: uppercase;
            font-size: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; /* Fuerza a respetar los anchos */
        }

        table th, table td {
            border: 1px solid #cbd5e1;
            padding: 6px 4px;
            word-wrap: break-word; /* Evita que el texto se salga de la celda */
            vertical-align: top;
        }

        th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8px;
            text-align: center;
        }

        /* Colores sutiles para diferenciar secciones */
        .col-teacher { width: 7%; }
        .col-area { width: 6%; background: #fefce8; }
        .col-main { width: 10%; } /* Objetivos, Barreras */
        .col-ajustes { width: 8%; background: #f0f9ff; }
        .col-social { width: 5%; }
        .col-eval { width: 12%; background: #f0fdf4; font-weight: bold; }

        tr:nth-child(even) {
            background-color: #fcfcfc;
        }

        .text-center { text-align: center; }
        
        .badge {
            display: inline-block;
            padding: 2px 4px;
            background: #e2e8f0;
            border-radius: 3px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="header-container">
        <h2>Reporte Individual de Ajustes Razonables (PIAR)</h2>
        <div style="margin-top: 5px; color: #64748b;">PERIODO {{ $period ?? '1' }}</div>
    </div>

    <div class="info-box">
        <table>
            <tr>
                <td style="border:none; background:none; width: 50%;">
                    <strong>Estudiante:</strong> <span style="font-size: 11px;">{{ $piar->student->name }} {{ $piar->student->last_name }}</span>
                </td>
                <td style="border:none; background:none; width: 50%;">
                    <strong>Grado:</strong> <span style="font-size: 11px;">{{ $piar->student->degree->degree ?? 'Sin grado' }}</span>
                </td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-teacher">Docente</th>
                <th class="col-area">Área</th>
                <th class="col-main">Objetivo</th>
                <th class="col-main">Barrera</th>
                <th class="col-ajustes">Ajuste Curricular</th>
                <th class="col-ajustes">Ajuste Metodol.</th>
                <th class="col-ajustes">Ajuste Evaluat.</th>
                <th class="col-social">Conviv.</th>
                <th class="col-social">Social.</th>
                <th class="col-social">Partic.</th>
                <th class="col-social">Auton.</th>
                <th class="col-social">Autocont.</th>
                <th class="col-eval">Evaluación de los Ajustes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($adjustments as $a)
            <tr>
                <td class="text-center">
                    {{ $a->teacher->name ?? 'N/A' }}<br>
                    <small style="color: #64748b;">{{ $a->teacher->last_name ?? '' }}</small>
                </td>
                <td class="text-center"><strong>{{ $a->area }}</strong></td>
                <td>{{ $a->objetivo }}</td>
                <td>{{ $a->barrera }}</td>
                <td>{{ $a->ajuste_curricular }}</td>
                <td>{{ $a->ajuste_metodologico }}</td>
                <td>{{ $a->ajuste_evaluativo }}</td>
                <td class="text-center">{{ $a->convivencia }}</td>
                <td class="text-center">{{ $a->socializacion }}</td>
                <td class="text-center">{{ $a->participacion }}</td>
                <td class="text-center">{{ $a->autonomia }}</td>
                <td class="text-center">{{ $a->autocontrol }}</td>
                <td class="col-eval">{{ $a->evaluacion }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; font-size: 7px; color: #94a3b8; text-align: right;">
        Documento generado automáticamente - Colegio Bethlemitas
    </div>

</body>
</html>