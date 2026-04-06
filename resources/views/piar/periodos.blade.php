@extends('layout.masterPage')

@section('title','PIAR - Periodos')

@section('content')

<style>
.container-piar {
    max-width: 900px;
    margin: auto;
}

.piar-back-btn {
    background: none;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #6b7280;
    font-size: 14px;
    padding: 6px 10px;
    border-radius: 8px;
    margin-bottom: 8px;
    transition: background 0.15s, color 0.15s;
}
.piar-back-btn:hover {
    background: #f3f4f6;
    color: #111827;
}
.piar-back-btn i {
    font-size: 1.1rem;
}

.piar-page-title {
    font-size: 22px;
    font-weight: 600;
    color: #111827;
    margin: 0 0 1rem;
}

.student-info {
    font-size: 14px;
    color: #374151;
    margin: 0 0 1.25rem;
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
}
.student-info b {
    color: #6b7280;
    font-weight: 500;
}

/* --- Contador --- */
.contador-banner {
    margin: 0 0 1.25rem;
    padding: 10px 14px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
    border: 1px solid transparent;
}
.contador-gray   { background: #f3f4f6; color: #374151; border-color: #e5e7eb; }
.contador-green  { background: #dcfce7; color: #166534; border-color: #bbf7d0; }
.contador-yellow { background: #fef9c3; color: #854d0e; border-color: #fde68a; }
.contador-red    { background: #fee2e2; color: #991b1b; border-color: #fecaca; }

/* --- Tarjeta de periodo --- */
.period-card {
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 18px 20px;
    margin-bottom: 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    transition: box-shadow 0.15s;
}
.period-card:hover {
    box-shadow: 0 3px 8px rgba(0,0,0,0.09);
}

.period-left {
    display: flex;
    align-items: center;
    gap: 14px;
}

.period-number {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: #f3f4f6;
    color: #374151;
    font-weight: 600;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.period-title {
    font-weight: 600;
    font-size: 16px;
    color: #111827;
    margin-bottom: 4px;
}

.estado {
    font-size: 11px;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 999px;
    display: inline-block;
    letter-spacing: 0.03em;
    text-transform: uppercase;
}
.completo {
    background: #dcfce7;
    color: #166534;
}
.pendiente {
    background: #fee2e2;
    color: #991b1b;
}

/* --- Botones --- */
.period-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    align-items: center;
}

.btn-open, .btn-eval, .btn-edit {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 8px 16px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 13px;
    cursor: pointer;
    transition: opacity 0.15s, transform 0.1s;
}
.btn-open:hover, .btn-eval:hover, .btn-edit:hover {
    opacity: 0.88;
}
.btn-open:active, .btn-eval:active, .btn-edit:active {
    transform: scale(0.97);
}

.btn-open  { background: #2563eb; color: #fff; }
.btn-eval  { background: #f59e0b; color: #111827; }
.btn-edit  { background: #9333ea; color: #fff; }

.btn-disabled {
    opacity: 0.4;
    cursor: not-allowed;
    pointer-events: none;
}
</style>

<button onclick="window.location.href='/addMinutes'" class="piar-back-btn">
    <i class="bi bi-arrow-left"></i> Volver
</button>

<div class="container container-piar">

    <h3 class="piar-page-title">PIAR — Periodos Académicos</h3>

    {{-- CONTADOR 30 DÍAS --}}
    @if(is_null($diasRestantes))
        <div class="contador-banner contador-gray">
            ⏳ El periodo aún no ha iniciado
        </div>
    @else
        <div class="contador-banner
            @if($diasRestantes > 10) contador-green
            @elseif($diasRestantes > 0 || $horasRestantes > 0) contador-yellow
            @else contador-red
            @endif
        ">
            @if($diasRestantes > 1)
                ⏳ Quedan <b>{{ (int)$diasRestantes }}</b> días para editar el periodo
            @elseif($diasRestantes == 1)
                ⏳ Queda <b>1 día</b> para editar el periodo
            @elseif($diasRestantes == 0 && $horasRestantes > 0)
                ⏳ Quedan <b>{{ (int)$horasRestantes }}</b> horas para editar el periodo
            @elseif($diasRestantes == 0 && $horasRestantes <= 0)
                ⚠️ Hoy es el último momento
            @else
                ❌ El plazo ya venció
            @endif
        </div>
    @endif

    <p class="student-info">
        <span><b>Estudiante:</b> {{ $piar->student->name }} {{ $piar->student->last_name }}</span>
        <span><b>Grado:</b> {{ $piar->student->degree->degree ?? 'Sin grado' }}</span>
    </p>

    {{-- ================= PERIODO 1 ================= --}}
    <div class="period-card">
        <div class="period-left">
            <div class="period-number">1</div>
            <div>
                <div class="period-title">Periodo 1</div>
                @if($period1)
                    <span class="estado completo">Completado</span>
                @else
                    <span class="estado pendiente">Pendiente</span>
                @endif
            </div>
        </div>

        <div class="period-actions">
            @if($period1)
                <a href="{{ route('piar.pdf.periodo1',$piar->id) }}" target="_blank">
                    <button class="btn-open" style="background:#16a34a;"><i class="bi bi-file-earmark-pdf"></i> Ver PDF</button>
                </a>
                <a href="{{ route('piar.evaluacion', [$piar->id, 1]) }}">
                    <button class="btn-eval"><i class="bi bi-upload"></i> Subir Evaluación</button>
                </a>
                <a href="{{ route('piar.editar.periodo1',$piar->id) }}">
                    <button class="btn-edit"><i class="bi bi-pencil"></i> Editar</button>
                </a>
            @endif

            @if($ready)
                <a href="{{ route('piar.periodo1',$piar->id) }}">
                    @if($ready && (!isset($diasRestantes) || $diasRestantes >= 0))
                        <a href="{{ route('piar.periodo1',$piar->id) }}">
                            <button class="btn-open">Abrir</button>
                        </a>
                    @else
                        <button class="btn-open btn-disabled">Bloqueado</button>
                    @endif
                </a>
            @else
                <button class="btn-open btn-disabled">Abrir</button>
            @endif
        </div>
    </div>

    {{-- ================= PERIODO 2 ================= --}}
    <div class="period-card">
        <div class="period-left">
            <div class="period-number">2</div>
            <div>
                <div class="period-title">Periodo 2</div>
                @if($period2)
                    <span class="estado completo">Completado</span>
                @else
                    <span class="estado pendiente">Pendiente</span>
                @endif
            </div>
        </div>

        <div class="period-actions">
            @if($period2)
                <a href="{{ route('piar.pdf.periodo2',$piar->id) }}" target="_blank">
                    <button class="btn-open" style="background:#16a34a;"><i class="bi bi-file-earmark-pdf"></i> Ver PDF</button>
                </a>
                <a href="{{ route('piar.evaluacion', [$piar->id, 2]) }}">
                    <button class="btn-eval"><i class="bi bi-upload"></i> Subir Evaluación</button>
                </a>
                <a href="{{ route('piar.editar.periodo2',$piar->id) }}">
                    <button class="btn-edit"><i class="bi bi-pencil"></i> Editar</button>
                </a>
            @endif

            @if($ready)
                <a href="{{ route('piar.periodo2',$piar->id) }}">
                    @if($ready && (!isset($diasRestantes) || $diasRestantes >= 0))
                        <a href="{{ route('piar.periodo2',$piar->id) }}">
                            <button class="btn-open">Abrir</button>
                        </a>
                    @else
                        <button class="btn-open btn-disabled">Bloqueado</button>
                    @endif
                </a>
            @else
                <button class="btn-open btn-disabled">Abrir</button>
            @endif
        </div>
    </div>

    {{-- ================= PERIODO 3 ================= --}}
    <div class="period-card">
        <div class="period-left">
            <div class="period-number">3</div>
            <div>
                <div class="period-title">Periodo 3</div>
                @if($period3)
                    <span class="estado completo">Completado</span>
                @else
                    <span class="estado pendiente">Pendiente</span>
                @endif
            </div>
        </div>

        <div class="period-actions">
            @if($period3)
                <a href="{{ route('piar.pdf.periodo3',$piar->id) }}" target="_blank">
                    <button class="btn-open" style="background:#16a34a;"><i class="bi bi-file-earmark-pdf"></i> Ver PDF</button>
                </a>
                <a href="{{ route('piar.evaluacion', [$piar->id, 3]) }}">
                    <button class="btn-eval"><i class="bi bi-upload"></i> Subir Evaluación</button>
                </a>
                <a href="{{ route('piar.editar.periodo3',$piar->id) }}">
                    <button class="btn-edit"><i class="bi bi-pencil"></i> Editar</button>
                </a>
            @endif

            @if($ready)
                <a href="{{ route('piar.periodo3',$piar->id) }}">
                    @if($ready && (!isset($diasRestantes) || $diasRestantes >= 0))
                        <a href="{{ route('piar.periodo3',$piar->id) }}">
                            <button class="btn-open">Abrir</button>
                        </a>
                    @else
                        <button class="btn-open btn-disabled">Bloqueado</button>
                    @endif
                </a>
            @else
                <button class="btn-open btn-disabled">Abrir</button>
            @endif
        </div>
    </div>

</div>

@endsection