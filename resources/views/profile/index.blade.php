@extends('layout.masterPage')

@section('title', 'Mi perfil')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-xl shadow p-8">

    {{-- TÍTULO ARRIBA --}}
    <h2 class="text-2xl font-bold mb-6">
        Mi perfil
    </h2>

    {{-- FOTO DE PERFIL --}}
    @php
        $photoPath = null;
        foreach (['jpg', 'jpeg', 'png'] as $ext) {
            if (file_exists(public_path("Imagenes_Perfil/perfil_{$user->id}.$ext"))) {
                $photoPath = asset("Imagenes_Perfil/perfil_{$user->id}.$ext");
                break;
            }
        }
    @endphp

    <div class="flex items-center gap-6 mb-8">
        <img
            id="previewImage"
            src="{{ $photoPath ?? asset('img/default-user.png') }}"
            class="w-24 h-24 rounded-full object-cover border"
            alt="Foto de perfil">

        <div class="flex flex-col gap-3">

            {{-- SUBIR / REEMPLAZAR FOTO --}}
            <form method="POST"
                  action="{{ route('profile.update.photo') }}"
                  enctype="multipart/form-data"
                  class="flex flex-col gap-3">
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
                    Formatos permitidos: JPG, JPEG, PNG, que no pasen de 2 megas / 2048 kilobytes
                </p>

                @error('photo')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror

                @if (session('success_photo'))
                    <p class="text-sm text-green-600">{{ session('success_photo') }}</p>
                @endif

                <div class="flex gap-2 mt-2">
                    <button class="bg-blue-600 text-white px-4 py-1 rounded hover:bg-blue-700">
                        Guardar foto
                    </button>

                    <a href="{{ route('profile') }}"
                       class="bg-gray-400 text-white px-4 py-1 rounded hover:bg-gray-500">
                        Cancelar
                    </a>
                </div>
            </form>

            {{-- ELIMINAR FOTO --}}
            @if ($photoPath)
            <form method="POST" action="{{ route('profile.delete.photo') }}">
                @csrf
                @method('DELETE')

                <button
                    class="bg-red-600 text-white px-4 py-1 rounded hover:bg-red-700 text-sm w-fit">
                    Eliminar foto
                </button>
            </form>
            @endif
        </div>
    </div>

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

{{-- PREVIEW DE IMAGEN --}}
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
