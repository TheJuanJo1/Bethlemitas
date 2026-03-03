@extends('layout.masterPage')

@section('css')
<link rel="stylesheet" type="text/css" href="#">
@endsection

@section('title', 'Añadir informe')

@section('content')

<div class="p-8 mx-auto bg-[#D5DBDB]">

    <button onclick="history.back()" style="background: none; border: none; cursor: pointer;">
        <i class="bi bi-arrow-left" style="font-size: 2rem;"></i>
    </button>

    <h1 class="mb-6 text-2xl font-bold text-gray-700">
        Informe de la consulta
    </h1>

    <form action="{{ route('store.report.student', $info_student->id) }}" 
          method="POST" 
          enctype="multipart/form-data">

        @csrf

        <!-- Nombres, Apellidos y Documento -->
        <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-3">

            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Nombres *
                </label>
                <input type="text" 
                       name="name"
                       value="{{ $info_student->name }}"
                       class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm"
                       maxlength="50" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Apellidos *
                </label>
                <input type="text" 
                       name="last_name"
                       value="{{ $info_student->last_name }}"
                       class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm"
                       required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">
                    N° documento *
                </label>
                <input type="number" 
                       name="number_documment"
                       value="{{ $info_student->number_documment }}"
                       class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm"
                       min="1" required>

                @error('number_documment')
                <p class="mt-2 text-sm text-red-600">
                    El número de documento ya se encuentra registrado
                </p>
                @enderror
            </div>

        </div>


        <!-- Grado, Grupo y Edad -->
        <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-3">

            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Grado *
                </label>
                <select name="degree"
                        class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm"
                        required>
                    <option value="">Seleccionar</option>
                    @foreach ($degrees as $degree)
                        <option value="{{ $degree->id }}"
                            {{ $info_student->id_degree == $degree->id ? 'selected' : '' }}>
                            {{ $degree->degree }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Grupo *
                </label>
                <select name="group"
                        class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm"
                        required>
                    <option value="">Seleccionar</option>
                    @foreach ($groups as $group)
                        <option value="{{ $group->id }}"
                            {{ $info_student->id_group == $group->id ? 'selected' : '' }}>
                            {{ $group->group }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Edad *
                </label>
                <input type="number" 
                       name="age"
                       value="{{ $info_student->age }}"
                       class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm"
                       min="1" required>
            </div>

        </div>


        <!-- Estado y Título -->
        <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2">

            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Estado *
                </label>
                <select name="state"
                        class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm"
                        required>
                    <option value="">Seleccionar</option>
                    @foreach ($states as $state)
                        <option value="{{ $state->id }}">
                            {{ $state->state }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Título de informe *
                </label>
                <input type="text"
                       name="title_report"
                       class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm"
                       placeholder="Título de informe"
                       required>
            </div>

        </div>


        <!-- Subir PDF -->
        <div class="mb-6">

            <label class="block text-sm font-medium text-gray-700">
                Subir ANEXO 1 (PDF)
            </label>

            <input type="file"
                   name="annex_one"
                   id="annex_one"
                   accept="application/pdf"
                   class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm">

            @error('annex_one')
            <p class="mt-2 text-sm text-red-600">
                El archivo debe ser PDF y máximo 2MB.
            </p>
            @enderror

        </div>


        <!-- PREVISUALIZACIÓN -->
        <div id="pdf-preview-container" class="hidden mb-6">

            <div class="flex items-center justify-between mb-2">
                <h2 class="text-lg font-semibold text-gray-700">
                    Previsualización del PDF
                </h2>

                <button type="button"
                        id="remove-pdf"
                        class="px-3 py-1 text-sm font-semibold text-white bg-red-500 rounded hover:bg-red-600">
                    Eliminar PDF
                </button>
            </div>

            <div class="bg-white border rounded shadow">
                <iframe id="pdf-preview"
                        class="w-full rounded"
                        style="height: 350px;">
                </iframe>
            </div>

        </div>


        <!-- Motivo -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700">
                Motivo *
            </label>
            <textarea name="reason_inquiry"
                      rows="3"
                      class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm"
                      required></textarea>
        </div>


        <!-- Recomendaciones -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700">
                Recomendaciones *
            </label>
            <textarea name="recomendations"
                      rows="3"
                      class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm"
                      required></textarea>
        </div>


        <!-- Botón Guardar -->
        <div class="text-left">
            <button type="submit"
                    class="px-4 py-2 font-semibold text-white bg-blue-500 rounded-md shadow-sm hover:bg-blue-600">
                Guardar
            </button>
        </div>

    </form>

</div>


{{-- SCRIPT --}}
<script>

const fileInput = document.getElementById('annex_one');
const previewContainer = document.getElementById('pdf-preview-container');
const previewFrame = document.getElementById('pdf-preview');
const removeBtn = document.getElementById('remove-pdf');

fileInput.addEventListener('change', function(event) {

    const file = event.target.files[0];

    if (file && file.type === "application/pdf") {

        const fileURL = URL.createObjectURL(file);
        previewFrame.src = fileURL;
        previewContainer.classList.remove('hidden');

    } else if (file) {

        alert("Por favor selecciona un archivo PDF válido.");
        fileInput.value = "";
    }

});

removeBtn.addEventListener('click', function() {

    fileInput.value = "";
    previewFrame.src = "";
    previewContainer.classList.add('hidden');

});

</script>

@endsection