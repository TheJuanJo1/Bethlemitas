@extends('layout.masterPage')

@section('title', 'Mi perfil')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-xl shadow p-8">

    {{-- FOTO DE PERFIL --}}
    <div class="flex items-center gap-6 mb-8">
        <img
            src="{{ $user->signature
                ? asset('storage/' . $user->signature)
                : asset('img/default-user.png') }}"
            class="w-24 h-24 rounded-full object-cover border"
            alt="Foto de perfil">

        <form method="POST"
              action="{{ route('profile.update.photo') }}"
              enctype="multipart/form-data"
              class="flex flex-col gap-2">
            @csrf
            @method('PUT')

            <input type="file" name="photo" accept="image/*" required>

            @error('photo')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror

            @if (session('success_photo'))
                <p class="text-sm text-green-600">{{ session('success_photo') }}</p>
            @endif

            <div class="flex gap-2">
                <button class="bg-blue-600 text-white px-4 py-1 rounded hover:bg-blue-700">
                    Guardar foto
                </button>

                <a href="{{ route('profile') }}"
                   class="bg-gray-400 text-white px-4 py-1 rounded hover:bg-gray-500">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

    {{-- TÍTULO --}}
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

        {{-- EDITAR CORREO --}}
        <div class="md:col-span-2">
            <p class="font-semibold text-gray-700 mb-2">Correo:</p>

            <form method="POST"
                  action="{{ route('profile.update.email') }}"
                  class="flex flex-col gap-3 max-w-md">
                @csrf
                @method('PUT')

                <input
                    type="email"
                    name="email"
                    value="{{ old('email', $user->email) }}"
                    class="border rounded px-3 py-2"
                    required>

                <input
                    type="password"
                    name="password"
                    placeholder="Contraseña"
                    class="border rounded px-3 py-2"
                    required>

                @error('email')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror

                @error('password')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror

                @if (session('success'))
                    <p class="text-sm text-green-600">{{ session('success') }}</p>
                @endif

                <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-fit">
                    Guardar cambios
                </button>
            </form>
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
