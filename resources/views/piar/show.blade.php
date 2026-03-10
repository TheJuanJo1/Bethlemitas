@extends('layout.masterPage')

@section('title','Ver PIAR')

@section('content')

<div class="container">

<h2>PIAR del estudiante</h2>

<p><b>Estudiante:</b> {{ $piar->student->name }}</p>

<p><b>Grado:</b> {{ $piar->student->degree }}</p>

<hr>

<h4>Características</h4>

<p>{{ $piar->characteristics->descripcion_estudiante }}</p>

<hr>

<h4>Ajustes razonables</h4>

<table class="table">

<thead>

<tr>

<th>Periodo</th>
<th>Área</th>
<th>Objetivo</th>
<th>Barrera</th>
<th>Ajuste</th>
<th>Evaluación</th>

</tr>

</thead>

<tbody>

@foreach($piar->adjustments as $adj)

<tr>

<td>{{ $adj->period }}</td>

<td>{{ $adj->area }}</td>

<td>{{ $adj->objetivo }}</td>

<td>{{ $adj->barrera }}</td>

<td>{{ $adj->ajuste }}</td>

<td>{{ $adj->evaluacion }}</td>

</tr>

@endforeach

</tbody>

</table>

<a href="{{ route('piar.pdf',$piar->id) }}" class="btn btn-danger">

Generar PDF

</a>

</div>

@endsection