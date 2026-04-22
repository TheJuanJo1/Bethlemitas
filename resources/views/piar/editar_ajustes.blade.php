@extends('layout.masterPage')

@section('title', 'PIAR - Editar Ajustes Razonables')

@section('content')
<div class="min-h-screen bg-slate-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        
        {{-- Botón Volver --}}
        <div class="mb-6">
            <button onclick="history.back()" class="inline-flex items-center text-slate-500 hover:text-slate-800 transition-colors duration-200 text-sm font-medium">
                <i class="bi bi-arrow-left mr-2"></i> Volver al listado
            </button>
        </div>

        {{-- Encabezado --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">
                PIAR <span class="text-orange-600">—</span> Editar Ajustes Razonables
            </h1>
        </div>

        {{-- Información del Estudiante --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-10">
            <div class="bg-slate-900 px-6 py-3">
                <h2 class="text-xs font-semibold text-slate-300 uppercase tracking-wider flex items-center">
                    <i class="bi bi-person-circle mr-2"></i> Datos del Estudiante
                </h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center space-x-3">
                    <span class="text-sm font-medium text-slate-500">Nombre completo:</span>
                    <span class="text-sm font-bold text-slate-800">{{ $piar->student->name }} {{ $piar->student->last_name }}</span>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="text-sm font-medium text-slate-500">Grado actual:</span>
                    <span class="text-sm font-bold text-slate-800">{{ $piar->student->degree->degree ?? 'Sin grado' }}</span>
                </div>
            </div>
        </div>

        {{-- Periodos --}}
        @php
            $periodosData = [
                ['titulo' => 'Periodo 1', 'id' => 1, 'datos' => $periodo1],
                ['titulo' => 'Periodo 2', 'id' => 2, 'datos' => $periodo2],
                ['titulo' => 'Periodo 3', 'id' => 3, 'datos' => $periodo3],
            ];
        @endphp

        @foreach($periodosData as $periodo)
            <div class="mb-12">
                <div class="flex items-center mb-6 space-x-4">
                    <h2 class="text-xl font-bold text-slate-800">{{ $periodo['titulo'] }}</h2>
                    <div class="flex-grow h-px bg-slate-200"></div>
                </div>

                @if($periodo['datos']->isEmpty())
                    <div class="bg-slate-100 border border-slate-200 rounded-lg p-6 text-center">
                        <p class="text-slate-500 italic text-sm">No hay ajustes registrados para este periodo.</p>
                    </div>
                @else
                    <form action="{{ route('piar.psico.ajustes.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="piar_id" value="{{ $piar->id }}">
                        <input type="hidden" name="period" value="{{ $periodo['id'] }}">

                        <div class="space-y-8">
                            @foreach($periodo['datos'] as $adj)
                                <div class="bg-white rounded-xl shadow-md border border-slate-200 overflow-hidden transition-all hover:shadow-lg">
                                    {{-- Cabecera del Área --}}
                                    <div class="bg-orange-50 px-6 py-4 border-b border-orange-100 flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="w-1 h-6 bg-orange-500 rounded-full mr-3"></div>
                                            <span class="text-orange-800 font-bold uppercase tracking-wide text-sm">{{ $adj->area }}</span>
                                            <input type="hidden" name="adjustment_id[]" value="{{ $adj->id }}">
                                        </div>
                                    </div>

                                    {{-- Campos del Formulario --}}
                                    <div class="p-8">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-8">
                                            
                                            {{-- Grupo 1: General --}}
                                            <div class="space-y-2">
                                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Objetivo</label>
                                                <textarea name="objetivo[]" rows="3" class="block w-full rounded-lg border-slate-300 bg-slate-50 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm p-3 transition-colors hover:bg-white">{{ $adj->objetivo }}</textarea>
                                            </div>
                                            
                                            <div class="space-y-2">
                                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Barrera</label>
                                                <textarea name="barrera[]" rows="3" class="block w-full rounded-lg border-slate-300 bg-slate-50 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm p-3 transition-colors hover:bg-white">{{ $adj->barrera }}</textarea>
                                            </div>

                                            {{-- Grupo 2: Ajustes --}}
                                            <div class="space-y-2">
                                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1 text-blue-700">Ajuste Curricular</label>
                                                <textarea name="ajuste_curricular[]" rows="3" class="block w-full rounded-lg border-blue-200 bg-blue-50/30 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-3 transition-colors hover:bg-white italic">{{ $adj->ajuste_curricular }}</textarea>
                                            </div>

                                            <div class="space-y-2">
                                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1 text-blue-700">Ajuste Metodológico</label>
                                                <textarea name="ajuste_metodologico[]" rows="3" class="block w-full rounded-lg border-blue-200 bg-blue-50/30 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-3 transition-colors hover:bg-white italic">{{ $adj->ajuste_metodologico }}</textarea>
                                            </div>

                                            <div class="space-y-2">
                                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1 text-blue-700">Ajuste Evaluativo</label>
                                                <textarea name="ajuste_evaluativo[]" rows="3" class="block w-full rounded-lg border-blue-200 bg-blue-50/30 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-3 transition-colors hover:bg-white italic">{{ $adj->ajuste_evaluativo }}</textarea>
                                            </div>

                                            {{-- Grupo 3: Social --}}
                                            <div class="space-y-2">
                                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1 text-emerald-700">Convivencia</label>
                                                <textarea name="convivencia[]" rows="3" class="block w-full rounded-lg border-emerald-200 bg-emerald-50/30 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm p-3 transition-colors hover:bg-white">{{ $adj->convivencia }}</textarea>
                                            </div>

                                            <div class="space-y-2">
                                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1 text-emerald-700">Socialización</label>
                                                <textarea name="socializacion[]" rows="3" class="block w-full rounded-lg border-emerald-200 bg-emerald-50/30 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm p-3 transition-colors hover:bg-white">{{ $adj->socializacion }}</textarea>
                                            </div>

                                            <div class="space-y-2">
                                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1 text-emerald-700">Participación</label>
                                                <textarea name="participacion[]" rows="3" class="block w-full rounded-lg border-emerald-200 bg-emerald-50/30 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm p-3 transition-colors hover:bg-white">{{ $adj->participacion }}</textarea>
                                            </div>

                                            <div class="space-y-2">
                                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1 text-purple-700">Autonomía</label>
                                                <textarea name="autonomia[]" rows="3" class="block w-full rounded-lg border-purple-200 bg-purple-50/30 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm p-3 transition-colors hover:bg-white">{{ $adj->autonomia }}</textarea>
                                            </div>

                                            <div class="space-y-2">
                                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1 text-purple-700">Autocontrol</label>
                                                <textarea name="autocontrol[]" rows="3" class="block w-full rounded-lg border-purple-200 bg-purple-50/30 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm p-3 transition-colors hover:bg-white">{{ $adj->autocontrol }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Botón de Guardar Periodo --}}
                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="inline-flex items-center px-8 py-3 bg-orange-600 border border-transparent rounded-lg font-bold text-white uppercase tracking-widest hover:bg-orange-700 active:bg-orange-800 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all shadow-lg hover:shadow-orange-200 transform hover:-translate-y-1">
                                <i class="bi bi-check-lg mr-2 text-xl"></i> Guardar {{ $periodo['titulo'] }}
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        @endforeach

    </div>
</div>

<style>
    /* Estilos personalizados para complementar Tailwind */
    textarea {
        resize: vertical;
        min-height: 80px;
    }
    .form-section-title {
        position: relative;
        padding-left: 1rem;
    }
    .form-section-title::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background-color: #f97316;
        border-radius: 2px;
    }
</style>
@endsection