<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<style>

body{
font-family: Arial, Helvetica, sans-serif;
font-size:13px;
margin-top:95px;
margin-bottom:60px;
}

.header{
position:fixed;
top:0;
left:0;
right:0;
text-align:center;
}

.header img{
width:100%;
}

.footer{
position:fixed;
bottom:0;
left:0;
right:0;
text-align:center;
font-size:11px;
}

h1{
text-align:center;
font-size:18px;
margin-bottom:10px;
}

table{
width:100%;
border-collapse:collapse;
margin-bottom:15px;
}

table, th, td{
border:1px solid black;
}

th,td{
padding:6px;
vertical-align:top;
word-wrap:break-word;
}

.titulo-seccion{
background:#f2f2f2;
font-weight:bold;
text-align:center;
}

.campo{
background:#f7f7f7;
font-weight:bold;
width:25%;
}

.caja-grande{
min-height:80px;
}

.page-break{
page-break-before:always;
}

</style>

</head>


<body>

<div class="header">
<img src="{{ public_path('img/credencialesGo.jpg') }}">
</div>

<div class="footer">
V14.16/02/2018. - Ver documento de instrucciones.<br>
Ministerio de Educación Nacional – Viceministerio de Educación Preescolar, Básica y Media – Decreto 1421 de 2017
</div>


<h1>
PLAN INDIVIDUAL DE AJUSTES RAZONABLES – PIAR –<br>
ANEXO 2
</h1>


<table>

<tr>
<td class="campo">Fecha de elaboración:</td>
<td>{{ $piar->created_at->format('d/m/Y') }}</td>

<td class="campo">Institución Educativa:</td>
<td>Bethelemitas</td>
</tr>

<tr>
<td class="campo">Sede:</td>
<td>Pereira</td>

<td class="campo">Jornada:</td>
<td>Diurna</td>
</tr>

<tr>
<td class="campo">Docentes que elaboran y cargo:</td>
<td colspan="3">

@if($periodo1->count() > 0)
{{ $periodo1->first()->teacher->name ?? '' }}
{{ $periodo1->first()->teacher->last_name ?? '' }}
@endif

</td>
</tr>

</table>



<table>

<tr>
<td colspan="4" class="titulo-seccion">
DATOS DEL ESTUDIANTE
</td>
</tr>

<tr>
<td class="campo">Nombre del estudiante</td>

<td>
{{ $piar->student->name ?? '' }}
{{ $piar->student->last_name ?? '' }}
</td>

<td class="campo">Documento de Identificación</td>

<td>
{{ $piar->student->number_documment ?? '' }}
</td>

</tr>


<tr>

<td class="campo">Edad</td>

<td>
{{ $piar->student->age ?? '' }}
</td>

<td class="campo">Grado</td>

<td>
{{ $piar->student->degree->degree ?? 'Sin grado' }}
</td>

</tr>

</table>




<table>

<tr>
<td colspan="2" class="titulo-seccion">
1. Características del Estudiante
</td>
</tr>


<tr>

<td class="campo">Descripción</td>

<td class="caja-grande">
{{ $piar->characteristics->descripcion_estudiante ?? '' }}
</td>

</tr>


<tr>

<td class="campo">Gustos e intereses</td>

<td class="caja-grande">
{{ $piar->characteristics->gustos_intereses ?? '' }}
</td>

</tr>


<tr>

<td class="campo">Expectativas de la familia</td>

<td class="caja-grande">
{{ $piar->characteristics->expectativas_familia ?? '' }}
</td>

</tr>


<tr>

<td class="campo">Habilidades</td>

<td class="caja-grande">
{{ $piar->characteristics->habilidades ?? '' }}
</td>

</tr>


<tr>

<td class="campo">Apoyos requeridos</td>

<td class="caja-grande">
{{ $piar->characteristics->apoyos_requeridos ?? '' }}
</td>

</tr>

</table>




<div class="page-break"></div>

<h3>Periodo 1</h3>

<table>

<tr>
<th>Docente</th>
<th>Área</th>
<th>Objetivo</th>
<th>Barrera</th>
<th>Ajuste</th>
<th>Evaluación</th>
</tr>


@foreach($periodo1 as $row)

<tr>

<td>
{{ $row->teacher->name ?? '' }}
{{ $row->teacher->last_name ?? '' }}
</td>

<td>
{{ $row->area ?? '' }}
</td>

<td>
{{ $row->objetivo ?? '' }}
</td>

<td>
{{ $row->barrera ?? '' }}
</td>

<td>
{{ $row->ajuste ?? '' }}
</td>

<td>
{{ $row->evaluacion ?? '' }}
</td>

</tr>

@endforeach

</table>




<div class="page-break"></div>

<h3>Periodo 2</h3>

<table>

<tr>
<th>Docente</th>
<th>Área</th>
<th>Objetivo</th>
<th>Barrera</th>
<th>Ajuste</th>
<th>Evaluación</th>
</tr>


@foreach($periodo2 as $row)

<tr>

<td>
{{ $row->teacher->name ?? '' }}
{{ $row->teacher->last_name ?? '' }}
</td>

<td>
{{ $row->area ?? '' }}
</td>

<td>
{{ $row->objetivo ?? '' }}
</td>

<td>
{{ $row->barrera ?? '' }}
</td>

<td>
{{ $row->ajuste ?? '' }}
</td>

<td>
{{ $row->evaluacion ?? '' }}
</td>

</tr>

@endforeach

</table>




<div class="page-break"></div>

<h3>Periodo 3</h3>

<table>

<tr>
<th>Docente</th>
<th>Área</th>
<th>Objetivo</th>
<th>Barrera</th>
<th>Ajuste</th>
<th>Evaluación</th>
</tr>


@foreach($periodo3 as $row)

<tr>

<td>
{{ $row->teacher->name ?? '' }}
{{ $row->teacher->last_name ?? '' }}
</td>

<td>
{{ $row->area ?? '' }}
</td>

<td>
{{ $row->objetivo ?? '' }}
</td>

<td>
{{ $row->barrera ?? '' }}
</td>

<td>
{{ $row->ajuste ?? '' }}
</td>

<td>
{{ $row->evaluacion ?? '' }}
</td>

</tr>

@endforeach

</table>



</body>
</html>