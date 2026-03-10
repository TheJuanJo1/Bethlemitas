@extends('layout.masterPage')

@section('title','Crear PIAR')

@section('content')

<style>

/* TEXTAREAS MÁS PEQUEÑOS */
.textarea-small{
height:55px;
resize:none;
}

/* LABEL ARRIBA DEL CAMPO */
.form-label{
display:block;
margin-bottom:6px;
font-weight:600;
}

.container-piar{
max-width:1100px;
margin:auto;
}

/* CAJAS DEL FORMULARIO */
.box-section{
border:2px solid #000;
border-radius:8px;
padding:20px;
margin-bottom:30px;
background:#f7f7f7;
}

/* TITULOS DE SECCION */
.box-title{
background:#2563eb;
color:white;
font-weight:bold;
padding:8px 15px;
border-radius:6px;
margin-bottom:20px;
text-shadow:1px 1px 0 #000;
}

/* INPUTS */
.form-control{
border:2px solid #000;
border-radius:6px;
padding:8px;
width:100%;
}

/* TEXTAREAS */
textarea.form-control{
border:2px solid #000;
}

/* DATOS DEL ESTUDIANTE */
.student-box{
border:2px solid #000;
border-radius:6px;
background:white;
padding:10px;
}

/* BOTON */
.btn-save{
background:#2563eb;
color:white;
font-weight:bold;
padding:12px 40px;
border:none;
border-radius:6px;
transition:0.2s;
}

.btn-save:hover{
background:#1e4ed8;
transform:scale(1.05);
}

/* GRID PARA CARACTERÍSTICAS */
.caracteristicas-grid{
display:grid;
grid-template-columns: repeat(3, 1fr);
gap:15px;
}

/* SEGUNDA FILA */
.caracteristicas-grid .full{
grid-column: span 1;
}

</style>


<div class="container container-piar">

<form action="{{ route('piar.store') }}" method="POST">

@csrf

<input type="hidden" name="student_id" value="{{ $student->id }}">


<!-- INFORMACIÓN GENERAL -->

<div class="box-section">

<div class="box-title">
Información General
</div>

<div style="display:grid; grid-template-columns:repeat(3,1fr); gap:15px;">

<div>
<label class="form-label">Institución</label>
<input type="text" name="institution" class="form-control" required>
</div>

<div>
<label class="form-label">Sede</label>
<input type="text" name="sede" class="form-control">
</div>

<div>
<label class="form-label">Jornada</label>
<input type="text" name="jornada" class="form-control">
</div>

</div>

</div>



<!-- DATOS DEL ESTUDIANTE -->

<div class="box-section">

<div class="box-title">
Datos del Estudiante
</div>

<div style="display:grid; grid-template-columns:repeat(3,1fr); gap:15px;">

<div class="student-box">
<b>Nombre</b><br>
{{ $student->name }} {{ $student->last_name }}
</div>

<div class="student-box">
<b>Documento</b><br>
{{ $student->number_documment }}
</div>

<div class="student-box">
<b>Grado</b><br>
{{ $student->degree->name ?? 'No asignado' }}
</div>

</div>

</div>



<!-- CARACTERÍSTICAS -->

<div class="box-section">

<div class="box-title">
Características del Estudiante
</div>

<div class="caracteristicas-grid">

<div>
<label class="form-label">Descripción del estudiante</label>
<textarea name="descripcion" class="form-control textarea-small"></textarea>
</div>

<div>
<label class="form-label">Gustos e intereses</label>
<textarea name="gustos" class="form-control textarea-small"></textarea>
</div>

<div>
<label class="form-label">Expectativas de la familia</label>
<textarea name="expectativas" class="form-control textarea-small"></textarea>
</div>

<div>
<label class="form-label">Habilidades</label>
<textarea name="habilidades" class="form-control textarea-small"></textarea>
</div>

<div>
<label class="form-label">Apoyos requeridos</label>
<textarea name="apoyos" class="form-control textarea-small"></textarea>
</div>

</div>

</div>



<!-- BOTON GUARDAR -->

<div style="text-align:right; margin-bottom:40px;">

<button type="submit" class="btn-save">
Guardar PIAR
</button>

</div>

</form>

</div>

@endsection