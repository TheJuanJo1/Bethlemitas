@extends('layout.masterPage')

@section('title','PIAR - Editar Periodo 1')

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
background:#2563eb;
color:white;
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
height:70px;
resize:none;
}

.btn-save{
background:#16a34a;
color:white;
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

<h3>Editar Periodo 1</h3>

<form action="{{ route('piar.update.periodo1') }}" method="POST">
    @csrf
@method('PUT')

<input type="hidden" name="piar_id" value="{{ $piar_id }}">

<div class="box-section">
<div class="box-title">Ajustes del Periodo</div>

@if($adjustments->isEmpty())
<p>No hay registros para editar.</p>
@else

<table>
<tbody>

@foreach($adjustments as $adj)

<input type="hidden" name="adjustment_id[]" value="{{ $adj->id }}">

<!-- FILA 1 -->
<tr>
<th>Área</th>
<td>{{ $adj->area }}</td>

<th>Objetivo</th>
<td>
<textarea name="objetivo[]" class="form-control">{{ $adj->objetivo }} </textarea>
</td>
</tr>

<!-- FILA 2 -->
<tr>
<th>Barrera</th>
<td>
<textarea name="barrera[]" class="form-control">{{ $adj->barrera }}</textarea>
</td>

<th>Ajuste Curricular</th>
<td>
<textarea name="ajuste_curricular[]" class="form-control">{{ $adj->ajuste_curricular }}</textarea>
</td>
</tr>

<!-- FILA 3 -->
<tr>
<th>Ajuste Metodológico</th>
<td>
<textarea name="ajuste_metodologico[]" class="form-control">{{ $adj->ajuste_metodologico }}</textarea>
</td>

<th>Ajuste Evaluativo</th>
<td>
<textarea name="ajuste_evaluativo[]" class="form-control">{{ $adj->ajuste_evaluativo }}</textarea>
</td>
</tr>

<!-- FILA 4 -->
<tr>
<th>Convivencia</th>
<td>
<textarea name="convivencia[]" class="form-control">{{ $adj->convivencia }}</textarea>
</td>

<th>Socialización</th>
<td>
<textarea name="socializacion[]" class="form-control">{{ $adj->socializacion }}</textarea>
</td>
</tr>

<!-- FILA 5 -->
<tr>
<th>Participación</th>
<td>
<textarea name="participacion[]" class="form-control">{{ $adj->participacion }}</textarea>
</td>

<th>Autonomía</th>
<td>
<textarea name="autonomia[]" class="form-control">{{ $adj->autonomia }}</textarea>
</td>
</tr>

<!-- FILA 6 -->
<tr>
<th>Autocontrol</th>
<td>
<textarea name="autocontrol[]" class="form-control">{{ $adj->autocontrol }}</textarea>
</td>

<th>Evaluación (solo lectura)</th>
<td>
<textarea class="form-control" readonly>{{ $adj->evaluacion }}</textarea>
</td>
</tr>

<tr>
<td colspan="4" class="separador"></td>
</tr>

@endforeach

</tbody>
</table>

@endif

</div>

<div style="text-align:right">
<button type="submit" class="btn-save">Guardar Cambios</button>
</div>

</form>

</div>

@endsection