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
                        <img src="{{ asset('img/credencialesGo.jpg') }}" class="h-12 mx-auto object-contain">
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
                                        @foreach($docentes as $doc)
                                            • {{ $doc->name }} ({{ $doc->id == $estudiante->id_teacher_director ? 'Dir. Grupo' : 'Docente' }}){{ !$loop->last ? ' | ' : '' }}
                                        @endforeach
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

                        <div class="border-2 border-black p-3 min-h-[150px] mb-6 no-break">
                            <p class="font-bold underline mb-1 uppercase text-[10px]">Compromisos específicos Periodo {{ $periodo_actual }}:</p>
                            <div class="text-[11px] space-y-2">
                                <p><strong>Ajustes Curriculares:</strong> {{ $datos->ajuste_curricular ?? 'N/R' }}</p>
                                <p><strong>Otros Apoyos:</strong> {{ $datos->ajuste_metodologico ?? 'N/R' }}</p>
                            </div>
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

                                @foreach($docentes as $docente)
                                <div class="text-center">
                                    <div class="border-b border-black w-full h-12"></div>
                                    <p class="text-[9px] font-bold mt-1 uppercase">
                                        {{ $docente->id == $estudiante->id_teacher_director ? 'Dir. Grupo' : 'Docente' }}: {{ $docente->name }}
                                    </p>
                                    <p class="text-[8px] italic">Firma del Profesional</p>
                                </div>
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