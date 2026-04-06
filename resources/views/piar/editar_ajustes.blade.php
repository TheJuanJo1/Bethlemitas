@extends('layout.masterPage')

@section('title','PIAR - Editar Ajustes Razonables')

@section('content')
<style>
.container-piar {
    max-width: 1100px;
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
    margin: 0 0 1.5rem;
}

.box-section {
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 0;
    margin-bottom: 24px;
    background: #fff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    overflow: hidden;
}

.box-title {
    background: #fff7ed;
    color: #9a3412;
    font-weight: 600;
    font-size: 14px;
    padding: 12px 20px;
    border-bottom: 1px solid #fed7aa;
    display: flex;
    align-items: center;
    gap: 8px;
}
.box-title::before {
    content: '';
    display: inline-block;
    width: 4px;
    height: 16px;
    background: #f97316;
    border-radius: 2px;
}

.box-section > p,
.box-section > form,
.box-section-body {
    padding: 16px 20px;
}

.box-section > p {
    margin: 0;
    color: #6b7280;
    font-size: 14px;
    padding: 16px 20px;
}

.student-info {
    margin: 0;
    font-size: 14px;
    color: #374151;
    padding: 16px 20px;
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}
.student-info span {
    display: flex;
    align-items: center;
    gap: 6px;
}
.student-info b {
    color: #6b7280;
    font-weight: 500;
}

.form-control {
    border: 1px solid #d1d5db;
    border-radius: 8px;
    padding: 8px 10px;
    width: 100%;
    font-size: 13px;
    background: #f9fafb;
    color: #111827;
    transition: border-color 0.15s, background 0.15s;
    box-sizing: border-box;
}
.form-control:focus {
    outline: none;
    border-color: #f97316;
    background: #fff;
}

textarea.form-control {
    height: 70px;
    resize: none;
}

.btn-save {
    background: #f97316;
    color: #fff;
    font-weight: 600;
    font-size: 13px;
    padding: 9px 28px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.15s, transform 0.1s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.btn-save:hover {
    background: #ea6c0a;
}
.btn-save:active {
    transform: scale(0.98);
}

.btn-row {
    text-align: right;
    margin-top: 16px;
    padding-top: 14px;
    border-top: 1px solid #f3f4f6;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    border: 1px solid #e5e7eb;
    padding: 10px 12px;
    background: #fff;
    vertical-align: top;
    font-size: 13px;
    color: #374151;
}

th {
    background: #f9fafb;
    color: #6b7280;
    font-weight: 600;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
</style>

<button onclick="history.back()" class="piar-back-btn">
    <i class="bi bi-arrow-left"></i> Volver
</button>

<div class="container container-piar">
    <h3 class="piar-page-title">PIAR — Editar Ajustes Razonables</h3>

    <div class="box-section">
        <div class="box-title">Datos del Estudiante</div>
        <p class="student-info">
            <span><b>Nombre:</b> {{ $piar->student->name }} {{ $piar->student->last_name }}</span>
            <span><b>Grado:</b> {{ $piar->student->degree->degree ?? 'Sin grado' }}</span>
        </p>
    </div>

    {{-- ================= PERIODO 1 ================= --}}
    <div class="box-section">
        <div class="box-title">Periodo 1</div>

        @if($periodo1->isEmpty())
            <p>No hay ajustes registrados para este periodo.</p>
        @else
            <form action="{{ route('piar.psico.ajustes.store') }}" method="POST" style="padding:16px 20px;">
                @csrf
                <input type="hidden" name="piar_id" value="{{ $piar->id }}">
                <input type="hidden" name="period" value="1">

                @include('piar.partials.tabla_ajustes', ['datos' => $periodo1])

                <div class="btn-row">
                    <button type="submit" class="btn-save">
                        <i class="bi bi-check2"></i> Guardar Periodo 1
                    </button>
                </div>
            </form>
        @endif
    </div>

    {{-- ================= PERIODO 2 ================= --}}
    <div class="box-section">
        <div class="box-title">Periodo 2</div>

        @if($periodo2->isEmpty())
            <p>No hay ajustes registrados para este periodo.</p>
        @else
            <form action="{{ route('piar.psico.ajustes.store') }}" method="POST" style="padding:16px 20px;">
                @csrf
                <input type="hidden" name="piar_id" value="{{ $piar->id }}">
                <input type="hidden" name="period" value="2">

                @include('piar.partials.tabla_ajustes', ['datos' => $periodo2])

                <div class="btn-row">
                    <button type="submit" class="btn-save">
                        <i class="bi bi-check2"></i> Guardar Periodo 2
                    </button>
                </div>
            </form>
        @endif
    </div>

    {{-- ================= PERIODO 3 ================= --}}
    <div class="box-section">
        <div class="box-title">Periodo 3</div>

        @if($periodo3->isEmpty())
            <p>No hay ajustes registrados para este periodo.</p>
        @else
            <form action="{{ route('piar.psico.ajustes.store') }}" method="POST" style="padding:16px 20px;">
                @csrf
                <input type="hidden" name="piar_id" value="{{ $piar->id }}">
                <input type="hidden" name="period" value="3">

                @include('piar.partials.tabla_ajustes', ['datos' => $periodo3])

                <div class="btn-row">
                    <button type="submit" class="btn-save">
                        <i class="bi bi-check2"></i> Guardar Periodo 3
                    </button>
                </div>
            </form>
        @endif
    </div>

</div>

@endsection