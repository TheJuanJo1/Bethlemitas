@extends('layout.base_structure')

{{-- Crear Grupo --}}
@section('first_frame')
<div class="p-4 bg-white rounded-lg shadow-md">
    <h2 class="mb-4 text-xl font-semibold">Crear grupo</h2>
    <form action="{{ route('store.group') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="grupo" class="block text-sm font-medium text-gray-700">Grupo *</label>
            <input type="text" id="grupo" name="group" class="block w-full px-3 py-2 mt-1 bg-gray-100 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" maxlength="50" value="{{ old('group') }}">
            @error('group')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Grupo ya existente o el campo se ecuentra vacío.</span></p>
            @enderror
        </div>
        <div class="mb-4">
            <label for="asignatures" class="block text-sm font-medium text-gray-700">Asignaturas que se imparten * (manten presionada la tecla Ctrl y seleciona las asignaturas):</label>
            <select id="asignatures" name="asignatures[]" class="block w-full px-3 py-2 mt-1 bg-gray-100 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" multiple>
                <option>Seleccionar</option>
                @foreach ($asignatures as $asignature)
                    <option value="{{ $asignature->id }}" 
                        {{ in_array($asignature->id, old('asignatures', [])) ? 'selected' : '' }}>
                        {{ $asignature->name_asignature }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600">Guardar</button>
    </form>
</div>
@endsection

{{-- Listar Grupos --}}
@section('list_table')
<div class="p-4 overflow-x-auto bg-white rounded-lg shadow-md">
    <h2 class="mb-4 text-xl font-semibold">Lista de grupos</h2>
    <div class="flex mb-4">
        <form class="flex mb-4" action="{{ route('create.group') }}">
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
                    <th class="px-4 py-2">Grupo</th>
                    <th class="px-4 py-2">Asignaturas</th>
                    <th class="px-4 py-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($list_groups as $group)
                    <tr>
                        <td class="px-4 py-2 border">{{ $group->group }}</td>
                        <td class="px-4 py-2 border">
                            @foreach ($group->asignatures as $asignature)
                                {{ $asignature->name_asignature }}@if(!$loop->last), @endif
                            @endforeach
                        </td>
                        <td class="px-4 py-2 border">
                            <button type="button" class="text-blue-600 hover:text-blue-800 hover:underline edit_group"
                                data-id = "{{ $group->id }}"
                                name-group = "{{ $group->group }}"
                                data-asignatures='@json($group->asignatures->pluck("id"))'>
                                Editar
                            </button>  |
                            <form action="{{ route('destroy.group', $group->id) }}" method="POST" style="display:inline;" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline" onclick="return confirm('¿Estás seguro de eliminar este grupo?')">
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

{{-- Editar Grupos --}}
@section('second_frame')
<div class="hidden p-4 bg-white rounded-lg shadow-md" id="modal_edit_group">
    <h2 class="mb-4 text-xl font-semibold">Editar grupo</h2>
    <form action="{{ route('update.group') }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" id="groupId" name="groupId" value="">
        <div class="mb-4">
            <label for="grupo_edit" class="block text-sm font-medium text-gray-700">Grupo *</label>
            <input type="text" id="grupo_edit" name="grupo_edit" class="block w-full px-3 py-2 mt-1 bg-gray-100 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            @error('grupo_edit')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Grupo ya existente o el campo se ecuentra vacío.</span></p>
            @enderror
        </div>
        <div class="mb-4">
            <label for="asignaturas_edit" class="block text-sm font-medium text-gray-700">Asignaturas que se imparten * (manten presionada la tecla Ctrl y seleciona las asignaturas):</label>
            <select id="asignaturas_edit" name="asignaturas_edit[]" class="block w-full px-3 py-2 mt-1 bg-gray-100 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" multiple>
                <option>Seleccionar</option>
                @foreach ($asignatures as $asignature)
                    <option value="{{ $asignature->id }}">
                        {{ $asignature->name_asignature }}
                    </option>
                @endforeach
            </select>
            @error('asignaturas_edit')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Debe de seleccionar minimo un opción.</span></p>
            @enderror
        </div>
        <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600">Editar</button>
        <button type="button" class="px-4 py-2 text-white bg-gray-500 rounded-md hover:bg-gray-600 button_exit">Cancelar</button>
    </form>
</div>
@endsection