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
            <x-referral-form :degrees="$degrees" :groups="$groups" />
            <!-- BOTÓN DE ENVÍO -->
            <div class="pt-6 border-t border-slate-100 flex justify-end">
                <button type="submit"
                    class="w-full sm:w-auto px-10 py-4 bg-indigo-600 text-white font-bold rounded-2xl shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95 flex items-center justify-center gap-2">
                    <span>Enviar remisión formal</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection </div>
</div>
@endsection