@extends('layout.masterPage')

@section('title','Crear PIAR')

@section('content')

<div class="container">

<h2>Crear PIAR</h2>

<form action="{{ route('piar.store') }}" method="POST">

@csrf

<input type="hidden" name="student_id" value="{{ $student->id }}">

<div class="row">

<div class="col-md-4">
<label>Institución</label>
<input type="text" name="institution" class="form-control" required>
</div>

<div class="col-md-4">
<label>Sede</label>
<input type="text" name="sede" class="form-control">
</div>

<div class="col-md-4">
<label>Jornada</label>
<input type="text" name="jornada" class="form-control">
</div>

</div>

<hr>

<h4>Datos del estudiante</h4>

<p><b>Nombre:</b> {{ $student->name }}</p>

<p><b>Documento:</b> {{ $student->document }}</p>

<p><b>Grado:</b> {{ $student->degree }}</p>

<hr>

<h4>Características del estudiante</h4>

<div class="mb-3">
<label>Descripción del estudiante</label>
<textarea name="descripcion" class="form-control"></textarea>
</div>

<div class="mb-3">
<label>Gustos e intereses</label>
<textarea name="gustos" class="form-control"></textarea>
</div>

<div class="mb-3">
<label>Expectativas familia</label>
<textarea name="expectativas" class="form-control"></textarea>
</div>

<div class="mb-3">
<label>Habilidades</label>
<textarea name="habilidades" class="form-control"></textarea>
</div>

<div class="mb-3">
<label>Apoyos requeridos</label>
<textarea name="apoyos" class="form-control"></textarea>
</div>

<button class="btn btn-primary">Guardar PIAR</button>

</form>

</div>

@endsection