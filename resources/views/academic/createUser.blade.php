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
                                <option value="{{ $role->id }}"> {{ $role->name }} </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group-half">
                        <label for="number_documment">Número de documento *</label>
                        <input type="text" id="number_documment" name="number_documment" value="{{ old('number_documment')}}" placeholder="Número de documento" required>
                        @error('number_documment')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">El número de documento ya se encuentra registrado</span></p>
                        @enderror
                    </div>
                    
                    <div class="form-group-half">
                        <label for="name">Nombre/s *</label>
                        <input type="text" id="name" name="name" value="{{ old('name')}}" placeholder="Nombre" required>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Incorrecto</span></p>
                        @enderror
                    </div>
                    
                    <div class="form-group-half">
                        <label for="lastname">Apellido/s *</label>
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" placeholder="Apellido" required>
                        @error('last_name')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Incorrecto</span></p>
                        @enderror
                    </div>

                    <div class="form-group-half">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">El email ya se encuentra registrado</span></p>
                        @enderror
                    </div>
                </div>

                <div class="form-group" >       
                    <div class="form-group-half" id="group_asignatures">
                        <label for="subjects">Asignaturas * (manten presionada la tecla Ctrl y seleciona las asignaturas):</label>
                        <select id="subjects" name="subjects[]" multiple disabled>
                            <option value="">Seleccionar</option>
                            @foreach ($asignatures as $asignature)
                                <option value="{{ $asignature->id }}" 
                                    {{ in_array($asignature->id, old('subjects', [])) ? 'selected' : '' }}>
                                    {{ $asignature->name_asignature }}
                                </option>
                            @endforeach
                        </select>
                        @error('subjects')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">¡Error! Campo vacío</span></p>
                        @enderror
                    </div>

                    <div class="form-group-half" id="group_load_group">
                        <label for="groups">Grupos a cargo * (manten presionada la tecla Ctrl y seleciona los grupos):</label>
                        <select id="groups" name="groups[]" multiple disabled>
                            <option value="">Seleccionar</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}"
                                    {{ in_array($group->id, old('groups', [])) ? 'selected' : '' }}>
                                    {{ $group->group }}</option>
                            @endforeach
                        </select>
                        @error('groups')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">¡Error! Campo vacío</span></p>
                        @enderror
                    </div>

                    <div class="form-group-half" id="group_group_director">
                        <label for="group_director">Director de grupo </label>
                        <select id="group_director" name="group_director" disabled>
                            <option value="">Seleccionar</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" {{ old('group_director') == $group->id ? 'selected' : '' }}>
                                    {{ $group->group }}</option>
                            @endforeach
                        </select>
                        @error('group_director')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">¡Error! Campo vacío</span></p>
                        @enderror
                    </div>

                    <div class="form-group-half" id="group_load_degree">
                        <label for="load_degree">Grados a cargo * (manten presionada la tecla Ctrl y seleciona los grados):</label>
                        <select id="load_degree" name="load_degree[]" multiple disabled>
                            <option value="">Seleccionar</option>
                            @foreach($degrees as $degree)
                                <option value="{{ $degree->id }}">{{ $degree->degree }}</option>
                            @endforeach
                        </select>
                        @error('load_degree')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">¡Error! Campo vacío</span></p>
                        @enderror
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