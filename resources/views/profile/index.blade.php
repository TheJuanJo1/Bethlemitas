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

<div class="max-w-6xl bg-white rounded-2xl shadow-xl shadow-slate-200/50 p-10 ml-6 border border-slate-100">

    {{-- TÍTULO --}}
    <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight mb-8">
        Mi perfil
    </h2>

    {{-- FOTO DE PERFIL --}}
    <div class="flex flex-col lg:flex-row items-start gap-10 mb-10">

        {{-- FOTO --}}
        <div class="flex flex-col items-center gap-4">
            <img
                id="previewImage"
                src="{{ $photoPath ?? asset('img/default-user.png') }}"
                class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg ring-1 ring-slate-200"
                alt="Foto de perfil">

            @if ($photoPath)
            {{-- SE AÑADIÓ ONSUBMIT PARA CONFIRMACIÓN --}}
            <form method="POST" action="{{ route('profile.delete.photo') }}" onsubmit="return confirmDeletePhoto()">
                @csrf
                @method('DELETE')
                <button class="bg-rose-600 text-white px-4 py-1.5 rounded-xl hover:bg-rose-700 text-xs font-bold transition-all shadow-lg shadow-rose-100">
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

                <label class="cursor-pointer inline-block bg-slate-100 text-slate-700 px-4 py-2 rounded-xl border border-slate-200 hover:bg-slate-200 text-sm font-bold w-fit transition-all">
                    Seleccionar foto
                    <input
                        type="file"
                        name="photo"
                        accept="image/*"
                        required
                        class="hidden"
                        onchange="previewPhoto(event)">
                </label>

                <p class="text-xs text-slate-500 font-medium">
                    JPG, JPEG o PNG — máximo 2 MB
                </p>

                @error('photo')
                    <p class="text-sm text-rose-500 font-medium">{{ $message }}</p>
                @enderror

                <div class="flex gap-3">
                    <button class="bg-indigo-600 text-white px-5 py-2 rounded-xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all text-sm">
                        Guardar foto
                    </button>

                    <a href="{{ route('profile') }}"
                       class="bg-slate-400 text-white px-5 py-2 rounded-xl font-bold hover:bg-slate-500 transition-all text-sm">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- DATOS DEL USUARIO --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-10">

        <div class="space-y-1">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Nombre</p>
            <p class="text-lg font-bold text-slate-700">{{ $user->name }}</p>
        </div>

        <div class="space-y-1">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Apellidos</p>
            <p class="text-lg font-bold text-slate-700">{{ $user->last_name }}</p>
        </div>

        {{-- ACTUALIZAR CORREO --}}
        <div class="md:col-span-2">
            <p class="font-bold text-slate-700 mb-4 text-sm uppercase tracking-wide">
                Actualizar correo
            </p>

            <form method="POST"
                  action="{{ route('profile.update.email') }}"
                  class="grid grid-cols-1 lg:grid-cols-4 gap-4 items-end max-w-5xl">
                @csrf
                @method('PUT')

                <div class="lg:col-span-2">
                    <label class="text-xs font-bold text-slate-500 ml-1">Correo</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        class="block w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all"
                        required>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 ml-1">Contraseña</label>
                    <input
                        type="password"
                        name="password"
                        class="block w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all"
                        required>
                </div>

                <button class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all h-[42px] text-sm">
                    Guardar
                </button>
            </form>

            @error('email')
                <p class="text-sm text-rose-500 font-medium mt-2">{{ $message }}</p>
            @enderror

            @error('password')
                <p class="text-sm text-rose-500 font-medium mt-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- ROLES --}}
        <div class="space-y-1">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Cargo</p>
            <p class="text-lg font-bold text-indigo-600 capitalize">{{ implode(', ', $roles) }}</p>
        </div>

        {{-- GRADOS --}}
        <div class="md:col-span-2">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Grupos / grados a cargo</p>

            @if ($degrees->count())
                <ul class="list-disc ml-6 mt-2 text-slate-600 font-medium space-y-1">
                    @foreach ($degrees as $degree)
                        <li>{{ $degree }}</li>
                    @endforeach
                </ul>
            @else
                <p class="italic text-slate-400 text-sm">No tiene grados asignados</p>
            @endif
        </div>

        {{-- DIRECTOR DE GRUPO --}}
        <div class="md:col-span-2">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Director de grupo</p>

            @if ($directorGroup)
                <p class="text-slate-700">
                    Sí – Grupo 
                    <strong class="text-indigo-600">{{ optional($directorGroup->director)->group ?? 'No es director de grupo' }}</strong>
                </p>
            @else
                <p class="italic text-slate-400 text-sm">No es director de grupo</p>
            @endif
        </div>

    </div>
</div>

{{-- SCRIPTS --}}
<script>
// Función para previsualizar la foto
function previewPhoto(event) {
    const reader = new FileReader();
    reader.onload = function () {
        document.getElementById('previewImage').src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}

// Función de confirmación para eliminar
function confirmDeletePhoto() {
    return confirm('¿Estás seguro de que deseas eliminar tu foto de perfil? Esta acción no se puede deshacer.');
}
</script>

@endsection