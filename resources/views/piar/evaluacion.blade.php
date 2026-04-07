@extends('layout.masterPage')

@section('title','PIAR - Evaluación')

@section('content')

<style>
    :root {
        --primary: #f59e0b;
        --primary-dark: #d97706;
        --bg-light: #f8fafc;
        --border-color: #e2e8f0;
    }

    .container-piar {
        max-width: 1100px;
        margin: 2rem auto;
        padding: 0 1rem;
        font-family: 'Inter', sans-serif;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        color: #64748b;
        transition: all 0.2s;
        text-decoration: none;
        margin-bottom: 1rem;
    }
    .btn-back:hover { color: var(--primary-dark); transform: translateX(-4px); }

    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .info-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid var(--border-color);
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
        display: flex;
        gap: 2rem;
        align-items: center;
    }

    .info-item b { color: #64748b; font-size: 0.85rem; text-transform: uppercase; display: block; }
    .info-item span { font-size: 1.1rem; font-weight: 600; color: #1e293b; }

    .adjustment-card {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        margin-bottom: 2.5rem;
        overflow: hidden;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);
    }

    .card-header {
        background: #fffbeb;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #fef3c7;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .area-badge {
        background: var(--primary);
        color: #111827;
        padding: 0.25rem 1rem;
        border-radius: 999px;
        font-weight: 800;
        font-size: 0.9rem;
    }

    /* Grid ajustado para mejor separación */
    .grid-details {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.2rem;
        padding: 1.5rem;
        background: #ffffff;
    }

    .detail-box {
        padding: 0.75rem;
        border-radius: 8px;
        background: #fdfdfd;
        border: 1px solid #f1f5f9;
    }

    .detail-box label {
        display: block;
        font-size: 0.7rem;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        margin-bottom: 0.4rem;
        letter-spacing: 0.025em;
    }

    .detail-box p {
        font-size: 0.9rem;
        color: #334155;
        line-height: 1.5;
        margin: 0;
    }

    /* Destacado para el área de evaluación */
    .evaluation-container {
        padding: 1.5rem;
        background: #f8fafc;
        border-top: 2px solid var(--primary);
    }

    .evaluation-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.75rem;
    }

    .form-control {
        width: 100%;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        padding: 1rem;
        font-size: 0.95rem;
        transition: all 0.2s;
        background: white;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
    }

    .btn-save {
        background: var(--primary);
        color: #111827;
        font-weight: 800;
        padding: 1rem 3rem;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 4px 10px rgba(245, 158, 11, 0.3);
    }

    .btn-save:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
    }
</style>

<div class="container-piar">
    
    <a href="javascript:history.back()" class="btn-back">
        <i class="bi bi-arrow-left-circle-fill" style="font-size: 1.5rem; margin-right: 0.5rem;"></i>
        <span>Volver a periodos</span>
    </a>

    <div class="header-section">
        <h2 style="font-weight: 800; color: #1e293b;">Evaluación PIAR <span style="color: var(--primary)">Periodo {{ $period }}</span></h2>
    </div>

    <div class="info-card">
        <div class="info-item">
            <b>Estudiante</b>
            <span>{{ $piar->student->name }} {{ $piar->student->last_name }}</span>
        </div>
        <div style="width: 1px; height: 40px; background: #e2e8f0;"></div>
        <div class="info-item">
            <b>Grado Actual</b>
            <span>{{ $piar->student->degree->degree ?? 'Sin grado' }}</span>
        </div>
    </div>

    <form action="{{ route('piar.evaluacion.store') }}" method="POST">
        @csrf
        <input type="hidden" name="piar_id" value="{{ $piar->id }}">
        <input type="hidden" name="period" value="{{ $period }}">

        @if($adjustments->isEmpty())
            <div class="adjustment-card" style="padding: 3rem; text-align: center; color: #94a3b8;">
                <i class="bi bi-folder-x" style="font-size: 3rem;"></i>
                <p style="margin-top: 1rem;">No hay ajustes registrados para este periodo.</p>
            </div>
        @else
            @foreach($adjustments as $adj)
            <div class="adjustment-card">
                <div class="card-header">
                    <span class="area-badge">{{ $adj->area }}</span>
                    <span style="font-size: 0.75rem; font-weight: 600; color: #b45309;">DOCENTE: {{ $adj->teacher->name ?? 'N/A' }}</span>
                </div>

                <div class="grid-details">
                    <div class="detail-box">
                        <label>Objetivo</label>
                        <p>{{ $adj->objetivo }}</p>
                    </div>
                    <div class="detail-box">
                        <label>Barrera Identificada</label>
                        <p>{{ $adj->barrera }}</p>
                    </div>

                    <div class="detail-box">
                        <label>Ajuste Curricular</label>
                        <p>{{ $adj->ajuste_curricular }}</p>
                    </div>
                    <div class="detail-box">
                        <label>Metodología</label>
                        <p>{{ $adj->ajuste_metodologico }}</p>
                    </div>

                    <div class="detail-box">
                        <label>Convivencia</label>
                        <p>{{ $adj->convivencia }}</p>
                    </div>
                    <div class="detail-box">
                        <label>Socialización</label>
                        <p>{{ $adj->socializacion }}</p>
                    </div>
                    <div class="detail-box">
                        <label>Participación</label>
                        <p>{{ $adj->participacion }}</p>
                    </div>
                    <div class="detail-box">
                        <label>Autonomía</label>
                        <p>{{ $adj->autonomia }}</p>
                    </div>
                    <div class="detail-box" style="grid-column: span 2;">
                        <label>Autocontrol</label>
                        <p>{{ $adj->autocontrol }}</p>
                    </div>
                </div>

                <div class="evaluation-container">
                    <div class="evaluation-label">
                        <i class="bi bi-pencil-square" style="color: var(--primary)"></i>
                        Evaluación del Logro para {{ $adj->area }}
                    </div>
                    <input type="hidden" name="adjustment_id[]" value="{{ $adj->id }}">
                    <textarea name="evaluacion[]" class="form-control" placeholder="Describa el nivel de logro alcanzado, dificultades y recomendaciones..." rows="4">{{ old('evaluacion.' . $loop->index, $adj->evaluacion) }}</textarea>
                </div>
            </div>
            @endforeach
        @endif

        <div style="text-align:right; margin-bottom: 4rem;">
            <button type="submit" class="btn-save">
                <i class="bi bi-cloud-arrow-up-fill" style="margin-right: 0.5rem;"></i> GUARDAR TODAS LAS EVALUACIONES
            </button>
        </div>
    </form>
</div>

@endsection