@extends('layout.masterPage')

@section('css')
    <link rel="stylesheet" type="text/css" href="#">
@endsection

@section('title', 'Detalles de remisión')

@section('content')

    <div class="p-8 mx-auto bg-[#D5DBDB]">
        <a href=" {{ route('index.student.remitted.psico')}} "><i class="bi bi-arrow-left" style="font-size: 2rem;"></i></a>
        <h1 class="mb-6 text-2xl font-bold text-gray-700">Motivo de remisión del estudiante</h1>
        <form action="{{ route('update.details.referral', $info_student->id) }}" method="POST">
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
                    value="{{ $info_student->name }}"
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
                    value="{{ $info_student->last_name }}"
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
                    value="{{ $info_student->number_documment }}"
                    class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    placeholder="N° documento"
                    min="1"
                    maxlength="13"
                    required
                />
                @error('number_documment')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">El número de documento ya se encuentra registrado  o no tiene la longitud requerida (max 11)</span></p>
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
                            <option value="{{ $degree->id }}" {{ $info_student->id_degree == $degree->id ? 'selected' : '' }} >{{ $degree->degree }}</option>
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
                            <option value="{{ $group->id }}" {{ $info_student->id_group == $group->id ? 'selected' : '' }} >{{ $group->group }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="age" class="block text-sm font-medium text-gray-700">Edad</label>
                    <input
                        type="number"
                        id="age"
                        name="age"
                        value="{{ $info_student->age }}"
                        class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        min="1"
                        placeholder="Edad"
                    />
                </div>
            </div>
        
            <!-- Motivo -->
            <div class="mb-6">
                <label for="reason_referral" class="block text-sm font-medium text-gray-700">Motivo *</label>
                <textarea
                id="reason_referral"
                name="reason_referral"
                rows="3"
                class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                placeholder="Motivo de la remisión..."
                required
                >{{ old('reason_referral', $info_referral->reason) }}</textarea>
            </div>
        
            <!-- Observaciones -->
            <div class="mb-6">
                <label for="observation" class="block text-sm font-medium text-gray-700">Observaciones *</label>
                <textarea
                id="observation"
                name="observation"
                rows="3"
                class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                placeholder="Aspectos familiares, sociales, cognitivos, afectivos, comportamientos especiales del estudiante..."
                required
                >{{ old('observation', $info_referral->observation) }}</textarea>
            </div>
        
            <!-- Estrategias -->
            <div class="mb-6">
                <label for="strategies" class="block text-sm font-medium text-gray-700">Estrategias *</label>
                <textarea
                id="strategies"
                name="strategies"
                rows="3"
                class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                placeholder="Estrategias aplicadas para ayudar al estudiante..."
                required
                >{{ old('strategies', $info_referral->strategies) }}</textarea>
            </div>
        
            <!-- Botón de editar -->
            <div class="text-left">
                <button
                type="submit"
                class="px-4 py-2 font-semibold text-white bg-blue-500 rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                Editar remisión
                </button>
            </div>
        </form>
    </div>

@endsection