@extends('layout.masterPage')

@section('title', 'Detalle de Remisión')

@section('content')
<div class="max-w-4xl mx-auto bg-white shadow rounded p-6">

    <h2 class="text-2xl font-bold mb-4">Detalle de Remisión</h2>

    <p><strong>Estudiante:</strong>
        {{ $referral->user_student->name }}
        {{ $referral->user_student->last_name }}
    </p>

    <p><strong>Remitido por:</strong>
        {{ $referral->user_teacher->name }}
        {{ $referral->user_teacher->last_name }}
    </p>

    <p><strong>Fecha:</strong> {{ $referral->created_at->format('Y-m-d') }}</p>

    <hr class="my-4">

    <p><strong>Motivo:</strong></p>
    <p class="text-gray-700">{{ $referral->reason }}</p>

    <p class="mt-3"><strong>Observación:</strong></p>
    <p class="text-gray-700">{{ $referral->observation }}</p>

    <p class="mt-3"><strong>Estrategias:</strong></p>
    <p class="text-gray-700">{{ $referral->strategies }}</p>

    <div class="mt-6">
        <a href="{{ url()->previous() }}"
           class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
           Volver
        </a>
    </div>

</div>
@endsection
