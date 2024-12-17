@extends('layout.masterPage')

@section('css')
    <link rel="stylesheet" type="text/css" href="#">
@endsection

@section('title', 'Estidiantes en espera')

@section('content')
    <div class="flex justify-center p-[2px]">
        <div class="w-[100%] px-4 bg-white rounded-lg shadow-md h-[45rem] overflow-auto">
            <div class="p-4 border-b bg-[white] z-10 sticky top-0 shadow-md w-full" id="content_title_waiting">
                <h1 class="text-3xl font-bold text-gray-700">Estudiantes en espera</h1>
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
                            <th class="px-4 py-2 border">Detalles</th>
                            <th class="px-4 py-2 border">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @forelse ($students as $student)
                            <tr class="hover:bg-gray-100">
                                <td class="px-4 py-2 border">{{ $student->name }} {{ $student->last_name }}</td>
                                <td class="px-4 py-2 text-center border">{{ $student->number_documment }}</td>
                                <td class="px-4 py-2 text-center border">{{ $student->degree->degree }}</td>
                                <td class="px-4 py-2 text-center border">{{ $student->group->group }}</td>
                                <td class="px-4 py-2 text-center border">{{ $student->age }}</td>
                                <td class="px-4 py-2 text-center border">
                                    <a href="{{ route('show.student.history', $student->id) }}" class="text-blue-500 hover:underline">Ver +</a>
                                </td>
                                <td class="px-4 py-2 text-center border">
                                    <a href="{{ route('edit.student', $student->id) }}" class="text-blue-500 hover:underline">Editar</a> |
                                    <button type="button" class="text-blue-500 acceptButton hover:underline"
                                        data-id = "{{ $student->id }}"
                                    >Aceptar</button> |
                                    <form action="{{ route('discard.student', $student->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="text-red-500 hover:underline" onclick="return confirm('¿Estás seguro de que quieres descartar al estudiante?')">Descartar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-2 text-center border">No se encontraron registros</td>
                            </tr> 
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{ $students->links() }} <!-- Para la paginación -->

    <div 
        id="hiddenContainer" 
        class="fixed inset-0 flex items-center justify-center hidden bg-gray-900 bg-opacity-50"
    >
        <!-- Contenido del modal -->
        <div class="p-8 bg-white rounded-lg shadow-lg">
            <form action="{{ route('accept.student.to.piar') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="studentId" name="studentId" value="">
                <div>
                    <label for="activation_period" class="block text-sm font-medium text-gray-700">Selecciona el periodo de activación del estudiante.</label>
                    <select
                        id="activation_period"
                        name="activation_period"
                        class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        required
                    >
                        <option value="">Seleccionar</option>
                        @foreach ($periods as $period)
                            <option value="{{ $period->id }}" {{ old('activation_period') == $period->id ? 'selected' : '' }} >{{ $period->period }}</option>
                        @endforeach
                    </select>
                    @error('activation_periodt')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">¡Error! Campo vacío.</span></p>
                    @enderror
                </div>
                
                <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600">Aceptar</button>
                <button 
                    id="closeButton" 
                    class="px-4 py-2 mt-4 text-white bg-red-500 rounded-lg hover:bg-red-600 focus:outline-none focus:ring focus:ring-red-300"
                >
                    Cerrar
                </button>
                
            </form>
        </div>
    </div>

@endsection

@section('JS')
    <script src="{{ asset('scripts/acceptStudent.js') }}"></script>
@endsection