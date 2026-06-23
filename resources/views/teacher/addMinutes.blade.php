 @extends('layout.masterPage')

@section('css')
    <style>
        .custom-scroll::-webkit-scrollbar { width: 8px; }
        .custom-scroll::-webkit-scrollbar-track { background: #f1f1f1; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        .btn-transition {
            transition: all 0.2s ease-in-out;
        }
        .btn-transition:hover {
            transform: scale(1.03);
        }
    </style>
@endsection

@section('title', 'Añadir Acta')

@section('content')
<div class="container mx-auto p-1 md:p-6 max-w-[1400px]">
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 flex flex-col h-[calc(100vh-180px)] min-h-[600px] overflow-hidden">
        
        <!-- Header -->
        <div class="p-4 md:p-6 border-b border-gray-100 bg-gray-50/50 rounded-t-xl sticky top-0 z-20">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl md:text-2xl font-extrabold text-gray-800 tracking-tight">Gestión de Actas PIAR</h1>
                    <p class="text-xs md:text-sm text-gray-500 mt-1">Selecciona un estudiante para gestionar sus periodos o acta.</p>
                </div>

                <div class="relative w-full lg:w-96">
                    <form action="#" class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                            <i class="bi bi-search"></i>
                        </span>
                        <input
                            type="search" 
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Nombre o documento..."
                            class="w-full pl-10 pr-4 py-2 bg-white border border-gray-300 rounded-full text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none shadow-sm"
                        />
                    </form>
                </div>
            </div>
        </div>

        <!-- Table Container -->
        <div class="flex-grow overflow-auto custom-scroll">
            <table class="w-full text-left border-separate border-spacing-0">
                <thead class="sticky top-0 z-10 bg-white">
                    <tr class="text-[10px] md:text-xs font-semibold uppercase tracking-wider text-gray-500 bg-white border-b">
                        <th class="px-4 md:px-6 py-4 border-b border-gray-200">Estudiante</th>
                        <th class="px-6 py-4 border-b border-gray-200 text-center hidden sm:table-cell">Documento</th>
                        <th class="px-4 md:px-6 py-4 border-b border-gray-200 text-center">Grado / Grupo</th>
                        <th class="px-6 py-4 border-b border-gray-200 text-center hidden md:table-cell">Edad</th>
                        <th class="px-4 md:px-6 py-4 border-b border-gray-200 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($students as $student)
                        <tr class="hover:bg-blue-50/20 transition-colors group">
                            <td class="px-4 md:px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 md:h-10 md:w-10 flex-shrink-0 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold mr-2 md:mr-3 text-xs md:text-base">
                                        {{ substr($student->name, 0, 1) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs md:text-sm font-bold text-gray-900 capitalize">
                                            {{ $student->name }} {{ $student->last_name }}
                                        </span>
                                        <span class="text-[10px] text-gray-500 sm:hidden">Doc: {{ $student->number_documment }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-600 hidden sm:table-cell">
                                <span class="bg-gray-100 px-2.5 py-1 rounded-md font-mono text-[10px] md:text-xs">
                                    {{ $student->number_documment }}
                                </span>
                            </td>
                            <td class="px-4 md:px-6 py-4 text-center">
                                <div class="text-[11px] md:text-sm font-medium text-gray-800">{{ $student->degree->degree }}</div>
                                <div class="text-[10px] text-gray-400">Grupo: {{ $student->group->group }}</div>
                            </td>
                            <td class="px-6 py-4 text-center text-xs md:text-sm text-gray-600 hidden md:table-cell">
                                {{ $student->age }} años
                            </td>
                            <td class="px-4 md:px-6 py-4">
                                <div class="flex flex-col sm:flex-row justify-center gap-1.5 md:gap-2">
                                    @if($student->piar && $student->piar->characteristics)
                                        <a href="{{ route('piar.periodos',$student->piar->id) }}" 
                                           class="inline-flex items-center justify-center px-2.5 py-1.5 md:px-3 md:py-2 text-[10px] md:text-xs font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-sm transition-all btn-transition">
                                            <i class="bi bi-journal-check mr-1 md:mr-1.5"></i>
                                            Periodos
                                        </a>
                                    @else
                                        <span class="inline-flex items-center justify-center px-3 py-1.5 md:px-4 md:py-2 rounded-lg bg-amber-50 text-amber-700 text-[10px] font-bold border border-amber-200">
                                            <i class="bi bi-hourglass-split mr-1 md:mr-1.5 animate-pulse"></i>
                                            PENDIENTE PSICO
                                        </span>
                                    @endif

                                    @if(Auth::user()->group_director === $student->id_group && $student->piar)
                                        <a href="{{ route('piar.annual_report.edit', $student->piar->id) }}" 
                                           class="inline-flex items-center justify-center px-2.5 py-1.5 md:px-3 md:py-2 text-[10px] md:text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition-all btn-transition">
                                            <i class="bi bi-file-earmark-bar-graph mr-1 md:mr-1.5"></i>
                                            Inf. Anual
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-20 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="bi bi-people text-5xl mb-4 opacity-20"></i>
                                    <p class="text-lg font-medium">No se encontraron estudiantes registrados</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="p-4 border-t border-gray-100 bg-gray-50/50 rounded-b-xl">
            {{ $students->links() }}
        </div>
    </div>
</div>
@endsection
