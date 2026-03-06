@extends('layout.masterPage')

@section('title', 'Detalle del informe')

@section('content')

<div class="p-8 mx-auto bg-[#D5DBDB]">

    <button onclick="history.back()" style="background: none; border: none; cursor: pointer;">
        <i class="bi bi-arrow-left" style="font-size: 2rem;"></i>
    </button>

    <h1 class="mb-6 text-2xl font-bold text-gray-700">
        Informe de la consulta
    </h1>

    {{-- ================= DATOS DEL ESTUDIANTE ================= --}}
    <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-3">

        <div>
            <label class="block text-sm font-medium text-gray-700">Nombres</label>
            <input type="text"
                   value="{{ $student->name }}"
                   class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm bg-gray-100"
                   disabled />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Apellidos</label>
            <input type="text"
                   value="{{ $student->last_name }}"
                   class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm bg-gray-100"
                   disabled />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">N° documento</label>
            <input type="text"
                   value="{{ $student->number_documment }}"
                   class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm bg-gray-100"
                   disabled />
        </div>
    </div>

    {{-- ================= INFORMACIÓN ACADÉMICA ================= --}}
    <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-3">

        <div>
            <label class="block text-sm font-medium text-gray-700">Grado</label>
            <input type="text"
                   value="{{ $student->degree->degree }}"
                   class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm bg-gray-100"
                   disabled />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Grupo</label>
            <input type="text"
                   value="{{ $report->group_student }}"
                   class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm bg-gray-100"
                   disabled />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Edad</label>
            <input type="text"
                   value="{{ $report->age_student }}"
                   class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm bg-gray-100"
                   disabled />
        </div>
    </div>

    {{-- ================= ESTADO Y TÍTULO ================= --}}
    <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2">

        <div>
            <label class="block text-sm font-medium text-gray-700">Estado</label>
            <input type="text"
                   value="{{ $student->states->state }}"
                   class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm bg-gray-100"
                   disabled />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Título del informe</label>
            <input type="text"
                   value="{{ $report->title_report }}"
                   class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm bg-gray-100"
                   disabled />
        </div>
    </div>

    {{-- ================= MOTIVO ================= --}}
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700">Motivo</label>
        <textarea rows="3"
                  class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm bg-gray-100"
                  disabled>{{ $report->reason_inquiry }}</textarea>
    </div>

    {{-- ================= RECOMENDACIONES ================= --}}
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700">Recomendaciones</label>
        <textarea rows="3"
                  class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm bg-gray-100"
                  disabled>{{ $report->recomendations }}</textarea>
    </div>
    

    {{-- ================= ANEXO PDF ================= --}}
   <div class="mb-6 p-4 bg-white rounded shadow">

        <label class="block text-sm font-medium text-gray-700 mb-2">
            📎 Anexo PDF
        </label>

        @if(!empty($report->annex_one))

            <a href="{{ asset('storage/'.$report->annex_one) }}"
            target="_blank"
            class="text-blue-600 underline hover:text-blue-800">

            Ver Anexo del Informe Psicológico
            </a>

            @else

            <p class="text-gray-500">
            No tiene anexo adjunto.
            </p>

        @endif

    </div>

</div>

@endsection