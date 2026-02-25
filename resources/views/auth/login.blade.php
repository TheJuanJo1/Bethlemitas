@extends('layoutLogin')

@section('title', 'Login')

@section('contentLogin')

<style>
    /* Fade + scale splash */
    #splash {
        animation: splashIn 0.6s ease-out forwards;
    }

    .splash-hide {
        animation: splashOut 0.6s ease-in forwards;
    }

    @keyframes splashIn {
        from {
            opacity: 0;
            transform: scale(0.96);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes splashOut {
        from {
            opacity: 1;
            transform: scale(1);
        }
        to {
            opacity: 0;
            transform: scale(1.05);
        }
    }

    /* Texto respirando */
    .loading-text {
        animation: fadeText 1.6s ease-in-out infinite;
    }

    @keyframes fadeText {
        0%, 100% { opacity: 0.4; }
        50% { opacity: 1; }
    }

    /* Login entrada suave */
    .login-show {
        animation: loginIn 0.7s ease-out forwards;
    }

    @keyframes loginIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

{{-- SPLASH --}}
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
        class="loading-text text-white text-lg font-semibold tracking-wide mb-4"
        style="text-shadow:
            -1px -1px 0 #000,
             1px -1px 0 #000,
            -1px  1px 0 #000,
             1px  1px 0 #000;">
        Cargando sistema...
    </p>

    {{-- BARRA --}}
    <div class="w-64 h-2 bg-gray-300 rounded-full overflow-hidden border border-black">
        <div id="progressBar"
             class="h-full bg-blue-600 rounded-full transition-[width] duration-200 ease-linear"
             style="width: 0%">
        </div>
    </div>
</div>

{{-- LOGIN --}}
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

            @if($errors->has('blocked'))
                <div class="px-4 py-3 text-yellow-800 bg-yellow-100 border border-yellow-500 rounded animate-pulse">
                    <strong>Cuenta suspendida:</strong>
                    {{ $errors->first('blocked') }}
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
                    class="mt-2 block w-full rounded-md px-2 py-1.5 text-black"
                    style="background-color:#0000004f"
                >
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <label class="block text-sm font-medium text-black">
                        Contraseña
                    </label>

                    <a href="{{ route('password.request') }}"
                       class="text-sm font-semibold text-blue-700 underline hover:text-blue-900">
                        ¿Olvidaste la contraseña?
                    </a>
                </div>

                <input
                    name="password"
                    type="password"
                    required
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

{{-- SCRIPT --}}
<script>
    let progress = 0;
    const bar = document.getElementById('progressBar');
    const splash = document.getElementById('splash');
    const login = document.getElementById('loginContent');

    const interval = setInterval(() => {
        progress += Math.random() * 8; // avance irregular natural
        if (progress > 100) progress = 100;
        bar.style.width = progress + '%';

        if (progress >= 100) {
            clearInterval(interval);

            splash.classList.add('splash-hide');

            setTimeout(() => {
                splash.classList.add('hidden');
                login.classList.remove('hidden');
                login.classList.add('login-show');
            }, 600);
        }
    }, 120);
</script>

@endsection