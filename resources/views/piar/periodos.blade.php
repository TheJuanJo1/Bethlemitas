@extends('layout.masterPage')

@section('title','PIAR - Periodos')

@section('content')

<style>

.container-piar{
max-width:900px;
margin:auto;
}

.period-card{
border:2px solid #000;
border-radius:10px;
padding:20px;
margin-bottom:20px;
display:flex;
justify-content:space-between;
align-items:center;
background:#f7f7f7;
}

.period-title{
font-weight:bold;
font-size:18px;
}

.estado{
font-weight:bold;
padding:5px 12px;
border-radius:6px;
}

.completo{
background:#16a34a;
color:white;
}

.pendiente{
background:#dc2626;
color:white;
}

.btn-open{
background:#2563eb;
color:white;
padding:10px 20px;
border:none;
border-radius:6px;
font-weight:bold;
}

.btn-eval{
background:#f59e0b;
color:#111827;
padding:10px 20px;
border:none;
border-radius:6px;
font-weight:bold;
}

.btn-disabled{
opacity:0.55;
cursor:not-allowed;
pointer-events:none;
}

</style>

<button onclick="window.location.href='/addMinutes'" style="background: none; border: none; cursor: pointer;">
        <i class="bi bi-arrow-left" style="font-size: 2rem;"></i>
</button>

<div class="container container-piar">

<h3>PIAR - Periodos Académicos</h3>
<p>
<b>Estudiante:</b> {{ $piar->student->name }} {{ $piar->student->last_name }}
&nbsp;&nbsp; | &nbsp;&nbsp;
<b>Grado:</b> {{ $piar->student->degree->degree ?? 'Sin grado' }}
</p>

{{-- PERIODO 1 --}}
<div class="period-card">

<div>

<div class="period-title">Periodo 1</div>

@if($period1)
<span class="estado completo">Completado</span>
@else
<span class="estado pendiente">Pendiente</span>
@endif

</div>

<div style="display:flex; gap:10px;">

@if($period1)
<a href="{{ route('piar.pdf.periodo1',$piar->id) }}" target="_blank">
<button class="btn-open" style="background:#16a34a;">
Ver PDF
</button>
</a>

<a href="{{ route('piar.evaluacion', [$piar->id, 1]) }}">
<button class="btn-eval">
Subir Evaluación
</button>
</a>
@endif

@if($ready)
    <a href="{{ route('piar.periodo1',$piar->id) }}">
    <button class="btn-open">Abrir</button>
    </a>
@else
    <button class="btn-open btn-disabled">Abrir</button>
@endif

</div>

</div>


{{-- PERIODO 2 --}}
<div class="period-card">

<div>

<div class="period-title">Periodo 2</div>

@if($period2)
<span class="estado completo">Completado</span>
@else
<span class="estado pendiente">Pendiente</span>
@endif

</div>

<div style="display:flex; gap:10px;">

@if($period2)
<a href="{{ route('piar.pdf.periodo2',$piar->id) }}" target="_blank">
<button class="btn-open" style="background:#16a34a;">
Ver PDF
</button>
</a>

<a href="{{ route('piar.evaluacion', [$piar->id, 2]) }}">
<button class="btn-eval">
Subir Evaluación
</button>
</a>
@endif

@if($ready)
    <a href="{{ route('piar.periodo2',$piar->id) }}">
    <button class="btn-open">Abrir</button>
    </a>
@else
    <button class="btn-open btn-disabled">Abrir</button>
@endif

</div>

</div>


{{-- PERIODO 3 --}}
<div class="period-card">

<div>

<div class="period-title">Periodo 3</div>

@if($period3)
<span class="estado completo">Completado</span>
@else
<span class="estado pendiente">Pendiente</span>
@endif

</div>

<div style="display:flex; gap:10px;">

@if($period3)
<a href="{{ route('piar.pdf.periodo3',$piar->id) }}" target="_blank">
<button class="btn-open" style="background:#16a34a;">
Ver PDF
</button>
</a>

<a href="{{ route('piar.evaluacion', [$piar->id, 3]) }}">
<button class="btn-eval">
Subir Evaluación
</button>
</a>
@endif

@if($ready)
    <a href="{{ route('piar.periodo3',$piar->id) }}">
    <button class="btn-open">Abrir</button>
    </a>
@else
    <button class="btn-open btn-disabled">Abrir</button>
@endif

</div>

</div>

</div>

@endsection