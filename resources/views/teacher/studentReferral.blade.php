@extends('layout.masterPage')

@section('css')
    <style>
        /* Estilo para quitar las flechas del input number */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }
    </style>
@endsection

@section('title', 'Nueva Remisión')

@section('content')
<div class="container mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        
        {{-- ENCABEZADO --}}
        <div class="bg-slate-50 border-b border-slate-100 p-8">
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Remisión de Estudiantes</h1>
            <p class="text-slate-500 text-sm mt-1 font-medium">Complete la información detallada para iniciar el proceso de acompañamiento PIAR.</p>
        </div>

        <form action="{{ route('store.referral') }}" method="POST" class="p-8 lg:p-10 space-y-8">
            @csrf

            {{-- SECCIÓN: DATOS BÁSICOS --}}
            <div>
                <div class="flex items-center gap-2 mb-6 text-indigo-600">
                    <span class="p-1.5 bg-indigo-50 rounded-lg">👤</span>
                    <h2 class="font-bold uppercase tracking-wider text-xs">Información Identitaria</h2>
                </div>
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                    <div class="space-y-1.5">
                        <label for="name" class="block text-xs font-bold text-slate-500 uppercase ml-1">Nombres *</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required maxlength="50" "
                            class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                    </div>

                    <div class="space-y-1.5">
                        <label for="last_name" class="block text-xs font-bold text-slate-500 uppercase ml-1">Apellidos *</label>
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required "
                            class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                    </div>

                    <div class="space-y-1.5">
                        <label for="number_documment" class="block text-xs font-bold text-slate-500 uppercase ml-1">N° Documento *</label>
                        <input type="number" id="number_documment" name="number_documment" value="{{ old('number_documment') }}" required "
                            class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none @error('number_documment') border-red-500 @enderror">
                        @error('number_documment')
                            <p class="mt-1 text-[11px] text-red-500 font-medium italic">Documento inválido o ya registrado.</p>
                        @enderror
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            {{-- SECCIÓN: ACADÉMICO --}}
            <div>
                <div class="flex items-center gap-2 mb-6 text-emerald-600">
                    <span class="p-1.5 bg-emerald-50 rounded-lg">🏫</span>
                    <h2 class="font-bold uppercase tracking-wider text-xs">Ubicación Escolar</h2>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                    <div class="space-y-1.5">
                        <label for="degree" class="block text-xs font-bold text-slate-500 uppercase ml-1">Grado *</label>
                        <select id="degree" name="degree" required
                            class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 focus:ring-2 focus:ring-indigo-500 outline-none appearance-none cursor-pointer">
                            <option value="">Seleccionar grado</option>
                            @foreach ($degrees as $degree)
                                <option value="{{ $degree->id }}" {{ old('degree') == $degree->id ? 'selected' : '' }}>{{ $degree->degree }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label for="group" class="block text-xs font-bold text-slate-500 uppercase ml-1">Grupo *</label>
                        <select id="group" name="group" required
                            class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 focus:ring-2 focus:ring-indigo-500 outline-none appearance-none cursor-pointer">
                            <option value="">Seleccionar grupo</option>
                            @foreach ($groups as $group)
                                <option value="{{ $group->id }}" {{ old('group') == $group->id ? 'selected' : '' }}>{{ $group->group }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label for="age" class="block text-xs font-bold text-slate-500 uppercase ml-1">Edad</label>
                        <input type="number" id="age" name="age" value="{{ old('age') }}" min="1" placeholder="Años"
                            class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            {{-- SECCIÓN: DETALLES --}}
            <div class="space-y-6">
                <div class="space-y-1.5">
                    <label for="reason_referral" class="block text-xs font-bold text-slate-500 uppercase ml-1 flex justify-between">
                        <span>Motivo *</span>
                        <span class="text-[10px] lowercase font-normal opacity-70">Razón principal de la remisión</span>
                    </label>
                    <textarea id="reason_referral" name="reason_referral" rows="3" required placeholder="Describa brevemente por qué remite al estudiante..."
                        class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-700 focus:ring-2 focus:ring-indigo-500 outline-none">{{ old('reason_referral') }}</textarea>
                </div>

                <div class="space-y-1.5">
                    <label for="observation" class="block text-xs font-bold text-slate-500 uppercase ml-1">Observaciones Detalladas *</label>
                    <textarea id="observation" name="observation" rows="3" required placeholder="Aspectos cognitivos, afectivos, comportamiento..."
                        class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-700 focus:ring-2 focus:ring-indigo-500 outline-none">{{ old('observation') }}</textarea>
                </div>

                <div class="space-y-1.5">
                    <label for="strategies" class="block text-xs font-bold text-slate-500 uppercase ml-1">Estrategias Aplicadas *</label>
                    <textarea id="strategies" name="strategies" rows="3" required placeholder="¿Qué acciones ha tomado usted como docente previamente?"
                        class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-700 focus:ring-2 focus:ring-indigo-500 outline-none">{{ old('strategies') }}</textarea>
                </div>
            </div>

            {{-- BOTÓN DE ENVÍO --}}
            <div class="pt-6 border-t border-slate-100 flex justify-end">
                <button type="submit"
                    class="w-full sm:w-auto px-10 py-4 bg-indigo-600 text-white font-bold rounded-2xl shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95 flex items-center justify-center gap-2">
                    <span>Enviar remisión formal</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection