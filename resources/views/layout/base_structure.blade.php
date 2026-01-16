@extends('layout.masterPage')

@section('title', 'Creation')

@section('content')
<div class="container p-4 mx-auto">
    <div class="grid grid-cols-3 gap-4">
        <!-- Primera columna -->
        <div class="flex flex-col col-span-1 gap-4">
            <!-- Panel de creación de grupos (primera fila) -->
            <div>
                @yield('first_frame')
            </div>
            <!-- Panel de edición de grupos (segunda fila) -->
            <div>
                @yield('second_frame')
            </div>
        </div>

        <!-- Segunda columna (tabla de grupos) -->
        <div class="col-span-2">
            @yield('list_table')
        </div>
    </div>
</div>
@endsection

@section('JS')
    <script src="{{ asset('scripts/group.js') }}"></script>
    <script src="{{ asset('scripts/degree.js') }}"></script>
    <script src="{{ asset('scripts/area.js') }}"></script>
@endsection