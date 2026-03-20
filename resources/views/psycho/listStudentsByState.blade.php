@extends('layout.masterPage')

@section('css')

<link rel="stylesheet" type="text/css" href="#">
@endsection

@section('title', 'Estudiantes')

@section('content')

<div class="flex justify-center p-1">
<div class="w-full bg-white rounded-lg shadow-md max-h-[80vh] overflow-auto">

<!-- HEADER -->

<div class="p-4 border-b bg-white sticky top-0 shadow-md w-full">

<h1 class="text-xl md:text-2xl lg:text-3xl font-bold text-gray-700">
Estudiantes {{ $stateLabel }}
</h1>

<!-- BUSCADOR -->

<div class="flex flex-col md:flex-row md:items-center mt-3 gap-2">

<form action="{{ $route }}" class="flex flex-wrap gap-2">

<input
type="search"
name="search"
value="{{ request('search') }}"
placeholder="Buscar..."
class="p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-200 w-full md:w-auto"
/>

<button
type="submit"
class="px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600"

>

Buscar </button>

</form>

</div>

</div>

<!-- TABLA -->

<div class="overflow-x-auto">

<table class="w-full border-collapse table-auto text-sm md:text-base">

<thead>
<tr class="text-gray-600 uppercase bg-gray-200 text-xs md:text-sm">

<th class="px-3 py-2 border">Nombre</th>

<th class="px-3 py-2 border hidden sm:table-cell">Documento</th>

<th class="px-3 py-2 border">Grado</th>

<th class="px-3 py-2 border hidden md:table-cell">Grupo</th>

<th class="px-3 py-2 border hidden lg:table-cell">Edad</th>

<th class="px-3 py-2 border hidden lg:table-cell">Anexo</th>

<th class="px-3 py-2 border">PIAR</th>

<th class="px-3 py-2 border">Acciones</th>

</tr>
</thead>

<tbody class="text-gray-700">

@forelse ($students as $student)

<tr class="hover:bg-gray-100">

<td class="px-3 py-2 border font-medium">
{{ $student->name }} {{ $student->last_name }}
</td>

<td class="px-3 py-2 text-center border hidden sm:table-cell">
{{ $student->number_documment }}
</td>

<td class="px-3 py-2 text-center border">
{{ $student->degree->degree }}
</td>

<td class="px-3 py-2 text-center border hidden md:table-cell">
{{ $student->group->group ?? 'Sin grupo' }}
</td>

<td class="px-3 py-2 text-center border hidden lg:table-cell">
{{ $student->age }}
</td>

<td class="px-3 py-2 text-center border hidden lg:table-cell">

@if($student->has_annex > 0) <i class="bi bi-check-circle-fill text-green-500 text-xl"></i>
@else <i class="bi bi-x-circle-fill text-red-500 text-xl"></i>
@endif

</td>

<td class="px-3 py-2 text-center border">

<div class="flex flex-col md:flex-row justify-center gap-1">

<a href="{{ route('piar.create', $student->id) }}"
class="px-2 py-1 text-xs text-black bg-yellow-400 rounded hover:bg-yellow-500">
Llenar </a>

@if($student->piar && $student->piar->adjustments()->exists())

<a href="{{ route('piar.psico.ajustes.edit', $student->piar->id) }}"
class="px-2 py-1 text-xs text-white bg-orange-500 rounded hover:bg-orange-600">
Editar </a>

@else

<span class="px-2 py-1 text-xs text-gray-500 bg-gray-200 rounded">
Editar
</span>

@endif

@if($student->piar && $student->piar->characteristics)

<a href="{{ route('piar.pdf',$student->piar->id) }}"
target="_blank"
class="px-2 py-1 text-xs text-white bg-green-600 rounded hover:bg-green-700">
Acta </a>

@else

<span class="px-2 py-1 text-xs text-gray-500 bg-gray-200 rounded">
Acta
</span>

@endif

</div>

</td>

<td class="px-3 py-2 text-center border">

<div class="flex flex-col md:flex-row justify-center gap-1">

<a href="{{ route('details.referral', $student->id) }}"
class="px-3 py-1 text-xs md:text-sm text-white bg-blue-500 rounded hover:bg-blue-600">
Ver </a>

<a href="{{ route('report.student', $student->id) }}"
class="px-3 py-1 text-xs md:text-sm text-white bg-red-500 rounded hover:bg-red-600">
Informe </a>

<a href="{{ route('show.student.history', $student->id) }}"
class="px-3 py-1 text-xs md:text-sm text-white bg-yellow-500 rounded hover:bg-orange-600">
Historial </a>

</div>

</td>

</tr>

@empty

<tr>
<td colspan="8" class="text-center py-4 text-gray-500">
No hay estudiantes para mostrar
</td>
</tr>

@endforelse

</tbody>

</table>

</div>

</div>
</div>

<div class="mt-4">
{{ $students->links() }}
</div>

@endsection
