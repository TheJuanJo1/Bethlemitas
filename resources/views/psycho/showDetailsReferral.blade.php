@extends('layout.masterPage')

@section('css')
    <style>
        .form-container {
            max-width: 1000px;
            margin: 2rem auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .form-header {
            background: #1e293b;
            color: white;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .back-btn {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.2s;
        }
        .back-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateX(-3px);
        }
    </style>
@endsection

@section('title', 'Detalles de remisión')

@section('content')

<div class="p-2 md:p-6 bg-gray-100 min-h-screen">
    <div class="form-container">
        <div class="form-header">
            <button onclick="history.back()" class="back-btn" title="Volver">
                <i class="bi bi-arrow-left text-xl"></i>
            </button>
            <div>
                <h1 class="text-xl md:text-2xl font-bold">Detalles de Remisión</h1>
                <p class="text-xs text-blue-300 opacity-80">Expediente de {{ $info_student->name }} {{ $info_student->last_name }}</p>
            </div>
        </div>

        <form action="{{ route('update.details.referral', $info_student->id) }}" method="POST" class="p-4 md:p-8">
            @csrf
            @method('PUT')
            
            <h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4 border-b pb-2">Información Personal</h2>
            
            <!-- Nombres, Apellidos y Documento -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div>
                    <label for="name" class="block text-xs font-bold text-gray-600 uppercase mb-1">Nombres *</label>
                    <input
                        type="text" id="name" name="name" value="{{ $info_student->name }}"
                        class="block w-full p-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                        maxlength="50" required
                    />
                </div>
                <div>
                    <label for="last_name" class="block text-xs font-bold text-gray-600 uppercase mb-1">Apellidos *</label>
                    <input
                        type="text" id="last_name" name="last_name" value="{{ $info_student->last_name }}"
                        class="block w-full p-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                        required
                    />
                </div>
                <div>
                    <label for="number_documment" class="block text-xs font-bold text-gray-600 uppercase mb-1">N° documento *</label>
                    <input
                        type="number" id="number_documment" name="number_documment" value="{{ $info_student->number_documment }}"
                        class="block w-full p-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-mono"
                        min="1" required
                    />
                    @error('number_documment')
                        <p class="mt-1 text-[10px] text-red-600 font-bold italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        
            <h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4 border-b pb-2">Datos Académicos</h2>

            <!-- Grado, Grupo y Edad -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div>
                    <label for="degree" class="block text-xs font-bold text-gray-600 uppercase mb-1">Grado *</label>
                    <select
                        id="degree" name="degree"
                        class="block w-full p-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                        required
                    >
                        <option value="">Seleccionar</option>
                        @foreach ($degrees as $degree)
                            <option value="{{ $degree->id }}" {{ $info_student->id_degree == $degree->id ? 'selected' : '' }} >{{ $degree->degree }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="group" class="block text-xs font-bold text-gray-600 uppercase mb-1">Grupo *</label>
                    <select
                        id="group" name="group"
                        class="block w-full p-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                        required
                    >
                        <option value="">Seleccionar</option>
                        @foreach ($groups as $group)
                            <option value="{{ $group->id }}" {{ $info_student->id_group == $group->id ? 'selected' : '' }} >{{ $group->group }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="age" class="block text-xs font-bold text-gray-600 uppercase mb-1">Edad</label>
                    <input
                        type="number" id="age" name="age" value="{{ $info_student->age }}"
                        class="block w-full p-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                        min="1" required
                    />
                </div>
            </div>
        
            <h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4 border-b pb-2">Cuerpo de la Remisión</h2>

            <!-- Motivo -->
            <div class="mb-6">
                <label for="reason_referral" class="block text-xs font-bold text-gray-600 uppercase mb-1">Motivo *</label>
                <textarea
                    id="reason_referral" name="reason_referral" rows="3"
                    class="block w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm"
                    placeholder="Escriba el motivo principal..." required
                >{{ old('reason_referral', $info_referral->reason ?? '') }}</textarea>
            </div>
        
            <!-- Observaciones -->
            <div class="mb-6">
                <label for="observation" class="block text-xs font-bold text-gray-600 uppercase mb-1">Observaciones *</label>
                <textarea
                    id="observation" name="observation" rows="4"
                    class="block w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm"
                    placeholder="Aspectos familiares, cognitivos, comportamientos..." required
                >{{ old('observation', $info_referral->observation ?? '') }}</textarea>
            </div>
        
            <!-- Estrategias -->
            <div class="mb-8">
                <label for="strategies" class="block text-xs font-bold text-gray-600 uppercase mb-1">Estrategias *</label>
                <textarea
                    id="strategies" name="strategies" rows="3"
                    class="block w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm"
                    placeholder="Estrategias aplicadas..." required
                >{{ old('strategies', $info_referral->strategies ?? '') }}</textarea>
            </div>

            <div class="flex flex-col md:flex-row gap-4 items-center justify-between bg-blue-50 p-6 rounded-xl border border-blue-100">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-600 text-white rounded-full">
                        <i class="bi bi-file-pdf"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase">Anexo PDF</p>
                        @if($report && $report->annex_one)
                            <a href="{{ asset('storage/'.$report->annex_one) }}" target="_blank" class="text-blue-700 font-bold hover:underline">
                                Ver Informe Psicológico Adjunto
                            </a>
                        @else
                            <p class="text-sm text-gray-400 italic">No hay anexos adjuntos</p>
                        @endif
                    </div>
                </div>

                <button type="submit" class="w-full md:w-auto px-8 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 shadow-md transition-all active:scale-95">
                    Guardar Cambios
                </button>
            </div>
        
        </form>
    </div>
</div>

@endsection
