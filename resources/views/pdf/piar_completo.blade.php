<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<style>

body{
font-family: Arial;
font-size:14px;
margin-top:85px;
margin-bottom:40px;
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
height:auto;
}

.footer{
position:fixed;
bottom:0;
left:0;
right:0;
text-align:center;
}

.footer img{
width:100%;
height:auto;
}

h1{
text-align:center;
}

h2{
background:#eee;
padding:5px;
}

table{
width:100%;
border-collapse:collapse;
margin-bottom:20px;
}

table,th,td{
border:1px solid black;
}

th,td{
padding:6px;
}

</style>

</head>

<body>

<div class="header">
    <img src="{{ public_path('img/credencialesGo.jpg') }}" alt="Encabezado">
</div>

<div class="footer">
    <h4>
    V14.16/02/2018. - Ver documento de instrucciones.
    Ministerio de Educación Nacional - Viceministerio de Educación Preescolar, Básica y Media - Decreto 1421 de 2017
    </h4>
</div>


<h1>PLAN INDIVIDUAL DE AJUSTES RAZONABLES - PIAR - ANEXO 2</h1>

<table>
<tr>
<th>Fecha de Elaboración</th>
<td>{{ $piar->created_at->format('d/m/Y') }}</td>
</tr>
</table>


<h2>Datos del Estudiante</h2>

<table> 

<tr>
<th>Nombre</th>
<td>{{ $piar->student->name }} {{ $piar->student->last_name }}</td>
</tr>

<tr>
<th>Documento</th>
<td>{{ $piar->student->number_documment }}</td>
</tr>

<tr>
<th>Edad</th>
<td>{{ $piar->student->age }} años</td>
</tr>

<tr>
<th>Grado</th>
<td>{{ $piar->student->degree->degree ?? 'Sin Grado Asignado' }}</td>
</tr>

<tr>
<th>Grupo</th>
<td>{{ $piar->student->group->group ?? 'Sin Grupo Asignado' }}</td>
</tr>

</table>


<h2>Características del Estudiante:</h2>

<table>

<tr>
<th>Descripción del estudiante</th>
<td>{{ $piar->characteristics->descripcion_estudiante ?? '' }}</td>
</tr>

<tr>
<th>Gustos e intereses</th>
<td>{{ $piar->characteristics->gustos_intereses ?? '' }}</td>
</tr>

<tr>
<th>Expectativas de la familia</th>
<td>{{ $piar->characteristics->expectativas_familia ?? '' }}</td>
</tr>

<tr>
<th>Habilidades</th>
<td>{{ $piar->characteristics->habilidades ?? '' }}</td>
</tr>

<tr>
<th>Apoyos requeridos</th>
<td>{{ $piar->characteristics->apoyos_requeridos ?? '' }}</td>
</tr>

</table>


<h2>Periodo 1</h2>

<table>

<tr>
<th>Docente</th>
<th>Área</th>
<th>Objetivo</th>
<th>Barrera</th>
<th>Ajuste</th>
<th>Evaluación de los Ajustes</th>
</tr>

@foreach($periodo1 as $row)

<tr>
<td>{{ $row->teacher->name ?? '' }} {{ $row->teacher->last_name ?? '' }}</td>
<td>{{ $row->area }}</td>
<td>{{ $row->objetivo }}</td>
<td>{{ $row->barrera }}</td>
<td>{{ $row->ajuste }}</td>
<td>{{ $row->evaluacion }}</td>
</tr>

@endforeach

</table>


<h2>Periodo 2</h2>

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
<td>{{ $row->teacher->name ?? '' }} {{ $row->teacher->last_name ?? '' }}</td>
<td>{{ $row->area }}</td>
<td>{{ $row->objetivo }}</td>
<td>{{ $row->barrera }}</td>
<td>{{ $row->ajuste }}</td>
<td>{{ $row->evaluacion }}</td>
</tr>

@endforeach

</table>


<h2>Periodo 3</h2>

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
<td>{{ $row->teacher->name ?? '' }} {{ $row->teacher->last_name ?? '' }}</td>
<td>{{ $row->area }}</td>
<td>{{ $row->objetivo }}</td>
<td>{{ $row->barrera }}</td>
<td>{{ $row->ajuste }}</td>
<td>{{ $row->evaluacion }}</td>
</tr>

@endforeach

</table>


</body>
</html>