@extends('layout.masterPage')

@section('css')
    <link rel="stylesheet" type="text/css" href="#">
@endsection

@section('title', 'Añadir Acta')

@section('content')
<div class="flex justify-center p-[2px]">
    <div class="w-[100%] px-4 bg-white rounded-lg shadow-md h-[45rem] overflow-auto">
        <div class="p-4 border-b bg-[white] z-10 sticky top-0 shadow-md w-full ">
            <h1 class="p-3 text-3xl font-bold text-gray-700">Selecciona un estudiante para generar un acta PIAR ó no PIAR.</h1>
            <div class="flex items-center mt-2">
                <form action="#">
                    <input
                        type="search" 
                        name="search"
                        placeholder="Buscar..."
                        class="p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-200"
                    />
                    <button type="submit" class="px-4 py-2 ml-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600 focus:outline-none">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M21 21l-4.35-4.35M16.65 16.65a7 7 0 111.414-1.414l4.35 4.35-1.414 1.414z"
                            />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse table-auto">
                <thead>
                    <tr class="text-sm text-gray-600 uppercase bg-gray-200">
                        <th class="px-4 py-2 border">Nombre completo</th>
                        <th class="px-4 py-2 border">Número de documento</th>
                        <th class="px-4 py-2 border">Grado</th>
                        <th class="px-4 py-2 border">Grupo</th>
                        <th class="px-4 py-2 border">Edad</th>
                        <th class="px-4 py-2 border">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @foreach ($students as $student)
                        <tr class="hover:bg-gray-100">
                            <td class="px-4 py-2 border">{{ $student->name }} {{ $student->last_name }}</td>
                            <td class="px-4 py-2 text-center border">{{ $student->number_documment }}</td>
                            <td class="px-4 py-2 text-center border">{{ $student->degree->degree }}</td>
                            <td class="px-4 py-2 text-center border">{{ $student->group->group }}</td>
                            <td class="px-4 py-2 text-center border">{{ $student->age }}</td>
                            <td class="px-4 py-2 text-center border">
                                <a href="#" class="text-blue-500 hover:underline">Seleccionar</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
{{ $students->links() }} <!-- Para la paginación -->
@endsection