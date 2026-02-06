@extends('layoutLogin')

@section('title', 'Login')

@section('contentLogin')

{{-- SPLASH / CARGA INICIAL --}}
<div id="splash"
     class="fixed inset-0 z-50 flex flex-col items-center justify-center
            bg-white/70 backdrop-blur-md">

    {{-- LOGO --}}
    <img
        src="{{ asset('img/logo.png') }}"
        alt="Logo"
        class="w-32 h-auto mb-6 animate-pulse"
    >

    {{-- TEXTO --}}
    <p
        class="text-white text-lg font-semibold tracking-wide mb-4"
        style="text-shadow:
            -1px -1px 0 #000,
             1px -1px 0 #000,
            -1px  1px 0 #000,
             1px  1px 0 #000;">.
    </p>

    {{-- BARRA DE PROGRESO --}}
    <div class="w-64 h-2 bg-gray-300 rounded-full overflow-hidden border border-black">
        <div id="progressBar"
             class="h-full bg-blue-600 rounded-full transition-all duration-300"
             style="width: 0%">
        </div>
    </div>
</div>

{{-- CONTENIDO LOGIN --}}
<div id="loginContent"
     class="hidden flex flex-col justify-center px-6 py-12 lg:px-8
            w-full max-w-md h-auto bg-[#747272a7]
            backdrop-filter backdrop-blur-[0.8px] rounded-lg">

    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
        <h2 class="mt-10 text-3xl text-center text-white">
            Iniciar sesión
        </h2>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
        <form class="space-y-6" action="{{ route('authenticate') }}" method="POST">
            @csrf

            @if($errors->has('invalid_credentials'))
                <div class="px-4 py-3 text-red-700 bg-red-100 border border-red-400 rounded">
                    <strong>Error:</strong>
                    {{ $errors->first('invalid_credentials') }}
                </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-black">
                    Número de documento
                </label>
                <input
                    name="number_documment"
                    type="text"
                    required
                    placeholder="Número de documento"
                    class="mt-2 block w-full rounded-md px-2 py-1.5 text-black"
                    style="background-color:#0000004f"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-black">
                    Contraseña
                </label>
                <input
                    name="password"
                    type="password"
                    required
                    placeholder="Contraseña"
                    class="mt-2 block w-full rounded-md px-2 py-1.5 text-black"
                    style="background-color:#0000004f"
                >
            </div>

            <button
                type="submit"
                class="w-full rounded-md bg-[#657eb471] px-3 py-1.5
                       text-sm font-semibold text-black hover:bg-[#323d56be]">
                Ingresar
            </button>
        </form>
    </div>
</div>

{{-- SCRIPT SPLASH --}}
<script>
    let progress = 0;
    const bar = document.getElementById('progressBar');

    const interval = setInterval(() => {
        progress += 10;
        bar.style.width = progress + '%';

        if (progress >= 100) {
            clearInterval(interval);
            document.getElementById('splash').classList.add('hidden');
            document.getElementById('loginContent').classList.remove('hidden');
        }
    }, 120);
</script>

@endsection