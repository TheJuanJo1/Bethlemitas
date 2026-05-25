<style>
    /* Estilo para quitar las flechas del input number */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    input[type=number] { -moz-appearance: textfield; }
</style>

{{-- Blade component for referral form --}}
@props(['degrees', 'groups', 'student' => null])

<div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
    <!-- Nombres -->
    <div class="space-y-1.5">
        <label for="name" class="block text-xs font-bold text-slate-500 uppercase ml-1">Nombres *</label>
        <input type="text" id="name" name="name" value="{{ old('name', optional($student)->name) }}" required maxlength="50"
            class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" />
    </div>
    <!-- Apellidos -->
    <div class="space-y-1.5">
        <label for="last_name" class="block text-xs font-bold text-slate-500 uppercase ml-1">Apellidos *</label>
        <input type="text" id="last_name" name="last_name" value="{{ old('last_name', optional($student)->last_name) }}" required maxlength="50"
            class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" />
    </div>
    <!-- Documento -->
    <div class="space-y-1.5">
        <label for="number_documment" class="block text-xs font-bold text-slate-500 uppercase ml-1">N° Documento *</label>
        <input type="number" id="number_documment" name="number_documment" value="{{ old('number_documment', optional($student)->number_documment) }}" required
            class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 focus:ring-2 focus:ring-indigo-500 @error('number_documment') border-red-500 @enderror" />
        @error('number_documment')
            <p class="mt-1 text-[11px] text-red-500 font-medium italic">Documento inválido o ya registrado.</p>
        @enderror
    </div>
</div>

<hr class="border-slate-100" />

<div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
    <!-- Grado -->
    <div class="space-y-1.5">
        <label for="degree" class="block text-xs font-bold text-slate-500 uppercase ml-1">Grado *</label>
        <select id="degree" name="degree" required class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 focus:ring-2 focus:ring-indigo-500 outline-none appearance-none cursor-pointer">
            <option value="">Seleccionar grado</option>
            @foreach ($degrees as $degree)
                <option value="{{ $degree->id }}" {{ (old('degree') ?? optional($student)->id_degree) == $degree->id ? 'selected' : '' }}>{{ $degree->degree }}</option>
            @endforeach
        </select>
    </div>
    <!-- Grupo -->
    <div class="space-y-1.5">
        <label for="group" class="block text-xs font-bold text-slate-500 uppercase ml-1">Grupo *</label>
        <select id="group" name="group" required class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 focus:ring-2 focus:ring-indigo-500 outline-none appearance-none cursor-pointer">
            <option value="">Seleccionar grupo</option>
            @foreach ($groups as $group)
                <option value="{{ $group->id }}" {{ (old('group') ?? optional($student)->id_group) == $group->id ? 'selected' : '' }}>{{ $group->group }}</option>
            @endforeach
        </select>
    </div>
    <!-- Edad -->
    <div class="space-y-1.5">
        <label for="age" class="block text-xs font-bold text-slate-500 uppercase ml-1">Edad</label>
        <input type="number" id="age" name="age" value="{{ old('age', optional($student)->age) }}" min="1" placeholder="Años"
            class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 focus:ring-2 focus:ring-indigo-500 outline-none" />
    </div>
</div>

<hr class="border-slate-100" />

<div class="space-y-6">
    <!-- Motivo -->
    <div class="space-y-1.5">
        <label for="reason_referral" class="block text-xs font-bold text-slate-500 uppercase ml-1 flex justify-between">
            <span>Motivo *</span>
            <span class="text-[10px] lowercase font-normal opacity-70">Razón principal de la remisión</span>
        </label>
        <textarea id="reason_referral" name="reason_referral" rows="3" required placeholder="Describa brevemente por qué remite al estudiante..."
            class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-700 focus:ring-2 focus:ring-indigo-500 outline-none">{{ old('reason_referral', optional($student)->reason_referral) }}</textarea>
    </div>
    <!-- Observaciones -->
    <div class="space-y-1.5">
        <label for="observation" class="block text-xs font-bold text-slate-500 uppercase ml-1">Observaciones Detalladas *</label>
        <textarea id="observation" name="observation" rows="3" required placeholder="Aspectos cognitivos, afectivos, comportamiento..."
            class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-700 focus:ring-2 focus:ring-indigo-500 outline-none">{{ old('observation', optional($student)->observation) }}</textarea>
    </div>
    <!-- Estrategias -->
    <div class="space-y-1.5">
        <label for="strategies" class="block text-xs font-bold text-slate-500 uppercase ml-1">Estrategias Aplicadas *</label>
        <textarea id="strategies" name="strategies" rows="3" required placeholder="¿Qué acciones ha tomado usted como docente previamente?"
            class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-700 focus:ring-2 focus:ring-indigo-500 outline-none">{{ old('strategies', optional($student)->strategies) }}</textarea>
    </div>
</div>
