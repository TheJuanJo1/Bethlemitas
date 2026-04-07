@extends('layout.masterPage')

@section('title','PIAR - Editar Periodo 2')

@section('content')

<style>
    :root {
        --brand-blue: #2563eb;
        --brand-blue-dark: #1e40af;
        --success: #16a34a;
        --border-gray: #e2e8f0;
        --text-main: #1e293b;
    }

    .container-piar {
        max-width: 1100px;
        margin: 2rem auto;
        padding: 0 1.5rem;
        font-family: 'Inter', sans-serif;
    }

    /* Botón Volver */
    .btn-back {
        display: inline-flex;
        align-items: center;
        color: #64748b;
        text-decoration: none;
        margin-bottom: 1.5rem;
        transition: all 0.2s;
        font-weight: 500;
    }
    .btn-back:hover { color: var(--brand-blue); transform: translateX(-5px); }

    /* Tarjeta Principal de Ajuste */
    .adjustment-editor-card {
        background: white;
        border-radius: 16px;
        border: 1px solid var(--border-gray);
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);
        margin-bottom: 3rem;
        overflow: hidden;
    }

    .card-header {
        background: linear-gradient(to right, #eff6ff, #ffffff);
        padding: 1.25rem 2rem;
        border-bottom: 1px solid #dbeafe;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .area-label {
        font-size: 0.75rem;
        font-weight: 800;
        color: var(--brand-blue);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .area-name {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f172a;
        display: block;
    }

    /* Grid de Formulario */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        padding: 2rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-group.full-width {
        grid-column: span 2;
    }

    .form-group label {
        font-size: 0.8rem;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
    }

    /* Estilo de Inputs */
    .form-control {
        border: 1.5px solid var(--border-gray);
        border-radius: 10px;
        padding: 0.8rem 1rem;
        font-size: 0.95rem;
        color: var(--text-main);
        transition: all 0.2s;
        background: #fcfcfc;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--brand-blue);
        background: white;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }

    textarea.form-control {
        min-height: 100px;
        resize: vertical;
    }

    /* Campo de Solo Lectura */
    .form-control[readonly] {
        background: #f1f5f9;
        color: #64748b;
        cursor: not-allowed;
        border-style: dashed;
    }

    .readonly-badge {
        font-size: 0.65rem;
        background: #e2e8f0;
        padding: 2px 6px;
        border-radius: 4px;
        margin-left: 8px;
    }

    /* Botón Guardar */
    .btn-save-container {
        position: sticky;
        bottom: 2rem;
        text-align: right;
        margin-top: 2rem;
        z-index: 50;
    }

    .btn-save {
        background: var(--success);
        color: white;
        font-weight: 700;
        padding: 1rem 3rem;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 10px 15px -3px rgba(22, 163, 74, 0.3);
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
    }

    .btn-save:hover {
        background: #15803d;
        transform: translateY(-2px);
        box-shadow: 0 20px 25px -5px rgba(22, 163, 74, 0.4);
    }
</style>

<div class="container-piar">
    
    <a href="javascript:history.back()" class="btn-back">
        <i class="bi bi-arrow-left-circle-fill" style="font-size: 1.5rem; margin-right: 0.5rem;"></i>
        <span>Volver sin guardar</span>
    </a>

    <div class="flex items-center justify-between mb-8">
        <h2 style="font-weight: 800; color: #0f172a; font-size: 1.8rem;">
            Editar Ajustes <span style="color: var(--brand-blue)">Periodo 2</span>
        </h2>
    </div>

    <form action="{{ route('piar.update.periodo2') }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="piar_id" value="{{ $piar_id }}">

        @if($adjustments->isEmpty())
            <div class="adjustment-editor-card" style="padding: 4rem; text-align: center;">
                <i class="bi bi-clipboard-x" style="font-size: 3rem; color: #cbd5e1;"></i>
                <p style="color: #94a3b8; margin-top: 1rem;">No hay registros para editar en este periodo.</p>
            </div>
        @else
            @foreach($adjustments as $adj)
            <input type="hidden" name="adjustment_id[]" value="{{ $adj->id }}">
            
            <div class="adjustment-editor-card">
                <div class="card-header">
                    <div>
                        <span class="area-label">Módulo de Área</span>
                        <span class="area-name">{{ $adj->area }}</span>
                    </div>
                    <div style="text-align: right">
                        <i class="bi bi-pencil-square" style="color: #bfdbfe; font-size: 1.5rem;"></i>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Objetivo de Aprendizaje</label>
                        <textarea name="objetivo[]" class="form-control" placeholder="Define el objetivo...">{{ $adj->objetivo }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Barreras Identificadas</label>
                        <textarea name="barrera[]" class="form-control" placeholder="Describe las barreras...">{{ $adj->barrera }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Ajuste Curricular</label>
                        <textarea name="ajuste_curricular[]" class="form-control">{{ $adj->ajuste_curricular }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Ajuste Metodológico</label>
                        <textarea name="ajuste_metodologico[]" class="form-control">{{ $adj->ajuste_metodologico }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Ajuste Evaluativo</label>
                        <textarea name="ajuste_evaluativo[]" class="form-control">{{ $adj->ajuste_evaluativo }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Convivencia</label>
                        <textarea name="convivencia[]" class="form-control">{{ $adj->convivencia }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Socialización</label>
                        <textarea name="socializacion[]" class="form-control">{{ $adj->socializacion }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Participación</label>
                        <textarea name="participacion[]" class="form-control">{{ $adj->participacion }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Autonomía</label>
                        <textarea name="autonomia[]" class="form-control">{{ $adj->autonomia }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Autocontrol</label>
                        <textarea name="autocontrol[]" class="form-control">{{ $adj->autocontrol }}</textarea>
                    </div>

                    <div class="form-group full-width">
                        <label>Evaluación  <span class="readonly-badge">SÓLO LECTURA</span></label>
                        <textarea class="form-control" readonly placeholder="No hay evaluación registrada aún.">{{ $adj->evaluacion }}</textarea>
                    </div>
                </div>
            </div>
            @endforeach
        @endif

        <div class="btn-save-container">
            <button type="submit" class="btn-save">
                <i class="bi bi-cloud-check-fill"></i>
                GUARDAR TODOS LOS CAMBIOS
            </button>
        </div>
    </form>
</div>

@endsection