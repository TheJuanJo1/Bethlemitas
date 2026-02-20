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

        <form id="filterForm" action="{{ route('index.users') }}" method="GET" class="flex gap-2">

            <input
                id="searchInput"
                type="search"
                name="search"
                class="py-2 pl-4 pr-3 border rounded-lg shadow-sm focus:outline-none"
                placeholder="Buscar..."
                value="{{ request('search') }}"
            >

            <select id="estadoFilter"
                    name="estado"
                    class="px-3 py-2 border rounded-lg shadow-sm focus:outline-none">

                <option value="todos" {{ request('estado') == 'todos' ? 'selected' : '' }}>
                    Todos
                </option>

                <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>
                    Activos
                </option>
            </select>
        </form>
    </div>

    <!-- TABLA (IMPORTANTE ID PARA AJAX) -->
    <div id="tableContainer" class="overflow-x-auto">
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
                        <td class="px-6 py-4">{{ $user->name }} {{ $user->last_name }}</td>
                        <td class="px-6 py-4">{{ $user->number_documment }}</td>

                        <td class="px-6 py-4">
                            @if ($user->hasRole('docente') && $user->areas->isNotEmpty())
                                {{ $user->areas->pluck('name_area')->implode(', ') }}
                            @else
                                <span class="text-gray-400">Sin Áreas</span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            @if ($user->hasRole('docente') && $user->groups->isNotEmpty())
                                {{ $user->groups->pluck('group')->implode(', ') }}
                            @elseif ($user->hasRole('psicoorientador') && $user->loadDegrees->isNotEmpty())
                                {{ $user->loadDegrees->pluck('degree.degree')->filter()->implode(', ') }}
                            @else
                                <span class="text-gray-400">Sin Asignación</span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            {{ optional($user->director)->group ?? 'Sin Grupo' }}
                        </td>

                        <td class="px-6 py-4">
                            @if (!empty($userRoles[$user->id]))
                                {{ is_array($userRoles[$user->id])
                                    ? implode(', ', $userRoles[$user->id])
                                    : $userRoles[$user->id] }}
                            @else
                                Sin Rol
                            @endif
                        </td>

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

{{-- SCRIPT AJAX PRO --}}
<script>
    const loader = document.getElementById('loader');
    const content = document.getElementById('content');
    const form = document.getElementById('filterForm');
    const estadoFilter = document.getElementById('estadoFilter');
    const searchInput = document.getElementById('searchInput');
    const tableContainer = document.getElementById('tableContainer');

    function showLoader() {
        loader.style.display = 'flex';
        loader.classList.remove('opacity-0');
    }

    function hideLoader() {
        loader.classList.add('opacity-0');
        setTimeout(() => loader.style.display = 'none', 300);
    }

    function fetchUsers(url = null) {
        showLoader();

        const formData = new FormData(form);
        const params = new URLSearchParams(formData).toString();
        const fetchUrl = url ? url : `{{ route('index.users') }}?${params}`;

        fetch(fetchUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTable = doc.getElementById('tableContainer');
                tableContainer.innerHTML = newTable.innerHTML;
                hideLoader();
            })
            .catch(() => hideLoader());
    }

    estadoFilter.addEventListener('change', () => fetchUsers());

    let debounceTimer;
    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => fetchUsers(), 600);
    });

    form.addEventListener('submit', e => {
        e.preventDefault();
        fetchUsers();
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.pagination a')) {
            e.preventDefault();
            fetchUsers(e.target.closest('a').href);
        }
    });

    window.addEventListener('load', () => {
        loader.classList.add('opacity-0');
        setTimeout(() => {
            loader.style.display = 'none';
            content.classList.remove('opacity-0');
        }, 500);
    });
</script>

@endsection