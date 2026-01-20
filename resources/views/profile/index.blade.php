@extends('layout.masterPage')

@section('title', 'Mi perfil')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-xl shadow p-8">

    <h2 class="text-2xl font-bold mb-6 flex items-center">
        <i class="bi bi-person-circle mr-3 text-3xl"></i> Mi perfil
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div>
            <p class="font-semibold text-gray-700">Nombre:</p>
            <p>{{ $user->name }}</p>
        </div>

        <div>
            <p class="font-semibold text-gray-700">Apellidos:</p>
            <p>{{ $user->last_name }}</p>
        </div>

        <div>
            <p class="font-semibold text-gray-700">Correo:</p>
            <p>{{ $user->email }}</p>
        </div>

        <div>
            <p class="font-semibold text-gray-700">Cargo:</p>
            <p class="capitalize">
                {{ implode(', ', $roles) }}
            </p>
        </div>

        <div class="md:col-span-2">
            <p class="font-semibold text-gray-700">Grupos / grados a cargo:</p>

            @if ($degrees->count())
                <ul class="list-disc ml-6">
                    @foreach ($degrees as $degree)
                        <li>{{ $degree }}</li>
                    @endforeach
                </ul>
            @else
                <p class="italic text-gray-500">No tiene grados asignados</p>
            @endif
        </div>

        <div class="md:col-span-2">
            <p class="font-semibold text-gray-700">Director de grupo:</p>

            @if ($directorGroup)
                <p>
                    Sí – Grupo
                    <strong>{{ $directorGroup->group->group }}</strong>
                </p>
            @else
                <p class="italic text-gray-500">No es director de grupo</p>
            @endif
        </div>

    </div>
</div>
@endsection
