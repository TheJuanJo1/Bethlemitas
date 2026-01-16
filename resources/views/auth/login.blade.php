@extends('layoutLogin')

@section('title', 'Login')

@section('contentLogin')


    <div class="flex flex-col justify-center px-6 py-12 lg:px-8 w-full max-w-md h-auto bg-[#747272a7] backdrop-filter backdrop-blur-[0.8px] rounded-lg" >
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
          <h2 class="px-1 mt-10 text-3xl leading-9 tracking-tight text-center text-white font-small border-black">Iniciar sesión</h2>
        </div>
      
        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
          <form class="space-y-6" action="{{ route('authenticate') }}" method="POST">
            @csrf
            @if($errors->has('invalid_credentials'))
                <div class="relative px-4 py-3 text-red-700 bg-red-100 border border-red-400 rounded" role="alert">
                    <strong class="font-bold">Error: </strong>
                    <span class="block sm:inline">{{ $errors->first('invalid_credentials') }}</span>
                </div>
            @endif
            <div>
              <label for="number_documment" class="block text-sm font-medium leading-6 text-black border-white">Número de documento</label>
              <div class="mt-2">
                <input id="number_documment" name="number_documment" type="number_documment" autocomplete="number_documment"  placeholder="Número de documento" required class="block w-full rounded-md border-0 px-2 py-1.5 text-black shadow-sm   placeholder:text-black sm:text-sm sm:leading-6" style="background-color: #0000004f">
              </div>
            </div>
      
            <div>
              <div class="flex items-center justify-between">
                <label for="password" class="block text-sm font-medium leading-6 text-black border-white">Contraseña</label>
                <div class="text-sm">
                  <a href="{{ route('password.request') }}" 
                    class="font-semibold text-[#2a56be] underline hover:text-[#0339fc]">
                   ¿Olvidaste la contraseña?
                  </a>

                </div>
              </div>
              <div class="mt-2">
                <input id="password" name="password" type="password" autocomplete="current-password" placeholder="Contraseña" required class="block w-full rounded-md border-0 px-2 py-1.5 text-black shadow-sm   placeholder:text-black sm:text-sm sm:leading-6" style="background-color: #0000004f">
              </div>
            </div>
      
            <div>
              <button type="submit" class="flex w-full justify-center rounded-md bg-[#657eb471] px-3 py-1.5 text-sm font-semibold leading-6 text-black shadow-sm hover:bg-[#323d56be] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Ingrear</button>
            </div>
          </form>
        </div>
    </div>
@endsection