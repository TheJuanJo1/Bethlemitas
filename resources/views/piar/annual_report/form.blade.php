@extends('layout.masterPage')

@section('title', 'Informe Anual por Competencias')

@section('css')
<style>
    .form-textarea {
        width: 100%;
        min-height: 120px;
        padding: 0.75rem 1rem;
        background-color: #f9fafb;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
        transition: all 0.2s ease-in-out;
        resize: vertical;
    }
    .form-textarea:focus {
        background-color: #ffffff;
        border-color: #4f46e5;
        ring: 2px;
        ring-color: #c7d2fe;
        outline: none;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
    }
    .meta-box {
        background: #f8fafc;
        border-left: 4px solid #6366f1;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto p-4 md:p-8 max-w-[1000px]">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
        
        <!-- Header con Imagen -->
        <div class="p-6 md:p-8 border-b border-slate-100 bg-slate-50/50">
            <div class="flex flex-col items-center text-center">
                <img src="{{ asset('img/ImagenAnual.png') }}" class="max-h-20 w-auto object-contain mb-4" alt="Logo de Inclusión">
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">
                    INFORME ANUAL POR COMPETENCIAS INCLUSIÓN ESCOLAR
                </h1>
                <p class="text-sm md:text-base font-semibold text-indigo-600 mt-2">
                    Decreto 1421 de 2017
                </p>
            </div>
        </div>

        <div class="p-6 md:p-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-xl text-emerald-800 text-sm flex items-center gap-2">
                    <i class="bi bi-check-circle-fill text-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Datos Autocompletados -->
            <div class="meta-box p-4 rounded-r-xl mb-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Nombre del estudiante</p>
                    <p class="text-base text-slate-800 font-bold">{{ $student->name }} {{ $student->last_name }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Identidad</p>
                    <p class="text-base text-slate-800 font-bold">{{ $student->number_documment }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Edad / Grado</p>
                    <p class="text-base text-slate-800 font-bold">
                        {{ $student->age }} años | {{ $student->degree->degree ?? 'N/A' }} {{ $student->group->group ?? '' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Director de Grupo</p>
                    <p class="text-base text-slate-800 font-bold">{{ $director ? ($director->name . ' ' . $director->last_name) : 'No asignado' }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Año lectivo</p>
                    <p class="text-base text-slate-800 font-bold">{{ $piar->year }}</p>
                </div>
            </div>

            <!-- Formulario de observaciones -->
            <form action="{{ route('piar.annual_report.store', $piar->id) }}" method="POST">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label for="competencies" class="block text-sm font-bold text-slate-700 mb-2">
                            Competencias, habilidades y/o saberes adquiridos:
                        </label>
                        <textarea id="competencies" name="competencies" class="form-textarea" 
                                  placeholder="Detalle las competencias y saberes logrados por el estudiante..."
                        >{{ old('competencies', $annualReport->competencies) }}</textarea>
                    </div>

                    <div>
                        <label for="aspects" class="block text-sm font-bold text-slate-700 mb-2">
                            Dificultades y/o necesidades persistentes:
                        </label>
                        <textarea id="aspects" name="aspects" class="form-textarea" 
                                  placeholder="Describa las necesidades de apoyo o dificultades que persisten..."
                        >{{ old('aspects', $annualReport->aspects) }}</textarea>
                    </div>

                    <div>
                        <label for="behavior_observation" class="block text-sm font-bold text-slate-700 mb-2">
                            Observaciones sobre comportamiento y convivencia escolar:
                        </label>
                        <textarea id="behavior_observation" name="behavior_observation" class="form-textarea" 
                                  placeholder="Indique las observaciones sobre su comportamiento y convivencia..."
                        >{{ old('behavior_observation', $annualReport->behavior_observation) }}</textarea>
                    </div>

                    <div>
                        <label for="academic_observation" class="block text-sm font-bold text-slate-700 mb-2">
                            Observaciones sobre desempeño académico:
                        </label>
                        <textarea id="academic_observation" name="academic_observation" class="form-textarea" 
                                  placeholder="Indique las observaciones sobre su rendimiento y desarrollo académico..."
                        >{{ old('academic_observation', $annualReport->academic_observation) }}</textarea>
                    </div>

                    <div>
                        <label for="recommendations" class="block text-sm font-bold text-slate-700 mb-2">
                            Recomendaciones para el siguiente año escolar (para la familia, docentes y directivos):
                        </label>
                        <textarea id="recommendations" name="recommendations" class="form-textarea" 
                                  placeholder="Escriba las recomendaciones generales de apoyo para el año siguiente..."
                        >{{ old('recommendations', $annualReport->recommendations) }}</textarea>
                    </div>
                </div>

                <!-- Botones -->
                <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row justify-end gap-3">
                    <a href="{{ route('addMinutes') }}" class="px-6 py-2.5 bg-slate-100 text-slate-700 font-bold rounded-lg hover:bg-slate-200 transition text-center">
                        Regresar
                    </a>
                    
                    <a href="{{ route('piar.annual_report.pdf', $piar->id) }}" target="_blank" class="px-6 py-2.5 bg-emerald-600 text-white font-bold rounded-lg hover:bg-emerald-700 transition flex items-center justify-center gap-2">
                        <i class="bi bi-file-earmark-pdf"></i> Generar PDF
                    </a>

                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 transition shadow-md shadow-indigo-100">
                        Guardar Cambios
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
