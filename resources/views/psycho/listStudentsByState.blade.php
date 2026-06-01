@extends('layout.masterPage')

@section('css')
<style>
    /* Estilo para manejar el ancho de las celdas de acciones */
    .actions-cell {
        min-width: 150px;
    }
    @media (min-width: 768px) {
        .actions-cell {
            min-width: 250px;
        }
    }
    .btn-transition {
        transition: all 0.2s ease-in-out;
    }
    .btn-transition:hover {
        transform: scale(1.05);
    }
    details summary { list-style: none; }
    table tbody td { overflow: visible; }
    .acta-dropdown .acta-submenu { display: none; }
    .acta-dropdown[open] .acta-submenu { display: flex; }
    .acta-dropdown[open] > summary {
        background-color: #a7f3d0;
        border-color: #059669;
    }
</style>
@endsection

@section('title', 'Estudiantes')

@section('content')

<div class="flex justify-center p-1 md:p-4">
    <div class="w-full bg-white rounded-lg shadow-lg overflow-hidden flex flex-col" style="max-height: 85vh;">

         <!-- Encabezado Sticky -->
         <div class="p-4 border-b bg-white sticky top-0 z-40 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h1 class="text-xl md:text-2xl font-extrabold text-gray-800 border-l-4 border-blue-600 pl-3">
                    Estudiantes {{ $stateLabel }}
                </h1>

                <form action="{{ $route }}" class="flex w-full md:w-auto gap-2">
                    <div class="relative flex-grow md:flex-grow-0">
                        <input type="search" name="search" value="{{ request('search') }}" placeholder="Buscar estudiante..."
                            class="w-full md:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent text-sm transition-all" />
                        <div class="absolute left-3 top-2.5 text-gray-400">
                            <i class="bi bi-search"></i>
                        </div>
                    </div>
                    <button type="submit" class="px-5 py-2 text-white bg-blue-600 rounded-full hover:bg-blue-700 transition-colors shadow-sm text-sm font-bold">
                        Buscar
                    </button>
                </form>
            </div>
        </div>

        <!-- Contenedor de Tabla con Scroll -->
        <div class="flex-grow overflow-auto overflow-x-auto">
            <table class="w-full border-collapse table-auto text-xs md:text-sm">
                <thead>
                    <tr class="text-gray-700 uppercase bg-gray-50 border-b">
                        <th class="px-4 py-3 text-left font-bold">Nombre</th>
                        <th class="px-3 py-3 text-center font-bold hidden lg:table-cell">Documento</th>
                        <th class="px-3 py-3 text-center font-bold hidden sm:table-cell">Grado</th>
                        <th class="px-3 py-3 text-center font-bold hidden xl:table-cell">Grupo</th>
                        <th class="px-3 py-3 text-center font-bold hidden lg:table-cell">Edad</th>
                        <th class="px-3 py-3 text-center font-bold hidden md:table-cell">Anexo</th>
                        <th class="px-3 py-3 text-center font-bold">Gestión PIAR</th>
                        <th class="px-3 py-3 text-center font-bold">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse ($students as $student)
                    <tr class="hover:bg-blue-50/30 transition-colors">
                        <td class="px-4 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-900">{{ $student->name }} {{ $student->last_name }}</span>
                                <span class="text-[10px] text-gray-500 lg:hidden">{{ $student->number_documment }}</span>
                                <span class="text-[10px] text-blue-600 sm:hidden">{{ $student->degree->degree }} {{ $student->group->group ?? '' }}</span>
                            </div>
                        </td>

                        <td class="px-3 py-4 text-center hidden lg:table-cell text-gray-600">
                            {{ $student->number_documment }}
                        </td>

                        <td class="px-3 py-4 text-center hidden sm:table-cell font-medium">
                            {{ $student->degree->degree }}
                        </td>

                        <td class="px-3 py-4 text-center hidden xl:table-cell">
                            <span class="px-2 py-1 bg-gray-100 rounded text-gray-700 text-[11px]">
                                {{ $student->group->group ?? 'Sin grupo' }}
                            </span>
                        </td>

                        <td class="px-3 py-4 text-center hidden lg:table-cell">
                            {{ $student->age }}
                        </td>

                        <td class="px-3 py-4 text-center hidden md:table-cell">
                            @if($student->has_annex > 0) 
                                <span class="text-green-600" title="Tiene anexo"><i class="bi bi-check-circle-fill text-lg"></i></span>
                            @else 
                                <span class="text-red-400" title="Sin anexo"><i class="bi bi-x-circle-fill text-lg"></i></span>
                            @endif
                        </td>

                        <td class="px-3 py-4 text-center bg-gray-50/50 overflow-visible relative">
                            <div class="flex flex-wrap justify-center gap-1.5 max-w-[150px] mx-auto overflow-visible">
                                <a href="{{ route('piar.create', $student->id) }}"
                                   class="px-2.5 py-1 text-[10px] font-bold text-amber-700 bg-amber-100 rounded border border-amber-200 hover:bg-amber-200 btn-transition"
                                   title="Crear PIAR">
                                   Llenar </a>

                                @if($student->piar && $student->piar->adjustments()->exists())
                                    <a href="{{ route('piar.psico.ajustes.edit', $student->piar->id) }}"
                                       class="px-2.5 py-1 text-[10px] font-bold text-orange-700 bg-orange-100 rounded border border-orange-200 hover:bg-orange-200 btn-transition"
                                       title="Editar Ajustes">
                                       Editar </a>
                                @else
                                    <span class="px-2.5 py-1 text-[10px] font-bold text-gray-400 bg-gray-50 rounded border border-gray-100 opacity-60 cursor-not-allowed">
                                       Editar
                                    </span>
                                @endif

                                <x-piar-acta-dropdown
                                    :piar-id="$student->piar?->id"
                                    :disabled="!($student->piar && $student->piar->characteristics)"
                                />
                            </div>
                        </td>

                        <td class="px-3 py-4 text-center actions-cell">
                            <div class="flex flex-wrap justify-center gap-1.5">
                                <a href="{{ route('details.referral', $student->id) }}"
                                   class="p-1.5 md:px-3 md:py-1 text-white bg-blue-500 rounded hover:bg-blue-600 btn-transition flex items-center justify-center"
                                   title="Ver Remisión">
                                   <i class="bi bi-eye md:hidden"></i><span class="hidden md:inline">Ver</span>
                                </a>

                                <a href="{{ route('report.student', $student->id) }}"
                                   class="p-1.5 md:px-3 md:py-1 text-white bg-red-500 rounded hover:bg-red-600 btn-transition flex items-center justify-center"
                                   title="Informe">
                                   <i class="bi bi-file-earmark-text md:hidden"></i><span class="hidden md:inline">Informe</span>
                                </a>

                                <a href="{{ route('show.student.history', $student->id) }}"
                                   class="p-1.5 md:px-3 md:py-1 text-white bg-yellow-500 rounded hover:bg-orange-600 btn-transition flex items-center justify-center"
                                   title="Historial">
                                   <i class="bi bi-clock-history md:hidden"></i><span class="hidden md:inline">Historial</span>
                                </a>

                                @if($student->piar)
                                    <a href="{{ route('piar.periodos', $student->piar->id) }}"
                                       class="p-1.5 md:px-3 md:py-1 text-white bg-indigo-600 rounded hover:bg-indigo-800 btn-transition flex items-center justify-center"
                                       title="Fecha Periodos">
                                       <i class="bi bi-calendar-check md:hidden"></i><span class="hidden md:inline text-[10px] leading-tight">Fechas</span>
                                    </a>
                                @else
                                    <button class="p-1.5 md:px-3 md:py-1 text-white bg-gray-400 rounded cursor-not-allowed opacity-60 flex items-center justify-center" 
                                            disabled title="Debe crear el PIAR primero">
                                        <i class="bi bi-calendar-x md:hidden"></i><span class="hidden md:inline text-[10px] leading-tight">Fechas</span>
                                    </button>
                                @endif

                                <a href="{{ route('piar.periodos.historial', $student->id) }}"
                                   class="p-1.5 md:px-3 md:py-1 text-white bg-slate-600 rounded hover:bg-slate-800 btn-transition flex items-center justify-center text-center"
                                   title="Cronología de los Periodos">
                                   <i class="bi bi-list-ol md:hidden"></i><span class="hidden md:inline text-[10px] leading-tight">Cronología</span>
                                </a>

                                @if($student->piar)
                                    <a href="{{ route('piar.anexo3', ['piar' => $student->piar->id, 'periodo' => 1]) }}"
                                       class="p-1.5 md:px-3 md:py-1 text-white bg-green-600 rounded hover:bg-green-800 btn-transition flex items-center justify-center"
                                       title="Anexo 3">
                                       <i class="bi bi-file-plus md:hidden"></i><span class="hidden md:inline text-[10px] leading-tight">Anexo 3</span>
                                    </a>
                                @else
                                    <button class="p-1.5 md:px-3 md:py-1 text-white bg-gray-400 rounded cursor-not-allowed opacity-60 flex items-center justify-center" 
                                            disabled title="Sin PIAR">
                                        <i class="bi bi-file-x md:hidden"></i><span class="hidden md:inline text-[10px] leading-tight">Anexo 3</span>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-10 text-gray-500 bg-gray-50">
                            <i class="bi bi-people text-4xl block mb-2 opacity-20"></i>
                            No hay estudiantes para mostrar
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="p-4 border-t bg-gray-50 mt-auto">
            {{ $students->links() }}
        </div>
    </div>
</div>

@endsection
