@extends('layout.masterPage')

@section('title', 'Mi perfil')

@section('content')

@php
    $photoPath = null;
    foreach (['jpg', 'jpeg', 'png'] as $ext) {
        if (file_exists(public_path("Imagenes_Perfil/perfil_{$user->number_documment}.$ext"))) {
            $photoPath = asset("Imagenes_Perfil/perfil_{$user->number_documment}.$ext");
            break;
        }
    }
@endphp

<div class="max-w-6xl bg-white rounded-xl shadow p-10 ml-6">

    {{-- TÍTULO --}}
    <h2 class="text-2xl font-bold mb-8">
        Mi perfil
    </h2>

    {{-- FOTO DE PERFIL --}}
    <div class="flex flex-col lg:flex-row items-start gap-10 mb-10">

        {{-- FOTO --}}
        <div class="flex flex-col items-center gap-4">
            <img
                id="previewImage"
                src="{{ $photoPath ?? asset('img/default-user.png') }}"
                class="w-32 h-32 rounded-full object-cover border shadow"
                alt="Foto de perfil">

            @if ($photoPath)
            <form method="POST" action="{{ route('profile.delete.photo') }}">
                @csrf
                @method('DELETE')
                <button class="bg-red-600 text-white px-4 py-1 rounded hover:bg-red-700 text-sm">
                    Eliminar foto
                </button>
            </form>
            @endif
        </div>

        {{-- FORMULARIO FOTO --}}
        <div class="flex-1">
            <form method="POST"
                  action="{{ route('profile.update.photo') }}"
                  enctype="multipart/form-data"
                  class="flex flex-col gap-4">
                @csrf
                @method('PUT')

                <label class="cursor-pointer inline-block bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 text-sm w-fit">
                    Seleccionar foto
                    <input
                        type="file"
                        name="photo"
                        accept="image/*"
                        required
                        class="hidden"
                        onchange="previewPhoto(event)">
                </label>

                <p class="text-xs text-gray-500">
                    JPG, JPEG o PNG — máximo 2 MB
                </p>

                @error('photo')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror

                <div class="flex gap-3">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Guardar foto
                    </button>

                    <a href="{{ route('profile') }}"
                       class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- DATOS DEL USUARIO --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-10">

        <div>
            <p class="font-semibold text-gray-700">Nombre</p>
            <p class="text-lg">{{ $user->name }}</p>
        </div>

        <div>
            <p class="font-semibold text-gray-700">Apellidos</p>
            <p class="text-lg">{{ $user->last_name }}</p>
        </div>

        {{-- ACTUALIZAR CORREO --}}
        <div class="md:col-span-2">
            <p class="font-semibold text-gray-700 mb-3">
                Actualizar correo
            </p>

            <form method="POST"
                  action="{{ route('profile.update.email') }}"
                  class="grid grid-cols-1 lg:grid-cols-4 gap-4 items-end max-w-5xl">
                @csrf
                @method('PUT')

                <div class="lg:col-span-2">
                    <label class="text-sm text-gray-600">Correo</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        class="border rounded px-3 py-2 w-full"
                        required>
                </div>

                <div>
                    <label class="text-sm text-gray-600">Contraseña</label>
                    <input
                        type="password"
                        name="password"
                        class="border rounded px-3 py-2 w-full"
                        required>
                </div>

                <button class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 h-fit">
                    Guardar
                </button>
            </form>

            @error('email')
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
            @enderror

            @error('password')
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- ROLES --}}
        <div>
            <p class="font-semibold text-gray-700">Cargo</p>
            <p class="capitalize">{{ implode(', ', $roles) }}</p>
        </div>

        {{-- GRADOS --}}
        <div class="md:col-span-2">
            <p class="font-semibold text-gray-700">Grupos / grados a cargo</p>

            @if ($degrees->count())
                <ul class="list-disc ml-6 mt-2">
                    @foreach ($degrees as $degree)
                        <li>{{ $degree }}</li>
                    @endforeach
                </ul>
            @else
                <p class="italic text-gray-500">No tiene grados asignados</p>
            @endif
        </div>

        {{-- DIRECTOR DE GRUPO --}}
        <div class="md:col-span-2">
            <p class="font-semibold text-gray-700">Director de grupo</p>

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

{{-- PREVIEW FOTO --}}
<script>
function previewPhoto(event) {
    const reader = new FileReader();
    reader.onload = function () {
        document.getElementById('previewImage').src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>

@endsection
