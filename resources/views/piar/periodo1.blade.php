@extends('layout.masterPage')

@section('title','PIAR - Periodo 1')

@section('content')

<style>
    :root {
        --primary: #1e40af; /* Un azul un poco más oscuro */
        --primary-hover: #1e3a8a;
        --bg-light: #f1f5f9;
        --border-strong: #94a3b8; /* Gris más oscuro para que se marquen las líneas */
        --text-main: #1e293b;
    }

    .container-piar {
        max-width: 1400px;
        margin: 20px auto;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: var(--text-main);
        padding: 0 15px;
    }

    /* Estilo de los bloques */
    .box-section {
        background: #ffffff;
        border-radius: 8px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        border: 1.5px solid var(--border-strong); /* Borde de sección más definido */
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

    /* Inputs y Textareas con bordes definidos */
    .form-control {
        border: 1.5px solid var(--border-strong); /* Líneas de inputs más marcadas */
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
        background-color: #fefce8; /* Un toque de color al escribir */
    }

    textarea.form-control {
        height: 90px;
        resize: vertical;
    }

    /* Tabla con líneas Fuertes */
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
        background: #e2e8f0; /* Fondo de encabezado más oscuro */
        color: #0f172a;
        font-weight: 700;
        text-transform: uppercase;
        padding: 12px 8px;
        border: 1.5px solid var(--border-strong); /* Línea de tabla gruesa */
    }

    td {
        border: 1.5px solid var(--border-strong); /* Línea de tabla gruesa */
        padding: 8px;
        vertical-align: top;
        background-color: #ffffff;
    }

    /* Fila cebra para no perder la vista */
    tbody tr:nth-child(even) {
        background-color: #f8fafc;
    }

    /* Botones */
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
        box-shadow: 0 4px 0px #173671; /* Efecto 3D simple */
    }

    .btn-save:active {
        transform: translateY(3px);
        box-shadow: none;
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
        PIAR - Ajustes Razonables (Periodo 1)
    </h2>

    <form action="{{ route('piar.periodo1.store') }}" method="POST">
        @csrf
        <input type="hidden" name="piar_id" value="{{ $piar->id }}">

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
                        <tr>
                            <td>
                                <select name="area[]" class="form-control" required style="font-weight: bold;">
                                    <option value="">-- Seleccione Área --</option>
                                    @foreach($areas as $area)
                                        <option value="{{ $area->name_area }}">{{ $area->name_area }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><textarea name="objetivo[]" class="form-control" required></textarea></td>
                            <td><textarea name="barrera[]" class="form-control" required></textarea></td>
                            <td><textarea name="ajuste_curricular[]" class="form-control" required></textarea></td>
                            <td><textarea name="ajuste_metodologico[]" class="form-control" required></textarea></td>
                            <td><textarea name="ajuste_evaluativo[]" class="form-control" required></textarea></td>
                            <td><textarea name="convivencia[]" class="form-control" required></textarea></td>
                            <td><textarea name="socializacion[]" class="form-control" required></textarea></td>
                            <td><textarea name="participacion[]" class="form-control" required></textarea></td>
                            <td><textarea name="autonomia[]" class="form-control" required></textarea></td>
                            <td><textarea name="autocontrol[]" class="form-control" required></textarea></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div style="text-align:right; margin: 40px 0;">
            <button type="submit" class="btn-save">
                <i class="bi bi-device-ssd"></i> GUARDAR INFORMACIÓN - PERIODO 1
            </button>
        </div>
    </form>
</div>

@endsection