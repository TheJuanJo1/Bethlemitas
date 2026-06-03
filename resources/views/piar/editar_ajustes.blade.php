@extends('layout.masterPage')

@section('title', 'PIAR - Editar Ajustes Razonables')

@section('content')
<div class="min-h-screen bg-slate-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        
        {{-- Botón Volver --}}
        <div class="mb-6">
            <button onclick="history.back()" class="inline-flex items-center text-slate-500 hover:text-slate-800 transition-colors duration-200 text-sm font-medium">
                <i class="bi bi-arrow-left mr-2"></i> Volver al listado
            </button>
        </div>

        {{-- Encabezado --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">
                PIAR <span class="text-orange-600">—</span> Editar Ajustes Razonables
            </h1>
        </div>

        {{-- Información del Estudiante --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-10">
            <div class="bg-slate-900 px-6 py-3">
                <h2 class="text-xs font-semibold text-slate-300 uppercase tracking-wider flex items-center">
                    <i class="bi bi-person-circle mr-2"></i> Datos del Estudiante
                </h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center space-x-3">
                    <span class="text-sm font-medium text-slate-500">Nombre completo:</span>
                    <span class="text-sm font-bold text-slate-800">{{ $piar->student->name }} {{ $piar->student->last_name }}</span>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="text-sm font-medium text-slate-500">Grado actual:</span>
                    <span class="text-sm font-bold text-slate-800">{{ $piar->student->degree->degree ?? 'Sin grado' }}</span>
                </div>
            </div>
        </div>

        {{-- Características del Estudiante (Sección 1 del Anexo 2) --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-10">
            <div class="bg-indigo-900 px-6 py-3">
                <h2 class="text-xs font-semibold text-slate-300 uppercase tracking-wider flex items-center">
                    <i class="bi bi-clipboard-data mr-2"></i> 1. Características del Estudiante (Anexo 2)
                </h2>
            </div>
            
                        <form id="ajustesForm" action="{{ route('piar.psico.ajustes.store') }}" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="piar_id" value="{{ $piar->id }}">
                <input type="hidden" name="seccion_characteristics" value="1">

                @if ($errors->any() && old('seccion_characteristics'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 gap-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider" style="line-height: 1.4;">1. Descripción general del estudiante con énfasis en gustos e intereses o aspectos que le desagradan, expectativas del estudiante y la familia:</label>
                        <!-- Quill editor for descripción_general -->
                        <div class="quill-wrapper">
                          <div id="editor_descripcion_estudiante" style="min-height:60px;">{!! old('descripcion_estudiante', $piar->characteristics->descripcion_estudiante ?? '') !!}</div>
                          <input type="hidden" name="descripcion_estudiante" id="input_descripcion_estudiante">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider" style="line-height: 1.4;">2. Descripción en términos de lo que hace, puede hacer o requiere apoyo el estudiante para favorecer su proceso educativo. Indique las habilidades, competencias, cualidades y aprendizajes con los que cuenta el estudiante para el grado en el que fue matriculado:</label>
                        <!-- Quill editor for habilidades -->
                        <div class="quill-wrapper">
                          <div id="editor_habilidades" style="min-height:60px;">{!! old('habilidades', $piar->characteristics->habilidades ?? '') !!}</div>
                          <input type="hidden" name="habilidades" id="input_habilidades">
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-bold text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all shadow-md">
                        <i class="bi bi-save mr-2"></i> Guardar Características
                    </button>
                </div>
            </form>
        </div>

        {{-- Periodos --}}
        @php
    $periodosData = [
        ['titulo' => 'Periodo 1', 'id' => 1, 'datos' => $periodo1],
        ['titulo' => 'Periodo 2', 'id' => 2, 'datos' => $periodo2],
        ['titulo' => 'Periodo 3', 'id' => 3, 'datos' => $periodo3],
    ];
@endphp

        @foreach($periodosData as $periodo)
            <div class="mb-12">
                <div class="flex items-center mb-6 space-x-4">
                    <h2 class="text-xl font-bold text-slate-800">{{ $periodo['titulo'] }}</h2>
                    <div class="flex-grow h-px bg-slate-200"></div>
                </div>

                    @if($periodo['datos']->isEmpty())
                    <div class="bg-slate-100 border border-slate-200 rounded-lg p-6 text-center">
                        <p class="text-slate-500 italic text-sm">No hay ajustes registrados para este periodo.</p>
                    </div>
                @else
                    <form action="{{ route('piar.psico.ajustes.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="piar_id" value="{{ $piar->id }}">
                        <input type="hidden" name="period" value="{{ $periodo['id'] }}">

                        {{-- Campo: Fecha de Evaluación (una sola vez por periodo) --}}
                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-8">
                            <div class="bg-indigo-50 px-6 py-3 border-b border-indigo-100 flex items-center">
                                <div class="w-1 h-5 bg-indigo-500 rounded-full mr-3"></div>
                                <span class="text-indigo-800 font-bold text-sm uppercase tracking-wide">
                                    <i class="bi bi-calendar-check mr-1"></i> Fecha de Evaluación – Psicoorientadora
                                </span>
                            </div>
                            <div class="p-6">
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">
                                    Fecha en que la psicoorientadora evalúa los ajustes
                                </label>
                                <input
                                    type="date"
                                    name="evaluation_date"
                                    value="{{ $periodo['datos']->first()?->evaluation_date ?? '' }}"
                                    class="block w-full max-w-xs rounded-lg border-slate-300 bg-slate-50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 transition-colors hover:bg-white"
                                >
                                <p class="text-xs text-slate-400 mt-2">
                                    <i class="bi bi-info-circle mr-1"></i>
                                    Este campo aplica de manera general a todo el periodo {{ $periodo['titulo'] }}.
                                </p>
                            </div>
                        </div>

                        <div class="space-y-8">
                            @foreach($periodo['datos'] as $adj)
                                <div class="bg-white rounded-xl shadow-md border border-slate-200 overflow-hidden transition-all hover:shadow-lg">
                                    {{-- Cabecera del Área --}}
                                    <div class="bg-orange-50 px-6 py-4 border-b border-orange-100 flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="w-1 h-6 bg-orange-500 rounded-full mr-3"></div>
                                            <span class="text-orange-800 font-bold uppercase tracking-wide text-sm">{{ $adj->area }}</span>
                                            <input type="hidden" name="adjustment_id[]" value="{{ $adj->id }}">
                                        </div>
                                    </div>

                                    {{-- Campos del Formulario --}}
                                    <div class="p-8">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-8">
                                            
                                            {{-- Grupo 1: General --}}
                                            <div class="space-y-2">
                                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Objetivo</label>
                                                <textarea name="objetivo[]" rows="3" class="block w-full rounded-lg border-slate-300 bg-slate-50 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm p-3 transition-colors hover:bg-white">{{ $adj->objetivo }}</textarea>
                                            </div>
                                            
                                            <div class="space-y-2">
                                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Barrera</label>
                                                <textarea name="barrera[]" rows="3" class="block w-full rounded-lg border-slate-300 bg-slate-50 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm p-3 transition-colors hover:bg-white">{{ $adj->barrera }}</textarea>
                                            </div>

                                            {{-- Grupo 2: Ajustes --}}
                                            <div class="space-y-2">
                                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1 text-blue-700">Ajuste Curricular</label>
                                                <textarea name="ajuste_curricular[]" rows="3" class="block w-full rounded-lg border-blue-200 bg-blue-50/30 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-3 transition-colors hover:bg-white italic">{{ $adj->ajuste_curricular }}</textarea>
                                            </div>

                                            <div class="space-y-2">
                                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1 text-blue-700">Ajuste Metodológico</label>
                                                <textarea name="ajuste_metodologico[]" rows="3" class="block w-full rounded-lg border-blue-200 bg-blue-50/30 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-3 transition-colors hover:bg-white italic">{{ $adj->ajuste_metodologico }}</textarea>
                                            </div>

                                            <div class="space-y-2">
                                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1 text-blue-700">Ajuste Evaluativo</label>
                                                <textarea name="ajuste_evaluativo[]" rows="3" class="block w-full rounded-lg border-blue-200 bg-blue-50/30 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-3 transition-colors hover:bg-white italic">{{ $adj->ajuste_evaluativo }}</textarea>
                                            </div>

                                            {{-- Grupo 3: Social --}}
                                            <div class="space-y-2">
                                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1 text-emerald-700">Convivencia</label>
                                                <textarea name="convivencia[]" rows="3" class="block w-full rounded-lg border-emerald-200 bg-emerald-50/30 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm p-3 transition-colors hover:bg-white">{{ $adj->convivencia }}</textarea>
                                            </div>

                                            <div class="space-y-2">
                                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1 text-emerald-700">Socialización</label>
                                                <textarea name="socializacion[]" rows="3" class="block w-full rounded-lg border-emerald-200 bg-emerald-50/30 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm p-3 transition-colors hover:bg-white">{{ $adj->socializacion }}</textarea>
                                            </div>

                                            <div class="space-y-2">
                                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1 text-emerald-700">Participación</label>
                                                <textarea name="participacion[]" rows="3" class="block w-full rounded-lg border-emerald-200 bg-emerald-50/30 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm p-3 transition-colors hover:bg-white">{{ $adj->participacion }}</textarea>
                                            </div>

                                            <div class="space-y-2">
                                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1 text-purple-700">Autonomía</label>
                                                <textarea name="autonomia[]" rows="3" class="block w-full rounded-lg border-purple-200 bg-purple-50/30 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm p-3 transition-colors hover:bg-white">{{ $adj->autonomia }}</textarea>
                                            </div>

                                            <div class="space-y-2">
                                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1 text-purple-700">Autocontrol</label>
                                                <textarea name="autocontrol[]" rows="3" class="block w-full rounded-lg border-purple-200 bg-purple-50/30 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm p-3 transition-colors hover:bg-white">{{ $adj->autocontrol }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Botón de Guardar Periodo --}}
                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="inline-flex items-center px-8 py-3 bg-orange-600 border border-transparent rounded-lg font-bold text-white uppercase tracking-widest hover:bg-orange-700 active:bg-orange-800 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all shadow-lg hover:shadow-orange-200 transform hover:-translate-y-1">
                                <i class="bi bi-check-lg mr-2 text-xl"></i> Guardar {{ $periodo['titulo'] }}
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        @endforeach
@push('styles')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
    /* Expandable Quill styles */
    .quill-wrapper {
        position: relative;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #cbd5e1;
        border-radius: 0.75rem;
        background-color: #ffffff;
        overflow: hidden;
        margin-bottom: 0.5rem;
    }
    .quill-wrapper:focus-within, .quill-wrapper.expanded {
        border-color: #4f46e5;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }
    .quill-wrapper .ql-container {
        height: 60px;
        min-height: 60px;
        font-family: inherit;
        font-size: 0.875rem;
        transition: height 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none !important;
    }
    .quill-wrapper.expanded .ql-container {
        height: 200px;
    }
    .quill-wrapper .ql-toolbar {
        border: none !important;
        border-bottom: 1px solid #cbd5e1 !important;
        background-color: #f8fafc;
        padding: 4px 8px;
        max-height: 0;
        overflow: hidden;
        opacity: 0;
        visibility: hidden;
        transition: max-height 0.25s ease-out, opacity 0.2s ease-out, visibility 0.2s;
    }
    .quill-wrapper.expanded .ql-toolbar {
        max-height: 80px;
        opacity: 1;
        visibility: visible;
        padding: 8px 12px;
    }
    .quill-wrapper .ql-editor.ql-blank::before {
        font-style: italic;
        color: #94a3b8;
    }

    /* Estilos personalizados para complementar Tailwind */
    textarea {
        resize: vertical;
        min-height: 80px;
    }
    .form-section-title {
        position: relative;
        padding-left: 1rem;
    }
    .form-section-title::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background-color: #f97316;
        border-radius: 2px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const toolbarOptions = [
      ['bold', 'italic', 'underline', 'strike'],
      [{ 'color': [] }, { 'background': [] }],
      [{ 'list': 'ordered'}, { 'list': 'bullet' }],
      ['clean']
    ];

    // Setup for explicit editor elements (Characteristics)
    function setupExplicitQuill(editorId, inputId) {
      const editorEl = document.getElementById(editorId);
      if (!editorEl) return null;
      const inputEl = document.getElementById(inputId);
      const wrapper = editorEl.closest('.quill-wrapper');
      
      const quill = new Quill(editorEl, {
        theme: 'snow',
        modules: { toolbar: toolbarOptions }
      });

      // Synchronize initial content to hidden input
      inputEl.value = quill.root.innerHTML;

      quill.on('selection-change', function(range) {
        if (range) {
          wrapper.classList.add('expanded');
        }
      });

      return { quill, inputEl };
    }

    const explicitEditors = [];
    const descEditor = setupExplicitQuill('editor_descripcion_estudiante', 'input_descripcion_estudiante');
    if (descEditor) explicitEditors.push(descEditor);
    const habEditor = setupExplicitQuill('editor_habilidades', 'input_habilidades');
    if (habEditor) explicitEditors.push(habEditor);

    const mainForm = document.getElementById('ajustesForm');
    if (mainForm) {
      mainForm.addEventListener('submit', function () {
        explicitEditors.forEach(item => {
          item.inputEl.value = item.quill.root.innerHTML;
        });
      });
    }

  // Initialize Quill editors for all textarea fields within period forms
    const textareaFields = document.querySelectorAll('.mt-6 textarea');
    let textareaCounter = 0;
    textareaFields.forEach(function(txt) {
        // Create wrapper and editor div
        const wrapper = document.createElement('div');
        wrapper.classList.add('quill-wrapper');
        const editorDiv = document.createElement('div');
        const editorId = 'dynamic_editor_' + textareaCounter;
        editorDiv.id = editorId;
        wrapper.appendChild(editorDiv);
        // Insert wrapper before the textarea and hide textarea
        txt.parentNode.insertBefore(wrapper, txt);
        txt.style.display = 'none';
        // Initialize Quill
        const quill = new Quill(editorDiv, {
            theme: 'snow',
            modules: { toolbar: toolbarOptions }
        });
        // Set initial content from textarea
        quill.root.innerHTML = txt.value;
        // Store reference for later sync
        txt.dataset.quillId = editorId;
        txt.quillInstance = quill;
        textareaCounter++;
    });

    // Extend form submit handler to sync dynamic editors
    if (mainForm) {
        const originalSubmit = mainForm.onsubmit;
        mainForm.addEventListener('submit', function (e) {
            // Sync explicit editors (already handled above)
            explicitEditors.forEach(item => {
                item.inputEl.value = item.quill.root.innerHTML;
            });
            // Sync all dynamic textarea editors
            document.querySelectorAll('textarea').forEach(function (txt) {
                if (txt.quillInstance) {
                    txt.value = txt.quillInstance.root.innerHTML;
                }
            });
            // If there was an original submit handler, call it
            if (typeof originalSubmit === 'function') {
                originalSubmit.call(this, e);
            }
        });
    }
    const periodForms = document.querySelectorAll('form:not(#ajustesForm)');
    periodForms.forEach(function(form) {
      const textareas = form.querySelectorAll('textarea');
      const quills = [];
      
      textareas.forEach(function(textarea) {
        if (textarea.style.display === 'none') return;
        
        // Skip date field
        if (textarea.getAttribute('type') === 'date' || textarea.name === 'evaluation_date') return;

        const content = textarea.value;
        
        // Create wrapper
        const wrapper = document.createElement('div');
        wrapper.classList.add('quill-wrapper');
        
        // Create editor div
        const editorDiv = document.createElement('div');
        editorDiv.style.minHeight = '60px';
        editorDiv.innerHTML = content;
        
        wrapper.appendChild(editorDiv);
        textarea.style.display = 'none';
        textarea.parentNode.insertBefore(wrapper, textarea);
        
        const quill = new Quill(editorDiv, {
          theme: 'snow',
          modules: { toolbar: toolbarOptions }
        });

        quill.on('selection-change', function(range) {
          if (range) {
            wrapper.classList.add('expanded');
          }
        });

        quills.push({ textarea, quill });
      });

      form.addEventListener('submit', function () {
        quills.forEach(function(item) {
          item.textarea.value = item.quill.root.innerHTML;
        });
      });
    });

    // Single global listener to collapse editors when clicking outside
    document.addEventListener('click', function(event) {
      document.querySelectorAll('.quill-wrapper.expanded').forEach(function(wrapper) {
        if (!wrapper.contains(event.target)) {
          wrapper.classList.remove('expanded');
        }
      });
    });
  });
</script>
@endpush
@endsection