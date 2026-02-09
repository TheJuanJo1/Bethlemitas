@extends('layout.masterPage')

@section('title', 'Lista de Usuarios')

@section('content')

{{-- LOADER --}}
<div id="loader"
     class="fixed inset-0 z-50 flex items-center justify-center bg-white transition-opacity duration-500">
    <div class="flex flex-col items-center gap-3">
        <div class="w-12 h-12 border-4 border-purple-300 border-t-purple-700 rounded-full animate-spin"></div>
        <span class="text-sm text-gray-600">Cargando usuarios...</span>
    </div>
</div>

<div id="content" class="p-4 opacity-0 transition-opacity duration-500">

    <!-- Encabezado -->
    <div class="sticky top-0 flex flex-col mb-4 lg:flex-row lg:items-center lg:justify-between">
        <h2 class="text-xl font-semibold">Lista de usuarios</h2>

        <div class="relative">
            <form action="{{ route('index.users') }}" method="GET" class="flex">
                <input
                    type="search"
                    name="search"
                    class="py-2 pl-4 pr-3 border rounded-l-lg shadow-sm focus:outline-none"
                    placeholder="Buscar..."
                    value="{{ request('search') }}"
                >
                <button type="submit"
                        class="px-4 py-2 font-bold bg-ligh-blue-600 text-white rounded-r-lg hover:bg-dark-blue-700">
                    🔍
                </button>
            </form>
        </div>
    </div>

    <!-- Tabla -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded-lg shadow-md">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium text-left uppercase">Nombre completo</th>
                    <th class="px-6 py-3 text-xs font-medium text-left uppercase">Documento</th>
                    <th class="px-6 py-3 text-xs font-medium text-left uppercase">Áreas</th>
                    <th class="px-6 py-3 text-xs font-medium text-left uppercase">Grupos a cargo</th>
                    <th class="px-6 py-3 text-xs font-medium text-left uppercase">Director de grupo</th>
                    <th class="px-6 py-3 text-xs font-medium text-left uppercase">Rol</th>
                    <th class="px-6 py-3 text-xs font-medium text-left uppercase">Acciones</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse ($users as $user)
                    <tr class="hover:bg-gray-50 transition">

                        <!-- Nombre -->
                        <td class="px-6 py-4">
                            {{ $user->name }} {{ $user->last_name }}
                        </td>

                        <!-- Documento -->
                        <td class="px-6 py-4">
                            {{ $user->number_documment }}
                        </td>

                        <!-- Áreas -->
                        <td class="px-6 py-4">
                            @if ($user->hasRole('docente') && $user->areas && $user->areas->isNotEmpty())
                                {{ $user->areas->pluck('name_area')->implode(', ') }}
                            @else
                                <span class="text-gray-400">Sin Áreas</span>
                            @endif
                        </td>

                        <!-- Grupos -->
                        <td class="px-6 py-4">
                            @if ($user->hasRole('docente') && $user->groups && $user->groups->isNotEmpty())
                                {{ $user->groups->pluck('group')->implode(', ') }}

                            @elseif ($user->hasRole('psicoorientador') && $user->loadDegrees)
                                @php
                                    $groups = collect();

                                    foreach ($user->loadDegrees as $load) {
                                        if ($load->degree && $load->degree->groups) {
                                            $groups = $groups->merge($load->degree->groups);
                                        }
                                    }
                                @endphp

                                {{ $groups->isNotEmpty()
                                    ? $groups->unique('id')->pluck('group')->implode(', ')
                                    : 'Sin Grupos' }}
                            @else
                                <span class="text-gray-400">Sin Grupos</span>
                            @endif
                        </td>

                        <!-- Director -->
                        <td class="px-6 py-4">
                            {{ optional(optional($user->director)->group)->group ?? 'Sin Grupo' }}
                        </td>

                        <!-- Rol -->
                        <td class="px-6 py-4">
                            @if (!empty($userRoles[$user->id]))
                                {{ is_array($userRoles[$user->id])
                                    ? implode(', ', $userRoles[$user->id])
                                    : $userRoles[$user->id] }}
                            @else
                                Sin Rol
                            @endif
                        </td>

                        <!-- Acciones -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('edit.user', $user->id) }}"
                               class="text-blue-600 hover:underline">
                                Editar
                            </a>

                            <span class="mx-2">|</span>

                            <form action="{{ route('destroy.user', $user->id) }}"
                                  method="POST"
                                  class="inline">
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="text-red-600 hover:underline"
                                    onclick="return confirm('¿Estás seguro de bloquear este usuario?')">
                                    Bloquear
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-6 text-center text-gray-500">
                            No hay usuarios registrados
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>

{{-- SCRIPT DEL LOADER --}}
<script>
    window.addEventListener('load', () => {
        const loader = document.getElementById('loader');
        const content = document.getElementById('content');

        loader.classList.add('opacity-0');

        setTimeout(() => {
            loader.style.display = 'none';
            content.classList.remove('opacity-0');
        }, 500);
    });
</script>

@endsection