@extends('layout.masterPage')

@section('title', 'Historial de Periodos PIAR')

@section('content')

<style>
    .timeline-container { position: relative; border-left: 4px solid #e2e8f0; margin-left: 20px; padding-left: 30px; }
    .year-marker { 
        position: absolute; left: -14px; top: 0; 
        width: 24px; height: 24px; background: #3b82f6; 
        border-radius: 50%; border: 4px solid white; 
    }
    .card-period { transition: all 0.3s ease; border-left: 4px solid #cbd5e1; }
    .card-period:hover { border-left-color: #3b82f6; transform: translateX(5px); }
</style>

<div class="flex justify-center p-4">
    <div class="w-full max-w-5xl">
        
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-slate-200">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-800">
                        Historial de Ajustes <span class="text-blue-600">PIAR</span>
                    </h1>
                    <p class="text-slate-500">Estudiante: <strong>{{ $student->name }} {{ $student->last_name }}</strong></p>
                </div>
                <a href="{{ url()->previous() }}" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-lg font-bold text-sm hover:bg-slate-200 transition">
                   <i class="bi bi-arrow-left mr-1"></i> Volver
                </a>
            </div>
        </div>

        @forelse($history as $year => $periods)
            <div class="mb-12 relative">
                <div class="timeline-container">
                    <div class="year-marker shadow-sm"></div>
                    <h2 class="text-xl font-black text-slate-700 mb-6 bg-slate-100 inline-block px-4 py-1 rounded-full">
                        Año Lectivo {{ $year }}
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($periods as $adjustment)
                            <div class="bg-white p-5 rounded-lg shadow-sm border border-slate-200 card-period">
                                <div class="flex justify-between items-start mb-3">
                                    <span class="text-xs font-bold uppercase tracking-wider text-blue-600">
                                        Periodo {{ $adjustment->period }}
                                    </span>
                                    <span class="text-[10px] text-slate-400">
                                        {{ $adjustment->created_at->format('d/m/Y') }}
                                    </span>
                                </div>
                                
                                <h4 class="font-bold text-slate-800 mb-2 line-clamp-1">
                                    {{ $adjustment->area ?? 'Sin área definida' }}
                                </h4>
                                
                                <p class="text-xs text-slate-500 mb-4 line-clamp-3">
                                    <strong>Objetivo:</strong> {{ Str::limit($adjustment->objetivo ?? 'No registrado.', 100) }}
                                </p>

                                <div class="flex gap-2">
                                <a href="{{ route('piar.pdf.period', ['piar' => $student->piar->id, 'adjustment' => $adjustment->id]) }}" 
                                target="_blank"
                                class="flex-1 text-center py-2 bg-emerald-50 text-emerald-700 text-xs font-bold rounded border border-emerald-100 hover:bg-emerald-100 transition">
                                    <i class="bi bi-file-earmark-pdf"></i> PDF
                                </a>
                                    <a href="{{ route('piar.psico.ajustes.edit', $student->piar->id) }}?year={{ $year }}&period={{ $adjustment->period }}" 
                                       class="px-3 py-2 bg-slate-50 text-slate-600 text-xs font-bold rounded border border-slate-200 hover:bg-slate-100 transition">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach

                        {{-- Placeholder para periodos faltantes en el año actual --}}
                        @if($periods->count() < 3 && $year == date('Y'))
                            <div class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-lg flex items-center justify-center p-6 italic text-slate-400 text-sm">
                                Pendiente por registrar...
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl p-12 text-center border border-slate-200 shadow-sm">
                <i class="bi bi-archive text-5xl text-slate-200 mb-4"></i>
                <h3 class="text-lg font-bold text-slate-700">No hay registros históricos</h3>
                <p class="text-slate-500 text-sm">Este estudiante aún no cuenta con ajustes registrados en el sistema.</p>
            </div>
        @endforelse

    </div>
</div>

@endsection