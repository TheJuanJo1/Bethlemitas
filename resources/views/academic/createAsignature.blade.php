@extends('layout.base_structure')

{{-- Crear Asignatura --}}
@section('first_frame')
<div class="p-4 bg-white rounded-lg shadow-md">
    <h2 class="mb-4 text-xl font-semibold">Crear Asignatura</h2>
    <form action="{{ route('store.asignature') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="asignature" class="block text-sm font-medium text-gray-700">Asignatura *</label>
            <input type="text" id="asignature" name="asignature" class="block w-full px-3 py-2 mt-1 bg-gray-100 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" maxlength="50" value="{{ old('asignature') }}">
            @error('asignature')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Asignatura ya existente o el campo se ecuentra vacío.</span></p>
            @enderror
        </div>
        <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600">Guardar</button>
    </form>
</div>
@endsection

{{-- Listar Asignaturas --}}
@section('list_table')
<div class="p-4 overflow-x-auto bg-white rounded-lg shadow-md">
    <h2 class="mb-4 text-xl font-semibold">Lista de grados</h2>
    <div class="flex mb-4">
        <form action="{{ route('create.asignature') }}" class="flex mb-4">
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
                    <th class="px-4 py-2">Asignatura</th>
                    <th class="px-4 py-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($list_asignatures as $asignature)
                    <tr>
                        <td class="px-4 py-2 border">{{ $asignature->name_asignature }}</td>
                        <td class="px-4 py-2 border">
                            <button type="button" class="text-blue-600 hover:text-blue-800 hover:underline edit_asignature"
                                data-id = "{{ $asignature->id }}"
                                name-asignature = "{{ $asignature->name_asignature }}"
                                >
                                Editar
                            </button>  |
                            <form action="{{ route('delete.asignature', $asignature->id) }}" method="POST" style="display:inline;" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline" onclick="return confirm('¿Estás seguro de eliminar esta asignatura?')">
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

{{-- Editar Asignaturas --}}
@section('second_frame')
<div class="hidden p-4 bg-white rounded-lg shadow-md" id="modal_edit_asignature">
    <h2 class="mb-4 text-xl font-semibold">Editar Asignaturas</h2>
    <form action="{{ route('update.asignature') }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" id="asignatureId" name="asignatureId" value="">
        <div class="mb-4">
            <label for="asignature_edit" class="block text-sm font-medium text-gray-700">Asignatura *</label>
            <input type="text" id="asignature_edit" name="asignature_edit" class="block w-full px-3 py-2 mt-1 bg-gray-100 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            @error('asignature_edit')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">
                    La asignatura ingresada anteriormente ya se encuentra registrada.</span></p>
            @enderror
        </div>
        
        <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600">Editar</button>
        <button type="button" class="px-4 py-2 text-white bg-gray-500 rounded-md hover:bg-gray-600 button_exit">Cancelar</button>
    </form>
</div>
@endsection