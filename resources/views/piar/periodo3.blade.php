@extends('layout.masterPage')

@section('title','PIAR - Periodo 3')

@section('content')

<style>
    :root {
        --primary: #1e40af; 
        --primary-hover: #1e3a8a;
        --secondary: #0d9488; /* Color para el Anexo de Familia */
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

    /* Estilo de los bloques */
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

    /* Inputs y Textareas */
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

    /* Tablas */
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
        PIAR - Ajustes Razonables (Periodo 3)
    </h2>

    <form action="{{ route('piar.periodo3.store') }}" method="POST" enctype="multipart/form-data">
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

        <div class="box-section" style="border-left: 8px solid var(--secondary);">
            <div class="box-title" style="background: var(--secondary);">Anexo 3 - Plan de Trabajo con la Familia</div>
            <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 15px;">
                Estrategias y compromisos para el apoyo desde el hogar en este tercer periodo.
            </p>

            <div class="table-responsive">
                <table id="tabla-anexo3">
                    <thead>
                        <tr>
                            <th style="width: 25%;">Nombre actividad</th>
                            <th>Descripción de la estrategia</th>
                            <th style="width: 20%;">Frecuencia (D, S, P)</th>
                            <th style="width: 50px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <input type="text" name="anexo3_actividad[]" class="form-control" placeholder="Nombre actividad">
                            </td>
                            <td>
                                <textarea name="anexo3_estrategia[]" class="form-control" style="height: 60px;" placeholder="¿Qué hará la familia?"></textarea>
                            </td>
                            <td>
                                <select name="anexo3_frecuencia[]" class="form-control">
                                    <option value="D">D (Diaria)</option>
                                    <option value="S">S (Semanal)</option>
                                    <option value="P">P (Permanente)</option>
                                </select>
                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                <div style="margin-top: 15px;">
                    <button type="button" onclick="agregarFilaAnexo()" class="btn-add-row">
                        <i class="bi bi-plus-circle"></i> AGREGAR ACTIVIDAD FAMILIAR
                    </button>
                </div>
            </div>
        </div>

        <div class="box-section">
            <div class="box-title" style="background: #1e293b;">Firma del Docente</div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        Subir imagen de la firma (PNG, JPG)
                    </label>
                    <input type="file" name="teacher_signature" accept="image/*" onchange="previewSignature(event)"
                        class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition-all border border-slate-300 rounded-lg p-2 bg-slate-50">
                    <p class="mt-2 text-xs text-slate-500 italic">
                        * Se recomienda una imagen con fondo transparente o blanco.
                    </p>
                </div>
                <div class="flex flex-col items-center justify-center p-4 bg-slate-50 border-2 border-dashed border-slate-300 rounded-xl min-h-[160px]">
                    <span id="preview-text" class="text-slate-400 text-sm font-medium italic">Vista previa de la firma</span>
                    <img id="signature-preview" src="#" alt="Vista previa" class="hidden max-w-full max-h-32 object-contain shadow-sm bg-white p-2">
                </div>
            </div>
        </div>

        <div style="text-align:right; margin: 40px 0;">
            <button type="submit" class="btn-save">
                <i class="bi bi-device-ssd"></i> GUARDAR INFORMACIÓN - PERIODO 3
            </button>
        </div>
    </form>
</div>

<script>
    function previewSignature(event) {
        const reader = new FileReader();
        const preview = document.getElementById('signature-preview');
        const text = document.getElementById('preview-text');

        reader.onload = function() {
            preview.src = reader.result;
            preview.classList.remove('hidden');
            text.classList.add('hidden');
        }

        if (event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }

    function agregarFilaAnexo() {
        const tbody = document.querySelector('#tabla-anexo3 tbody');
        const nuevaFila = document.createElement('tr');
        
        nuevaFila.innerHTML = `
            <td><input type="text" name="anexo3_actividad[]" class="form-control"></td>
            <td><textarea name="anexo3_estrategia[]" class="form-control" style="height: 60px;"></textarea></td>
            <td>
                <select name="anexo3_frecuencia[]" class="form-control">
                    <option value="D">D (Diaria)</option>
                    <option value="S">S (Semanal)</option>
                    <option value="P">P (Permanente)</option>
                </select>
            </td>
            <td style="text-align:center; vertical-align:middle;">
                <button type="button" onclick="this.parentElement.parentElement.remove()" class="btn" style="color: #ef4444;">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(nuevaFila);
    }
</script>

@endsection 