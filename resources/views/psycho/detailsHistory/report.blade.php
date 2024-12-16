@extends('layout.masterPage')

@section('css')
    <link rel="stylesheet" type="text/css" href="#">
@endsection

@section('title', 'Añadir informe')

@section('content')
    <div class="p-8 mx-auto bg-[#D5DBDB]">
        <h1 class="mb-6 text-2xl font-bold text-gray-700">Informe de la consulta</h1>
        <form action="#" method="POST">
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
                    disabled
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
                    disabled
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
                    disabled
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
                    <input
                        type="text"
                        id="degree"
                        name="degree"
                        value="{{ $student->degree->degree }}"
                        class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Grado"
                        min="1"
                        maxlength="13"
                        disabled
                    />
                </div>
                <div>
                    <label for="group" class="block text-sm font-medium text-gray-700">Grupo *</label>
                    <input
                        type="text"
                        id="group"
                        name="group"
                        value="{{ $report->group_student }}"
                        class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Grupo"
                        min="1"
                        maxlength="13"
                        disabled
                    />
                </div>
                <div>
                    <label for="age" class="block text-sm font-medium text-gray-700">Edad</label>
                    <input
                        type="number"
                        id="age"
                        name="age"
                        value="{{ $report->age_student }}"
                        class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        min="1"
                        placeholder="Edad"
                        disabled
                    />
                </div>
            </div>

            <!-- Estado y título de informe -->
            <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-3">
                <div>
                    <label for="state" class="block text-sm font-medium text-gray-700">Estado *</label>
                    <input
                        type="text"
                        id="state"
                        name="state"
                        value="{{ $student->states->state }}"
                        class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Estado"
                        min="1"
                        maxlength="13"
                        disabled
                    />
                </div>
                <div>
                    <label for="title_report" class="block text-sm font-medium text-gray-700">Título de informe *</label>
                    <input
                        type="text"
                        id="title_report"
                        name="title_report"
                        value="{{ $report->title_report }}"
                        class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Título de informe"
                        disabled
                    />
                </div>
            </div>
        
            <!-- Motivo de consulta -->
            <div class="mb-6">
                <label for="reason_inquiry" class="block text-sm font-medium text-gray-700">Motivo *</label>
                <textarea
                id="reason_inquiry"
                name="reason_inquiry"
                rows="3"
                class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                placeholder="Motivo de la consulta..."
                required
                >{{ $report->reason_inquiry }}</textarea>
            </div>
        
            <!-- Recomendaciones -->
            <div class="mb-6">
                <label for="recomendations" class="block text-sm font-medium text-gray-700">Recomendaciones *</label>
                <textarea
                id="recomendations"
                name="recomendations"
                rows="3"
                class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                placeholder="Recomendaciones para el estudiante..."
                required
                >{{ $report->recomendations }}</textarea>
            </div>
        
            <!-- Botón de guardar -->
            <div class="text-left">
                <button
                type="submit"
                class="px-4 py-2 font-semibold text-white bg-blue-500 rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                Guardar
                </button>
            </div>
        </form>
    </div>
@endsection