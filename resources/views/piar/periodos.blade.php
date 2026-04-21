@extends('layout.masterPage')

@section('title','PIAR - Periodos')

@section('content')

<style>
    :root {
        --primary: #2563eb;
        --success: #16a34a;
        --warning: #f59e0b;
        --danger: #dc2626;
        --gray-bg: #f8fafc;
        --border-color: #e2e8f0;
    }

    .container-piar {
        max-width: 900px;
        margin: 2rem auto;
        padding: 0 1rem;
        font-family: 'Inter', sans-serif;
    }

    /* Botón Volver */
    .piar-back-btn {
        background: white;
        border: 1px solid var(--border-color);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #64748b;
        font-size: 13px;
        font-weight: 600;
        padding: 8px 16px;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        transition: all 0.2s;
        text-decoration: none;
    }
    .piar-back-btn:hover {
        background: #f1f5f9;
        color: var(--primary);
        transform: translateX(-4px);
    }

    /* Banner del Contador Mejorado */
    .contador-card {
        border-radius: 12px;
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
        border: 1px solid transparent;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .contador-content { display: flex; align-items: center; gap: 12px; }
    .contador-icon { font-size: 1.5rem; }
    .contador-text { font-size: 14px; font-weight: 500; }
    .contador-text b { font-size: 16px; }

    .status-green { background: #f0fdf4; color: #166534; border-color: #bbf7d0; }
    .status-yellow { background: #fffbeb; color: #92400e; border-color: #fef3c7; }
    .status-red { background: #fef2f2; color: #991b1b; border-color: #fee2e2; }
    .status-gray { background: #f8fafc; color: #475569; border-color: #e2e8f0; }

    /* Info Estudiante */
    .student-header {
        background: #1e293b;
        color: white;
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Tarjetas de Periodo */
    .period-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s ease;
    }
    .period-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.08);
    }

    .period-info { display: flex; align-items: center; gap: 1rem; }
    .period-num {
        width: 45px;
        height: 45px;
        background: var(--gray-bg);
        color: #475569;
        font-weight: 800;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .badge-status {
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        padding: 2px 10px;
        border-radius: 20px;
        letter-spacing: 0.5px;
    }
    .bg-complete { background: #dcfce7; color: #15803d; }
    .bg-pending { background: #f1f5f9; color: #64748b; }

    /* Botones de Acción */
    .action-group { display: flex; gap: 8px; }
    .btn-action {
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-pdf { background: #f1f5f9; color: #ef4444; border: 1px solid #fee2e2; }
    .btn-eval { background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; }
    .btn-edit { background: #eef2ff; color: #4338ca; border: 1px solid #e0e7ff; }
    .btn-primary { background: var(--primary); color: white; }

    .btn-action:hover:not(:disabled) { transform: translateY(-1px); filter: brightness(0.95); }
    .btn-disabled { opacity: 0.5; cursor: not-allowed; background: #f1f5f9; color: #94a3b8; border: none; }

</style>

<div class="container-piar">

    <a href="/addMinutes" class="piar-back-btn">
        <i class="bi bi-chevron-left"></i> Volver al listado
    </a>

    <div class="student-header">
        <div>
            <div style="font-size: 12px; opacity: 0.8; text-transform: uppercase; font-weight: 700;">Expediente Estudiantil</div>
            <div style="font-size: 1.4rem; font-weight: 800;">{{ $piar->student->name }} {{ $piar->student->last_name }}</div>
        </div>
        <div style="text-align: right;">
            <div style="font-size: 12px; opacity: 0.8; text-transform: uppercase; font-weight: 700;">Grado</div>
            <div style="font-size: 1.1rem; font-weight: 700;">{{ $piar->student->degree->degree ?? 'N/A' }}</div>
        </div>
    </div>

    {{-- CONTADOR DE DÍAS --}}
    @if(is_null($diasRestantes))
        <div class="contador-card status-gray">
            <div class="contador-content">
                <span class="contador-icon">⏳</span>
                <span class="contador-text">El sistema de edición aún no ha sido habilitado para este periodo.</span>
            </div>
        </div>
    @else
        <div class="contador-card 
            @if($diasRestantes > 10) status-green 
            @elseif($diasRestantes > 0 || $horasRestantes > 0) status-yellow 
            @else status-red @endif">
            
            <div class="contador-content">
                <span class="contador-icon">
                    @if($diasRestantes > 10) <i class="bi bi-clock-history"></i>
                    @elseif($diasRestantes > 0) <i class="bi bi-exclamation-triangle-fill"></i>
                    @else <i class="bi bi-x-circle-fill"></i> @endif
                </span>
                <div class="contador-text">
                    @if($diasRestantes > 1)
                        Tiempo restante para edición: <b>{{ (int)$diasRestantes }} días</b>
                    @elseif($diasRestantes == 1)
                        ¡Atención! Solo queda <b>1 día</b> para realizar cambios.
                    @elseif($diasRestantes == 0 && $horasRestantes > 0)
                        Cierre inminente: Quedan <b>{{ (int)$horasRestantes }} horas</b>.
                    @elseif($diasRestantes == 0 && $horasRestantes <= 0)
                        <b>⚠️ Últimas horas de acceso.</b> El sistema cerrará a medianoche.
                    @else
                        <b>Acceso cerrado.</b> El plazo de edición ha finalizado.
                    @endif
                </div>
            </div>
            
            @if($diasRestantes >= 0)
            <div class="badge-status" style="background: rgba(0,0,0,0.05)">SISTEMA ABIERTO</div>
            @endif
        </div>
    @endif

    {{-- LISTADO DE PERIODOS --}}
    @php
        $periodos = [
            ['id' => 1, 'data' => $period1, 'slug' => 'periodo1'],
            ['id' => 2, 'data' => $period2, 'slug' => 'periodo2'],
            ['id' => 3, 'data' => $period3, 'slug' => 'periodo3'],
        ];
    @endphp

    @foreach($periodos as $p)
    @php 
        $status = $periodData[$p['id']]; 
    @endphp
    <div class="period-card">
        <div class="period-info">
            <div class="period-num">{{ $p['id'] }}</div>
            <div>
                <div style="font-weight: 800; color: #1e293b;">Periodo Académico {{ $p['id'] }}</div>
                @if($p['data'])
                    <span class="badge-status bg-complete">Completado</span>
                @else
                    <span class="badge-status bg-pending">Sin registros</span>
                @endif

                @if($status['isLocked'])
                    <span class="badge-status" style="background: #fee2e2; color: #b91c1c;">Cerrado</span>
                @endif
            </div>
        </div>

        <div class="action-group">
            @if($p['data'])
                <a href="{{ route('piar.pdf.'.$p['slug'], $piar->id) }}" target="_blank" class="btn-action btn-pdf">
                    <i class="bi bi-file-earmark-pdf-fill"></i> PDF
                </a>
                
                @if(!$status['isLocked'])
                    <a href="{{ route('piar.evaluacion', [$piar->id, $p['id']]) }}" class="btn-action btn-eval">
                        <i class="bi bi-journal-check"></i> Evaluar
                    </a>
                    <a href="{{ route('piar.editar.'.$p['slug'], $piar->id) }}" class="btn-action btn-edit">
                        <i class="bi bi-pencil-fill"></i> Editar
                    </a>
                @endif
            @endif

            @if($ready && !$status['isLocked'])
                <a href="{{ route('piar.'.$p['slug'], $piar->id) }}" class="btn-action btn-primary">
                    {{ $p['data'] ? 'Añadir más' : 'Abrir Periodo' }}
                </a>
            @else
                <button class="btn-action btn-disabled" disabled title="El plazo de edición ha finalizado">
                    <i class="bi bi-lock-fill"></i> Bloqueado
                </button>
            @endif
        </div>
    </div>
    @endforeach

</div>

@endsection