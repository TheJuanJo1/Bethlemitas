@props(['piarId', 'disabled' => false])

@if($disabled)
    <span {{ $attributes->merge(['class' => 'px-2.5 py-1 text-[10px] font-bold text-gray-400 bg-gray-50 rounded border border-gray-100 opacity-60 cursor-not-allowed']) }}>
        ACTA
    </span>
@else
    <details class="relative inline-block acta-dropdown">
        <summary class="list-none cursor-pointer px-2.5 py-1 text-[10px] font-bold text-emerald-700 bg-emerald-100 rounded border border-emerald-200 hover:bg-emerald-200 btn-transition inline-flex items-center gap-0.5 [&::-webkit-details-marker]:hidden"
                 title="Descargar Acta (Anexo 1 + 2 + 3)">
            ACTA <i class="bi bi-chevron-up text-[8px] opacity-70"></i>
        </summary>

        {{-- Barra horizontal encima del botón --}}
        <div class="acta-submenu absolute left-1/2 -translate-x-1/2 bottom-full mb-1 z-[100]
                    flex flex-row flex-nowrap items-center gap-0.5
                    rounded-lg bg-white shadow-lg ring-1 ring-black/10 p-0.5
                    whitespace-nowrap">
            @foreach([1 => 'P.1', 2 => 'P.2', 3 => 'P.3'] as $num => $label)
                <a href="{{ route('piar.pdf.acta', ['piar' => $piarId, 'periodo' => $num]) }}"
                   target="_blank"
                   class="px-2 py-1 text-[9px] font-semibold text-gray-700 rounded hover:bg-emerald-100 hover:text-emerald-800"
                   title="Periodo {{ $num }}">
                    {{ $label }}
                </a>
            @endforeach
            <span class="text-gray-300 text-[10px] select-none px-0.5">|</span>
            <a href="{{ route('piar.pdf.acta', ['piar' => $piarId, 'periodo' => 'todos']) }}"
               target="_blank"
               class="px-2 py-1 text-[9px] font-bold text-emerald-800 rounded hover:bg-emerald-100"
               title="Todos los periodos">
                Todos
            </a>
        </div>
    </details>
@endif
