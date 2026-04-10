@extends('layout.masterPage')

@section('title', 'Añadir informe')

@section('content')
<div class="container mx-auto py-10 px-4 sm:px-6">
    <div class="max-w-5xl mx-auto bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        
        {{-- HEADER --}}
        <div class="bg-slate-50 border-b border-slate-100 p-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <button onclick="history.back()" 
                    class="flex items-center justify-center w-10 h-10 rounded-full bg-white border border-slate-200 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 transition-all shadow-sm">
                    <i class="bi bi-arrow-left" style="font-size: 1.25rem;"></i>
                </button>
                <div>
                    <h1 class="text-xl font-black text-slate-800 tracking-tight">Informe de la consulta</h1>
                    <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest">Registro de nueva sesión</p>
                </div>
            </div>
        </div>

        <form action="{{ route('store.report.student', $info_student->id) }}" 
              method="POST" 
              enctype="multipart/form-data"
              class="p-8 lg:p-10">
            @csrf

            {{-- BLOQUE 1: DATOS DEL ESTUDIANTE --}}
            <div class="mb-12">
                <div class="flex items-center gap-2 mb-6 text-indigo-600">
                    <span class="text-lg">👤</span>
                    <h2 class="font-bold uppercase tracking-widest text-[11px]">Datos del Estudiante</h2>
                    <div class="flex-grow h-px bg-indigo-50 ml-2"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase ml-1">Nombres *</label>
                        <input type="text" name="name" value="{{ $info_student->name }}" required
                            class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-700 focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 transition-all outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase ml-1">Apellidos *</label>
                        <input type="text" name="last_name" value="{{ $info_student->last_name }}" required
                            class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-700 focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 transition-all outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase ml-1">N° Documento *</label>
                        <input type="number" name="number_documment" value="{{ $info_student->number_documment }}" required
                            class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-700 focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 transition-all outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase ml-1">Grado *</label>
                        <div class="relative custom-select-container">
                            <select name="degree" required 
                                onfocus="toggleSelect(this, true)" 
                                onblur="toggleSelect(this, false)"
                                onchange="toggleSelect(this, false)"
                                class="block w-full px-4 py-3 bg-transparent border border-slate-200 rounded-xl text-slate-700 focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 transition-all outline-none appearance-none cursor-pointer relative z-20">
                                <option value="" class="bg-white">Seleccionar</option>
                                @foreach ($degrees as $degree)
                                    <option value="{{ $degree->id }}" {{ $info_student->id_degree == $degree->id ? 'selected' : '' }} class="bg-white">
                                        {{ $degree->degree }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none transition-all duration-300 select-arrow z-30">
                                <i class="bi bi-chevron-down text-slate-400"></i>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase ml-1">Grupo *</label>
                        <div class="relative custom-select-container">
                            <select name="group" required 
                                onfocus="toggleSelect(this, true)" 
                                onblur="toggleSelect(this, false)"
                                onchange="toggleSelect(this, false)"
                                class="block w-full px-4 py-3 bg-transparent border border-slate-200 rounded-xl text-slate-700 focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 transition-all outline-none appearance-none cursor-pointer relative z-20">
                                <option value="" class="bg-white">Seleccionar</option>
                                @foreach ($groups as $group)
                                    <option value="{{ $group->id }}" {{ $info_student->id_group == $group->id ? 'selected' : '' }} class="bg-white">
                                        {{ $group->group }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none transition-all duration-300 select-arrow z-30">
                                <i class="bi bi-chevron-down text-slate-400"></i>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase ml-1">Edad *</label>
                        <input type="number" name="age" value="{{ $info_student->age }}" required
                            class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-700 focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 transition-all outline-none">
                    </div>
                </div>
            </div>

            {{-- BLOQUE 2: DETALLES --}}
            <div class="mb-12">
                <div class="flex items-center gap-2 mb-6 text-indigo-600">
                    <span class="text-lg">📋</span>
                    <h2 class="font-bold uppercase tracking-widest text-[11px]">Detalles del Informe</h2>
                    <div class="flex-grow h-px bg-indigo-50 ml-2"></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase ml-1">Estado *</label>
                        <div class="relative custom-select-container">
                            <select name="state" required 
                                onfocus="toggleSelect(this, true)" 
                                onblur="toggleSelect(this, false)"
                                onchange="toggleSelect(this, false)"
                                class="block w-full px-4 py-3 bg-transparent border border-slate-200 rounded-xl text-slate-700 focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 transition-all outline-none appearance-none cursor-pointer relative z-20">
                                <option value="" class="bg-white">Seleccionar</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}" class="bg-white">{{ $state->state }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none transition-all duration-300 select-arrow z-30">
                                <i class="bi bi-chevron-down text-slate-400"></i>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase ml-1">Título de informe *</label>
                        <input type="text" name="title_report" placeholder="Ej: Evolución primer trimestre" required
                            class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-700 focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 transition-all outline-none">
                    </div>
                </div>
                
                <div class="space-y-6">
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase ml-1">Motivo *</label>
                        <textarea name="reason_inquiry" rows="3" required placeholder="Describa el motivo..."
                            class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-700 focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 transition-all outline-none"></textarea>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase ml-1">Recomendaciones *</label>
                        <textarea name="recomendations" rows="3" required placeholder="Sugerencias..."
                            class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-700 focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 transition-all outline-none"></textarea>
                    </div>
                </div>
            </div>

            {{-- ANEXOS --}}
            <div class="mb-12">
                <div class="flex items-center gap-2 mb-6 text-indigo-600">
                    <span class="text-lg">📎</span>
                    <h2 class="font-bold uppercase tracking-widest text-[11px]">Archivos Adjuntos</h2>
                    <div class="flex-grow h-px bg-indigo-50 ml-2"></div>
                </div>
                <div class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-3xl p-8 text-center transition-all hover:bg-slate-100/50">
                    <input type="file" name="annex_one" id="annex_one" accept="application/pdf" class="hidden">
                    <label for="annex_one" class="cursor-pointer group">
                        <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                            <i class="bi bi-cloud-arrow-up text-3xl text-indigo-500"></i>
                        </div>
                        <p class="text-sm font-bold text-slate-700">Haz clic para subir el Anexo 1</p>
                    </label>
                </div>
                <div id="pdf-preview-container" class="hidden mt-8 animate-fade-in-down">
                    <iframe id="pdf-preview" class="w-full h-[450px] border-8 border-slate-100 rounded-3xl"></iframe>
                    <button type="button" id="remove-pdf" class="mt-4 text-xs font-bold text-red-500 uppercase tracking-widest hover:underline">Eliminar archivo</button>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-100 flex items-center justify-between">
                <button type="submit" class="px-10 py-4 bg-indigo-600 text-white font-black rounded-2xl hover:bg-slate-800 transition-all shadow-xl uppercase text-xs tracking-widest flex items-center gap-2">
                    <span>Guardar Informe</span>
                    <i class="bi bi-send-fill"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }

    /* Mejora en la transición de la flecha */
    .select-arrow {
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), color 0.3s ease;
    }

    .custom-select-container.is-active .select-arrow {
        transform: rotate(180deg);
        color: #6366f1;
    }

    @keyframes fade-in-down {
        0% { opacity: 0; transform: translateY(-10px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-down { animation: fade-in-down 0.4s ease-out; }
</style>

<script>
    // Se añade un pequeño delay en el blur para evitar que la animación 
    // se corte antes de que el usuario vea la selección cerrada
    function toggleSelect(element, active) {
        const container = element.closest('.custom-select-container');
        if (active) {
            container.classList.add('is-active');
        } else {
            setTimeout(() => {
                container.classList.remove('is-active');
            }, 150); 
        }
    }

    // Lógica PDF
    const fileInput = document.getElementById('annex_one');
    const previewContainer = document.getElementById('pdf-preview-container');
    const previewFrame = document.getElementById('pdf-preview');
    const removeBtn = document.getElementById('remove-pdf');

    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file && file.type === "application/pdf") {
            previewFrame.src = URL.createObjectURL(file);
            previewContainer.classList.remove('hidden');
        }
    });

    removeBtn.addEventListener('click', () => {
        fileInput.value = "";
        previewContainer.classList.add('hidden');
    });
</script>
@endsection