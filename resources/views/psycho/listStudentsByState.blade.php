@extends('layout.masterPage')

@section('css')
    <link rel="stylesheet" type="text/css" href="#">
@endsection

@section('title', 'Estudiantes')

@section('content')

<div class="flex justify-center p-[2px]">
    <div class="w-[100%] px-4 bg-white rounded-lg shadow-md h-[45rem] overflow-auto">
        
        <!-- HEADER -->
        <div class="p-4 border-b bg-white sticky top-0 shadow-md w-full">
            <h1 class="text-3xl font-bold text-gray-700">
                Estudiantes {{ $stateLabel }}
            </h1>

            <!-- BUSCADOR -->
            <div class="flex items-center mt-2">
                <form action="{{ $route }}">
                    <input
                        type="search"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Buscar..."
                        class="p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-200"
                    />
                    <button type="submit"
                        class="px-4 py-2 ml-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600">
                        Buscar
                    </button>
                </form>
            </div>
        </div>

        <!-- TABLA -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse table-auto">
                <thead>
                    <tr class="text-sm text-gray-600 uppercase bg-gray-200">
                        <th class="px-4 py-2 border">Nombre completo</th>
                        <th class="px-4 py-2 border">Documento</th>
                        <th class="px-4 py-2 border">Grado</th>
                        <th class="px-4 py-2 border">Grupo</th>
                        <th class="px-4 py-2 border">Edad</th>
                        <th class="px-4 py-2 border">Acciones</th>
                    </tr>
                </thead>

                <tbody class="text-gray-700">
                    @forelse ($students as $student)
                        <tr class="hover:bg-gray-100">
                            <td class="px-4 py-2 border">
                                {{ $student->name }} {{ $student->last_name }}
                            </td>

                            <td class="px-4 py-2 text-center border">
                                {{ $student->number_documment }}
                            </td>

                            <td class="px-4 py-2 text-center border">
                                {{ $student->degree->degree }}
                            </td>

                            <td class="px-4 py-2 text-center border">
                                {{ $student->group->group ?? 'Sin grupo' }}
                            </td>

                            <td class="px-4 py-2 text-center border">
                                {{ $student->age }}
                            </td>

                            <!-- 🔥 ACCIONES COMPLETAS -->
                            <td class="px-4 py-2 text-center border space-x-2">

                                <!-- Ver detalles -->
                                <a href="{{ route('details.referral', $student->id) }}"
                                   class="px-3 py-1 text-sm text-white bg-blue-500 rounded hover:bg-blue-600">
                                    Ver
                                </a>

                                <!-- Crear informe -->
                                <a href="{{ route('report.student', $student->id) }}"
                                   class="px-3 py-1 text-sm text-white bg-red-500 rounded hover:bg-red-600">
                                    Informe
                                </a>

                                <!-- Ver historial -->
                                <a href="{{ route('show.student.history', $student->id) }}"
                                   class="px-3 py-1 text-sm text-white bg-yellow-500 rounded hover:bg-orange-600">
                                    Historial
                                </a>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-500">
                                No hay estudiantes para mostrar
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

<div class="mt-4">
    {{ $students->links() }}
</div>

@endsection