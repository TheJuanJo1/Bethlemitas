@extends('layout.masterPage')

@section('title','Crear PIAR')

@section('content')

<style>
    :root {
        --primary: #2563eb;
        --primary-dark: #1e40af;
        --bg-body: #f8fafc;
        --border-color: #e2e8f0;
        --text-muted: #64748b;
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
        gap: 8px;
        color: var(--text-muted);
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 1.5rem;
        transition: all 0.2s;
    }
    .btn-back:hover { color: var(--primary); transform: translateX(-4px); }

    /* Secciones de Formulario */
    .form-card {
        background: white;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .card-header-custom {
        background: #f1f5f9;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-header-custom i { color: var(--primary); font-size: 1.2rem; }
    .card-header-custom span { font-weight: 800; color: #1e293b; text-transform: uppercase; letter-spacing: 0.5px; font-size: 0.85rem; }

    /* Grid de Datos Informativos */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        padding: 1.5rem;
    }

    .data-item {
        background: #f8fafc;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        border: 1px solid #f1f5f9;
    }

    .data-item label {
        display: block;
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        margin-bottom: 2px;
    }

    .data-item span {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
    }

    /* Campos de Entrada */
    .fields-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        padding: 1.5rem;
    }

    .form-group { display: flex; flex-direction: column; gap: 0.5rem; }
    .form-group label { font-size: 0.85rem; font-weight: 700; color: #475569; }

    .form-control {
        border: 2px solid var(--border-color);
        border-radius: 10px;
        padding: 0.8rem;
        font-size: 0.95rem;
        transition: all 0.2s;
        background: #fcfcfc;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        background: white;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }

    .textarea-small {
        height: 80px;
        resize: vertical;
    }

    /* Botón Guardar */
    .btn-save {
        background: var(--primary);
        color: white;
        font-weight: 800;
        padding: 1rem 3rem;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
    }

    .btn-save:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 20px 25px -5px rgba(37, 99, 235, 0.4);
    }

    /* Errores */
    .error-box {
        background: #fee2e2;
        border-left: 4px solid #dc2626;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 8px;
    }
</style>

<div class="container-piar">

    <a href="javascript:history.back()" class="btn-back">
        <i class="bi bi-arrow-left-circle-fill" style="font-size: 1.5rem;"></i>
        <span>Volver</span>
    </a>

    <h2 style="font-weight: 800; color: #0f172a; margin-bottom: 2rem;">Crear Nuevo <span style="color: var(--primary)">PIAR</span></h2>

    <form action="{{ route('piar.store') }}" method="POST">
        @csrf
        <input type="hidden" name="student_id" value="{{ $student->id }}">

        <div class="form-card">
            <div class="card-header-custom">
                <i class="bi bi-building"></i>
                <span>Información General</span>
            </div>
            <div class="info-grid">
                <div class="data-item">
                    <label>Institución</label>
                    <span>Bethlemitas</span>
                </div>
                <div class="data-item">
                    <label>Sede</label>
                    <span>Pereira</span>
                </div>
                <div class="data-item">
                    <label>Jornada</label>
                    <span>Diurna</span>
                </div>
                <div class="data-item">
                    <label>Fecha Elaboración</label>
                    <span>{{ date('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="card-header-custom">
                <i class="bi bi-person-badge"></i>
                <span>Datos del Estudiante</span>
            </div>
            <div class="info-grid">
                <div class="data-item">
                    <label>Nombre Completo</label>
                    <span>{{ $student->name }} {{ $student->last_name }}</span>
                </div>
                <div class="data-item">
                    <label>Identificación</label>
                    <span>{{ $student->number_documment }}</span>
                </div>
                <div class="data-item">
                    <label>Grado</label>
                    <span>{{ $student->degree->degree ?? 'N/A' }}</span>
                </div>
                <div class="data-item">
                    <label>Edad</label>
                    <span>{{ $student->age }} años</span>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="error-box">
                <ul style="margin:0; padding-left: 1.2rem; color: #991b1b; font-weight: 600;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-card">
            <div class="card-header-custom" style="background: #eff6ff; border-bottom-color: #dbeafe;">
                <i class="bi bi-clipboard-data"></i>
                <span>Características del Estudiante</span>
            </div>
            <div class="fields-grid">
                <div class="form-group">
                    <label>Descripción del estudiante</label>
                    <textarea name="descripcion" class="form-control textarea-small" placeholder="Personalidad, comportamiento general..." required>{{ old('descripcion') }}</textarea>
                </div>

                <div class="form-group">
                    <label>Gustos e intereses</label>
                    <textarea name="gustos" class="form-control textarea-small" placeholder="¿Qué le motiva al estudiante?" required>{{ old('gustos') }}</textarea>
                </div>

                <div class="form-group">
                    <label>Expectativas de la familia</label>
                    <textarea name="expectativas" class="form-control textarea-small" placeholder="¿Qué espera la familia lograr?" required>{{ old('expectativas') }}</textarea>
                </div>

                <div class="form-group">
                    <label>Habilidades</label>
                    <textarea name="habilidades" class="form-control textarea-small" placeholder="Fortalezas académicas o sociales..." required>{{ old('habilidades') }}</textarea>
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label>Apoyos requeridos</label>
                    <textarea name="apoyos" class="form-control textarea-small" placeholder="¿Qué herramientas o personas necesita para su proceso?" required>{{ old('apoyos') }}</textarea>
                </div>
            </div>
        </div>

        <div style="text-align:right; margin-bottom: 5rem;">
            <button type="submit" class="btn-save">
                <i class="bi bi-save-fill" style="margin-right: 8px;"></i> REGISTRAR PIAR
            </button>
        </div>

    </form>
</div>

@endsection