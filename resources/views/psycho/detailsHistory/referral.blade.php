    @extends('layout.masterPage')

    @section('css')
        <link rel="stylesheet" type="text/css" href="#">
    @endsection

    @section('title', 'Detalles de remisión')

    @section('content')

        <div class="p-8 mx-auto bg-[#D5DBDB]">
            <h1 class="mb-6 text-2xl font-bold text-gray-700">Motivo de remisión del estudiante</h1>
            <form action="{{ route('update.history.details.referral', $referral->id ) }}" method="POST">
                @csrf
                @method('PUT')
                <!-- Nombres, Apellidos y Documento -->
                <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-3">
                    <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nombres *</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ $student->name }}"
                        class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        maxlength="50"
                        placeholder="Nombres"
                        disabled
                    />
                    </div>
                    <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700">Apellidos *</label>
                    <input
                        type="text"
                        id="last_name"
                        name="last_name"
                        value="{{ $student->last_name }}"
                        class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Apellidos"
                        disabled
                    />
                    </div>
                    <div>
                    <label for="number_documment" class="block text-sm font-medium text-gray-700">N° documento *</label>
                    <input
                        type="number"
                        id="number_documment"
                        name="number_documment"
                        value="{{ $student->number_documment }}"
                        class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="N° documento"
                        min="1"
                        maxlength="13"
                        disabled 
                    />
                    @error('number_documment')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">El número de documento ya se encuentra registrado  o no tiene la longitud requerida (max 11)</span></p>
                    @enderror
                    </div>
                </div>
            
                <!-- Grado, Grupo y Edad -->
                <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-3">
                    <div>
                        <label for="degree" class="block text-sm font-medium text-gray-700">Grado *</label>
                        <input
                            type="degree"
                            id="degree"
                            name="degree"
                            value="{{ $referral->course }}"
                            class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Grado"
                            min="1"
                            maxlength="13"
                            disabled 
                        />
                    </div>
                    <div>
                        <label for="group" class="block text-sm font-medium text-gray-700">Grupo *</label>
                        <input
                            type="group"
                            id="group"
                            name="group"
                            value="{{ $student->group->group }}"
                            class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Grupo"
                            min="1"
                            maxlength="13"
                            disabled 
                        />
                    </div>
                    <div>
                        <label for="age" class="block text-sm font-medium text-gray-700">Edad</label>
                        <input
                            type="number"
                            id="age"
                            name="age"
                            value="{{ $student->age }}"
                            class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            min="1"
                            placeholder="Edad"
                            required
                            disabled
                        />
                    </div>
                </div>
            
                <!-- Motivo -->
                <div class="mb-6">
                    <label for="reason_referral" class="block text-sm font-medium text-gray-700">Motivo *</label>
                    <textarea
                    id="reason_referral"
                    name="reason_referral"
                    rows="3"
                    class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Motivo de la remisión..."
                    required
                    >{{ old('reason_referral', $referral->reason) }}</textarea>
                    @error('reason_referral')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">¡Error! Campo vacío.</span></p>
                    @enderror
                </div>
            
                <!-- Observaciones -->
                <div class="mb-6">
                    <label for="observation" class="block text-sm font-medium text-gray-700">Observaciones *</label>
                    <textarea
                    id="observation"
                    name="observation"
                    rows="3"
                    class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Aspectos familiares, sociales, cognitivos, afectivos, comportamientos especiales del estudiante..."
                    required
                    >{{ old('observation', $referral->observation) }}</textarea>
                    @error('observation')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">¡Error! Campo vacío.</span></p>
                    @enderror
                </div>
            
                <!-- Estrategias -->
                <div class="mb-6">
                    <label for="strategies" class="block text-sm font-medium text-gray-700">Estrategias *</label>
                    <textarea
                    id="strategies"
                    name="strategies"
                    rows="3"
                    class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Estrategias aplicadas para ayudar al estudiante..."
                    required
                    >{{ old('strategies', $referral->strategies) }}</textarea>
                    @error('strategies')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">¡Error! Campo vacío.</span></p>
                    @enderror
                </div>
            
                <!-- Botón de editar -->
                <div class="text-left">
                    <button
                    type="submit"
                    class="px-4 py-2 font-semibold text-white bg-blue-500 rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                    Editar remisión
                    </button>
                </div>
            </form>
        </div>

    @endsection