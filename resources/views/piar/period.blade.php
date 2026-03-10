@extends('layout.masterPage')

@section('title','Ajustes Razonables')

@section('content')

<div class="container">

<h2>Ajustes Razonables - Periodo {{ $period }}</h2>

<form action="{{ route('piar.adjustment.store') }}" method="POST">

@csrf

<input type="hidden" name="piar_id" value="{{ $piar->id }}">
<input type="hidden" name="period" value="{{ $period }}">

<table class="table table-bordered">

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

@foreach($areas as $area)

<tr>

<td>

{{ $area }}

<input type="hidden" name="area[]" value="{{ $area }}">

</td>

<td>

<textarea name="objetivo[]" class="form-control"></textarea>

</td>

<td>

<textarea name="barrera[]" class="form-control"></textarea>

</td>

<td>

<textarea name="ajuste[]" class="form-control"></textarea>

</td>

<td>

<textarea name="evaluacion[]" class="form-control"></textarea>

</td>

</tr>

@endforeach

</tbody>

</table>

<button class="btn btn-success">Guardar Ajustes</button>

</form>

</div>

@endsection