<!-- Anexo 3 Actividades Section -->
<p style="font-size: 0.85rem; color: #64748b; margin-bottom: 1.5rem;">
    Registre las actividades, estrategias y frecuencia de apoyo desde el hogar.
</p>

<div style="overflow-x: auto; border: 1px solid var(--border-gray); border-radius: 12px; margin-bottom: 1.5rem;">
    <table style="width: 100%; border-collapse: collapse;" id="tabla-anexo3">
        <thead>
            <tr style="background: #f8fafc;">
                <th style="padding: 1rem; border-bottom: 1px solid var(--border-gray); text-align: left; font-size: 0.8rem; font-weight: 700; color: #475569; text-transform: uppercase; width: 30%;">Nombre actividad</th>
                <th style="padding: 1rem; border-bottom: 1px solid var(--border-gray); text-align: left; font-size: 0.8rem; font-weight: 700; color: #475569; text-transform: uppercase;">Descripción de la estrategia</th>
                <th style="padding: 1rem; border-bottom: 1px solid var(--border-gray); text-align: left; font-size: 0.8rem; font-weight: 700; color: #475569; text-transform: uppercase; width: 25%;">Frecuencia (D, S, P, N/A)</th>
                <th style="width: 60px; padding: 1rem; border-bottom: 1px solid var(--border-gray);"></th>
            </tr>
        </thead>
        <tbody>
            @if(isset($familyActivities) && $familyActivities->isNotEmpty())
                @foreach($familyActivities as $act)
                <tr>
                    <td style="padding: 1rem; border-bottom: 1px solid var(--border-gray);">
                        <input type="text" name="anexo3_actividad[]" class="form-control" value="{{ $act->activity }}" placeholder="Si no cumple escriba: N/A" required>
                    </td>
                    <td style="padding: 1rem; border-bottom: 1px solid var(--border-gray);">
                        <textarea name="anexo3_estrategia[]" class="form-control" style="height: 60px;" placeholder="Si no cumple escriba: N/A" required>{{ $act->strategy }}</textarea>
                    </td>
                    <td style="padding: 1rem; border-bottom: 1px solid var(--border-gray);">
                        <select name="anexo3_frecuencia[]" class="form-control" required>
                            <option value="D" {{ $act->frequency == 'D' ? 'selected' : '' }}>D (Diaria)</option>
                            <option value="S" {{ $act->frequency == 'S' ? 'selected' : '' }}>S (Semanal)</option>
                            <option value="P" {{ $act->frequency == 'P' ? 'selected' : '' }}>P (Permanente)</option>
                            <option value="N/A" {{ $act->frequency == 'N/A' ? 'selected' : '' }}>N/A (No aplica)</option>
                        </select>
                    </td>
                    <td style="padding: 1rem; border-bottom: 1px solid var(--border-gray); text-align: center; vertical-align: middle;">
                        <button type="button" onclick="this.parentElement.parentElement.remove()" class="btn" style="color: #ef4444; background: none; border: none; cursor: pointer;">
                            <i class="bi bi-trash" style="font-size: 1.25rem;"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td style="padding: 1rem; border-bottom: 1px solid var(--border-gray);">
                        <input type="text" name="anexo3_actividad[]" class="form-control" placeholder="Si no cumple escriba: N/A" required>
                    </td>
                    <td style="padding: 1rem; border-bottom: 1px solid var(--border-gray);">
                        <textarea name="anexo3_estrategia[]" class="form-control" style="height: 60px;" placeholder="Si no cumple escriba: N/A" required></textarea>
                    </td>
                    <td style="padding: 1rem; border-bottom: 1px solid var(--border-gray);">
                        <select name="anexo3_frecuencia[]" class="form-control" required>
                            <option value="D">D (Diaria)</option>
                            <option value="S">S (Semanal)</option>
                            <option value="P">P (Permanente)</option>
                            <option value="N/A">N/A (No aplica)</option>
                        </select>
                    </td>
                    <td style="padding: 1rem; border-bottom: 1px solid var(--border-gray);"></td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

<div>
    <button type="button" onclick="agregarFilaAnexo()" style="background: #0d9488; color: white; padding: 0.6rem 1.2rem; font-size: 0.8rem; font-weight: 700; border: none; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; transition: background 0.2s;">
        <i class="bi bi-plus-circle"></i> AGREGAR ACTIVIDAD PARA LA FAMILIA
    </button>
</div>
