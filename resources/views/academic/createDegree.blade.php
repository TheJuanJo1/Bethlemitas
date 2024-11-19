@extends('layout.base_structure')

{{-- Crear Grado --}}
@section('first_frame')
<div class="p-4 bg-white rounded-lg shadow-md">
    <h2 class="mb-4 text-xl font-semibold">Crear grado</h2>
    <form action="{{ route('store.degree') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="degree" class="block text-sm font-medium text-gray-700">Grado *</label>
            <input type="text" id="degree" name="degree" class="block w-full px-3 py-2 mt-1 bg-gray-100 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" maxlength="25" value="{{ old('degree') }}">
            @error('degree')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Grado ya existente o el campo se ecuentra vacío.</span></p>
            @enderror
        </div>
        <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600">Guardar</button>
    </form>
</div>
@endsection

{{-- Listar Grados --}}
@section('list_table')
<div class="p-4 overflow-x-auto bg-white rounded-lg shadow-md">
    <h2 class="mb-4 text-xl font-semibold">Lista de grados</h2>
    <div class="flex mb-4">
        <form action="{{ route('create.degree') }}" class="flex mb-4">
            <input type="search" name="search" placeholder="Buscar..." class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md">
            <button type="submit" class="px-3 py-2 ml-2 text-gray-600 bg-gray-300 rounded-md">
                🔍
            </button>
        </form>
    </div>
    <div style="max-height: 530px; overflow-y: auto;">
        <table class="w-full table-auto">
            <thead class="bg-gray-200">
                <tr class="text-left">
                    <th class="px-4 py-2">Grado</th>
                    <th class="px-4 py-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($list_degrees as $degree)
                    <tr>
                        <td class="px-4 py-2 border">{{ $degree->degree }}</td>
                        <td class="px-4 py-2 border">
                            <button type="button" class="text-blue-600 hover:text-blue-800 hover:underline edit_degree"
                                data-id = "{{ $degree->id }}"
                                name-degree = "{{ $degree->degree }}"
                                >
                                Editar
                            </button>  |
                            <form action="{{ route('delete.degree', $degree->id) }}" method="POST" style="display:inline;" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline" onclick="return confirm('¿Estás seguro de eliminar este grado?')">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

{{-- Editar Grados --}}
@section('second_frame')
<div class="hidden p-4 bg-white rounded-lg shadow-md" id="modal_edit_degree">
    <h2 class="mb-4 text-xl font-semibold">Editar grados</h2>
    <form action="{{ route('update.degree') }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" id="degreeId" name="degreeId" value="">
        <div class="mb-4">
            <label for="degree_edit" class="block text-sm font-medium text-gray-700">Grado *</label>
            <input type="text" id="degree_edit" name="degree_edit" class="block w-full px-3 py-2 mt-1 bg-gray-100 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            @error('degree_edit')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">
                    El grado ingresado anteriormente ya se encuentra registrado.</span></p>
            @enderror
        </div>
        
        <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600">Editar</button>
        <button type="button" class="px-4 py-2 text-white bg-gray-500 rounded-md hover:bg-gray-600 button_exit">Cancelar</button>
    </form>
</div>
@endsection