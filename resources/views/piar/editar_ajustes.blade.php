@extends('layout.masterPage')

@section('title','PIAR - Editar Ajustes Razonables')

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
background:#f97316;
color:#111827;
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
background:#f97316;
color:#111827;
font-weight:bold;
padding:12px 40px;
border:none;
border-radius:6px;
}
.btn-row{
text-align:right;
margin-top:15px;
}
table{
width:100%;
border-collapse:collapse;
}
th, td{
border:1px solid #000;
padding:8px;
background:#fff;
vertical-align:top;
font-size:12px;
}
th{
background:#f3f4f6;
}
</style>

<button onclick="history.back()" style="background: none; border: none; cursor: pointer;">
    <i class="bi bi-arrow-left" style="font-size: 2rem;"></i>
</button>

<div class="container container-piar">
    <h3>PIAR - Editar Ajustes Razonables</h3>

    <div class="box-section">
        <div class="box-title">Datos del Estudiante</div>
        <p>
            <b>Nombre:</b> {{ $piar->student->name }} {{ $piar->student->last_name }}
            &nbsp;&nbsp; | &nbsp;&nbsp;
            <b>Grado:</b> {{ $piar->student->degree->degree ?? 'Sin grado' }}
        </p>
    </div>

    {{-- ================= PERIODO 1 ================= --}}
    <div class="box-section">
        <div class="box-title">Periodo 1</div>

        @if($periodo1->isEmpty())
            <p>No hay ajustes registrados para este periodo.</p>
        @else
            <form action="{{ route('piar.psico.ajustes.store') }}" method="POST">
                @csrf
                <input type="hidden" name="piar_id" value="{{ $piar->id }}">
                <input type="hidden" name="period" value="1">

                @include('piar.partials.tabla_ajustes', ['datos' => $periodo1])

                <div class="btn-row">
                    <button type="submit" class="btn-save">Guardar Periodo 1</button>
                </div>
            </form>
        @endif
    </div>

    {{-- ================= PERIODO 2 ================= --}}
    <div class="box-section">
        <div class="box-title">Periodo 2</div>

        @if($periodo2->isEmpty())
            <p>No hay ajustes registrados para este periodo.</p>
        @else
            <form action="{{ route('piar.psico.ajustes.store') }}" method="POST">
                @csrf
                <input type="hidden" name="piar_id" value="{{ $piar->id }}">
                <input type="hidden" name="period" value="2">

                @include('piar.partials.tabla_ajustes', ['datos' => $periodo2])

                <div class="btn-row">
                    <button type="submit" class="btn-save">Guardar Periodo 2</button>
                </div>
            </form>
        @endif
    </div>

    {{-- ================= PERIODO 3 ================= --}}
    <div class="box-section">
        <div class="box-title">Periodo 3</div>

        @if($periodo3->isEmpty())
            <p>No hay ajustes registrados para este periodo.</p>
        @else
            <form action="{{ route('piar.psico.ajustes.store') }}" method="POST">
                @csrf
                <input type="hidden" name="piar_id" value="{{ $piar->id }}">
                <input type="hidden" name="period" value="3">

                @include('piar.partials.tabla_ajustes', ['datos' => $periodo3])

                <div class="btn-row">
                    <button type="submit" class="btn-save">Guardar Periodo 3</button>
                </div>
            </form>
        @endif
    </div>

</div>

@endsection