@extends('layout.masterPage')

@section('css')
    {{-- Agregamos un poco de suavizado para las transiciones --}}
    <style>
        .custom-scroll::-webkit-scrollbar { width: 8px; }
        .custom-scroll::-webkit-scrollbar-track { background: #f1f1f1; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
@endsection

@section('title', 'Añadir Acta')

@section('content')
<div class="container mx-auto p-6 max-w-[1400px]">
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 flex flex-col h-[calc(100vh-180px)] min-h-[600px]">
        
        <div class="p-6 border-b border-gray-100 bg-gray-50/50 rounded-t-xl">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">Gestión de Actas PIAR</h1>
                    <p class="text-sm text-gray-500 mt-1">Selecciona un estudiante para generar o descargar su acta.</p>
                </div>

                <div class="relative w-full md:w-96">
                    <form action="#" class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                        <input
                            type="search" 
                            name="search"
                            placeholder="Buscar por nombre o documento..."
                            class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none"
                        />
                    </form>
                </div>
            </div>
        </div>

        <div class="flex-grow overflow-auto custom-scroll">
            <table class="w-full text-left border-separate border-spacing-0">
                <thead class="sticky top-0 z-10 bg-white">
                    <tr class="text-xs font-semibold uppercase tracking-wider text-gray-500 bg-white">
                        <th class="px-6 py-4 border-b border-gray-200">Estudiante</th>
                        <th class="px-6 py-4 border-b border-gray-200 text-center">Documento</th>
                        <th class="px-6 py-4 border-b border-gray-200 text-center">Grado / Grupo</th>
                        <th class="px-6 py-4 border-b border-gray-200 text-center">Edad</th>
                        <th class="px-6 py-4 border-b border-gray-200 text-center">Estado y Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($students as $student)
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold mr-3">
                                        {{ substr($student->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-900 capitalize">
                                        {{ $student->name }} {{ $student->last_name }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-600">
                                <span class="bg-gray-100 px-2.5 py-1 rounded-md font-mono text-xs">
                                    {{ $student->number_documment }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-600">
                                <div class="font-medium text-gray-800">{{ $student->degree->degree }}</div>
                                <div class="text-xs text-gray-400">Grupo: {{ $student->group->group }}</div>
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-600">
                                {{ $student->age }} años
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    @if($student->piar && $student->piar->characteristics)
                                        <a href="{{ route('piar.periodos',$student->piar->id) }}" 
                                           class="inline-flex items-center px-3 py-2 text-xs font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-sm transition-all">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                            Periodos
                                        </a>
                                        <a href="{{ route('piar.pdf',$student->piar->id) }}" target="_blank"
                                           class="inline-flex items-center px-3 py-2 text-xs font-semibold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 shadow-sm transition-all">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            Descargar PIAR
                                        </a>
                                    @else
                                        <span class="inline-flex items-center px-4 py-2 rounded-lg bg-amber-50 text-amber-700 text-[11px] font-bold border border-amber-200">
                                            <svg class="w-4 h-4 mr-1.5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            PENDIENTE PSICO
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <svg class="w-16 h-16 mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    <p class="text-lg">No se encontraron estudiantes registrados</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100 bg-gray-50/50 rounded-b-xl">
            {{ $students->links() }}
        </div>
    </div>
</div>
@endsection