<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>

body{
font-family: DejaVu Sans, sans-serif;
}

.title{
text-align:center;
font-size:22px;
font-weight:bold;
margin-bottom:20px;
}

.section{
border:1px solid #000;
padding:10px;
margin-bottom:15px;
}

.section-title{
background:#2563eb;
color:white;
padding:5px;
font-weight:bold;
margin-bottom:10px;
}

table{
width:100%;
border-collapse:collapse;
}

td{
border:1px solid #000;
padding:8px;
}

</style>

</head>

<body>

<button onclick="history.back()" style="background: none; border: none; cursor: pointer;">
        <i class="bi bi-arrow-left" style="font-size: 2rem;"></i>
</button>

<div class="title">
PLAN INDIVIDUAL DE AJUSTES RAZONABLES - PIAR
</div>

<div class="section">

<div class="section-title">
Información General
</div>

<table>
<tr>
<td><b>Fecha de Elaboración</b></td>
<td>{{ \Carbon\Carbon::parse($piar->created_at)->format('d/m/Y') }}</td>
</tr>

<tr>
<td><b>Institución</b></td>
<td>Bethelemitas</td>
</tr>

<tr>
<td><b>Sede</b></td>
<td>Pereira</td>
</tr>

<tr>
<td><b>Jornada</b></td>
<td>Diurna</td>
</tr>
</table>

</div>

<tr>
<td><b>Grado</b></td>
<td>{{ $piar->student->degree->degree }}</td>
</tr>

<tr>
<td><b>Grupo</b></td>
<td>{{ $piar->student->group->group }}</td>
</tr>

<div class="section">

<div class="section-title">
Datos del Estudiante
</div>

<table>

<tr>
<td><b>Nombre</b></td>
<td>{{ $piar->student->name }} {{ $piar->student->last_name }}</td>
</tr>

<tr>
<td><b>Documento</b></td>
<td>{{ $piar->student->number_documment }}</td>
</tr>

</table>

</div>


<div class="section">

<div class="section-title">
Características del Estudiante
</div>

<table>

<tr>
<td><b>Descripción</b></td>
<td>{{ $piar->descripcion }}</td>
</tr>

<tr>
<td><b>Gustos</b></td>
<td>{{ $piar->gustos }}</td>
</tr>

<tr>
<td><b>Expectativas</b></td>
<td>{{ $piar->expectativas }}</td>
</tr>

<tr>
<td><b>Habilidades</b></td>
<td>{{ $piar->habilidades }}</td>
</tr>

<tr>
<td><b>Apoyos</b></td>
<td>{{ $piar->apoyos }}</td>
</tr>

</table>

</div>

</body>
</html>