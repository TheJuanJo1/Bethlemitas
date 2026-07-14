@extends('layout.masterPage')

@section('css')
    <link rel="stylesheet" type="text/css" href="#">
@endsection

@section('title', 'Historial')

@section('content')
    <button onclick="history.back()" style="background: none; border: none; cursor: pointer;">
  <i class="bi bi-arrow-left" style="font-size: 2rem;"></i>
</button>   
    <h1 class="p-2 text-3xl font-bold text-gray-700">{{ $student->name }} {{ $student->last_name }}</h1>
    <br>
    {{-- Tabla de remisiones --}}
    <div class="flex justify-center p-[2px]">
        <div class="w-[100%] px-4 bg-white rounded-lg shadow-md h-[45rem] overflow-auto">
            <div class="p-4 border-b bg-[white] z-10 sticky top-0 shadow-md w-full ">
                <h1 class="text-3xl font-bold text-gray-700">Remisiones</h1>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse table-auto">
                    <thead>
                        <tr class="text-sm text-gray-600 uppercase bg-gray-200">
                            <th class="px-4 py-2 border">Fecha de remisión</th>
                            <th class="px-4 py-2 border">Nombre de quién remite</th>
                            <th class="px-4 py-2 border">Última edición</th>
                            <th class="px-4 py-2 border">Detalles</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @forelse ($referrals as $referral)
                            <tr class="hover:bg-gray-100">
                                    <td class="px-4 py-2 text-center border">{{ $referral->created_at->format('Y-m-d') }}</td>
                                    <td class="px-4 py-2 text-center border">{{ $referral->user_teacher->name }} {{ $referral->user_teacher->last_name }}</td>
                                    <td class="px-4 py-2 text-center border">{{ $referral->updated_at->format('Y-m-d') }}</td>
                                    <td class="px-4 py-2 text-center border">
                                        <a href="{{ route('history.details.referral', $referral->id) }}" class="text-blue-500 hover:underline">Ver +</a>
                                    </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-2 text-center border">No se encontraron registros</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{ $referrals->links() }} <!-- Para la paginación -->

    <br>

    {{-- Tabla de informes --}}
    <div class="flex justify-center p-[2px]">
        <div class="w-[100%] px-4 bg-white rounded-lg shadow-md h-[45rem] overflow-auto">
            <div class="p-4 border-b bg-[white] z-10 sticky top-0 shadow-md w-full ">
                <h1 class="text-3xl font-bold text-gray-700">Informes</h1>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse table-auto">
                    <thead>
                        <tr class="text-sm text-gray-600 uppercase bg-gray-200">
                            <th class="px-4 py-2 border">Fecha de informe</th>
                            <th class="px-4 py-2 border">Título</th>
                            <th class="px-4 py-2 border">Nombre de quién redacta el informe</th>
                            <th class="px-4 py-2 border">Última edición</th>
                            <th class="px-4 py-2 border">Detalles</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @forelse ($reports as $report)
                            <tr class="hover:bg-gray-100">
                                <td class="px-4 py-2 text-center border">{{ optional($report->created_at)->format('Y-m-d') }}</td>
                                <td class="px-4 py-2 text-center border">{{ $report->title_report }}</td>
                                <td class="px-4 py-2 text-center border">
                                    {{ $report->user_psychology->name ?? '' }} {{ $report->user_psychology->last_name ?? '' }}
                                </td>
                                <td class="px-4 py-2 text-center border">{{ optional($report->updated_at)->format('Y-m-d') }}</td>
                                <td class="px-4 py-2 text-center border">
                                    <a href="{{ route('history.details.report', $report->id) }}" class="text-blue-500 hover:underline">Ver +</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-2 text-center border">No se encontraron registros</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{ $reports->links() }} <!-- Para la paginación -->

    <br>

    {{-- Tabla de PIAR Histórico --}}
    <div class="flex justify-center p-[2px]">
        <div class="w-[100%] px-4 bg-white rounded-lg shadow-md h-[30rem] overflow-auto">
            <div class="p-4 border-b bg-[white] z-10 sticky top-0 shadow-md w-full ">
                <h1 class="text-3xl font-bold text-gray-700">Historial de PIAR</h1>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse table-auto">
                    <thead>
                        <tr class="text-sm text-gray-600 uppercase bg-gray-200">
                            <th class="px-4 py-2 border">Año Lectivo</th>
                            <th class="px-4 py-2 border">Docente Responsable</th>
                            <th class="px-4 py-2 border">Sede / Jornada</th>
                            <th class="px-4 py-2 border">Fecha de Inicio</th>
                            <th class="px-4 py-2 border">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @forelse ($piars as $piar)
                            <tr class="hover:bg-gray-100">
                                <td class="px-4 py-2 text-center border font-bold text-blue-600 text-lg">{{ $piar->year }}</td>
                                <td class="px-4 py-2 text-center border">
                                    {{ $piar->teacher->name ?? 'No asignado' }} {{ $piar->teacher->last_name ?? '' }}
                                </td>
                                <td class="px-4 py-2 text-center border">
                                    {{ $piar->sede ?? 'Bethlemitas' }} - {{ $piar->jornada ?? 'Única' }}
                                </td>
                                <td class="px-4 py-2 text-center border">{{ $piar->created_at->format('Y-m-d') }}</td>
                                <td class="px-4 py-2 text-center border">
                                    <div class="flex justify-center gap-4">
                                        <a href="{{ route('piar.pdf.acta', ['piar' => $piar->id, 'periodo' => 'todos']) }}" target="_blank" class="text-emerald-600 hover:underline font-semibold flex items-center gap-1">
                                            <i class="bi bi-file-earmark-pdf-fill"></i> PDF Completo (Anexo 1, 2 y 3)
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-2 text-center border">No se encontraron registros PIAR para este estudiante</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection