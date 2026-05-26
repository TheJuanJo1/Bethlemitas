@extends('layout.masterPage')

@section('title','PIAR - Editar Periodo 1')

@section('content')

@php
    $piar = \App\Models\Piar::with('student.degree')->findOrFail($piar_id);
@endphp

<style>
    :root { 
        --primary: #1e40af; 
        --primary-hover: #1e3a8a;
        --secondary: #0d9488; /* Verde para el Anexo 3 */
        --bg-light: #f1f5f9;
        --border-strong: #94a3b8; 
        --text-main: #1e293b;
    }

    .container-piar {
        max-width: 1400px;
        margin: 20px auto;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: var(--text-main);
        padding: 0 15px;
    }

    .box-section {
        background: #ffffff;
        border-radius: 8px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        border: 1.5px solid var(--border-strong);
    }

    .box-title {
        background: var(--primary);
        color: white;
        font-weight: 700;
        padding: 10px 20px;
        border-radius: 4px;
        margin-bottom: 20px;
        display: inline-block;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .form-control {
        border: 1.5px solid var(--border-strong);
        border-radius: 4px;
        padding: 8px;
        width: 100%;
        font-size: 13px;
        background-color: #fff;
        transition: all 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 2px rgba(30, 64, 175, 0.2);
        background-color: #fefce8;
    }

    textarea.form-control {
        height: 90px;
        resize: vertical;
    }

    .table-responsive {
        overflow-x: auto;
        border: 1.5px solid var(--border-strong);
        border-radius: 4px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12.5px;
        background: white;
    }

    th {
        background: #e2e8f0;
        color: #0f172a;
        font-weight: 700;
        text-transform: uppercase;
        padding: 12px 8px;
        border: 1.5px solid var(--border-strong);
    }

    td {
        border: 1.5px solid var(--border-strong);
        padding: 8px;
        vertical-align: top;
        background-color: #ffffff;
    }

    tbody tr:nth-child(even) {
        background-color: #f8fafc;
    }

    .btn-back {
        background: white;
        border: 2px solid var(--border-strong);
        border-radius: 8px;
        width: 50px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
        color: var(--primary);
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-back:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .btn-save {
        background: var(--primary);
        color: white;
        font-weight: 700;
        padding: 14px 40px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: transform 0.1s, background 0.2s;
        box-shadow: 0 4px 0px #173671;
    }

    .btn-save:active {
        transform: translateY(3px);
        box-shadow: none;
    }

    .btn-add-row {
        background: var(--secondary);
        color: white;
        padding: 8px 15px;
        font-size: 11px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        box-shadow: 0 3px 0px #064e4b;
    }

    .student-info {
        background: #f1f5f9;
        padding: 15px;
        border-radius: 6px;
        border-left: 5px solid var(--primary);
    }
</style>

<div class="container-piar">
    
    <button onclick="history.back()" class="btn-back" title="Volver">
        <i class="bi bi-arrow-left"></i>
    </button>

    <h2 style="margin-bottom: 25px; color: #0f172a; border-bottom: 3px solid var(--primary); display: inline-block; padding-bottom: 5px;">
        Editar PIAR - Ajustes Razonables (Periodo 1)
    </h2>

    <form action="{{ route('piar.update.periodo1') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="piar_id" value="{{ $piar_id }}">

        <div class="box-section">
            <div class="box-title">Datos del Estudiante</div>
            <div class="student-info">
                <span style="font-size: 1.1rem;">
                    <strong>Nombre:</strong> {{ $piar->student->name }} {{ $piar->student->last_name }} 
                    <span style="margin: 0 20px; color: #94a3b8;">|</span>
                    <strong>Grado:</strong> {{ $piar->student->degree->degree ?? 'Sin grado' }}
                </span>
            </div>
        </div>

        <div class="box-section">
            <div class="box-title">Matriz de Ajustes</div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th rowspan="2" style="min-width: 180px;">Área</th>
                            <th rowspan="2">Objetivo / Propósito</th>
                            <th rowspan="2">Barreras</th>
                            <th colspan="3" style="background: #bfdbfe; border-bottom: 2px solid var(--primary);">Ajustes Razonables</th>
                            <th colspan="5" style="background: #cbd5e1;">Otros Aspectos</th>
                        </tr>
                        <tr>
                            <th style="background: #dbeafe;">Curriculares</th>
                            <th style="background: #dbeafe;">Metodológicos</th>
                            <th style="background: #dbeafe;">Evaluativos</th>
                            <th>Convivencia</th>
                            <th>Socialización</th>
                            <th>Participación</th>
                            <th>Autonomía</th>
                            <th>Autocontrol</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($adjustments as $adj)
                        <input type="hidden" name="adjustment_id[]" value="{{ $adj->id }}">
                        <tr>
                            <td>
                                <span class="font-bold text-blue-800 text-[13px] block mb-1">{{ $adj->area }}</span>
                                <span class="text-[10px] text-gray-500 block">Docente: {{ auth()->user()->name }} {{ auth()->user()->last_name }}</span>
                            </td>
                            <td><textarea name="objetivo[]" class="form-control" required>{{ $adj->objetivo }}</textarea></td>
                            <td><textarea name="barrera[]" class="form-control" required>{{ $adj->barrera }}</textarea></td>
                            <td><textarea name="ajuste_curricular[]" class="form-control" required>{{ $adj->ajuste_curricular }}</textarea></td>
                            <td><textarea name="ajuste_metodologico[]" class="form-control" required>{{ $adj->ajuste_metodologico }}</textarea></td>
                            <td><textarea name="ajuste_evaluativo[]" class="form-control" required>{{ $adj->ajuste_evaluativo }}</textarea></td>
                            <td><textarea name="convivencia[]" class="form-control" required>{{ $adj->convivencia }}</textarea></td>
                            <td><textarea name="socializacion[]" class="form-control" required>{{ $adj->socializacion }}</textarea></td>
                            <td><textarea name="participacion[]" class="form-control" required>{{ $adj->participacion }}</textarea></td>
                            <td><textarea name="autonomia[]" class="form-control" required>{{ $adj->autonomia }}</textarea></td>
                            <td><textarea name="autocontrol[]" class="form-control" required>{{ $adj->autocontrol }}</textarea></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="box-section" style="border-left: 8px solid var(--secondary);">
            <div class="box-title" style="background: var(--secondary);">Anexo 3 - Plan de Trabajo con la Familia</div>
            <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 15px;">
                Modifique las actividades, estrategias y frecuencia de apoyo desde el hogar para este periodo.
            </p>

            <div class="table-responsive">
                <table id="tabla-anexo3">
                    <thead>
                        <tr>
                            <th style="width: 25%;">Nombre actividad</th>
                            <th>Descripción de la estrategia</th>
                            <th style="width: 20%;">Frecuencia (D, S, P, N/A)</th>
                            <th style="width: 50px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($familyActivities as $act)
                        <tr>
                            <td>
                                <input type="text" name="anexo3_actividad[]" class="form-control" placeholder="Si no cumple escriba: N/A" required value="{{ $act->activity }}">
                            </td>
                            <td>
                                <textarea name="anexo3_estrategia[]" class="form-control" style="height: 60px;" placeholder="Si no cumple escriba: N/A" required>{{ $act->strategy }}</textarea>
                            </td>
                            <td>
                                <select name="anexo3_frecuencia[]" class="form-control" required>
                                    <option value="D" {{ $act->frequency == 'D' ? 'selected' : '' }}>D (Diaria)</option>
                                    <option value="S" {{ $act->frequency == 'S' ? 'selected' : '' }}>S (Semanal)</option>
                                    <option value="P" {{ $act->frequency == 'P' ? 'selected' : '' }}>P (Permanente)</option>
                                    <option value="N/A" {{ $act->frequency == 'N/A' ? 'selected' : '' }}>N/A (No aplica)</option>
                                </select>
                            </td>
                            <td style="text-align:center; vertical-align:middle;">
                                <button type="button" onclick="this.parentElement.parentElement.remove()" class="btn" style="color: #ef4444; background: none; border: none; cursor: pointer;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td>
                                <input type="text" name="anexo3_actividad[]" class="form-control" placeholder="Si no cumple escriba: N/A" required>
                            </td>
                            <td>
                                <textarea name="anexo3_estrategia[]" class="form-control" style="height: 60px;" placeholder="Si no cumple escriba: N/A" required></textarea>
                            </td>
                            <td>
                                <select name="anexo3_frecuencia[]" class="form-control" required>
                                    <option value="D">D (Diaria)</option>
                                    <option value="S">S (Semanal)</option>
                                    <option value="P">P (Permanente)</option>
                                    <option value="N/A">N/A (No aplica)</option>
                                </select>
                            </td>
                            <td></td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div style="margin-top: 15px;">
                    <button type="button" onclick="agregarFilaAnexo()" class="btn-add-row">
                        <i class="bi bi-plus-circle"></i> AGREGAR ACTIVIDAD PARA LA FAMILIA
                    </button>
                </div>
            </div>
        </div>

        <div style="text-align:right; margin: 40px 0;">
            <button type="submit" class="btn-save">
                <i class="bi bi-cloud-check-fill"></i> GUARDAR TODOS LOS CAMBIOS - PERIODO 1
            </button>
        </div>
    </form>
</div>

<script>
    function agregarFilaAnexo() {
        const tbody = document.querySelector('#tabla-anexo3 tbody');
        const nuevaFila = document.createElement('tr');
        
        nuevaFila.innerHTML = `
            <td><input type="text" name="anexo3_actividad[]" class="form-control" placeholder="Si no cumple escriba: N/A" required></td>
            <td><textarea name="anexo3_estrategia[]" class="form-control" style="height: 60px;" placeholder="Si no cumple escriba: N/A" required></textarea></td>
            <td>
                <select name="anexo3_frecuencia[]" class="form-control" required>
                    <option value="D">D (Diaria)</option>
                    <option value="S">S (Semanal)</option>
                    <option value="P">P (Permanente)</option>
                    <option value="N/A">N/A (No aplica)</option>
                </select>
            </td>
            <td style="text-align:center; vertical-align:middle;">
                <button type="button" onclick="this.parentElement.parentElement.remove()" class="btn" style="color: #ef4444; background: none; border: none; cursor: pointer;">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(nuevaFila);
    }
</script>

@endsection