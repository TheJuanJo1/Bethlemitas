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
        margin: 1rem auto;
        padding: 0 0.75rem;
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
        padding: 1rem;
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 1.5rem;
        border: 1px solid transparent;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    @media (min-width: 640px) {
        .contador-card {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.5rem;
        }
    }

    .contador-content { display: flex; align-items: center; gap: 12px; }
    .contador-icon { font-size: 1.5rem; }
    .contador-text { font-size: 13px; font-weight: 500; }
    .contador-text b { font-size: 15px; }

    .status-green { background: #f0fdf4; color: #166534; border-color: #bbf7d0; }
    .status-yellow { background: #fffbeb; color: #92400e; border-color: #fef3c7; }
    .status-red { background: #fef2f2; color: #991b1b; border-color: #fee2e2; }
    .status-gray { background: #f8fafc; color: #475569; border-color: #e2e8f0; }

    /* Info Estudiante */
    .student-header {
        background: #1e293b;
        color: white;
        padding: 1.25rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    @media (min-width: 640px) {
        .student-header {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
        }
    }

    /* Tarjetas de Periodo */
    .period-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 1rem;
        margin-bottom: 1rem;
        display: flex;
        flex-direction: column;
        gap: 15px;
        transition: all 0.3s ease;
    }

    @media (min-width: 768px) {
        .period-card {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem;
        }
    }

    .period-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.08);
    }

    .period-info { display: flex; align-items: center; gap: 1rem; }
    .period-num {
        width: 40px;
        height: 40px;
        flex-shrink: 0;
        background: var(--gray-bg);
        color: #475569;
        font-weight: 800;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    .badge-status {
        font-size: 9px;
        font-weight: 800;
        text-transform: uppercase;
        padding: 2px 8px;
        border-radius: 20px;
        letter-spacing: 0.5px;
    }
    .bg-complete { background: #dcfce7; color: #15803d; }
    .bg-pending { background: #f1f5f9; color: #64748b; }

    /* Botones de Acción */
    .action-group { 
        display: flex; 
        flex-wrap: wrap;
        gap: 6px; 
    }

    @media (max-width: 767px) {
        .action-group {
            justify-content: flex-start;
        }
    }

    .btn-action {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
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

    @php
        $user = auth()->user();
        $isPsico = $user->hasRole('psicoorientador') || $user->hasRole('Psicoorientador');
        $backRoute = $isPsico ? route('psico.students.active') : '/addMinutes';
    @endphp

    <a href="{{ $backRoute }}" class="piar-back-btn">
        <i class="bi bi-chevron-left"></i> <span class="hidden sm:inline">Volver al listado</span><span class="sm:hidden">Volver</span>
    </a>

    <div class="student-header">
        <div>
            <div style="font-size: 11px; opacity: 0.8; text-transform: uppercase; font-weight: 700;">Expediente Estudiantil</div>
            <div style="font-size: 1.25rem; font-weight: 800;">{{ $piar->student->name }} {{ $piar->student->last_name }}</div>
            
            @if($piar->characteristics)
                <a href="{{ route('piar.pdf', $piar->id) }}" target="_blank" 
                   class="inline-flex items-center mt-2 px-3 py-1 bg-emerald-500 hover:bg-emerald-600 text-white text-[10px] font-bold rounded-lg transition-colors shadow-sm">
                    <i class="bi bi-file-earmark-pdf-fill mr-1"></i> ACTA COMPLETA
                </a>
            @endif
        </div>
        <div class="sm:text-right">
            <div style="font-size: 11px; opacity: 0.8; text-transform: uppercase; font-weight: 700;">Grado</div>
            <div style="font-size: 1.1rem; font-weight: 700;">{{ $piar->student->degree->degree ?? 'N/A' }}</div>
        </div>
    </div>

    {{-- CONTADOR DE TIEMPO --}}
    @if(is_null($diasRestantes))
        <div class="contador-card status-gray">
            <div class="contador-content">
                <span class="contador-icon">⏳</span>
                <span class="contador-text">
                    @php
                        $activePeriod = null;
                        for($i=1;$i<=3;$i++) {
                            if($periodData[$i]['isOpenedByDate'] && !$periodData[$i]['isLocked'] && !$periodData[$i]['isCompleted']) {
                                $activePeriod = $i;
                                break;
                            }
                        }
                    @endphp
                    @if($activePeriod)
                        El Periodo {{ $activePeriod }} está habilitado.
                    @else
                        No hay periodos activos para edición actualmente.
                    @endif
                </span>
            </div>
        </div>
    @else
        <div class="contador-card 
            @if($diasRestantes > 5) status-green 
            @elseif($minutosRestantes > 0) status-yellow 
            @else status-red @endif">
            
            <div class="contador-content">
                <span class="contador-icon">
                    @if($diasRestantes > 5) <i class="bi bi-clock-history"></i>
                    @elseif($minutosRestantes > 0) <i class="bi bi-exclamation-triangle-fill"></i>
                    @else <i class="bi bi-x-circle-fill"></i> @endif
                </span>
                <div class="contador-text">
                    @if($diasRestantes > 1)
                        Tiempo restante: <b>{{ (int)$diasRestantes }} días</b>
                    @elseif($diasRestantes == 1)
                        ¡Atención! Queda <b>1 día</b> y algunas horas.
                    @elseif($horasRestantes > 0)
                        ¡Cierre inminente! Quedan <b>{{ (int)$horasRestantes }} horas</b>.
                    @elseif($minutosRestantes > 0)
                        ¡Últimos minutos! Quedan <b>{{ (int)$minutosRestantes }} min</b>.
                    @else
                        <b>Plazo finalizado.</b> El sistema está cerrado.
                    @endif
                </div>
            </div>
            
            @if($minutosRestantes > 0)
                <div class="badge-status" style="background: rgba(0,0,0,0.05); text-align: center;">SISTEMA ABIERTO</div>
            @else
                <div class="badge-status" style="background: rgba(0,0,0,0.05); text-align: center;">SISTEMA CERRADO</div>
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
        
        $canFillOrEdit = $ready && ($isPsico || (!$status['isLocked'] && $status['isOpenedByDate'] && $status['prevPeriodComplete']));
        
        $lockMessage = "";
        if (!$ready) $lockMessage = "La caracterización no está lista.";
        elseif (!$isPsico) {
            if ($status['isLocked']) $lockMessage = "Cerró el: " . ($status['closingDate'] ? $status['closingDate']->format('d/m/Y H:i') : 'Pendiente');
            elseif (!$status['isOpenedByDate']) $lockMessage = "Abre el: " . ($status['openingDate'] ? $status['openingDate']->format('d/m/Y H:i') : 'Pendiente');
            elseif (!$status['prevPeriodComplete']) $lockMessage = "Debe completar el periodo anterior.";
        }
    @endphp
    <div class="period-card">
        <div class="period-info">
            <div class="period-num">{{ $p['id'] }}</div>
            <div>
                <div style="font-weight: 800; color: #1e293b; font-size: 14px;">Periodo Académico {{ $p['id'] }}</div>
                <div class="flex flex-wrap gap-1 mt-1">
                    @if($status['isCompleted'])
                        <span class="badge-status bg-complete">Completado</span>
                    @elseif($p['data'])
                        <span class="badge-status" style="background: #e0f2fe; color: #0369a1;">En proceso</span>
                    @else
                        <span class="badge-status bg-pending">Sin registros</span>
                    @endif

                    @if($status['isLocked'])
                        <span class="badge-status" style="background: #fee2e2; color: #b91c1c;">Cerrado</span>
                    @endif

                    @if(!$status['isOpenedByDate'])
                        <span class="badge-status" style="background: #fff7ed; color: #9a3412;">Próximamente</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="action-group">
            @if($canFillOrEdit)
                <a href="{{ route('piar.' . $p['slug'], $piar->id) }}" class="btn-action btn-primary">
                    <i class="bi bi-plus-circle-fill"></i> Llenar
                </a>
            @else
                <button class="btn-action btn-disabled" disabled title="{{ $lockMessage }}">
                    <i class="bi bi-lock-fill"></i> Llenar
                </button>
            @endif

            @if($p['data'])
                @if($canFillOrEdit)
                    <a href="{{ route('piar.editar.'.$p['slug'], $piar->id) }}" class="btn-action btn-edit">
                        <i class="bi bi-pencil-fill"></i> Editar
                    </a>
                    <a href="{{ route('piar.evaluacion', [$piar->id, $p['id']]) }}" class="btn-action btn-eval">
                        <i class="bi bi-journal-check"></i> Evaluar
                    </a>
                @else
                    <button class="btn-action btn-disabled" disabled title="{{ $lockMessage }}">
                        <i class="bi bi-pencil-fill"></i> Editar
                    </button>
                @endif
            @endif

            @if($isPsico)
                <button type="button" class="btn-action" style="background: #fff7ed; color: #ea580c; border: 1px solid #fdba74;" 
                        onclick="openDateModal({{ $p['id'] }}, 
                        '{{ $status['openingDate'] ? $status['openingDate']->format('Y-m-d\TH:i') : '' }}',
                        '{{ $status['closingDate'] ? $status['closingDate']->format('Y-m-d\TH:i') : '' }}')">
                    <i class="bi bi-calendar-event-fill"></i> <span class="hidden sm:inline">Habilitar</span> Fecha
                </button>
            @endif

            @if($p['data'])
                <a href="{{ route('piar.pdf.'.$p['slug'], $piar->id) }}" target="_blank" class="btn-action btn-pdf">
                    <i class="bi bi-file-earmark-pdf-fill"></i> PDF
                </a>
            @endif
        </div>
    </div>
    @endforeach

</div>

<!-- Modal para Habilitar Fecha -->
<div id="dateModal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
    <div style="background:white; padding:1.5rem; border-radius:12px; max-width:400px; width:95%; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1);">
        <h3 style="margin-top:0; font-weight:800; color:#1e293b; font-size: 1.1rem;">Habilitar Periodo</h3>
        <p style="font-size:12px; color:#64748b; margin-bottom:1.25rem;">Defina las fechas de apertura y cierre.</p>
        
        <form action="{{ route('piar.period.updateDate') }}" method="POST">
            @csrf
            <input type="hidden" name="period_id" id="modal_period_id">
            
            <div style="margin-bottom:1rem;">
                <label style="display:block; font-size:10px; font-weight:700; text-transform:uppercase; color:#475569; margin-bottom:4px;">Apertura</label>
                <input type="datetime-local" name="opening_date" id="modal_opening_date" required 
                       style="width:100%; padding:8px; border:1px solid #e2e8f0; border-radius:8px; font-family:inherit; font-size: 14px;">
            </div>

            <div style="margin-bottom:1.25rem;">
                <label style="display:block; font-size:10px; font-weight:700; text-transform:uppercase; color:#475569; margin-bottom:4px;">Cierre</label>
                <input type="datetime-local" name="closing_date" id="modal_closing_date" required 
                       style="width:100%; padding:8px; border:1px solid #e2e8f0; border-radius:8px; font-family:inherit; font-size: 14px;">
            </div>
            
            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <button type="button" onclick="closeDateModal()" style="padding:8px 14px; border-radius:8px; border:1px solid #e2e8f0; background:white; color:#64748b; font-weight:600; cursor:pointer; font-size: 13px;">Cerrar</button>
                <button type="submit" style="padding:8px 14px; border-radius:8px; border:none; background:#2563eb; color:white; font-weight:600; cursor:pointer; font-size: 13px;">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openDateModal(periodId, openingDate, closingDate) {
        document.getElementById('modal_period_id').value = periodId;
        if(openingDate) {
            document.getElementById('modal_opening_date').value = openingDate;
        }
        if(closingDate) {
            document.getElementById('modal_closing_date').value = closingDate;
        }
        document.getElementById('dateModal').style.display = 'flex';
    }

    function closeDateModal() {
        document.getElementById('dateModal').style.display = 'none';
    }

    window.onclick = function(event) {
        let modal = document.getElementById('dateModal');
        if (event.target == modal) {
            closeDateModal();
        }
    }
</script>

@endsection
