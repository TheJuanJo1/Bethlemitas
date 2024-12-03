@extends('layout.masterPage')

@section('css')
    <link rel="stylesheet" type="text/css" href="#">
@endsection

@section('title', 'Editar Estudiante')

@section('content')
    <div class="p-8 mx-auto bg-[#D5DBDB]">
        <a href=" {{ route('index.student.remitted')}} "><i class="bi bi-arrow-left" style="font-size: 2rem;"></i></a>
        <h1 class="mb-6 text-2xl font-bold text-gray-700">Editar estudiante</h1>
        <form action="{{ route('update.student', $student->id) }}" method="POST">
            @csrf
            @method('PUT')
            <!-- Nombres, Apellidos y Documento -->
            <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-3">
                <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nombres *</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ $student->name }}"
                    class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    maxlength="50"
                    placeholder="Nombres"
                    required
                />
                </div>
                <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700">Apellidos *</label>
                <input
                    type="text"
                    id="last_name"
                    name="last_name"
                    value="{{ $student->last_name }}"
                    class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Apellidos"
                    required
                />
                </div>
                <div>
                <label for="number_documment" class="block text-sm font-medium text-gray-700">N° documento *</label>
                <input
                    type="number"
                    id="number_documment"
                    name="number_documment"
                    value="{{ $student->number_documment }}"
                    class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    placeholder="N° documento"
                    min="1"
                    maxlength="13"
                    required
                />
                @error('number_documment')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">El número de documento ya se encuentra registrado</span></p>
                @enderror
                </div>
            </div>
        
            <!-- Grado, Grupo y Edad -->
            <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-3">
                <div>
                    <label for="degree" class="block text-sm font-medium text-gray-700">Grado *</label>
                    <select
                        id="degree"
                        name="degree"
                        class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        required
                    >
                        <option value="">Seleccionar</option>
                        @foreach ($degrees as $degree)
                            <option value="{{ $degree->id }}" {{ $student->id_degree == $degree->id ? 'selected' : '' }} >{{ $degree->degree }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="group" class="block text-sm font-medium text-gray-700">Grupo *</label>
                    <select
                        id="group"
                        name="group"
                        class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        required
                    >
                        <option value="">Seleccionar</option>
                        @foreach ($groups as $group)
                            <option value="{{ $group->id }}" {{ $student->id_group == $group->id ? 'selected' : '' }} >{{ $group->group }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="age" class="block text-sm font-medium text-gray-700">Edad</label>
                    <input
                        type="number"
                        id="age"
                        name="age"
                        value="{{ $student->age }}"
                        class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        min="1"
                        placeholder="Edad"
                    />
                </div>
            </div>

            <!-- Botón de envío -->
            <div class="text-left">
                <button
                type="submit"
                class="px-4 py-2 font-semibold text-white bg-blue-500 rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                Editar
                </button>
            </div>
        </form>
    </div>
@endsection