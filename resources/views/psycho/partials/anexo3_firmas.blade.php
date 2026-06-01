@php
    $firmas = $firmasAnexo3 ?? ['manual' => [], 'digital' => []];
@endphp

<div class="mt-12 no-break">
    <div class="grid grid-cols-2 gap-x-10 gap-y-12">
        @foreach($firmas['manual'] as $slot)
        <div class="text-center">
            <div class="border-b border-black w-full mb-1 h-8"></div>
            <p class="text-[9px] font-bold uppercase">{{ $slot['label'] }}</p>
            <p class="text-[8px]">{{ $slot['name'] ?? '________________' }}</p>
        </div>
        @endforeach

        @foreach($firmas['digital'] as $slot)
        <div class="text-center">
            <div class="border-b border-black w-full mb-1 h-16 flex flex-col items-center justify-end pb-1 overflow-hidden">
                @if(!empty($slot['image']))
                    <img src="{{ $slot['image'] }}" class="max-h-14 object-contain scale-125" alt="Firma">
                @else
                    <div class="text-[7px] text-gray-300 italic mb-1 uppercase">Firma digital no cargada</div>
                @endif
            </div>
            <p class="text-[9px] font-bold uppercase leading-none mt-1">{{ $slot['label'] }}</p>
            <p class="text-[8px] font-bold text-gray-900">{{ $slot['name'] }}</p>
            <p class="text-[8px] italic leading-tight">Firma del Profesional</p>
        </div>
        @endforeach
    </div>
</div>
