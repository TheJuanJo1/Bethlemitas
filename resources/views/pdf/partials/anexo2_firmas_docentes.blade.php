@php
    $firmasDocentes = $firmasDocentesAnexo2 ?? [];
@endphp

@if(count($firmasDocentes) > 0)
    <div class="page-break"></div>
    <h3 style="text-align: center; margin-bottom: 15px;">FIRMAS DE DOCENTES — ANEXO 2</h3>

    <table class="firma-grid-docentes" style="width: 100%; border: none; border-collapse: collapse;">
        @foreach(array_chunk($firmasDocentes, 2) as $fila)
            <tr>
                @foreach($fila as $slot)
                    <td style="width: 50%; border: none; padding: 12px 16px; vertical-align: top;">
                        <div style="height: 55px; border-bottom: 1px solid #000; text-align: center;">
                            @if(!empty($slot['image']))
                                <img src="{{ $slot['image'] }}" alt="" style="max-height: 50px; max-width: 100%;">
                            @else
                                <span style="font-size: 7px; color: #999;">SIN FIRMA DIGITAL</span>
                            @endif
                        </div>
                        <div style="font-size: 9px; font-weight: bold; margin-top: 6px; text-transform: uppercase;">
                            Docente {{ $slot['name'] }}
                        </div>
                        @if(!empty($slot['areas_label']))
                            <div style="font-size: 8px; margin-top: 2px;">{{ $slot['areas_label'] }}</div>
                        @endif
                        <div style="font-size: 7px; font-style: italic; margin-top: 2px;">Firma del Profesional</div>
                    </td>
                @endforeach
                @if(count($fila) === 1)
                    <td style="border: none;"></td>
                @endif
            </tr>
        @endforeach
    </table>
@endif
