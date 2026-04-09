@extends('layout.base_structure')

{{-- Crear Grupo --}}
@section('first_frame')
    <div class="p-6 bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-200/50">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Crear Grupo</h2>
        </div>

        <form action="{{ route('store.group') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="grupo" class="block text-sm font-bold text-slate-700 mb-1">Nombre del Grupo *</label>
                <input type="text" id="grupo" name="group"
                    class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none placeholder-slate-400"
                    maxlength="50" value="{{ old('group') }}" placeholder="Ej: 10-01" required>
                @error('group')
                    <p class="mt-2 text-xs text-rose-500 font-medium">Grupo ya existente o el campo está vacío.</p>
                @enderror
            </div>
            <button type="submit" class="w-full px-4 py-2.5 text-white bg-indigo-600 rounded-xl font-bold text-sm shadow-lg shadow-indigo-100 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all">
                Guardar Grupo
            </button>
        </form>
    </div>
@endsection

{{-- Listar Grupos --}}
@section('list_table')
    <div class="p-6 bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-200/50">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Lista de Grupos</h2>
            
            <form action="{{ route('create.group') }}" method="GET" class="flex items-center gap-2">
                <div class="relative">
                    <input type="search" name="search" placeholder="Buscar grupo..." value="{{ request('search') }}"
                        class="pl-4 pr-10 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none w-full sm:w-64 transition-all">
                    <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                </div>
            </form>
        </div>

        <div class="rounded-xl border border-slate-100 overflow-hidden">
            <div style="max-height: 530px; overflow-y: auto;" class="scrollbar-thin scrollbar-thumb-slate-200">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0 bg-slate-50 border-b border-slate-200 z-10">
                        <tr>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-widest">Grupo</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-widest">Docentes Asignados</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-widest text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($list_groups as $group)
                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                        {{ $group->group }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1 max-w-xs">
                                        @forelse ($group->teachers as $teacher)
                                            <span class="text-sm text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md">
                                                {{ $teacher->name }} {{ $teacher->last_name }}
                                            </span>
                                        @empty
                                            <span class="text-xs text-slate-400 italic">Sin docentes asignados</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <button type="button" 
                                        class="p-2 inline-flex items-center justify-center bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition-all edit_group"
                                        data-id="{{ $group->id }}" name-group="{{ $group->group }}" title="Editar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </button>

                                    <form action="{{ route('destroy.group', $group->id) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" 
                                            class="p-2 inline-flex items-center justify-center bg-rose-50 text-rose-500 rounded-lg hover:bg-rose-500 hover:text-white transition-all"
                                            onclick="return confirm('¿Estás seguro de eliminar este grupo?')">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

{{-- Editar Grupo --}}
@section('second_frame')
    <div class="hidden p-6 bg-white rounded-2xl border-2 border-indigo-100 shadow-2xl animate-in fade-in zoom-in duration-300" id="modal_edit_group">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Editar Grupo</h2>
        </div>

        <form action="{{ route('update.group') }}" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <input type="hidden" id="groupId" name="groupId" value="">
            
            <div>
                <label for="grupo_edit" class="block text-sm font-bold text-slate-700 mb-1">Nombre del Grupo </label>
                <input type="text" id="grupo_edit" name="grupo_edit"
                    class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all outline-none">
                @error('grupo_edit')
                    <p class="mt-2 text-xs text-rose-500 font-medium">Este grupo ya se encuentra registrado o está vacío.</p>
                @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 px-4 py-2.5 text-white bg-indigo-600 rounded-xl font-bold text-sm hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100">
                    Actualizar
                </button>
                <button type="button"
                    class="px-4 py-2.5 text-slate-500 bg-slate-100 rounded-xl font-bold text-sm hover:bg-slate-200 transition-all button_exit">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
@endsection