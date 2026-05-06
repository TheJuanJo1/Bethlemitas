@extends('layout.masterPage')

@section('title', 'Gestión de Usuarios | PiarManager')

@section('content')

{{-- LOADER CON EFECTO GLASSMORPHISM --}}
<div id="loader" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-50/80 backdrop-blur-sm transition-opacity duration-500">
    <div class="flex flex-col items-center">
        <div class="relative w-16 h-16">
            <div class="absolute inset-0 border-4 border-indigo-100 rounded-full"></div>
            <div class="absolute inset-0 border-4 border-indigo-600 rounded-full border-t-transparent animate-spin"></div>
        </div>
        <span class="mt-4 text-sm font-bold text-indigo-900 tracking-widest uppercase animate-pulse text-center px-4">Cargando Datos</span>
    </div>
</div>

<div id="content" class="p-2 md:p-6 lg:p-10 opacity-0 transition-opacity duration-700 bg-[#f8fafc] min-h-screen">

    <div class="flex flex-col mb-8 gap-6 lg:flex-row lg:items-end lg:justify-between border-b border-slate-200 pb-8">
        <div>
            <nav class="flex mb-2 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                <span>Administración</span>
                <span class="mx-2">/</span>
                <span class="text-indigo-600">Usuarios</span>
            </nav>
            <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">Lista de Usuarios</h2>
            <p class="text-slate-500 text-sm mt-1">Gestiona los permisos y roles del personal educativo.</p>
        </div>

        <form id="filterForm" action="{{ route('index.users') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative group">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input
                    id="searchInput"
                    type="search"
                    name="search"
                    class="block w-full sm:w-72 pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all"
                    placeholder="Nombre o identificación..."
                    value="{{ request('search') }}"
                >
            </div>

            <select id="estadoFilter"
                    name="estado"
                    class="block w-full sm:w-44 px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 cursor-pointer appearance-none transition-all">
                <option value="todos" {{ request('estado') == 'todos' ? 'selected' : '' }}>Todos los estados</option>
                <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Solo Activos</option>
            </select>
        </form>
    </div>

    <div id="tableContainer" class="bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-200/50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs md:text-sm">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-200">
                        <th class="px-4 md:px-6 py-4 font-bold text-slate-500 uppercase tracking-widest">Personal</th>
                        <th class="px-4 md:px-6 py-4 font-bold text-slate-500 uppercase tracking-widest hidden md:table-cell">Asignación</th>
                        <th class="px-4 md:px-6 py-4 font-bold text-slate-500 uppercase tracking-widest text-center hidden sm:table-cell">Rol</th>
                        <th class="px-4 md:px-6 py-4 font-bold text-slate-500 uppercase tracking-widest text-center">Estado</th>
                        <th class="px-4 md:px-6 py-4 font-bold text-slate-500 uppercase tracking-widest text-right">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($users as $user)
                        <tr class="group hover:bg-slate-50/80 transition-all {{ $user->id_state == 2 ? 'bg-red-50/30' : '' }}">
                            
                            <td class="px-4 md:px-6 py-5">
                                <div class="flex items-center gap-3 md:gap-4">
                                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex-shrink-0 flex items-center justify-center text-white font-bold shadow-md shadow-indigo-200 uppercase text-[10px] md:text-sm">
                                        {{ substr($user->name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-xs md:text-sm font-bold text-slate-800 truncate">{{ $user->name }} {{ $user->last_name }}</div>
                                        <div class="text-[10px] md:text-[12px] text-slate-400 font-medium tracking-tight truncate">Doc: {{ $user->number_documment }}</div>
                                        <div class="sm:hidden mt-1">
                                            <span class="inline-flex px-1.5 py-0.5 rounded bg-slate-100 text-[8px] font-black uppercase text-slate-500">
                                                {{ is_array($userRoles[$user->id] ?? null) ? implode(', ', $userRoles[$user->id]) : ($userRoles[$user->id] ?? 'Sin Rol') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 md:px-6 py-5 hidden md:table-cell">
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[9px] font-bold text-slate-400 uppercase min-w-[40px]">Áreas:</span>
                                        <span class="text-[11px] text-slate-600 font-medium truncate max-w-[200px]" title="{{ $user->areas->unique('id')->pluck('name_area')->implode(', ') }}">
                                            {{ $user->hasRole('docente') && $user->areas->isNotEmpty() ? $user->areas->unique('id')->pluck('name_area')->implode(', ') : '---' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[9px] font-bold text-slate-400 uppercase min-w-[40px]">Grupos:</span>
                                        <span class="text-[11px] text-slate-600 truncate max-w-[200px]">
                                            @if ($user->hasRole('docente') && $user->groups->isNotEmpty())
                                                {{ $user->groups->pluck('group')->implode(', ') }}
                                            @elseif ($user->hasRole('psicoorientador') && $user->loadDegrees->isNotEmpty())
                                                {{ $user->loadDegrees->pluck('degree.degree')->filter()->implode(', ') }}
                                            @else
                                                <span class="text-slate-300 italic">Sin asignación</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 md:px-6 py-5 text-center hidden sm:table-cell">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200 uppercase tracking-tight">
                                    {{ is_array($userRoles[$user->id] ?? null) ? implode(', ', $userRoles[$user->id]) : ($userRoles[$user->id] ?? 'Sin Rol') }}
                                </span>
                            </td>

                            <td class="px-4 md:px-6 py-5 text-center">
                                @if($user->id_state == 1)
                                    <span class="inline-flex items-center gap-1 px-2 md:px-3 py-1 rounded-full text-[10px] md:text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        <span class="w-1 md:w-1.5 h-1 md:h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 md:px-3 py-1 rounded-full text-[10px] md:text-[11px] font-bold bg-red-50 text-red-700 border border-red-100">
                                        <span class="w-1 md:w-1.5 h-1 md:h-1.5 rounded-full bg-red-500"></span>
                                        Bloqueado
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 md:px-6 py-5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2 md:gap-4">
                                    <a href="{{ route('edit.user', $user->id) }}" 
                                       class="p-1 md:px-0 inline-flex items-center text-indigo-600 hover:text-indigo-800 transition-colors"
                                       title="Editar Usuario">
                                        <svg class="w-5 h-5 md:w-4 md:h-4 md:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        <span class="hidden md:inline font-bold">Editar</span>
                                    </a>

                                    <form action="{{ route('destroy.user', $user->id) }}" method="POST" class="inline">
                                        @csrf @method('PUT')
                                        <button type="submit" 
                                                class="p-1 md:px-0 inline-flex items-center font-bold {{ $user->id_state == 1 ? 'text-rose-500 hover:text-rose-700' : 'text-emerald-500 hover:text-emerald-700' }} transition-colors"
                                                onclick="return confirm('¿Seguro que desea cambiar el estado?')"
                                                title="{{ $user->id_state == 1 ? 'Bloquear' : 'Activar' }}">
                                            @if($user->id_state == 1)
                                                <svg class="w-5 h-5 md:w-4 md:h-4 md:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                                <span class="hidden md:inline">Bloquear</span>
                                            @else
                                                <svg class="w-5 h-5 md:w-4 md:h-4 md:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                                                <span class="hidden md:inline">Activar</span>
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="p-4 bg-slate-50 rounded-full mb-4 text-slate-300">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-700">No hay registros</h3>
                                    <p class="text-slate-400 text-sm">No encontramos usuarios con los criterios actuales.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-200">
            {{ $users->links() }}
        </div>
    </div>
</div>

<script>
    const loader = document.getElementById('loader');
    const content = document.getElementById('content');
    const form = document.getElementById('filterForm');
    const tableContainer = document.getElementById('tableContainer');

    function removeLoader() {
        if (loader && !loader.classList.contains('opacity-0')) {
            loader.classList.add('opacity-0');
            setTimeout(() => {
                loader.style.display = 'none';
                if (content) content.classList.remove('opacity-0');
            }, 500);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(removeLoader, 100); 
    });

    window.addEventListener('load', removeLoader);
    setTimeout(removeLoader, 2500);

    function fetchUsers(url = null) {
        if(tableContainer) tableContainer.style.opacity = '0.6';

        const formData = new FormData(form);
        const params = new URLSearchParams(formData).toString();
        const fetchUrl = url ? url : `{{ route('index.users') }}?${params}`;

        fetch(fetchUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTable = doc.getElementById('tableContainer');
                if (newTable && tableContainer) {
                    tableContainer.innerHTML = newTable.innerHTML;
                }
                tableContainer.style.opacity = '1';
            })
            .catch(error => {
                console.error('Error:', error);
                if(tableContainer) tableContainer.style.opacity = '1';
            });
    }

    if(document.getElementById('estadoFilter')) {
        document.getElementById('estadoFilter').addEventListener('change', () => fetchUsers());
    }

    let debounceTimer;
    if(document.getElementById('searchInput')) {
        document.getElementById('searchInput').addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => fetchUsers(), 600);
        });
    }

    if(form) {
        form.addEventListener('submit', e => {
            e.preventDefault();
            fetchUsers();
        });
    }

    document.addEventListener('click', function(e) {
        const link = e.target.closest('.pagination a');
        if (link) {
            e.preventDefault();
            fetchUsers(link.href);
        }
    });
</script>

@endsection
