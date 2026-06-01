@php
    $firmas = $firmasAnexo3 ?? ['manual' => [], 'digital' => []];
@endphp

<table class="firma-grid no-break" style="margin-top: 20px; border: none; width: 100%;">
    <tr>
        @foreach($firmas['manual'] as $slot)
        <td style="width: 50%; border: none; padding: 8px 12px; vertical-align: top;">
            <div class="firma-box"></div>
            <div class="firma-label">{{ $slot['label'] }}</div>
            <div style="font-size: 8px;">{{ $slot['name'] ?? '________________' }}</div>
        </td>
        @endforeach
    </tr>
    @foreach(array_chunk($firmas['digital'], 2) as $fila)
    <tr>
        @foreach($fila as $slot)
        <td style="width: 50%; border: none; padding: 8px 12px; vertical-align: top;">
            <div class="firma-box">
                @if(!empty($slot['image']))
                    <img src="{{ $slot['image'] }}" alt="">
                @else
                    <span style="font-size: 7px; color: #999;">SIN FIRMA DIGITAL</span>
                @endif
            </div>
            <div class="firma-label">{{ $slot['label'] }}</div>
            <div style="font-size: 8px; font-weight: bold;">{{ $slot['name'] }}</div>
            <div style="font-size: 8px; font-style: italic;">Firma del Profesional</div>
        </td>
        @endforeach
        @if(count($fila) === 1)
        <td style="border: none;"></td>
        @endif
    </tr>
    @endforeach
</table>
