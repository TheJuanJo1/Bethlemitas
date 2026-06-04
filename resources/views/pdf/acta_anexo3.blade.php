<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page { size: letter; margin: 1cm; }
        body { font-family: Arial, sans-serif; font-size: 10px; color: #000; line-height: 1.25; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        th, td { border: 1px solid #000; padding: 4px; vertical-align: top; }
        .bg-gray { background: #f3f4f6; }
        h1 { font-size: 13px; margin: 4px 0; text-transform: uppercase; }
        h2 { font-size: 11px; margin: 2px 0; text-transform: uppercase; }
        .text-center { text-align: center; }
        .page-break { page-break-before: always; }
        .no-break { page-break-inside: avoid; }
        .legal p, .legal li { margin: 0 0 6px 0; text-align: justify; }
        .legal ul { margin: 0; padding-left: 14px; }
        .firma-box { height: 50px; border-bottom: 1px solid #000; text-align: center; }
        .firma-box img { max-height: 45px; max-width: 100%; }
        .firma-label { font-size: 8px; font-weight: bold; text-transform: uppercase; margin-top: 3px; }
        .firma-grid td { width: 50%; border: none; padding: 8px 12px; vertical-align: top; }
        .logo { height: 48px; }
        .act-title { color: #1d4ed8; font-size: 11px; }
    </style>
</head>
<body>
@foreach($bloques as $index => $bloque)
    @if($index > 0)<div class="page-break"></div>@endif

    <table>
        <tr>
            <td class="text-center" style="width: 65%;">
                @if(!empty($logoPath))
                    <img src="{{ $logoPath }}" class="logo" alt="">
                @else
                    <strong style="font-size: 11px;">I.E. Bethlemitas</strong>
                @endif
            </td>
            <td class="text-center bg-gray" style="width: 35%;">
                <strong>PIAR</strong><br>
                <span style="font-size: 9px;">Decreto 1421/2017</span>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="text-center bg-gray">
                <h1>Acta de Acuerdo</h1>
                <h2>Plan Individual de Ajustes Razonables – PIAR –</h2>
                <h2 class="act-title">ANEXO 3 - PERIODO {{ $bloque['periodo'] }}</h2>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td style="width: 33%;"><strong>Fecha:</strong> {{ now()->format('d/m/Y') }}</td>
            <td colspan="2"><strong>Institución educativa y Sede:</strong> I.E. Bethlemitas</td>
        </tr>
        <tr>
            <td><strong>Nombre del estudiante:</strong><br>{{ $estudiante->name }} {{ $estudiante->last_name }}</td>
            <td><strong>Identificación:</strong><br>{{ $estudiante->number_documment }}</td>
            <td><strong>Edad:</strong> {{ $estudiante->age }} | <strong>Grado:</strong> {{ $estudiante->degree->degree ?? '' }}</td>
        </tr>
        <tr>
            <td colspan="3">
                <strong>Equipo directivo y docentes:</strong>
                @php
                    $nombres = [];
                    if ($director) {
                        $nombres[] = $director->name.' '.$director->last_name.' (Dir. Grupo)';
                    }
                    $psicoEquipo = \App\Services\PiarFirmasResolver::psicorientadoraParaEstudiante($estudiante->id);
                    if ($psicoEquipo) {
                        $nombres[] = $psicoEquipo->name.' '.$psicoEquipo->last_name.' (Psicoorientación)';
                    }
                    foreach ($bloque['docentes'] as $doc) {
                        if ((!$director || $doc->id != $director->id) && (!$psicoEquipo || $doc->id != $psicoEquipo->id)) {
                            $nombres[] = $doc->name.' '.$doc->last_name.' (Docente)';
                        }
                    }
                @endphp
                {{ implode(' | ', $nombres) }}
            </td>
        </tr>
        <tr>
            <td colspan="2"><strong>Familia:</strong> {{ $familiar_manual ?? $estudiante->acudiente ?? '________________' }}</td>
            <td><strong>Parentesco:</strong> {{ $parentesco_manual ?? $estudiante->parentesco_acudiente ?? '________________' }}</td>
        </tr>
    </table>

    <div class="legal" style="margin: 12px 2px;">
        <p>Según el Decreto 1421 de 2017 la educación inclusiva es un proceso permanente que reconoce, valora y responde a la diversidad de características, intereses, posibilidades y expectativas de los estudiantes para promover su desarrollo, aprendizaje y participación, en un ambiente de aprendizaje común, sin discriminación o exclusión.</p>
        <p>La inclusión solo es posible cuando se unen los esfuerzos del colegio, el estudiante y la familia. De ahí la importancia de formalizar con las firmas, la presente Acta Acuerdo.</p>
        <p>El Establecimiento Educativo ha realizado la valoración y definido los ajustes razonables que facilitarán al estudiante su proceso educativo.</p>
        <p>La Familia se compromete a cumplir y firmar los compromisos señalados en el PIAR y en las actas de acuerdo, para fortalecer los procesos escolares del estudiante y en particular a:</p>
        <p><strong>Los padres de familia se comprometen a:</strong></p>
        <ul>
            <li>Tener continuidad con los procesos terapéuticos integrales que el estudiante esté desarrollando.</li>
            <li>Presentar a la institución educativa los respectivos informes de terapias Integrales y citas de control.</li>
            <li>Acudir a los respectivos llamados por parte de área de Psicoorientación.</li>
            <li>Desarrollar las actividades propuestas para el acompañamiento en casa.</li>
            <li>Comprender que el hecho de tener PIAR no es un criterio para la promoción ni la omisión de medidas correctivas.</li>
        </ul>
        <p>Es de anotar, que la Institución Educativa NO SE HACE RESPONSABLE DE LA PERMANENCIA Y PROMOCIÓN DEL ESTUDIANTE, en caso de incumplimiento de compromisos, poco acompañamiento familiar, ausencias reiteradas, entre otras causas establecidas en el manual de convivencia.</p>
        <p><strong>Y en casa apoyará con las siguientes actividades:</strong></p>
    </div>

    <table class="no-break">
        <tr><th colspan="3" class="bg-gray">Anexo 3 - Actividades (Periodo {{ $bloque['periodo'] }})</th></tr>
        <tr class="bg-gray" style="font-size: 9px;">
            <th style="width: 30%;">Nombre Actividad</th>
            <th>Descripción de la estrategia / Compromiso</th>
            <th style="width: 18%;">Frecuencia</th>
        </tr>
        @forelse($bloque['familyActivities'] as $activity)
        <tr>
            <td><strong>{{ $activity->activity }}</strong></td>
            <td>{!! nl2br(e(strip_tags($activity->strategy ?? ''))) !!}</td>
            <td class="text-center">
                @if($activity->frequency == 'D') D (Diaria)
                @elseif($activity->frequency == 'S') S (Semanal)
                @elseif($activity->frequency == 'P') P (Permanente)
                @elseif($activity->frequency == 'N/A') N/A
                @else {{ $activity->frequency }}
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="3" class="text-center" style="font-style: italic;">No se han registrado actividades para este periodo.</td></tr>
        @endforelse
    </table>

    @include('pdf.partials.anexo3_firmas', ['firmasAnexo3' => $bloque['firmasAnexo3']])

    <p class="text-center" style="font-size: 8px; margin-top: 16px;">V14.16/02/2018. - Ministerio de Educación Nacional – Decreto 1421 de 2017</p>
@endforeach
</body>
</html>
