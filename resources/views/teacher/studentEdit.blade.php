@extends('layout.masterPage')

@section('css')
    <link rel="stylesheet" type="text/css" href="#">
@endsection

@section('title', 'Editar Estudiante')

@section('content')
    <div class="p-8 mx-auto bg-[#D5DBDB]">
        @if (Auth::user()->hasRole('docente'))
            <a href=" {{ route('index.student.remitted')}} "><i class="bi bi-arrow-left" style="font-size: 2rem;"></i></a>
        @elseif (Auth::user()->hasRole('psicoorientador'))
            <a href=" {{ route('waiting.students')}} "><i class="bi bi-arrow-left" style="font-size: 2rem;"></i></a>
        @endif
        <h1 class="mb-6 text-2xl font-bold text-gray-700">Editar estudiante</h1>
        <form action="{{ route('update.student', $student->id) }}" method="POST">
            @csrf
            @method('PUT')
<x-referral-form :degrees="$degrees" :groups="$groups" :student="$student" />

            <!-- Botón de envío -->
            <div class="text-left">
                <button
                type="submit"
                class="px-4 py-2 font-semibold text-white bg-blue-500 rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                Editar
                </button>
            </div>
        </form>
    </div>
@endsection