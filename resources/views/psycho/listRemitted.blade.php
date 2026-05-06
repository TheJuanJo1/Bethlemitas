@extends('layout.masterPage')

@section('css')
<style>
    .btn-transition {
        transition: all 0.2s ease-in-out;
    }
    .btn-transition:hover {
        transform: scale(1.05);
    }
</style>
@endsection

@section('title', 'Estudiantes Remitidos')

@section('content')

<div class="flex justify-center p-1 md:p-4">
    <div class="w-full bg-white rounded-lg shadow-lg overflow-hidden flex flex-col" style="max-height: 85vh;">
        
        <!-- Encabezado Sticky -->
        <div class="p-4 border-b bg-white sticky top-0 z-40 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h1 class="text-xl md:text-2xl font-extrabold text-gray-800 border-l-4 border-red-500 pl-3">
                    Estudiantes Remitidos
                </h1>

                <form action="{{ route('index.student.remitted.psico') }}" class="flex w-full md:w-auto gap-2">
                    <div class="relative flex-grow md:flex-grow-0">
                        <input type="search" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre o doc..."
                            class="w-full md:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent text-sm transition-all" />
                        <div class="absolute left-3 top-2.5 text-gray-400">
                            <i class="bi bi-search"></i>
                        </div>
                    </div>
                    <button type="submit" class="px-5 py-2 text-white bg-red-500 rounded-full hover:bg-red-600 transition-colors shadow-sm text-sm font-bold">
                        Buscar
                    </button>
                </form>
            </div>
        </div>

        <!-- Tabla -->
        <div class="flex-grow overflow-auto">
            <table class="w-full border-collapse table-auto text-xs md:text-sm">
                <thead>
                    <tr class="text-gray-700 uppercase bg-gray-50 border-b">
                        <th class="px-4 py-3 text-left font-bold">Nombre completo</th>
                        <th class="px-3 py-3 text-center font-bold hidden md:table-cell">Documento</th>
                        <th class="px-3 py-3 text-center font-bold">Grado</th>
                        <th class="px-3 py-3 text-center font-bold hidden lg:table-cell">Grupo</th>
                        <th class="px-3 py-3 text-center font-bold hidden sm:table-cell">Edad</th>
                        <th class="px-3 py-3 text-center font-bold">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse ($students as $student)
                        <tr class="hover:bg-red-50/20 transition-colors">
                            <td class="px-4 py-4">
                                <div class="flex flex-col">
                                    <span class="font-bold text-gray-900">{{ $student->name }} {{ $student->last_name }}</span>
                                    <span class="text-[10px] text-gray-500 md:hidden">{{ $student->number_documment }}</span>
                                </div>
                            </td>

                            <td class="px-3 py-4 text-center hidden md:table-cell">
                                {{ $student->number_documment }}
                            </td>

                            <td class="px-3 py-4 text-center font-medium">
                                {{ $student->degree->degree }}
                            </td>

                            <td class="px-3 py-4 text-center hidden lg:table-cell">
                                <span class="px-2 py-1 bg-gray-100 rounded text-gray-700 text-[11px]">
                                    {{ $student->group->group ?? 'Sin grupo' }}
                                </span>
                            </td>

                            <td class="px-3 py-4 text-center hidden sm:table-cell">
                                {{ $student->age }}
                            </td>

                            <td class="px-3 py-4 text-center">
                                <div class="flex flex-wrap justify-center gap-2">
                                    <!-- Ver detalles -->
                                    <a href="{{ route('details.referral', $student->id) }}"
                                       class="p-1.5 md:px-3 md:py-1 text-white bg-blue-500 rounded hover:bg-blue-600 btn-transition flex items-center gap-1 shadow-sm"
                                       title="Ver detalles">
                                        <i class="bi bi-eye"></i> <span class="hidden md:inline">Ver</span>
                                    </a>

                                    <!-- Crear informe -->
                                    <a href="{{ route('report.student', $student->id) }}"
                                       class="p-1.5 md:px-3 md:py-1 text-white bg-red-500 rounded hover:bg-red-600 btn-transition flex items-center gap-1 shadow-sm"
                                       title="Crear informe">
                                        <i class="bi bi-file-earmark-plus"></i> <span class="hidden md:inline">Informe</span>
                                    </a>

                                    <!-- Ver historial -->
                                    <a href="{{ route('show.student.history', $student->id) }}"
                                       class="p-1.5 md:px-3 md:py-1 text-white bg-yellow-500 rounded hover:bg-orange-600 btn-transition flex items-center gap-1 shadow-sm"
                                       title="Ver historial">
                                        <i class="bi bi-clock-history"></i> <span class="hidden md:inline">Historial</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-gray-500 bg-gray-50">
                                <i class="bi bi-person-x text-4xl block mb-2 opacity-20"></i>
                                No hay estudiantes remitidos para mostrar
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
