@extends('layout.homePage')

@section('content')
<style>
    :root {
        --primary: #2563eb;
        --success: #16a34a;
        --warning: #f59e0b;
        --danger: #dc2626;
        --gray-bg: #f8fafc;
        --border-color: #e2e8f0;
    }

    /* Solución al desborde: Wrapper controlado en lugar de container directo */
    .view-content-wrapper {
        width: 100%;
        max-width: 1050px; 
        margin: 0 auto;
        padding: 0 15px;
    }

    .boton-volver {
        background: white;
        border: 1px solid var(--border-color);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #64748b;
        font-size: 13px;
        font-weight: 600;
        padding: 8px 16px;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        transition: all 0.2s;
        text-decoration: none;
    }
    .boton-volver:hover {
        background: #f1f5f9;
        color: var(--primary);
        transform: translateX(-4px);
    }

    .no-break {
        break-inside: avoid;
        page-break-inside: avoid;
    }

    @media print {
        .no-print { display: none !important; }
        input { border: none !important; background: transparent !important; padding: 0 !important; }
        body { background: white !important; }
        .view-content-wrapper { max-width: none; padding: 0; margin: 0; }
        #documento-oficial { border: none !important; padding: 0 !important; box-shadow: none !important; }
        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        thead { display: table-header-group; }
    }
</style>

<div class="view-content-wrapper">
    <a href="/psico/students/active" class="boton-volver no-print">
        <i class="bi bi-chevron-left"></i> Volver al listado
    </a>

    <div class="no-print mb-6 bg-slate-50 p-4 rounded-lg border border-slate-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <label class="block text-xs font-bold mb-2 text-slate-600 uppercase tracking-wider">Visualizar Periodo del Anexo 3:</label>
            <div class="flex space-x-2">
                @foreach([1, 2, 3] as $p)
                    <a href="{{ route('piar.anexo3', ['piar' => $piar->id, 'periodo' => $p]) }}" 
                       class="px-5 py-1.5 rounded-md text-sm font-bold transition-all {{ $periodo_actual == $p ? 'bg-blue-700 text-white shadow-md' : 'bg-white text-blue-700 border border-blue-700 hover:bg-blue-50' }}">
                        Periodo {{ $p }}
                    </a>
                @endforeach
            </div>
        </div>

        <a href="{{ route('piar.anexo3', ['piar' => $piar->id, 'periodo' => $periodo_actual, 'download' => 1]) }}" 
           id="btn-pdf"
           target="_blank"
           class="flex items-center justify-center bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-lg font-bold text-sm transition-colors shadow-lg">
            <i class="bi bi-file-earmark-pdf mr-2"></i> GENERAR PDF OFICIAL
        </a>
    </div>

    <div class="bg-white border border-gray-300 shadow-sm p-8" id="documento-oficial">
        
        <table class="w-full border-2 border-black border-collapse">
            <thead>
                <tr>
                    <td class="border-2 border-black p-2 w-2/3 text-center">
                        <img src="{{ asset('img/credencialesGo.png') }}" alt="Logos Mineducación" class="h-14 mx-auto object-contain">
                    </td>
                    <td class="border-2 border-black p-2 w-1/3 text-center bg-gray-50">
                        <p class="font-bold text-[10px]">PIAR</p>
                        <p class="text-[9px]">Decreto 1421/2017</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="border-2 border-black p-2 text-center bg-gray-100">
                        <h1 class="font-bold text-sm uppercase">Acta de Acuerdo</h1>
                        <h2 class="font-bold text-xs uppercase">Plan Individual de Ajustes Razonables – PIAR –</h2>
                        <h2 class="font-bold text-blue-800 text-xs uppercase">Anexo 3 - Periodo {{ $periodo_actual }}</h2>
                    </td>
                </tr>
            </thead>
        </table>

        <table class="w-full border-x-2 border-b-2 border-black border-collapse text-[10px]">
            <tr>
                <td class="border border-black p-2 w-1/3">
                    <span class="font-bold uppercase text-[9px]">Fecha:</span><br>{{ now()->format('d/m/Y') }}
                </td>
                <td colspan="2" class="border border-black p-2">
                    <span class="font-bold uppercase text-[9px]">Institución educativa y Sede:</span><br>I.E. Bethlemitas
                </td>
            </tr>
            <tr>
                <td class="border border-black p-2">
                    <span class="font-bold uppercase text-[9px]">Nombre del estudiante:</span><br>{{ $estudiante->name }} {{ $estudiante->last_name }}
                </td>
                <td class="border border-black p-2">
                    <span class="font-bold uppercase text-[9px]">Identificación:</span><br>{{ $estudiante->number_documment }}
                </td>
                <td class="border border-black p-2">
                    <span class="font-bold uppercase text-[9px]">Grado:</span> {{ $estudiante->id_degree }} | <span class="font-bold uppercase text-[9px]">Edad:</span> {{ $estudiante->age }}
                </td>
            </tr>
            
            <tr class="border border-black">
                <td class="border border-black p-2 align-top w-1/4">
                    <span class="font-bold uppercase text-[9px]">Equipo Directivo y Docentes:</span>
                </td>
                <td colspan="2" class="border border-black p-2">
                    <div class="grid grid-cols-2 gap-x-4">
                        @php
                            $shownItems = [];
                            if ($director) {
                                $shownItems[] = [
                                    'name' => $director->name . ' ' . $director->last_name,
                                    'role' => 'Director de Grupo'
                                ];
                            }
                            foreach ($docentes as $doc) {
                                if (!$director || $doc->id != $director->id) {
                                    $shownItems[] = [
                                        'name' => $doc->name . ' ' . $doc->last_name,
                                        'role' => 'Docente de Área'
                                    ];
                                }
                            }
                        @endphp
                        @if(count($shownItems) > 0)
                            @foreach($shownItems as $item)
                                <p class="text-[10px] border-b border-gray-100 py-1">
                                    • {{ $item['name'] }} 
                                    <span class="text-[8px] text-gray-500 italic">
                                        ({{ $item['role'] }})
                                    </span>
                                </p>
                            @endforeach
                        @else
                            <p class="text-[10px] text-gray-400 italic">No se han registrado docentes en este periodo.</p>
                        @endif
                    </div>
                </td>
            </tr>

            <tr>
                <td class="border border-black p-2">
                    <span class="font-bold uppercase text-[9px]">Nombres familia del estudiante:</span><br>
                    <input type="text" id="acudiente_input" 
                        class="w-full bg-blue-50 border-b border-blue-200 outline-none p-1 text-[10px] font-semibold" 
                        placeholder="Escriba el nombre del familiar..."
                        value="{{ $estudiante->acudiente }}" required>
                </td>
                <td colspan="2" class="border border-black p-2">
                    <span class="font-bold uppercase text-[9px]">Parentesco:</span><br>
                    <input type="text" id="parentesco_input" 
                        class="w-full bg-blue-50 border-b border-blue-200 outline-none p-1 text-[10px] font-semibold" 
                        placeholder="Ej: Madre, Padre, Tío..."
                        value="{{ $estudiante->parentesco_acudiente }}" required>
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
                <li><span class="font-bold">•</span> Tener continuidad con los procesos terapéuticos integrales (terapia ocupacional, fonoaudiología, psiquiatría y psicología) que el estudiante este desar rollando.</li>
                <br>
                <li><span class="font-bold">•</span>Presentar a la institución educativa los respectivos informes de terapiasIntegrales y citas de control.</li>
                <br>
                <li><span class="font-bold">•</span>Acudir a los respectivos llamados por parte de área de Psicoorientación.</li>
                <br>
                <li> <span class="font-bold">•</span>Desarrollar las actividades propuestas para el acompañamiento en casa.</li>
                <br>
                <li><span class="font-bold">•</span>Los padres de familia y el estudiante comprenden de manera oportuna que el hecho de tener PIAR, no es un criterio para la promoción ni la omisión de la aplicación de las medidas correctivas de acuerdo con el manual deConvivencia de la institución.</li>
                <br>
            </ul>
            <p>Es de anotar, que la Institución Educativa NO SE HACE RESPONSABLE DE LA PERMANENCIA Y PROMOCIÓN DEL ESTUDIANTE, en caso de que se presente:</p>
            <ul>
                <li><span class="font-bold">•</span>Incumplimiento relevante en cualquiera de los compromisos pactados con la Institución Educativa</li>
                <br>
                <li><span class="font-bold">•</span>Poco o ningún acompañamiento familiar</li>
                <br>
                <li><span class="font-bold">•</span>Falta o ningún apoyo en las actividades escolares y/o planes caseros (Tareas, trabajos, revisión cuadernos, etc)</li>
                <br>
                <li><span class="font-bold">•</span>Ausencia reiterada y no justificada de la jornada escolar.</li>
                <br>
                <li><span class="font-bold">•</span>No presentar reportes de atención y/o tratamiento (Evaluación o Terapias) correspondientes según la necesidad del estudiante</li>
                <br>
                <li><span class="font-bold">•</span>Interrupción o no realización del tratamiento farmacológico, terapéutico y/o de control, según prescripción médica o del Especialista.</li>
                <br>
                <li><span class="font-bold">•</span>No asistir a los llamados institucionales y a los controles y seguimientos del Estudiante.</li>
                <br>
                <li><span class="font-bold">•</span>Alterar los certificados, reportes o recomendaciones médicas enviadas por los Profesionales Especialistas o Instancias Institucionales.</li>
                <br>
                <li><span class="font-bold">•</span>El tener PIAR no garantiza la promoción escolar. Si el estudiante no cumple con tareas, trabajos, acuerdos y plazos de entrega de estos, cuando falta a clase y no se pone al día, pierde de manera reiterada exámenes por desinterés y falta de compromiso, debe hacer el proceso de recuperación de acuerdo con el SIEE Institucional.</li>
                <br>
                <li><span class="font-bold">•</span>Y en casa apoyará con las siguientes actividades:</li>
                <br>
            </ul>               
            
        </div>

        <div class="border-2 border-black p-4 min-h-[120px] mb-8 no-break">
            <h3 class="font-bold text-[10px] uppercase border-b border-black mb-2 pb-1">Anexo 3 - Actividades (Periodo {{ $periodo_actual }})</h3>
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

        <div class="mt-12 no-break">
            <div class="grid grid-cols-2 gap-x-16 gap-y-12">
                <div class="text-center">
                    <div class="border-b border-black w-full mb-1 h-8"></div>
                    <p class="text-[9px] font-bold uppercase">Padre de Familia / Acudiente</p>
                    <p id="acudiente_label" class="text-[8px] italic">{{ $estudiante->acudiente ?? '________________' }}</p>
                </div>

                <div class="text-center">
                    <div class="border-b border-black w-full mb-1 h-8"></div>
                    <p class="text-[9px] font-bold uppercase">Firma del Estudiante</p>
                    <p class="text-[8px]">{{ $estudiante->name }} {{ $estudiante->last_name }}</p>
                </div>

                @if($director)
                    @php
                        $sigDirector = $director->signature ?? \App\Models\PiarAdjustment::where('teacher_id', $director->id)
                            ->whereNotNull('teacher_signature')
                            ->latest()
                            ->value('teacher_signature');
                    @endphp
                    <div class="text-center">
                        <div class="border-b border-black w-full mb-1 h-16 flex flex-col items-center justify-end pb-1 overflow-hidden">
                            @if($sigDirector)
                                <img src="{{ asset('storage/' . $sigDirector) }}" class="max-h-14 object-contain scale-125" alt="Firma">
                            @else
                                <div class="text-[7px] text-gray-300 italic mb-1 uppercase">Firma Digital no cargada</div>
                            @endif
                        </div>
                        <p class="text-[9px] font-bold uppercase leading-none mt-1">
                            Director de Grupo
                        </p>
                        <p class="text-[8px] font-bold text-gray-900">{{ $director->name }} {{ $director->last_name }}</p>
                    </div>
                @endif

                @foreach($docentes as $doc)
                    @if(!$director || $doc->id != $director->id)
                    @php
                        $sigDocente = $doc->signature ?? \App\Models\PiarAdjustment::where('teacher_id', $doc->id)
                            ->whereNotNull('teacher_signature')
                            ->latest()
                            ->value('teacher_signature');
                    @endphp
                    <div class="text-center">
                        <div class="border-b border-black w-full mb-1 h-16 flex flex-col items-center justify-end pb-1 overflow-hidden">
                            @if($sigDocente)
                                <img src="{{ asset('storage/' . $sigDocente) }}" class="max-h-14 object-contain scale-125" alt="Firma">
                            @else
                                <div class="text-[7px] text-gray-300 italic mb-1 uppercase">Firma Digital no cargada</div>
                            @endif
                        </div>
                        <p class="text-[9px] font-bold uppercase leading-none mt-1">
                            Docente
                        </p>
                        <p class="text-[8px] font-bold text-gray-900">{{ $doc->name }} {{ $doc->last_name }}</p>
                    </div>
                    @endif
                @endforeach
                <div class="text-center">
                    <div class="border-b border-black w-full mb-1 h-8"></div>
                    <p class="text-[9px] font-bold uppercase">Directivo Docente / Rectoría</p>
                    <p class="text-[8px]">I.E. Bethlemitas</p>
                </div>
            </div>
        </div>

        <div class="mt-16 pt-2 border-t border-gray-300 text-center opacity-70">
            <p class="text-[8px] font-bold italic">V14.16/02/2018. - Ministerio de Educación Nacional – Decreto 1421 de 2017</p>
        </div>
    </div>
</div>

<script>
    const inputAcudiente = document.getElementById('acudiente_input');
    const inputParentesco = document.getElementById('parentesco_input');
    const labelAcudiente = document.getElementById('acudiente_label');
    const btnPdf = document.getElementById('btn-pdf');

    function syncData() {
        const valAcudiente = inputAcudiente.value;
        const valParentesco = inputParentesco.value;
        labelAcudiente.innerText = valAcudiente || '________________';

        const url = new URL(btnPdf.href);
        url.searchParams.set('familiar', valAcudiente);
        url.searchParams.set('parentesco', valParentesco);
        btnPdf.href = url.toString();
    }

    inputAcudiente.addEventListener('input', syncData);
    inputParentesco.addEventListener('input', syncData);
    syncData();
</script>
@endsection