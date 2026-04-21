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

    /* Botón Volver */
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
</style>

<a href="/psico/students/active" class="boton-volver">
        <i class="bi bi-chevron-left"></i> Volver al listado
</a>

<div class="container mx-auto p-4 no-print">
    <div class="mb-6 bg-slate-50 p-4 rounded-lg border border-slate-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
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
</div>

<div class="container mx-auto p-8 bg-white border border-gray-300 shadow-sm" id="documento-oficial">
    
    <table class="w-full border-2 border-black border-collapse">
        <thead>
            <tr>
                <td class="border-2 border-black p-2 w-2/3 text-center">
                    <img src="{{ asset('img/credencialesGo.jpg') }}" alt="Logos Mineducación" class="h-14 mx-auto object-contain">
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
                    @if(isset($docentes) && $docentes->count() > 0)
                        @foreach($docentes as $docente)
                            <p class="text-[10px] border-b border-gray-100 py-1">
                                • {{ $docente->name }} 
                                <span class="text-[8px] text-gray-500 italic">
                                    ({{ $docente->id == $estudiante->id_teacher_director ? 'Director de Grupo' : 'Docente de Área' }})
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
        <p>
            Según el <strong>Decreto 1421 de 2017</strong>, la educación inclusiva es un proceso permanente que reconoce, valora y responde a la diversidad de características, intereses, posibilidades y expectativas de los estudiantes, con el fin de promover su desarrollo, aprendizaje y participación en un ambiente de aprendizaje común, sin discriminación ni exclusión.
        </p>
        <p>
            La inclusión solo es posible cuando se unen los esfuerzos de la <strong>institución educativa, los docentes, los directivos, el estudiante y la familia</strong>. De ahí la importancia de formalizar, mediante la firma, la presente <strong>Acta de Acuerdo</strong>.
        </p>
        <p>
            El <strong>Establecimiento Educativo</strong> ha realizado la valoración pedagógica correspondiente y ha definido los ajustes razonables necesarios para facilitar el proceso educativo del estudiante.
        </p>
        <p>
            La <strong>Familia</strong> se compromete a cumplir y firmar los compromisos señalados en el PIAR y en las actas de acuerdo, con el fin de fortalecer los procesos escolares del estudiante, y en particular:
        </p>
    </div>

    <div class="border-2 border-black p-4 min-h-[160px] mb-8 no-break">
        <h3 class="font-bold text-[10px] uppercase border-b border-black mb-2 pb-1">Compromisos específicos para el aula (Periodo {{ $periodo_actual }})</h3>
        <div class="text-[11px] space-y-3">
            <div>
                <span class="font-bold text-blue-900 underline">Ajustes Curriculares y Metodológicos:</span>
                <p class="mt-1">{{ $datos->ajuste_curricular ?? 'Sin registros.' }}</p>
            </div>
            <div>
                <span class="font-bold text-blue-900 underline">Otros Apoyos:</span>
                <p class="mt-1">{{ $datos->ajuste_metodologico ?? 'Sin registros.' }}</p>
            </div>
        </div>
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

            @foreach($docentes as $doc)
                <div class="text-center">
                    <div class="border-b border-black w-full mb-1 h-8"></div>
                    <p class="text-[9px] font-bold uppercase">
                        {{ $doc->id == $estudiante->id_teacher_director ? 'Director de Grupo' : 'Docente' }}
                    </p>
                    <p class="text-[8px]">{{ $doc->name }}</p>
                </div>
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

<style>
    /* Estilos para que no se corten las firmas o cuadros */
    .no-break {
        break-inside: avoid;
        page-break-inside: avoid;
    }

    @media print {
        .no-print { display: none !important; }
        input { border: none !important; background: transparent !important; padding: 0 !important; }
        body { background: white !important; }
        .container { width: 100% !important; max-width: none !important; margin: 0 !important; padding: 0 !important; border: none !important; box-shadow: none !important; }
        #documento-oficial { border: none !important; padding: 0 !important; }
        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        thead { display: table-header-group; } /* Esto repite el logo arriba */
    }
</styl>

<script>
    // Lógica para capturar lo que escribes y mandarlo al controlador
    const inputAcudiente = document.getElementById('acudiente_input');
    const inputParentesco = document.getElementById('parentesco_input');
    const labelAcudiente = document.getElementById('acudiente_label');
    const btnPdf = document.getElementById('btn-pdf');

    function syncData() {
        const valAcudiente = inputAcudiente.value;
        const valParentesco = inputParentesco.value;

        // Actualiza el nombre en la firma de abajo en tiempo real
        labelAcudiente.innerText = valAcudiente || '________________';

        // Modifica la URL del botón de PDF
        const url = new URL(btnPdf.href);
        url.searchParams.set('familiar', valAcudiente);
        url.searchParams.set('parentesco', valParentesco);
        btnPdf.href = url.toString();
    }

    inputAcudiente.addEventListener('input', syncData);
    inputParentesco.addEventListener('input', syncData);

    // Ejecutar una vez al cargar
    syncData();
</script>
@endsection