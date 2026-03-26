@extends('layout.masterPage')

@section('title','PIAR - Periodo 1')

@section('content')

<style>

.container-piar{
max-width:1200px;
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
border:1px solid #000;
border-radius:4px;
padding:6px;
width:100%;
font-size:12px;
}

textarea.form-control{
height:60px;
resize:none;
}

table{
width:100%;
border-collapse:collapse;
font-size:12px;
}

th, td{
border:1px solid #000;
padding:5px;
text-align:center;
vertical-align:middle;
}

thead th{
background:#dbeafe;
font-weight:bold;
}

.btn-save{
background:#2563eb;
color:white;
font-weight:bold;
padding:12px 40px;
border:none;
border-radius:6px;
}

</style>

<button onclick="history.back()" style="background:none;border:none;cursor:pointer;">
<i class="bi bi-arrow-left" style="font-size:2rem;"></i>
</button>

<div class="container container-piar">

<h3>PIAR - Ajustes Razonables (Periodo 1)</h3>

<form action="{{ route('piar.periodo1.store') }}" method="POST">

@csrf

<input type="hidden" name="piar_id" value="{{ $piar->id }}">

<div class="box-section">

<div class="box-title">
Datos del Estudiante
</div>

<p>
<b>Nombre:</b> {{ $piar->student->name }} {{ $piar->student->last_name }}
&nbsp;&nbsp; | &nbsp;&nbsp;
<b>Grado:</b> {{ $piar->student->degree->degree ?? 'Sin grado' }}
</p>

</div>


<div class="box-section">

<div class="box-title">
Ajustes Razonables
</div>

<table>

<thead>

<tr>
<th rowspan="2">Área</th>
<th rowspan="2">Objetivo / Propósito</th>
<th rowspan="2">Barreras</th>

<th colspan="3">Ajustes Razonables</th>

<th colspan="5">Otros</th>
</tr>

<tr>
<th>Curriculares</th>
<th>Metodológicos</th>
<th>Evaluativos</th>

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
<select name="area[]" class="form-control" required>
<option value="">Seleccione</option>
@foreach($areas as $area)
<option value="{{ $area->name_area }}">{{ $area->name_area }}</option>
@endforeach
</select>
</td>

<td>
<textarea name="objetivo[]" class="form-control" required></textarea>
</td>

<td>
<textarea name="barrera[]" class="form-control" required></textarea>
</td>

<td>
<textarea name="ajuste_curricular[]" class="form-control" required></textarea>
</td>

<td>
<textarea name="ajuste_metodologico[]" class="form-control" required></textarea>
</td>

<td>
<textarea name="ajuste_evaluativo[]" class="form-control" required></textarea>
</td>

<td>
<textarea name="convivencia[]" class="form-control" required></textarea>
</td>

<td>
<textarea name="socializacion[]" class="form-control" required></textarea>
</td>

<td>
<textarea name="participacion[]" class="form-control" required></textarea>
</td>

<td>
<textarea name="autonomia[]" class="form-control" required></textarea>
</td>

<td>
<textarea name="autocontrol[]" class="form-control" required></textarea>
</td>

</tr>

</tbody>

</table>

</div>

<div style="text-align:right">

<button type="submit" class="btn-save">
Guardar Periodo 1
</button>

</div>

</form>

</div>

@endsection