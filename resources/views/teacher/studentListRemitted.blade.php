@extends('layout.masterPage')

@section('css')
<style>
    /* Scrollbar personalizada para modo claro */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9; /* slate-100 */
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1; /* slate-300 */
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8; /* slate-400 */
    }
</style>
@endsection

@section('title', 'Estudiantes Remitidos')

@section('content')

<div class="min-h-screen p-4 sm:p-6 lg:p-8 bg-slate-50 text-slate-800">
    <div class="max-w-7xl mx-auto">
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                    Estudiantes <span class="text-indigo-600">Remitidos</span>
                </h1>
                <p class="mt-2 text-sm text-slate-600">
                    Listado de estudiantes actualmente en proceso de remisión.
                </p>
            </div>

            <div class="relative group">
                <form action="{{ route('index.student.remitted') }}" class="flex items-center">
                    <div class="relative w-full sm:w-80">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400 group-focus-within:text-indigo-600 transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M16.65 16.65a7 7 0 111.414-1.414l4.35 4.35-1.414 1.414z" />
                            </svg>
                        </div>
                        <input
                            type="search" 
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Buscar estudiante..."
                            class="block w-full pl-10 pr-3 py-2.5 bg-white border border-slate-300 rounded-xl leading-5 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 sm:text-sm shadow-sm"
                        />
                    </div>
                    <button type="submit" class="ml-3 inline-flex items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-xl shadow-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 transform hover:scale-105 active:scale-95">
                        Buscar
                    </button>   
                </form>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Nombre Completo</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Documento</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Grado</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Grupo</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-center">Edad</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($students as $student)
                            <tr class="group hover:bg-slate-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-700 flex items-center justify-center text-white font-bold text-sm shadow-sm uppercase">
                                                {{ substr($student->name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-slate-900 group-hover:text-indigo-600 transition-colors">
                                                {{ $student->name }} {{ $student->last_name }}
                                            </div>
                                            <div class="text-xs text-slate-500">
                                                Estudiante activo
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $student->number_documment }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 font-medium">
                                    {{ $student->degree->degree }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $student->group ? 'bg-indigo-50 text-indigo-700 border border-indigo-100' : 'bg-slate-50 text-slate-400 border border-slate-200' }}">
                                        {{ $student->group->group ?? 'Sin grupo' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 text-center">
                                    <span class="font-medium">{{ $student->age }} años</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('edit.student', $student->id) }}" class="inline-flex items-center px-3 py-1.5 border border-indigo-200 text-indigo-600 hover:bg-indigo-600 hover:text-white rounded-lg transition-all duration-200 shadow-sm">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Editar
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-slate-500 text-lg font-medium">No se encontraron estudiantes remitidos.</p>
                                        @if(request('search'))
                                            <p class="text-slate-400 text-sm mt-1">Intenta con otros términos de búsqueda.</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
                {{ $students->links() }}
            </div>
        </div>
    </div>
</div>

@endsection