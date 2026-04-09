@extends('layout.masterPage')

@section('title', 'Crear Usuario | PiarManager')

@section('content')
<div class="p-6 lg:p-10 bg-[#f8fafc] min-h-screen">
    
    {{-- Encabezado --}}
    <div class="mb-8 border-b border-slate-200 pb-6">
        <nav class="flex mb-2 text-xs font-semibold text-slate-400 uppercase tracking-wider">
            <span>Administración</span>
            <span class="mx-2">/</span>
            <a href="{{ route('index.users') }}" class="hover:text-indigo-600 transition-colors">Usuarios</a>
            <span class="mx-2">/</span>
            <span class="text-indigo-600">Nuevo</span>
        </nav>
        <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Crear Nuevo Usuario</h2>
        <p class="text-slate-500 mt-1">Registra personal docente o administrativo en la plataforma.</p>
    </div>

    <form action="{{ route('store.user') }}" method="POST" class="max-w-5xl">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            {{-- SECCIÓN 1: DATOS PERSONALES --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 lg:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Información de Perfil</h3>
                </div>

                <div class="space-y-5">
                    {{-- Rol --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Rol del Sistema *</label>
                        <select id="role" name="role" required class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none">
                            <option value="">Seleccionar rol...</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role') == $role->id ? 'selected' : '' }}> {{ $role->name }} </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Documento --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Número de Documento *</label>
                        <input type="number" id="number_documment" name="number_documment" value="{{ old('number_documment')}}" placeholder="Ej: 1090..." required
                               class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none">
                        @error('number_documment')
                            <p class="mt-1.5 text-xs text-rose-500 font-medium">El documento ya existe o excede los 11 caracteres.</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- Nombres --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Nombre(s) *</label>
                            <input type="text" id="name" name="name" value="{{ old('name')}}" placeholder="Nombres" required
                                   class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none">
                        </div>
                        {{-- Apellidos --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Apellido(s) *</label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" placeholder="Apellidos" required
                                   class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none">
                        </div>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Correo Electrónico *</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="ejemplo@correo.com" required
                               class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none">
                        @error('email')
                            <p class="mt-1.5 text-xs text-rose-500 font-medium">Este correo ya está registrado.</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- SECCIÓN 2: ASIGNACIÓN --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 lg:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Carga Académica</h3>
                </div>

                <div class="space-y-5">
                    {{-- Áreas --}}
                    <div id="group_areas">
                        <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Áreas de Enseñanza (Ctrl + Click)</label>
                        <select id="areas" name="areas[]" multiple disabled class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 min-h-[100px] transition-all disabled:opacity-50 disabled:bg-slate-100 outline-none">
                            @foreach ($areas as $area)
                                <option value="{{ $area->id }}" {{ in_array($area->id, old('areas', [])) ? 'selected' : '' }}>
                                    {{ $area->name_area }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Grupos --}}
                    <div id="group_load_group">
                        <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Grupos a Cargo</label>
                        <select id="groups" name="groups[]" multiple disabled class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 min-h-[100px] transition-all disabled:opacity-50 outline-none">
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" {{ in_array($group->id, old('groups', [])) ? 'selected' : '' }}>{{ $group->group }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Director de Grupo --}}
                    <div id="group_group_director">
                        <label class="block text-sm font-bold text-slate-700 mb-1">Director de Grupo</label>
                        <select id="group_director" name="group_director" disabled class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 transition-all disabled:opacity-50 outline-none">
                            <option value="">Ninguno</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" {{ old('group_director') == $group->id ? 'selected' : '' }}>{{ $group->group }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Grados (Psicoorientador) --}}
                    <div id="group_load_degree">
                        <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Grados Asignados</label>
                        <select id="load_degree" name="load_degree[]" multiple disabled class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 min-h-[100px] transition-all disabled:opacity-50 outline-none">
                            @foreach($degrees as $degree)
                                <option value="{{ $degree->id }}">{{ $degree->degree }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Contenedores Dinámicos (JS) --}}
        <div class="mt-8">
            <p id="description" class="text-sm text-slate-500 italic mb-2"></p>
            <div id="selected-areas-container" class="flex flex-wrap gap-2"></div>
        </div>

        {{-- Acciones del Formulario --}}
        <div class="mt-10 flex items-center justify-end gap-4 border-t border-slate-200 pt-8">
            <a href="{{ route('index.users') }}" class="px-6 py-2.5 text-sm font-bold text-slate-500 hover:text-slate-800 transition-colors">
                Cancelar
            </a>
            <button type="submit" class="px-8 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all">
                Guardar Usuario
            </button>
        </div>
    </form>
</div>
@endsection

@section('JS')
    <script>
        const dbOptions = @json($groups);
    </script>
    <script src="{{ asset('scripts/createUser.js') }}"></script>
@endsection