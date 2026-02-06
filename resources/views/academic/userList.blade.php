@extends('layout.masterPage')

@section('title', 'Lista de Usuarios')

@section('content')

<div class="p-4">
    <!-- Encabezado -->
    <div class="sticky top-0 flex flex-col mb-4 lg:flex-row lg:items-center lg:justify-between">
        <h2 class="text-xl font-semibold">Lista de usuarios</h2>

        <div class="relative">
            <form action="{{ route('index.users') }}" method="GET">
                <input
                    type="search"
                    name="search"
                    class="py-2 pl-4 pr-8 border rounded-lg shadow-sm"
                    placeholder="Buscar..."
                >
                <button type="submit"
                    class="px-4 py-2 font-bold rounded-r bg-purple-white hover:bg-purple-200 text-purple-lighter">
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
                @foreach ($users as $user)
                    <tr>
                        <!-- Nombre -->
                        <td class="px-6 py-4">
                            {{ $user->name }} {{ $user->last_name }}
                        </td>

                        <!-- Documento -->
                        <td class="px-6 py-4">
                            {{ $user->number_documment }}
                        </td>

                        <!-- Áreas (SOLO DOCENTE) -->
                        <td class="px-6 py-4">
                            @if ($user->hasRole('docente') && $user->areas->isNotEmpty())
                                {{ $user->areas->pluck('name_area')->implode(', ') }}
                            @else
                                Sin Áreas
                            @endif
                        </td>

                        <!-- Grupos -->
                        <td class="px-6 py-4">
                            {{-- DOCENTE --}}
                            @if ($user->hasRole('docente') && $user->groups->isNotEmpty())
                                {{ $user->groups->pluck('group')->implode(', ') }}

                            {{-- PSICOORIENTADOR --}}
                            @elseif ($user->hasRole('psicoorientador'))
                                @php
                                    $groups = collect();

                                    foreach ($user->loadDegrees as $load) {
                                        if ($load->degree) {
                                            $groups = $groups->merge($load->degree->groups);
                                        }
                                    }
                                @endphp

                                {{ $groups->unique('id')->pluck('group')->implode(', ') ?: 'Sin Grupos' }}

                            @else
                                Sin Grupos
                            @endif
                        </td>

                        <!-- Director de grupo -->
                        <td class="px-6 py-4">
                            {{ optional($user->director)->group ?? 'Sin Grupo' }}
                        </td>

                        <!-- Rol -->
                        <td class="px-6 py-4">
                            @if (isset($userRoles[$user->id]) && is_array($userRoles[$user->id]))
                                {{ implode(', ', $userRoles[$user->id]) }}
                            @else
                                {{ $userRoles[$user->id] ?? 'Sin Rol' }}
                            @endif
                        </td>

                        <!-- Acciones -->
                        <td class="px-6 py-4">
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
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>

@endsection