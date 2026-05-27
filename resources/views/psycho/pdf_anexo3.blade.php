<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acta de Acuerdo - PIAR - {{ $estudiante->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page { size: letter; margin: 1cm; }
        body { background: white; font-family: 'Arial', sans-serif; color: black; }
        
        /* Repetir encabezado en cada hoja si es necesario */
        table { width: 100%; border-collapse: collapse; }
        thead { display: table-header-group; }
        
        table, th, td { border: 1.5px solid black !important; }
        
        /* Evitar que las firmas o bloques se partan */
        .no-break {
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .bg-official { background-color: #f3f4f6 !important; -webkit-print-color-adjust: exact; }
    </style>
</head>
<body onload="window.print()">
    <div class="max-w-[800px] mx-auto">
        
        <table class="border-none">
            <thead>
                <tr>
                    <td class="w-2/3 p-2 text-center border-2 border-black">
                        <img src="{{ asset('img/credencialesGo.png') }}" class="h-12 mx-auto object-contain">
                    </td>
                    <td class="w-1/3 p-2 text-center bg-official border-2 border-black">
                        <p class="font-bold text-[10px]">PIAR</p>
                        <p class="text-[9px]">Decreto 1421/2017</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="p-1 text-center bg-official border-2 border-black">
                        <h1 class="font-bold text-sm uppercase leading-tight">Acta de Acuerdo</h1>
                        <h2 class="font-bold text-xs uppercase leading-tight">Plan Individual de Ajustes Razonables – PIAR –</h2>
                        <h2 class="font-bold text-xs uppercase text-blue-800">ANEXO 3 - PERIODO {{ $periodo_actual }}</h2>
                    </td>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td colspan="2" class="p-0 border-none">
                        
                        <table class="w-full text-[10px] mt-2">
                            <tr>
                                <td class="p-1 w-1/3"><span class="font-bold">Fecha:</span> {{ now()->format('d/m/Y') }}</td>
                                <td colspan="2" class="p-1"><span class="font-bold">Institución educativa y Sede:</span> I.E. Bethlemitas</td>
                            </tr>
                            <tr>
                                <td class="p-1"><span class="font-bold">Nombre del estudiante:</span> {{ $estudiante->name }} {{ $estudiante->last_name }}</td>
                                <td class="p-1"><span class="font-bold">Identificación:</span> {{ $estudiante->number_documment }}</td>
                                <td class="p-1">
                                    <span class="font-bold">Edad:</span> {{ $estudiante->age }} | <span class="font-bold">Grado:</span> {{ $estudiante->id_degree }}
                                </td>
                            </tr>
                            <tr>
                                <td class="p-1 align-top">
                                    <span class="font-bold text-[9px]">Equipo directivo y docentes:</span>
                                </td>
                                <td colspan="2" class="p-1">
                                    <div class="text-[9px]">
                                        @php
                                            $shownNames = [];
                                            if ($director) {
                                                $shownNames[] = $director->name . ' ' . $director->last_name . ' (Dir. Grupo)';
                                            }
                                            foreach ($docentes as $doc) {
                                                if (!$director || $doc->id != $director->id) {
                                                    $shownNames[] = $doc->name . ' ' . $doc->last_name . ' (Docente)';
                                                }
                                            }
                                        @endphp
                                        {!! implode(' | ', array_map(function($name) { return '• ' . e($name); }, $shownNames)) !!}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-1">
                                    <span class="font-bold">Familia:</span> {{ $familiar_manual ?? $estudiante->acudiente ?? '________________' }}
                                </td>
                                <td colspan="2" class="p-1">
                                    <span class="font-bold">Parentesco:</span> {{ $parentesco_manual ?? $estudiante->parentesco_acudiente ?? '________________' }}
                                </td>
                            </tr>
                        </table>

                        <div class="my-6 text-[10.5px] text-justify leading-tight space-y-3 px-1">
                            <p>Según el Decreto 1421 de 2017 la educación inclusiva es un proceso permanente que reconoce, valora y responde a la diversidad de características, intereses, posibilidades y expectativas de los estudiantes para promover su desarrollo, aprendizaje y participación, en un ambiente de aprendizaje común, sin discriminación o exclusión.</p>
                            <p>La inclusión solo es posible cuando se unen los esfuerzos del colegio, el estudiante y la familia. De ahí la importancia de formalizar con las firmas, la presente Acta Acuerdo.</p>
                            <p>El Establecimiento Educativo ha realizado la valoración y definido los ajustes razonables que facilitarán al estudiante su proceso educativo.</p>
                            <p>La Familia se compromete a cumplir y firmar los compromisos señalados en el PIAR y en las actas de acuerdo, para fortalecer los procesos escolares del estudiante y en particular a:</p>
                            <p>Los padres de familia se comprometen a:</p>
                            <ul>
                                <li><span class="font-bold">•</span> Tener continuidad con los procesos terapéuticos integrales (terapia ocupacional, fonoaudiología, psiquiatría y psicología) que el estudiante este desarrollando.</li>
                                <br>
                                <li><span class="font-bold">•</span> Presentar a la institución educativa los respectivos informes de terapias Integrales y citas de control.</li>
                                <br>
                                <li><span class="font-bold">•</span> Acudir a los respectivos llamados por parte de área de Psicoorientación.</li>
                                <br>
                                <li><span class="font-bold">•</span> Desarrollar las actividades propuestas para el acompañamiento en casa.</li>
                                <br>
                                <li><span class="font-bold">•</span> Los padres de familia y el estudiante comprenden de manera oportuna que el hecho de tener PIAR, no es un criterio para la promoción ni la omisión de la aplicación de las medidas correctivas de acuerdo con el manual de Convivencia de la institución.</li>
                                <br>
                            </ul>
                            <p>Es de anotar, que la Institución Educativa NO SE HACE RESPONSABLE DE LA PERMANENCIA Y PROMOCIÓN DEL ESTUDIANTE, en caso de que se presente:</p>
                            <ul>
                                <li><span class="font-bold">•</span> Incumplimiento relevante en cualquiera de los compromisos pactados con la Institución Educativa</li>
                                <br>
                                <li><span class="font-bold">•</span> Poco o ningún acompañamiento familiar</li>
                                <br>
                                <li><span class="font-bold">•</span> Falta o ningún apoyo en las actividades escolares y/o planes caseros (Tareas, trabajos, revisión cuadernos, etc)</li>
                                <br>
                                <li><span class="font-bold">•</span> Ausencia reiterada y no justificada de la jornada escolar.</li>
                                <br>
                                <li><span class="font-bold">•</span> No presentar reportes de atención y/o tratamiento (Evaluación o Terapias) correspondientes según la necesidad del estudiante</li>
                                <br>
                                <li><span class="font-bold">•</span> Interrupción o no realización del tratamiento farmacológico, terapéutico y/o de control, según prescripción médica o del Especialista.</li>
                                <br>
                                <li><span class="font-bold">•</span> No asistir a los llamados institucionales y a los controles y seguimientos del Estudiante.</li>
                                <br>
                                <li><span class="font-bold">•</span> Alterar los certificados, reportes o recomendaciones médicas enviadas por los Profesionales Especialistas o Instancias Institucionales.</li>
                                <br>
                                <li><span class="font-bold">•</span> El tener PIAR no garantiza la promoción escolar. Si el estudiante no cumple con tareas, trabajos, acuerdos y plazos de entrega de estos, cuando falta a clase y no se pone al día, pierde de manera reiterada exámenes por desinterés y falta de compromiso, debe hacer el proceso de recuperación de acuerdo con el SIEE Institucional.</li>
                                <br>
                                <li><span class="font-bold">•</span> Y en casa apoyará con las siguientes actividades:</li>
                                <br>
                            </ul>               
                        </div>

                        <div class="border-2 border-black p-4 min-h-[120px] mb-8 no-break">
                            <p class="font-bold underline mb-1 uppercase text-[10px]">Anexo 3 - Actividades (Periodo {{ $periodo_actual }}):</p>
                            <table class="w-full text-[10px] border-collapse mt-2">
                                <thead>
                                    <tr class="bg-gray-100 font-bold uppercase text-[9px]">
                                        <th class="border border-black p-2 text-left w-1/3">Nombre Actividad</th>
                                        <th class="border border-black p-2 text-left">Descripción de la estrategia / Compromiso</th>
                                        <th class="border border-black p-2 text-center w-1/4">Frecuencia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($familyActivities as $activity)
                                    <tr>
                                        <td class="border border-black p-2 align-top font-semibold">{{ $activity->activity }}</td>
                                        <td class="border border-black p-2 align-top text-justify">{{ $activity->strategy }}</td>
                                        <td class="border border-black p-2 align-top text-center font-semibold">
                                            @if($activity->frequency == 'D') D (Diaria)
                                            @elseif($activity->frequency == 'S') S (Semanal)
                                            @elseif($activity->frequency == 'P') P (Permanente)
                                            @elseif($activity->frequency == 'N/A') N/A (No aplica)
                                            @else {{ $activity->frequency }}
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="border border-black p-2 text-center text-gray-400 italic">No se han registrado actividades de apoyo en el hogar para este periodo.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="no-break mt-10">
                            <div class="grid grid-cols-2 gap-x-10 gap-y-12 px-4">
                                
                                <div class="text-center">
                                    <div class="border-b border-black w-full h-12"></div>
                                    <p class="text-[9px] font-bold mt-1 uppercase">Firma del Padre / Acudiente</p>
                                    <p class="text-[8px]">{{ $familiar_manual ?? $estudiante->acudiente }}</p>
                                </div>

                                <div class="text-center">
                                    <div class="border-b border-black w-full h-12"></div>
                                    <p class="text-[9px] font-bold mt-1 uppercase">Firma del Estudiante</p>
                                    <p class="text-[8px]">{{ $estudiante->name }} {{ $estudiante->last_name }}</p>
                                </div>

                                @if($director)
                                @php
                                    $sigDirector = $director->signature ?? \App\Models\PiarAdjustment::where('teacher_id', $director->id)
                                        ->whereNotNull('teacher_signature')
                                        ->latest()
                                        ->value('teacher_signature');
                                    
                                    $directorSigUrl = '';
                                    if ($sigDirector) {
                                        $directorSigUrl = str_contains($sigDirector, 'Imagenes_Firma') ? asset($sigDirector) : asset('storage/' . $sigDirector);
                                    }
                                @endphp
                                <div class="text-center">
                                    <div class="border-b border-black w-full h-16 flex flex-col items-center justify-end pb-1 overflow-hidden">
                                        @if($sigDirector)
                                            <img src="{{ $directorSigUrl }}" class="max-h-14 object-contain scale-125">
                                        @else
                                            <div class="h-4 text-[6px] text-gray-300 italic">SIN FIRMA DIGITAL</div>
                                        @endif
                                    </div>
                                    <p class="text-[9px] font-bold mt-1 uppercase leading-none">
                                        Dir. Grupo: {{ $director->name }} {{ $director->last_name }}
                                    </p>
                                    <p class="text-[8px] italic leading-tight">Firma del Profesional</p>
                                </div>
                                @endif

                                @foreach($docentes as $docente)
                                @if(!$director || $docente->id != $director->id)
                                @php
                                    $sigDocente = $docente->signature ?? \App\Models\PiarAdjustment::where('teacher_id', $docente->id)
                                        ->whereNotNull('teacher_signature')
                                        ->latest()
                                        ->value('teacher_signature');
                                    
                                    $docenteSigUrl = '';
                                    if ($sigDocente) {
                                        $docenteSigUrl = str_contains($sigDocente, 'Imagenes_Firma') ? asset($sigDocente) : asset('storage/' . $sigDocente);
                                    }
                                @endphp
                                <div class="text-center">
                                    <div class="border-b border-black w-full h-16 flex flex-col items-center justify-end pb-1 overflow-hidden">
                                        @if($sigDocente)
                                            <img src="{{ $docenteSigUrl }}" class="max-h-14 object-contain scale-125">
                                        @else
                                            <div class="h-4 text-[6px] text-gray-300 italic">SIN FIRMA DIGITAL</div>
                                        @endif
                                    </div>
                                    <p class="text-[9px] font-bold mt-1 uppercase leading-none">
                                        Docente: {{ $docente->name }} {{ $docente->last_name }}
                                    </p>
                                    <p class="text-[8px] italic leading-tight">Firma del Profesional</p>
                                </div>
                                @endif
                                @endforeach

                                <div class="text-center">
                                    <div class="border-b border-black w-full h-12"></div>
                                    <p class="text-[9px] font-bold mt-1 uppercase">Directivo Docente / Rectoría</p>
                                    <p class="text-[8px]">I.E. Bethlemitas</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 text-[8px] text-center opacity-70">
                            <p>V14.16/02/2018. - Ministerio de Educación Nacional – Decreto 1421 de 2017</p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>