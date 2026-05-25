@extends('layout.masterPage')

@section('title', 'Login')

@section('content')
<div class="max-w-md mx-auto mt-10 p-6 bg-white rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Iniciar sesión</h2>
    <form method="POST" action="{{ route('authenticate') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1" for="email">Correo</label>
            <input type="email" name="email" id="email" required class="w-full border rounded px-3 py-2" />
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" for="password">Contraseña</label>
            <input type="password" name="password" id="password" required class="w-full border rounded px-3 py-2" />
        </div>
        <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded">Entrar</button>
    </form>
</div>
@endsection
