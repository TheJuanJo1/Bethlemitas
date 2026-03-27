<!DOCTYPE html>
<html>
<head>

<meta charset="utf-8">

<style>

body{
font-family: Arial, sans-serif;
font-size:11px;
}

h2{
text-align:center;
margin-bottom:10px;
}

.info{
margin-bottom:15px;
}

table{
width:100%;
border-collapse:collapse;
}

table th, table td{
border:1px solid #000;
padding:5px;
vertical-align:top;
}

th{
background:#e5e5e5;
font-size:11px;
}

.small{
font-size:10px;
}

</style>

</head>

<body>

<h2>PIAR - PERIODO 3</h2>

<div class="info">
<strong>Estudiante:</strong> {{ $piar->student->name }} {{ $piar->student->last_name }}
&nbsp;&nbsp; | &nbsp;&nbsp;
<strong>Grado:</strong> {{ $piar->student->degree->degree ?? 'Sin grado' }}
</div>

<table>

<thead>

<tr>
<th>Docente</th>
<th>Área</th>
<th>Objetivo</th>
<th>Barrera</th>

<th>Ajuste Curricular</th>
<th>Ajuste Metodológico</th>
<th>Ajuste Evaluativo</th>

<th>Convivencia</th>
<th>Socialización</th>
<th>Participación</th>
<th>Autonomía</th>
<th>Autocontrol</th>

<th>Evaluación</th>
</tr>

</thead>

<tbody>

@foreach($adjustments as $a)

<tr>

<td>
{{ $a->teacher->name ?? '' }} {{ $a->teacher->last_name ?? '' }}
</td>

<td>{{ $a->area }}</td>

<td>{{ $a->objetivo }}</td>

<td>{{ $a->barrera }}</td>

<td>{{ $a->ajuste_curricular }}</td>

<td>{{ $a->ajuste_metodologico }}</td>

<td>{{ $a->ajuste_evaluativo }}</td>

<td>{{ $a->convivencia }}</td>

<td>{{ $a->socializacion }}</td>

<td>{{ $a->participacion }}</td>

<td>{{ $a->autonomia }}</td>

<td>{{ $a->autocontrol }}</td>

<td>{{ $a->evaluacion }}</td>

</tr>

@endforeach

</tbody>

</table>

</body>
</html>