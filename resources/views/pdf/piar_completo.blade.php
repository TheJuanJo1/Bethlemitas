<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 0.5cm;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            margin-top: 100px;
            margin-bottom: 60px;
            line-height: 1.2;
        }
        .header {
            position: fixed;
            top: -10px;
            left: 0;
            right: 0;
            text-align: center;
        }
        .header img {
            width: 100%;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #333;
        }
        h1 {
            text-align: center;
            font-size: 16px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        h3 {
            background: #e5e5e5;
            padding: 5px;
            text-align: center;
            margin-top: 10px;
            border: 1px solid #000;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            table-layout: fixed;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 4px;
            vertical-align: top;
            word-wrap: break-word;
        }
        th {
            background: #f2f2f2;
            text-align: center;
            font-size: 9px;
            font-weight: bold;
        }
        .titulo-seccion {
            background: #f2f2f2;
            font-weight: bold;
            text-align: center;
            font-size: 12px;
        }
        .campo {
            background: #f7f7f7;
            font-weight: bold;
            width: 25%;
        }
        .caja-grande {
            min-height: 80px;
        }
        .page-break {
            page-break-before: always;
        }
        .vertical-text {
            width: 25px;
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8px;
            background: #f9f9f9;
        }
        .inner-label {
            font-weight: bold;
            background: #f2f2f2;
            width: 90px;
        }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('img/credencialesGo.jpg') }}">
    </div>

    <div class="footer">
        V14.16/02/2018. - Ministerio de Educación Nacional – Decreto 1421 de 2017
    </div>

    <h1>
        PLAN INDIVIDUAL DE AJUSTES RAZONABLES – PIAR –<br>
        ANEXO 2
    </h1>

    <table>
        <tr>
            <td class="campo">Fecha de elaboración:</td>
            <td>{{ $piar->created_at->format('d/m/Y') }}</td>
            <td class="campo">Institución Educativa:</td>
            <td>Bethelemitas</td>
        </tr>
        <tr>
            <td class="campo">Sede:</td>
            <td>Pereira</td>
            <td class="campo">Jornada:</td>
            <td>Diurna</td>
        </tr>
        <tr>
            <td class="campo">Docentes que elaboran:</td>
            <td colspan="3">
                @if($periodo1->count() > 0)
                    {{ $periodo1->first()->teacher->name ?? '' }}
                    {{ $periodo1->first()->teacher->last_name ?? '' }}
                @endif
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="4" class="titulo-seccion">DATOS DEL ESTUDIANTE</td>
        </tr>
        <tr>
            <td class="campo">Nombre del estudiante</td>
            <td>
                {{ $piar->student->name ?? '' }}
                {{ $piar->student->last_name ?? '' }}
            </td>
            <td class="campo">Documento</td>
            <td>{{ $piar->student->number_documment ?? '' }}</td>
        </tr>
        <tr>
            <td class="campo">Edad</td>
            <td>{{ $piar->student->age ?? '' }}</td>
            <td class="campo">Grado</td>
            <td>{{ $piar->student->degree->degree ?? 'Sin grado' }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="2" class="titulo-seccion">1. Características del Estudiante</td>
        </tr>
        <tr>
            <td class="campo">Descripción</td>
            <td class="caja-grande">{{ $piar->characteristics->descripcion_estudiante ?? '' }}</td>
        </tr>
        <tr>
            <td class="campo">Gustos e intereses</td>
            <td class="caja-grande">{{ $piar->characteristics->gustos_intereses ?? '' }}</td>
        </tr>
        <tr>
            <td class="campo">Expectativas familia</td>
            <td class="caja-grande">{{ $piar->characteristics->expectativas_familia ?? '' }}</td>
        </tr>
        <tr>
            <td class="campo">Habilidades</td>
            <td class="caja-grande">{{ $piar->characteristics->habilidades ?? '' }}</td>
        </tr>
        <tr>
            <td class="campo">Apoyos requeridos</td>
            <td class="caja-grande">{{ $piar->characteristics->apoyos_requeridos ?? '' }}</td>
        </tr>
    </table>
    @php
        $periodos = [
            ['id' => 1, 'nombre' => '1 Periodo', 'data' => $periodo1],
            ['id' => 2, 'nombre' => '2 Periodo', 'data' => $periodo2],
            ['id' => 3, 'nombre' => '3 Periodo', 'data' => $periodo3]
        ];
    @endphp

    @foreach($periodos as $p)
        <div class="page-break"></div>
        <h3>{{ strtoupper($p['nombre']) }}</h3>

        @foreach($p['data'] as $row)
            <table>
                <thead>
                    <tr>
                        <th style="width: 30px;">ÁREAS</th>
                        <th style="width: 23%;">OBJETIVOS / PROPÓSITOS (EBC / DBA)</th>
                        <th style="width: 23%;">BARRERAS EVIDENCIADAS</th>
                        <th style="width: 28%;">AJUSTES RAZONABLES (Apoyos)</th>
                        <th>EVALUACIÓN DE AJUSTES (SIEE)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td rowspan="3" class="vertical-text">
                            @foreach(str_split($row->area ?? 'AREA') as $char)
                                {{ $char }}<br>
                            @endforeach
                        </td>
                        <td rowspan="3">{{ $row->objetivo ?? '' }}</td>
                        <td rowspan="3">{{ $row->barrera ?? '' }}</td>
                        <td style="height: 40px;"><strong>Currículo:</strong><br>{{ $row->ajuste_curricular ?? '' }}</td>
                        <td rowspan="3">{{ $row->evaluacion ?? '' }}</td>
                    </tr>
                    <tr>
                        <td style="height: 40px;"><strong>Metodología:</strong><br>{{ $row->ajuste_metodologico ?? '' }}</td>
                    </tr>
                    <tr>
                        <td style="height: 40px;"><strong>Evaluación:</strong><br>{{ $row->ajuste_evaluativo ?? '' }}</td>
                    </tr>

                    <tr>
                        <td rowspan="5" class="vertical-text">O<br>T<br>R<br>A<br>S</td>
                        <td class="inner-label">Convivencia</td>
                        <td colspan="3">{{ $row->convivencia ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="inner-label">Socialización</td>
                        <td colspan="3">{{ $row->socializacion ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="inner-label">Participación</td>
                        <td colspan="3">{{ $row->participacion ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="inner-label">Autonomía</td>
                        <td colspan="3">{{ $row->autonomia ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="inner-label">Autocontrol</td>
                        <td colspan="3">{{ $row->autocontrol ?? '' }}</td>
                    </tr>
                </tbody>
            </table>
            <div style="height: 10px;"></div>
        @endforeach
    @endforeach

</body>
</html>