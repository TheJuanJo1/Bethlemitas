@extends('layout.masterPage')

@section('title','PIAR - Periodo 2')

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
background:#2563eb;
color:white;
font-weight:bold;
padding:12px 40px;
border:none;
border-radius:6px;
}

</style>

<button onclick="window.location.href='/piar/5/periodos'" style="background: none; border: none; cursor: pointer;">
        <i class="bi bi-arrow-left" style="font-size: 2rem;"></i>
</button>

<div class="container container-piar">

<h3>PIAR - Ajustes Razonables (Periodo 2)</h3>

<form action="{{ route('piar.periodo2.store') }}" method="POST">

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

<div class="row">

<div class="col-md-2 mb-3">
<label>Área</label>
<select name="area[]" class="form-control">

<option value="">Seleccione área</option>

@foreach($areas as $area)
<option value="{{ $area->name_area }}">{{ $area->name_area }}</option>
@endforeach

</select>
</div>

<div class="col-md-2 mb-3">
<label>Objetivo</label>
<textarea name="objetivo[]" class="form-control" required></textarea>
</div>

<div class="col-md-2 mb-3">
<label>Barrera</label>
<textarea name="barrera[]" class="form-control" required></textarea>
</div>

<div class="col-md-3 mb-3">
<label>Ajuste Razonable</label>
<textarea name="ajuste[]" class="form-control" required></textarea>
</div>

</div>

</div>

<div style="text-align:right">

<button type="submit" class="btn-save">
Guardar Periodo 2
</button>

</div>

</form>

</div>

@endsection