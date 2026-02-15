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
    <div class="sticky top-0 flex flex-col gap-3 mb-4 lg:flex-row lg:items-center lg:justify-between">
        <h2 class="text-xl font-semibold">Lista de usuarios</h2>

        <form action="{{ route('index.users') }}" method="GET" class="flex gap-2">

            {{-- Buscador --}}
            <input
                type="search"
                name="search"
                class="py-2 pl-4 pr-3 border rounded-lg shadow-sm focus:outline-none"
                placeholder="Buscar..."
                value="{{ request('search') }}"
            >

            {{-- Filtro Estado --}}
            <select name="estado"
                    class="px-3 py-2 border rounded-lg shadow-sm focus:outline-none">

                <option value="todos"
                    {{ request('estado') == 'todos' ? 'selected' : '' }}>
                    Todos
                </option>

                <option value="activo"
                    {{ request('estado') == 'activo' ? 'selected' : '' }}>
                    Activos
                </option>

            </select>

            <button type="submit"
                    class="px-4 py-2 font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                🔍
            </button>
        </form>
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
                    <th class="px-6 py-3 text-xs font-medium text-left uppercase">Estado</th>
                    <th class="px-6 py-3 text-xs font-medium text-left uppercase">Acciones</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse ($users as $user)

                    <tr class="transition {{ $user->id_state == 2 ? 'bg-red-50 hover:bg-red-100' : 'hover:bg-gray-50' }}">

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
                            @if ($user->hasRole('docente') && $user->areas->isNotEmpty())
                                {{ $user->areas->pluck('name_area')->implode(', ') }}
                            @else
                                <span class="text-gray-400">Sin Áreas</span>
                            @endif
                        </td>

                        <!-- Grupos -->
                        <td class="px-6 py-4">
                            @if ($user->hasRole('docente') && $user->groups->isNotEmpty())
                                {{ $user->groups->pluck('group')->implode(', ') }}

                            @elseif ($user->hasRole('psicoorientador') && $user->loadDegrees->isNotEmpty())
                                {{ $user->loadDegrees
                                    ->pluck('degree.degree')
                                    ->filter()
                                    ->implode(', ') }}

                            @else
                                <span class="text-gray-400">Sin Asignación</span>
                            @endif
                        </td>

                        <!-- Director -->
                        <td class="px-6 py-4">
                            {{ optional($user->director)->group ?? 'Sin Grupo' }}
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

                        <!-- Estado -->
                        <td class="px-6 py-4">
                            @if($user->id_state == 1)
                                <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded">
                                    Activo
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded">
                                    Bloqueado
                                </span>
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
                                @method('PUT')

                                @if($user->id_state == 1)
                                    <button type="submit"
                                            class="text-red-600 hover:underline"
                                            onclick="return confirm('¿Estás seguro de bloquear este usuario?')">
                                        Bloquear
                                    </button>
                                @else
                                    <button type="submit"
                                            class="text-green-600 hover:underline"
                                            onclick="return confirm('¿Deseas activar nuevamente este usuario?')">
                                        Desbloquear
                                    </button>
                                @endif
                            </form>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-6 text-center text-gray-500">
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

{{-- SCRIPT LOADER --}}
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