@extends('layout.masterPage')

@section('title','PIAR - Evaluación')

@section('content')

<style>
.container-piar{
max-width:1100px;
margin:auto;
}

.box-section{
border:2px solid #000;
border-radius:8px;
padding:20px;
margin-bottom:30px;
background:#f7f7f7;
}

.box-title{
background:#f59e0b;
color:#111827;
font-weight:bold;
padding:8px 15px;
border-radius:6px;
margin-bottom:20px;
}

.form-control{
border:2px solid #000;
border-radius:6px;
padding:8px;
width:100%;
}

textarea.form-control{
height:90px;
resize:none;
}

.btn-save{
background:#f59e0b;
color:#111827;
font-weight:bold;
padding:12px 40px;
border:none;
border-radius:6px;
}

table{
width:100%;
border-collapse:collapse;
}

th, td{
border:1px solid #000;
padding:8px;
vertical-align:top;
background:#fff;
font-size:12px;
}

th{
background:#f3f4f6;
font-weight:bold;
width:20%;
}

.separador{
background:#ddd;
height:6px;
}
</style>

<button onclick="history.back()" style="background: none; border: none; cursor: pointer;">
    <i class="bi bi-arrow-left" style="font-size: 2rem;"></i>
</button>

<div class="container container-piar">
    <h3>PIAR - Evaluación (Periodo {{ $period }})</h3>

    <div class="box-section">
        <div class="box-title">Datos del Estudiante</div>
        <p>
        <b>Nombre:</b> {{ $piar->student->name }} {{ $piar->student->last_name }}
        &nbsp;&nbsp; | &nbsp;&nbsp;
        <b>Grado:</b> {{ $piar->student->degree->degree ?? 'Sin grado' }}
        </p>
    </div>

    <form action="{{ route('piar.evaluacion.store') }}" method="POST">
        @csrf
        <input type="hidden" name="piar_id" value="{{ $piar->id }}">
        <input type="hidden" name="period" value="{{ $period }}">

        <div class="box-section">
            <div class="box-title">Evaluación por Ajuste</div>

            @if($adjustments->isEmpty())
                <p>No hay ajustes registrados para este periodo.</p>
            @else
                <table>
                    <tbody>

                        @foreach($adjustments as $adj)

                        <!-- FILA 1 -->
                        <tr>
                            <th>Área</th>
                            <td>{{ $adj->area }}</td>

                            <th>Objetivo</th>
                            <td>{{ $adj->objetivo }}</td>
                        </tr>

                        <!-- FILA 2 -->
                        <tr>
                            <th>Barrera</th>
                            <td>{{ $adj->barrera }}</td>

                            <th>Ajuste Curricular</th>
                            <td>{{ $adj->ajuste_curricular }}</td>
                        </tr>

                        <!-- FILA 3 -->
                        <tr>
                            <th>Ajuste Metodológico</th>
                            <td>{{ $adj->ajuste_metodologico }}</td>

                            <th>Ajuste Evaluativo</th>
                            <td>{{ $adj->ajuste_evaluativo }}</td>
                        </tr>

                        <!-- FILA 4 -->
                        <tr>
                            <th>Convivencia</th>
                            <td>{{ $adj->convivencia }}</td>

                            <th>Socialización</th>
                            <td>{{ $adj->socializacion }}</td>
                        </tr>

                        <!-- FILA 5 -->
                        <tr>
                            <th>Participación</th>
                            <td>{{ $adj->participacion }}</td>

                            <th>Autonomía</th>
                            <td>{{ $adj->autonomia }}</td>
                        </tr>

                        <!-- FILA 6 -->
                        <tr>
                            <th>Autocontrol</th>
                            <td>{{ $adj->autocontrol }}</td>

                            <th>Evaluación</th>
                            <td>
                                <input type="hidden" name="adjustment_id[]" value="{{ $adj->id }}">
                                <textarea name="evaluacion[]" class="form-control">
{{ old('evaluacion.' . $loop->index, $adj->evaluacion) }}
                                </textarea>
                            </td>
                        </tr>

                        <!-- Separador visual -->
                        <tr>
                            <td colspan="4" class="separador"></td>
                        </tr>

                        @endforeach

                    </tbody>
                </table>
            @endif
        </div>

        <div style="text-align:right">
            <button type="submit" class="btn-save">Guardar Evaluación</button>
        </div>
    </form>
</div>

@endsection