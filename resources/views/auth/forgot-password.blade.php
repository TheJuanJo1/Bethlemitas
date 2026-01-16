@extends('layoutLogin')

@section('title', 'Recuperar contraseña')

@section('contentLogin')

<div class="flex flex-col justify-center px-6 py-12 lg:px-8 w-full max-w-md bg-[#747272a7] backdrop-blur rounded-lg">
    
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
        <h2 class="mt-6 text-3xl text-center text-white">
            Recuperar contraseña
        </h2>
        <p class="mt-2 text-center text-sm text-white">
            Ingresa tu correo para recuperar tu contraseña
        </p>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
        <form class="space-y-6" action="{{ route('password.email') }}" method="POST">
            @csrf

            @if (session('status'))
                <div class="px-4 py-3 text-green-700 bg-green-100 rounded">
                    {{ session('status') }}
                </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-black">
                    Correo electrónico
                </label>
                <div class="mt-2">
                    <input type="email" name="email" required
                        placeholder="correo@ejemplo.com"
                        class="block w-full rounded-md px-2 py-1.5 text-black"
                        style="background-color: #0000004f">
                </div>
            </div>

            <div>
                <button type="submit"
                    class="flex w-full justify-center rounded-md bg-[#657eb471] px-3 py-1.5 text-sm font-semibold text-black hover:bg-[#323d56be]">
                    Enviar enlace
                </button>
            </div>
        </form>

        <div class="mt-4 text-center">
            <a href="{{ route('login') }}" class="text-sm text-blue-600 underline">
                Volver al login
            </a>
        </div>
    </div>
</div>

@endsection
