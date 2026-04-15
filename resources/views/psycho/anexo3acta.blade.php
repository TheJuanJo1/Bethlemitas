@extends('layout.homePage')

@section('content')
<div class="container mx-auto p-6 bg-white shadow-md">
    <div class="flex justify-between items-center border-b-2 border-gray-800 pb-4 mb-6">
        <div class="flex items-center">
            <img src="{{ asset('img/credencialesGo.jpg') }}" alt="Logos Oficiales" class="h-16 object-contain">
        </div>
        <div class="text-right">
            <h1 class="font-bold text-lg text-gray-800 uppercase leading-tight">Acta de Acuerdo</h1>
            <p class="text-[10px] text-gray-600">Plan Individual de Ajustes Razonables – PIAR –</p>
            <p class="font-bold text-blue-800">ANEXO 3</p>
        </div>
    </div>

    <div class="mb-6 bg-slate-50 p-4 rounded-lg border border-slate-200 no-print flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <label class="block text-xs font-bold mb-2 text-slate-600 uppercase tracking-wider">Visualizar Periodo:</label>
            <div class="flex space-x-2">
                @foreach([1, 2, 3] as $p)
                    <a href="{{ route('piar.anexo3', ['piar' => $piar->id, 'periodo' => $p]) }}" 
                       class="px-5 py-1.5 rounded-md text-sm font-bold transition-all {{ $periodo_actual == $p ? 'bg-blue-700 text-white shadow-md' : 'bg-white text-blue-700 border border-blue-700 hover:bg-blue-50' }}">
                        P{{ $p }}
                    </a>
                @endforeach
            </div>
        </div>

        <button onclick="window.print()" class="flex items-center justify-center bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg font-bold text-sm transition-colors shadow-lg group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            DESCARGAR ACTA (PDF)
        </button>
    </div>

    <div class="mb-6">
        <table class="w-full border-2 border-gray-800 text-xs">
            <tr>
                <td class="border border-gray-800 p-2 w-1/3">
                    <span class="font-bold block uppercase text-[9px] text-gray-500">Fecha de Generación:</span>
                    {{ now()->format('d/m/Y') }}
                </td>
                <td class="border border-gray-800 p-2" colspan="2">
                    <span class="font-bold block uppercase text-[9px] text-gray-500">Institución educativa y Sede:</span>
                    Institución Educativa Bethlemitas
                </td>
            </tr>
            <tr>
                <td class="border border-gray-800 p-2">
                    <span class="font-bold block uppercase text-[9px] text-gray-500">Nombre del estudiante:</span>
                    {{ $estudiante->name }} {{ $estudiante->last_name }}
                </td>
                <td class="border border-gray-800 p-2">
                    <span class="font-bold block uppercase text-[9px] text-gray-500">Documento de Identificación:</span>
                    {{ $estudiante->type_documment ?? 'TI' }}: {{ $estudiante->number_documment }}
                </td>
                <td class="border border-gray-800 p-2 w-1/4">
                    <div class="flex justify-between">
                        <div>
                            <span class="font-bold block uppercase text-[9px] text-gray-500">Edad:</span>
                            {{ $estudiante->age ?? 'N/R' }}
                        </div>
                        <div class="border-l border-gray-300 pl-4">
                            <span class="font-bold block uppercase text-[9px] text-gray-500">Grado:</span>
                            {{ $estudiante->id_degree ?? 'N/R' }}
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="border border-gray-800 p-2">
                    <span class="font-bold block uppercase text-[9px] text-gray-500">Nombres familia del estudiante:</span>
                    {{ $estudiante->acudiente ?? '__________________________' }}
                </td>
                <td class="border border-gray-800 p-2" colspan="2">
                    <span class="font-bold block uppercase text-[9px] text-gray-500">Parentesco:</span>
                    {{ $estudiante->parentesco_acudiente ?? '__________________________' }}
                </td>
            </tr>
        </table>
    </div>

    <div class="space-y-4 mb-8">
        <div class="bg-gray-800 text-white p-1.5 px-3">
            <h3 class="font-bold uppercase text-[10px] tracking-widest text-center">Compromisos específicos para el aula - Periodo {{ $periodo_actual }}</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-3 border border-gray-300 rounded bg-white">
                <label class="block text-[9px] font-bold text-blue-800 uppercase mb-2">Ajuste Curricular</label>
                <p class="text-xs text-gray-700 min-h-[50px] leading-relaxed">{{ $datos->ajuste_curricular ?? 'Sin registros.' }}</p>
            </div>
            <div class="p-3 border border-gray-300 rounded bg-white">
                <label class="block text-[9px] font-bold text-blue-800 uppercase mb-2">Ajuste Metodológico</label>
                <p class="text-xs text-gray-700 min-h-[50px] leading-relaxed">{{ $datos->ajuste_metodologico ?? 'Sin registros.' }}</p>
            </div>
        </div>

        <div class="grid grid-cols-4 gap-2">
            @foreach(['convivencia', 'socializacion', 'participacion', 'autonomia'] as $campo)
            <div class="p-2 border rounded bg-slate-50 text-center">
                <span class="block text-[8px] font-bold text-gray-500 uppercase">{{ $campo }}</span>
                <p class="text-[10px] font-bold text-gray-900">{{ $datos->$campo ?? 'N/A' }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <div class="mt-32">
        <div class="grid grid-cols-2 gap-20 px-10">
            <div class="text-center">
                <div class="border-b border-gray-900 mb-2"></div>
                <p class="text-[10px] font-bold uppercase">Firma del Padre de Familia / Acudiente</p>
                <p class="text-[9px] text-gray-400 italic">C.C. ________________________</p>
            </div>
            <div class="text-center">
                <div class="border-b border-gray-900 mb-2"></div>
                <p class="text-[10px] font-bold uppercase">Firma del Docente / Institución</p>
                <p class="text-[9px] text-gray-400 italic">I.E. Bethlemitas</p>
            </div>
        </div>
        
        <div class="mt-16 flex flex-col items-center opacity-70">
            <div class="text-[10px] font-bold text-gray-700">V14.16/02/2018. - Ministerio de Educación Nacional – Decreto 1421 de 2017</div>
        </div>
    </div>
</div>

<style>
    @media print {
        /* Oculta botones y selectores al imprimir */
        .no-print { display: none !important; }
        
        /* Ajusta el contenedor para que ocupe toda la hoja */
        .container { 
            box-shadow: none !important; 
            border: none !important; 
            padding: 0 !important; 
            width: 100% !important; 
            max-width: none !important; 
        }
        
        body { background: white !important; }
        
        /* Asegura que los colores de fondo se impriman */
        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    }
</style>
@endsection