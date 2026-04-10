@extends('layout.masterPage')

@section('title', 'Detalle del Informe')

@section('content')
<div class="container mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        
        {{-- ENCABEZADO --}}
        <div class="bg-slate-50 border-b border-slate-100 p-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <button onclick="history.back()" 
                    class="flex items-center justify-center w-10 h-10 rounded-full bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 transition-all shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <div>
                    <h1 class="text-xl font-black text-slate-800 tracking-tight">Informe de la Consulta</h1>
                    <p class="text-slate-500 text-xs font-medium uppercase tracking-wider">Detalles del reporte psicológico</p>
                </div>
            </div>
            
            {{-- Badge de Estado --}}
            <div class="px-4 py-1.5 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-700 text-xs font-bold uppercase tracking-widest">
                {{ $student->states->state }}
            </div>
        </div>

        <div class="p-8 lg:p-10 space-y-10">
            
            {{-- SECCIÓN: FICHA DEL ESTUDIANTE --}}
            <section>
                <div class="flex items-center gap-2 mb-6 text-slate-400">
                    <span class="text-lg">🆔</span>
                    <h2 class="font-bold uppercase tracking-widest text-[11px]">Identificación y Datos Académicos</h2>
                    <div class="flex-grow h-px bg-slate-100 ml-2"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase ml-1">Nombres</label>
                        <div class="px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-slate-700 font-medium">
                            {{ $student->name }}
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase ml-1">Apellidos</label>
                        <div class="px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-slate-700 font-medium">
                            {{ $student->last_name }}
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase ml-1">N° Documento</label>
                        <div class="px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-slate-700 font-medium">
                            {{ $student->number_documment }}
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-6">
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase ml-1">Grado</label>
                        <div class="px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-slate-600 italic">
                            {{ $student->degree->degree }}
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase ml-1">Grupo</label>
                        <div class="px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-slate-600 font-medium">
                            {{ $report->group_student }}
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase ml-1">Edad</label>
                        <div class="px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-slate-600">
                            {{ $report->age_student }} años
                        </div>
                    </div>
                </div>
            </section>

            {{-- SECCIÓN: CONTENIDO DEL INFORME --}}
            <section class="space-y-8">
                <div class="flex items-center gap-2 mb-2 text-slate-400">
                    <span class="text-lg">📝</span>
                    <h2 class="font-bold uppercase tracking-widest text-[11px]">Contenido del Reporte</h2>
                    <div class="flex-grow h-px bg-slate-100 ml-2"></div>
                </div>

                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase ml-1">Título del Informe</label>
                    <div class="px-5 py-4 bg-indigo-50/30 border border-indigo-100 rounded-2xl text-slate-800 font-bold text-lg">
                        {{ $report->title_report }}
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-8">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase ml-1">Motivo de la Consulta</label>
                        <div class="px-5 py-4 bg-white border border-slate-200 rounded-2xl text-slate-700 leading-relaxed shadow-sm italic">
                            {{ $report->reason_inquiry }}
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase ml-1">Recomendaciones Profesionales</label>
                        <div class="px-5 py-4 bg-white border border-slate-200 rounded-2xl text-slate-700 leading-relaxed shadow-sm">
                            {{ $report->recomendations }}
                        </div>
                    </div>
                </div>
            </section>

            {{-- SECCIÓN: ANEXO --}}
            <section class="pt-6 border-t border-slate-100">
                <div class="max-w-sm">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">📎 Archivo Adjunto</label>
                    
                    @if(!empty($report->annex_one))
                        <a href="{{ asset('storage/'.$report->annex_one) }}" target="_blank"
                           class="flex items-center gap-4 p-4 bg-slate-50 border border-slate-200 rounded-2xl text-indigo-600 hover:bg-white hover:border-indigo-300 hover:shadow-md transition-all group">
                            <div class="w-12 h-12 flex items-center justify-center bg-white rounded-xl shadow-sm group-hover:bg-indigo-50 transition-colors">
                                <span class="text-2xl">PDF</span>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800">Ver Anexo PDF</p>
                                <p class="text-[10px] text-slate-500 font-medium">Informe Psicológico Externo</p>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-auto text-slate-300 group-hover:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    @else
                        <div class="flex items-center gap-4 p-4 bg-slate-50/50 border border-dashed border-slate-200 rounded-2xl text-slate-400">
                            <div class="w-12 h-12 flex items-center justify-center opacity-50">
                                <span class="text-2xl">🚫</span>
                            </div>
                            <p class="text-[10px] font-bold uppercase tracking-tighter">No hay documentos adjuntos</p>
                        </div>
                    @endif
                </div>
            </section>
        </div>
    </div>
</div>
@endsection