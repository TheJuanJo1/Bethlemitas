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
padding:10px;
vertical-align:top;
background:#fff;
}

th{
background:#f3f4f6;
font-weight:bold;
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
                <p>No hay ajustes registrados, realizados por usted para este periodo.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th style="width:14%">Área</th>
                            <th style="width:18%">Objetivo</th>
                            <th style="width:18%">Barrera</th>
                            <th style="width:22%">Ajuste</th>
                            <th style="width:28%">Evaluación</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($adjustments as $adj)
                            <tr>
                                <td>{{ $adj->area }}</td>
                                <td>{{ $adj->objetivo }}</td>
                                <td>{{ $adj->barrera }}</td>
                                <td>{{ $adj->ajuste }}</td>
                                <td>
                                    <input type="hidden" name="adjustment_id[]" value="{{ $adj->id }}">
                                    <textarea name="evaluacion[]" class="form-control">{{ old('evaluacion.' . $loop->index, $adj->evaluacion) }}</textarea>
                                </td>
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
