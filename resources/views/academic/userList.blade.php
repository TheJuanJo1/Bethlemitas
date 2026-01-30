@extends('layout.masterPage')
    
@section('title', 'Lista de Usuarios')

@section('content')

{{-- Este diseño es de Tailwind CSS --}}

<div class="p-4">
    <!-- Encabezado de la tabla -->
    <div class="sticky top-0 flex flex-col mb-4 lg:flex-row lg:items-center lg:justify-between">
        <h2 class="text-xl font-semibold">Lista de usuarios</h2>
        <div class="relative">
            <form action="{{ route('index.users') }}" method="GET">
                <input type="search" name="search" class="py-2 pl-4 pr-8 border rounded-lg shadow-sm" placeholder="Buscar...">
                <button type="submit"
                    class="px-4 py-2 font-bold rounded-r bg-purple-white hover:bg-purple-200 text-purple-lighter">
                    <svg version="1.1" class="h-4 text-dark" xmlns="http://www.w3.org/2000/svg"
                        x="0px" y="0px" viewBox="0 0 52.966 52.966">
                        <path d="M51.704,51.273L36.845,35.82c3.79-3.801,6.138-9.041,6.138-14.82
                        c0-11.58-9.42-21-21-21s-21,9.42-21,21s9.42,21,21,21
                        c5.083,0,9.748-1.817,13.384-4.832l14.895,15.491
                        c0.196,0.205,0.458,0.307,0.721,0.307
                        c0.25,0,0.499-0.093,0.693-0.279
                        C52.074,52.304,52.086,51.671,51.704,51.273z
                        M21.983,40c-10.477,0-19-8.523-19-19
                        s8.523-19,19-19s19,8.523,19,19
                        S32.459,40,21.983,40z" />
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Tabla de usuarios -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded-lg shadow-md">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-700 uppercase">Nombre completo</th>
                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-700 uppercase">Número de documento</th>
                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-700 uppercase">Áreas</th>
                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-700 uppercase">Grupos a cargo</th>
                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-700 uppercase">Director de grupo</th>
                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-700 uppercase">Rol</th>
                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-700 uppercase">Acciones</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($users as $user)
                    <tr>  
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $user->name }} {{ $user->last_name }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $user->number_documment }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($user->areas->isNotEmpty())
                                {{ $user->areas->pluck('name_area')->implode(', ') }}
                            @else
                                Sin Áreas
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($user->groups->isNotEmpty())
                                {{ $user->groups->pluck('group')->implode(', ') }}
                            @else
                                Sin Grupos
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ optional($user->director)->group ?? 'Sin Grupo' }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            @if (isset($userRoles[$user->id]) && is_array($userRoles[$user->id]))
                                <span class="badge badge-primary">
                                    {{ implode(', ', $userRoles[$user->id]) }}
                                </span>
                            @else
                                {{ $userRoles[$user->id] ?? 'Sin Rol' }}
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('edit.user', $user->id) }}" class="text-blue-500 hover:underline">
                                Editar
                            </a>
                            <span class="mx-2">|</span>

                            <form action="{{ route('destroy.user', $user->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-red-500 hover:underline"
                                    onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $users->links() }}
    </div>
</div>

@endsection
