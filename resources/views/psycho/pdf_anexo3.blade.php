<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Anexo 3 - PIAR - Periodo {{ $periodo_actual }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page { size: letter; margin: 1cm; }
        body { background: white; font-family: sans-serif; }
        .print-only { display: block; }
    </style>
</head>
<body onload="window.print()"> <div class="max-w-4xl mx-auto p-4">
        <div class="flex justify-between items-center border-b-2 border-gray-800 pb-2 mb-4">
            <img src="{{ asset('img/credencialesGo.jpg') }}" class="h-14 object-contain">
            <div class="text-right">
                <h1 class="font-bold text-md uppercase">Acta de Acuerdo</h1>
                <p class="text-[9px]">Plan Individual de Ajustes Razonables – PIAR –</p>
                <p class="font-bold text-blue-800 text-xs">ANEXO 3</p>
            </div>
        </div>

        <table class="w-full border-collapse border border-black text-[10px] mb-4">
            <tr>
                <td class="border border-black p-1 bg-gray-50 font-bold w-1/4">ESTUDIANTE:</td>
                <td class="border border-black p-1">{{ $estudiante->name }} {{ $estudiante->last_name }}</td>
                <td class="border border-black p-1 bg-gray-50 font-bold w-1/6">DOC:</td>
                <td class="border border-black p-1">{{ $estudiante->number_documment }}</td>
            </tr>
            <tr>
                <td class="border border-black p-1 bg-gray-50 font-bold">INSTITUCIÓN:</td>
                <td class="border border-black p-1">Bethlemitas</td>
                <td class="border border-black p-1 bg-gray-50 font-bold">PERIODO:</td>
                <td class="border border-black p-1 font-bold text-center">{{ $periodo_actual }}</td>
            </tr>
        </table>

        <div class="mb-4">
            <div class="bg-gray-800 text-white p-1 text-[10px] font-bold uppercase text-center mb-2">
                Compromisos para el Aula
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div class="border border-black p-2 min-h-[100px]">
                    <span class="font-bold text-[9px] block mb-1">AJUSTE CURRICULAR:</span>
                    <p class="text-[10px]">{{ $datos->ajuste_curricular ?? 'N/R' }}</p>
                </div>
                <div class="border border-black p-2 min-h-[100px]">
                    <span class="font-bold text-[9px] block mb-1">AJUSTE METODOLÓGICO:</span>
                    <p class="text-[10px]">{{ $datos->ajuste_metodologico ?? 'N/R' }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-4 gap-1 mb-6">
            @foreach(['convivencia', 'socializacion', 'participacion', 'autonomia'] as $c)
            <div class="border border-black p-1 text-center">
                <span class="block text-[8px] font-bold uppercase">{{ $c }}</span>
                <span class="text-[10px]">{{ $datos->$c ?? 'N/A' }}</span>
            </div>
            @endforeach
        </div>

        <div class="mt-20 grid grid-cols-2 gap-16 px-8">
            <div class="text-center border-t border-black pt-1">
                <p class="text-[10px] font-bold uppercase">Padre de Familia / Acudiente</p>
                <p class="text-[9px]">C.C. ________________________</p>
            </div>
            <div class="text-center border-t border-black pt-1">
                <p class="text-[10px] font-bold uppercase">Docente / Institución</p>
                <p class="text-[9px]">I.E. Bethlemitas</p>
            </div>
        </div>

        <div class="mt-10 text-center text-[8px] text-gray-500 italic">
            Documento generado el {{ now()->format('d/m/Y H:i') }} - Sistema PiarManager
        </div>
    </div>
</body>
</html>