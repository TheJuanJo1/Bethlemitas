@extends('layout.masterPage')

@section('title', 'Detalles de remisión')

@section('content')
<div class="container mx-auto py-8 px-4 sm:px-6">
    <div class="max-w-5xl mx-auto bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        
        {{-- CABECERA --}}
        <div class="bg-slate-50 border-b border-slate-100 p-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <button onclick="history.back()" 
                    class="flex items-center justify-center w-10 h-10 rounded-full bg-white border border-slate-200 text-slate-600 hover:bg-white hover:shadow transition-all">
                    <i class="bi bi-arrow-left" style="font-size: 1.25rem;"></i>
                </button>
                <div>
                    <h1 class="text-xl font-black text-slate-800 tracking-tight">Detalles de Remisión</h1>
                    <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest">Consulta de expediente académico</p>
                </div>
            </div>
            <div class="hidden sm:block">
                <span class="px-4 py-1 text-[10px] font-black uppercase tracking-tighter bg-slate-200 text-slate-600 rounded-full">Modo Lectura</span>
            </div>
        </div>

        <div class="p-8 lg:p-10 space-y-12">
            
            {{-- SECCIÓN 1: IDENTIFICACIÓN --}}
            <section>
                <div class="flex items-center gap-2 mb-6 text-slate-400">
                    <span class="text-lg">🆔</span>
                    <h2 class="font-bold uppercase tracking-widest text-[11px]">Identificación del Estudiante</h2>
                    <div class="flex-grow h-px bg-slate-100 ml-2"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase ml-1">Nombres</label>
                        <div class="px-4 py-3 bg-slate-50/50 border border-slate-100 rounded-xl text-slate-700 font-medium">
                            {{ $student->name }}
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase ml-1">Apellidos</label>
                        <div class="px-4 py-3 bg-slate-50/50 border border-slate-100 rounded-xl text-slate-700 font-medium">
                            {{ $student->last_name }}
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase ml-1">N° Documento</label>
                        <div class="px-4 py-3 bg-slate-50/50 border border-slate-100 rounded-xl text-slate-700 font-medium">
                            {{ $student->number_documment }}
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-6">
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase ml-1">Grado</label>
                        <div class="px-4 py-3 bg-slate-50/50 border border-slate-100 rounded-xl text-slate-600 italic">
                            {{ $referral->course }}
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase ml-1">Grupo</label>
                        <div class="px-4 py-3 bg-slate-50/50 border border-slate-100 rounded-xl text-slate-600 font-medium">
                            {{ $student->group->group }}
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase ml-1">Edad</label>
                        <div class="px-4 py-3 bg-slate-50/50 border border-slate-100 rounded-xl text-slate-600">
                            {{ $student->age }} años
                        </div>
                    </div>
                </div>
            </section>

            {{-- SECCIÓN 2: DETALLES DE LA REMISIÓN --}}
            <section class="space-y-8">
                <div class="flex items-center gap-2 mb-2 text-slate-400">
                    <span class="text-lg">📄</span>
                    <h2 class="font-bold uppercase tracking-widest text-[11px]">Información de la Remisión</h2>
                    <div class="flex-grow h-px bg-slate-100 ml-2"></div>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Motivo</label>
                    <div class="px-6 py-5 bg-white border border-slate-200 rounded-2xl text-slate-700 leading-relaxed shadow-sm italic">
                        {{ old('reason_referral', $referral->reason) }}
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Observaciones</label>
                    <div class="px-6 py-5 bg-white border border-slate-200 rounded-2xl text-slate-700 leading-relaxed shadow-sm">
                        {{ old('observation', $referral->observation) }}
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Estrategias Aplicadas</label>
                    <div class="px-6 py-5 bg-white border border-slate-200 rounded-2xl text-slate-700 leading-relaxed shadow-sm">
                        {{ old('strategies', $referral->strategies) }}
                    </div>
                </div>
            </section>

            {{-- SECCIÓN 3: ANEXO --}}
            <section class="pt-6">
                <div class="max-w-sm">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">📎 Archivo Adjunto</label>
                    
                    @if(isset($report) && $report->annex_one)
                        <a href="{{ asset('storage/' . $report->annex_one) }}" target="_blank"
                           class="flex items-center gap-4 p-4 bg-indigo-50 border border-indigo-100 rounded-2xl text-indigo-700 hover:bg-white hover:border-indigo-300 transition-all group">
                            <div class="w-12 h-12 flex items-center justify-center bg-white rounded-xl shadow-sm">
                                <i class="bi bi-file-earmark-pdf text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-tight">Ver Anexo PDF</p>
                                <p class="text-[10px] font-medium opacity-70">Documento psicológico</p>
                            </div>
                        </a>
                    @else
                        <div class="flex items-center gap-4 p-4 bg-slate-50 border border-dashed border-slate-200 rounded-2xl text-slate-400">
                            <p class="text-[10px] font-bold uppercase tracking-widest mx-auto">No tiene anexo adjunto</p>
                        </div>
                    @endif
                </div>
            </section>
        </div>

        {{-- PIE DE PÁGINA INFORMATIVO --}}
        <div class="bg-slate-50 border-t border-slate-100 p-6 flex justify-center">
            <p class="text-[10px] text-slate-400 font-medium uppercase tracking-[0.2em]">PiarManager • Vista de solo lectura</p>
        </div>
    </div>
</div>
@endsection