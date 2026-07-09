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
            page-break-inside: avoid;
        }
        tr {
            page-break-inside: avoid;
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
            <td>Colegio Sagrado Corazón de Jesús Hermanas Bethlemitas Pereira</td>
        </tr>
        <tr>
            <td class="campo">Sede:</td>
            <td>Pereira</td>
            <td class="campo">Jornada:</td>
            <td>Única</td>
        </tr>
        <tr>
            <td class="campo" style="vertical-align: top;">Docentes que elaboran:</td>
            <td colspan="3" style="font-size: 10px; line-height: 1.4;">
                @php
                    $docentesElaboranCompleto = \App\Services\PiarFirmasResolver::docentesConAreas($piar->id, [1, 2, 3]);
                @endphp
                @forelse($docentesElaboranCompleto as $item)
                    <strong>Docente {{ $item['teacher']->name }} {{ $item['teacher']->last_name }}</strong>
                    — {{ \App\Services\PiarFirmasResolver::formatearEtiquetaAreas($item['areas']) }}<br>
                @empty
                    Sin docentes registrados.
                @endforelse
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
            <th class="titulo-seccion">1. Características del Estudiante</th>
        </tr>
    </table>

    <table style="margin-top: 5px; table-layout: fixed; width: 100%;">
        <tr>
            <th style="text-align: left; background: #f2f2f2; font-size: 9px; padding: 6px; line-height: 1.4; font-weight: bold;">
                Descripción general del estudiante con énfasis en gustos e intereses o aspectos que le desagradan, expectativas del estudiante y la familia:
            </th>
        </tr>
        <tr>
            <td style="padding: 8px; min-height: 80px; font-size: 10px; line-height: 1.4;">
                {!! $piar->characteristics->descripcion_estudiante ?? '' !!}
            </td>
        </tr>
    </table>

    <table style="margin-top: 10px; table-layout: fixed; width: 100%;">
        <tr>
            <th style="text-align: left; background: #f2f2f2; font-size: 9px; padding: 6px; line-height: 1.4; font-weight: bold;">
                Descripción en términos de lo que hace, puede hacer o requiere apoyo el estudiante para favorecer su proceso educativo. Indique las habilidades, competencias, cualidades y aprendizajes con los que cuenta el estudiante para el grado en el que fue matriculado:
            </th>
        </tr>
        <tr>
            <td style="padding: 8px; min-height: 80px; font-size: 10px; line-height: 1.4;">
                {!! $piar->characteristics->habilidades ?? '' !!}
            </td>
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
                        <th style="width: 12%;">ÁREAS</th>
                        <th style="width: 22%;">OBJETIVOS / PROPÓSITOS (EBC / DBA)</th>
                        <th style="width: 22%;">BARRERAS EVIDENCIADAS</th>
                        <th style="width: 24%;">AJUSTES RAZONABLES (Apoyos)</th>
                        <th style="width: 20%;">EVALUACIÓN DE AJUSTES (SIEE)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-weight: bold; text-align: center; vertical-align: middle; background: #fafafa; font-size: 9px;">
                            {{ $row->area ?? 'N/A' }}
                        </td>
                        <td>{{ $row->cleanField('objetivo') }}</td>
                        <td>{{ $row->cleanField('barrera') }}</td>
                        <td>
                            <div style="margin-bottom: 6px;">
                                <strong>Ajuste Currícular:</strong><br>
                                {{ $row->cleanField('ajuste_curricular') ?: 'N/A' }}
                            </div>
                            <div style="margin-bottom: 6px;">
                                <strong>Ajuste Metodologíco:</strong><br>
                                {{ $row->cleanField('ajuste_metodologico') ?: 'N/A' }}
                            </div>
                            <div>
                                <strong>Ajuste Evaluativo:</strong><br>
                                {{ $row->cleanField('ajuste_evaluativo') ?: 'N/A' }}
                            </div>
                        </td>
                        <td>{{ $row->cleanField('evaluacion') }}</td>
                    </tr>

                    <tr style="background: #f2f2f2;">
                        <th colspan="5" style="text-align: left; font-size: 8px; font-weight: bold; padding: 4px 6px;">
                            OTROS AJUSTES
                        </th>
                    </tr>
                    <tr>
                        <td colspan="2"><strong>Convivencia:</strong></td>
                        <td colspan="3">{{ $row->cleanField('convivencia') ?: 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong>Socialización:</strong></td>
                        <td colspan="3">{{ $row->cleanField('socializacion') ?: 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong>Participación:</strong></td>
                        <td colspan="3">{{ $row->cleanField('participacion') ?: 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong>Autonomía:</strong></td>
                        <td colspan="3">{{ $row->cleanField('autonomia') ?: 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong>Autocontrol:</strong></td>
                        <td colspan="3">{{ $row->cleanField('autocontrol') ?: 'N/A' }}</td>
                    </tr>
                </tbody>
            </table>
        @endforeach
    @endforeach

    @php
        $firmasDocentesAnexo2 = \App\Services\PiarFirmasResolver::firmasDocentesAnexo2($piar->id, [1, 2, 3], true);
    @endphp
    @include('pdf.partials.anexo2_firmas_docentes', ['firmasDocentesAnexo2' => $firmasDocentesAnexo2])

</body>
</html>