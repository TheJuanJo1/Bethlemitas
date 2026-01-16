@extends('layoutLogin')

@section('title', 'Nueva contraseña')

@section('contentLogin')

<div class="flex flex-col justify-center px-6 py-12 lg:px-8 w-full max-w-md bg-[#747272a7] backdrop-filter backdrop-blur rounded-lg">

    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
        <h2 class="mt-6 text-3xl text-center text-white">
            Crear nueva contraseña
        </h2>
        <p class="mt-2 text-center text-sm text-white">
            Ingresa tu nueva contraseña
        </p>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
        <form class="space-y-6" action="{{ route('password.update') }}" method="POST">
            @csrf

            <!-- Token obligatorio -->
            <input type="hidden" name="token" value="{{ $token }}">

            <!-- Correo -->
            <input type="hidden" name="email" value="{{ request()->email }}">

            @if ($errors->any())
                <div class="px-4 py-3 text-red-700 bg-red-100 rounded">
                    <ul class="text-sm">
                        @foreach ($errors->all() as $error)
                            <li>- {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-black">
                    Nueva contraseña
                </label>
                <div class="mt-2">
                    <input type="password" name="password" required
                        placeholder="Nueva contraseña"
                        class="block w-full rounded-md px-2 py-1.5 text-black"
                        style="background-color: #0000004f">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-black">
                    Confirmar contraseña
                </label>
                <div class="mt-2">
                    <input type="password" name="password_confirmation" required
                        placeholder="Confirmar contraseña"
                        class="block w-full rounded-md px-2 py-1.5 text-black"
                        style="background-color: #0000004f">
                </div>
            </div>

            <div>
                <button type="submit"
                    class="flex w-full justify-center rounded-md bg-[#657eb471] px-3 py-1.5 text-sm font-semibold text-black hover:bg-[#323d56be]">
                    Guardar contraseña
                </button>
            </div>
        </form>
    </div>
</div>

@endsection