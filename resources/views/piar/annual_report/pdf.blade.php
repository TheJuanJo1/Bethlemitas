<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe Anual de Inclusión - {{ $student->name }} {{ $student->last_name }}</title>
    <style>
        @page { size: letter; margin: 1.2cm; }
        body { font-family: Arial, sans-serif; font-size: 9.5px; color: #000; line-height: 1.3; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #000; padding: 5px; vertical-align: top; }
        .bg-gray { background: #f3f4f6; }
        h1 { font-size: 12.5px; margin: 2px 0; text-transform: uppercase; font-weight: bold; }
        h2 { font-size: 10.5px; margin: 1px 0; text-transform: uppercase; color: #4b5563; }
        .text-center { text-align: center; }
        .no-break { page-break-inside: avoid; }
        
        /* Estilos de firmas */
        .firma-grid { width: 100%; border: none; margin-top: 15px; }
        .firma-grid td { border: none; padding: 10px 15px; vertical-align: bottom; }
        .firma-box { height: 45px; border-bottom: 1px solid #000; text-align: center; position: relative; }
        .firma-box img { max-height: 42px; max-width: 100%; display: block; margin: 0 auto; }
        .firma-label { font-size: 7.5px; font-weight: bold; text-transform: uppercase; margin-top: 3px; }
        .firma-name { font-size: 8.5px; font-weight: bold; }
        .firma-title { font-size: 7.5px; font-style: italic; color: #444; }
        .sin-firma { font-size: 7px; color: #999; line-height: 45px; text-align: center; }
        .logo { height: 60px; max-width: 100%; }
    </style>
</head>
<body>

    <!-- Encabezado -->
    <table>
        <tr>
            <td class="text-center" style="width: 70%; vertical-align: middle;">
                @if(!empty($headerLogo))
                    <img src="{{ $headerLogo }}" class="logo" alt="Logo de Inclusión">
                @else
                    <strong style="font-size: 11px;">I.E. Bethlemitas</strong>
                @endif
            </td>
            <td class="text-center bg-gray" style="width: 30%; vertical-align: middle;">
                <strong>PIAR</strong><br>
                <span style="font-size: 8px;">Decreto 1421/2017</span>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="text-center bg-gray">
                <h1>INFORME ANUAL POR COMPETENCIAS INCLUSIÓN ESCOLAR</h1>
                <h2>Decreto 1421 de 2017</h2>
            </td>
        </tr>
    </table>

    <!-- Información del Estudiante -->
    <table>
        <tr>
            <td style="width: 50%;"><strong>Nombre del estudiante:</strong><br>{{ $student->name }} {{ $student->last_name }}</td>
            <td style="width: 50%;"><strong>Identidad:</strong><br>{{ $student->number_documment }}</td>
        </tr>
        <tr>
            <td><strong>Edad:</strong> {{ $student->age }} años | <strong>Grado:</strong> {{ $student->degree->degree ?? 'N/A' }} {{ $student->group->group ?? '' }}</td>
            <td><strong>Director de Grupo:</strong><br>{{ $director ? ($director->name . ' ' . $director->last_name) : 'No asignado' }}</td>
        </tr>
        <tr>
            <td colspan="2"><strong>Año lectivo:</strong> {{ $piar->year }}</td>
        </tr>
    </table>

    <!-- Criterios de Evaluación -->
    <table>
        <thead>
            <tr class="bg-gray">
                <th style="width: 35%; text-align: left;">Criterio / Aspecto</th>
                <th style="width: 65%; text-align: left;">Observaciones y Detalles</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Competencias, habilidades y/o saberes adquiridos:</strong></td>
                <td>{!! !empty($annualReport->competencies) ? nl2br(e($annualReport->cleanField('competencies'))) : '<span style="color:#aaa; font-style:italic;">No registrado</span>' !!}</td>
            </tr>
            <tr>
                <td><strong>Dificultades y/o necesidades persistentes:</strong></td>
                <td>{!! !empty($annualReport->aspects) ? nl2br(e($annualReport->cleanField('aspects'))) : '<span style="color:#aaa; font-style:italic;">No registrado</span>' !!}</td>
            </tr>
            <tr>
                <td><strong>Observaciones sobre comportamiento y convivencia escolar:</strong></td>
                <td>{!! !empty($annualReport->behavior_observation) ? nl2br(e($annualReport->cleanField('behavior_observation'))) : '<span style="color:#aaa; font-style:italic;">No registrado</span>' !!}</td>
            </tr>
            <tr>
                <td><strong>Observaciones sobre desempeño académico:</strong></td>
                <td>{!! !empty($annualReport->academic_observation) ? nl2br(e($annualReport->cleanField('academic_observation'))) : '<span style="color:#aaa; font-style:italic;">No registrado</span>' !!}</td>
            </tr>
            <tr>
                <td><strong>Recomendaciones para el siguiente año escolar (para la familia, docentes y directivos):</strong></td>
                <td>{!! !empty($annualReport->recommendations) ? nl2br(e($annualReport->cleanField('recommendations'))) : '<span style="color:#aaa; font-style:italic;">No registrado</span>' !!}</td>
            </tr>
        </tbody>
    </table>

    <!-- Firmas -->
    <div class="no-break" style="margin-top: 15px;">
        <table class="firma-grid">
            <!-- Fila 1: Director de grupo y Psicoorientadora -->
            <tr>
                <td style="width: 50%;">
                    <div class="firma-box">
                        @if(!empty($directorFirma))
                            <img src="{{ $directorFirma }}" alt="">
                        @else
                            <div class="sin-firma">FIRMA DIGITAL</div>
                        @endif
                    </div>
                    <div class="firma-label">Director de Grupo</div>
                    <div class="firma-name">{{ $director ? ($director->name . ' ' . $director->last_name) : '____________________________________' }}</div>
                    <div class="firma-title">Firma del Profesional</div>
                </td>
                <td style="width: 50%;">
                    <div class="firma-box">
                        @if(!empty($psicoFirma))
                            <img src="{{ $psicoFirma }}" alt="">
                        @else
                            <div class="sin-firma">FIRMA DIGITAL</div>
                        @endif
                    </div>
                    <div class="firma-label">Docente Orientadora (Psicoorientadora)</div>
                    <div class="firma-name">{{ $psico ? ($psico->name . ' ' . $psico->last_name) : '____________________________________' }}</div>
                    <div class="firma-title">Firma del Profesional</div>
                </td>
            </tr>
            
            <!-- Fila 2: Coordinador Convivencia y Coordinadora Académica -->
            <tr>
                <td>
                    <div class="firma-box">
                        <!-- Firma manual -->
                    </div>
                    <div class="firma-label">Coordinador Convivencia</div>
                    <div class="firma-name">Juan Mauricio</div>
                    <div class="firma-title">Firma Manual</div>
                </td>
                <td>
                    <div class="firma-box">
                        @if(!empty($coordinadorFirma))
                            <img src="{{ $coordinadorFirma }}" alt="">
                        @else
                            <div class="sin-firma">FIRMA DIGITAL</div>
                        @endif
                    </div>
                    <div class="firma-label">Coordinadora Académica</div>
                    <div class="firma-name">{{ $coordinador ? ($coordinador->name . ' ' . $coordinador->last_name) : 'María Antonia' }}</div>
                    <div class="firma-title">Firma del Profesional</div>
                </td>
            </tr>

            <!-- Fila 3: Rector / Representante Legal -->
            <tr>
                <td colspan="2" style="width: 100%; text-align: center; padding-left: 25%; padding-right: 25%;">
                    <div class="firma-box" style="margin-top: 10px;">
                        <!-- Firma manual -->
                    </div>
                    <div class="firma-label">Rector / Representante Legal</div>
                    <div class="firma-name">Rector I.E. Bethlemitas</div>
                    <div class="firma-title">Firma Manual</div>
                </td>
            </tr>
        </table>
    </div>

    <p style="text-align: center; font-size: 7.5px; margin-top: 20px; color: #4b5563;">
        Ministerio de Educación Nacional – Decreto 1421 de 2017
    </p>

</body>
</html>
