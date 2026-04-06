<table>
    <thead>
        <tr>
            <th>Área</th>
            <th>Objetivo</th>
            <th>Barrera</th>
            <th>Curricular</th>
            <th>Metodológico</th>
            <th>Evaluativo</th>
            <th>Convivencia</th>
            <th>Socialización</th>
            <th>Participación</th>
            <th>Autonomía</th>
            <th>Autocontrol</th>
        </tr>
    </thead>
    <tbody>
        @foreach($datos as $adj)
        <tr>
            <td>
                {{ $adj->area }}
                <input type="hidden" name="adjustment_id[]" value="{{ $adj->id }}">
            </td>

            <td><textarea name="objetivo[]" class="form-control">{{ $adj->objetivo }}</textarea></td>
            <td><textarea name="barrera[]" class="form-control">{{ $adj->barrera }}</textarea></td>

            <td><textarea name="ajuste_curricular[]" class="form-control">{{ $adj->ajuste_curricular }}</textarea></td>
            <td><textarea name="ajuste_metodologico[]" class="form-control">{{ $adj->ajuste_metodologico }}</textarea></td>
            <td><textarea name="ajuste_evaluativo[]" class="form-control">{{ $adj->ajuste_evaluativo }}</textarea></td>

            <td><textarea name="convivencia[]" class="form-control">{{ $adj->convivencia }}</textarea></td>
            <td><textarea name="socializacion[]" class="form-control">{{ $adj->socializacion }}</textarea></td>
            <td><textarea name="participacion[]" class="form-control">{{ $adj->participacion }}</textarea></td>
            <td><textarea name="autonomia[]" class="form-control">{{ $adj->autonomia }}</textarea></td>
            <td><textarea name="autocontrol[]" class="form-control">{{ $adj->autocontrol }}</textarea></td>
        </tr>
        @endforeach
    </tbody>
</table>