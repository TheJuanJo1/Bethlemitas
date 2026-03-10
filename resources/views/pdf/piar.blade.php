<h2>PLAN INDIVIDUAL DE AJUSTES RAZONABLES</h2>

<p>Estudiante: {{ $piar->student->name }}</p>

<p>Documento: {{ $piar->student->document }}</p>

<p>Grado: {{ $piar->student->degree }}</p>

<hr>

<h3>Características</h3>

<p>{{ $piar->characteristics->descripcion_estudiante }}</p>

<hr>

<h3>Ajustes razonables</h3>

@foreach($piar->adjustments as $adj)

<p>

Area: {{ $adj->area }} <br>

Objetivo: {{ $adj->objetivo }} <br>

Barrera: {{ $adj->barrera }} <br>

Ajuste: {{ $adj->ajuste }} <br>

Evaluación: {{ $adj->evaluacion }}

</p>

@endforeach