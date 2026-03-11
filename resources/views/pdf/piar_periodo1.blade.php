<!DOCTYPE html>
<html>
<head>

<meta charset="utf-8">

<style>

body{
font-family: Arial, sans-serif;
font-size:12px;
}

h2{
text-align:center;
margin-bottom:20px;
}

table{
width:100%;
border-collapse:collapse;
}

table th, table td{
border:1px solid #000;
padding:6px;
}

th{
background:#e5e5e5;
}

</style>

</head>

<body>

<h2>PIAR - Periodo 1</h2>

<p><strong>Estudiante:</strong> {{ $piar->student->name }} {{ $piar->student->last_name }}</p>

<br>

<table>

<thead>

<tr>
<th>Área</th>
<th>Objetivo</th>
<th>Barrera</th>
<th>Ajuste</th>
<th>Evaluación</th>
</tr>

</thead>

<tbody>

@foreach($adjustments as $a)

<tr>
<td>{{ $a->area }}</td>
<td>{{ $a->objetivo }}</td>
<td>{{ $a->barrera }}</td>
<td>{{ $a->ajuste }}</td>
<td>{{ $a->evaluacion }}</td>
</tr>

@endforeach

</tbody>

</table>

</body>
</html>