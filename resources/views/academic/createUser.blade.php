@extends('layout.masterPage')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/createUser.css') }}">
@endsection

@section('title', 'Create user')

@section('content')
    <div class="container">
        <h2 style="font-size: 32px; text-align: left; margin-bottom: 40px; font-weight: 600;">Crear usuario</h2>
        <form action="{{ route('store.user') }}" method="POST">
            @csrf
            <div class="container-div">
                <div class="form-group">
                    <div class="form-group-half">
                        <label for="groups">Role *</label>
                        <select id="role" name="role" required>
                            <option value="">Seleccionar</option>
                            @foreach($roles as $role)
                                <option value="{{ $role }}">{{ $role }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group-half">
                        <label for="number_documment">Número de documento *</label>
                        <input type="text" id="number_documment" name="number_documment" placeholder="Número de documento" required>
                    </div>
                    
                    <div class="form-group-half">
                        <label for="name">Nombre/s *</label>
                        <input type="text" id="name" name="name" placeholder="Nombre" required>
                    </div>
                    
                    <div class="form-group-half">
                        <label for="lastname">Apellido/s *</label>
                        <input type="text" id="lastname" name="lastname" placeholder="Apellido" required>
                    </div>
                </div>

                <div class="form-group" >       
                    <div class="form-group-half" id="group_asignatures">
                        <label for="subjects">Asignaturas *</label>
                        <select id="subjects" name="subjects" required>
                            <option value="">Seleccionar</option>
                            @foreach ($asignatures as $asignature)
                                <option value="{{ $asignature }}">{{ $asignature }}</option>                            
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group-half" id="group_load_group">
                        <label for="groups">Grupos a cargo *</label>
                        <select id="groups" name="groups" required>
                            <option value="">Seleccionar</option>
                            @foreach($groups as $group)
                                <option value="{{ $group }}">{{ $group }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group-half" id="group_group_director">
                        <label for="group_director">Director de grupo </label>
                        <select id="group_director" name="group_director">
                            <option value="">Seleccionar</option>
                            @foreach($groups as $group)
                                <option value="{{ $group }}">{{ $group }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="hidden form-group-half" id="group_load_degree">
                        <label for="load_degree">Grados a cargo *</label>
                        <select id="load_degreer" name="load_degree">
                            <option value="">Seleccionar</option>
                            @foreach($degrees as $degree)
                                <option value="{{ $degree }}">{{ $degree }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn">Crear usuario</button>
        </form>
    </div>
@endsection

@section('JS')
    <script src="{{ asset('scripts/createUser.js') }}"></script>
@endsection